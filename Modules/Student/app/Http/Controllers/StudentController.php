<?php

namespace Modules\Student\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Individual;
use App\Models\Application;
use App\Models\ApplicationDataPermission;
use App\Events\IndividualCreated;
use App\Events\IndividualUpdated;
use App\Models\IndividualAddress;
use App\Models\IndividualEmployment;
use App\Models\IndividualIdentity;
use App\Models\Country;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;
use Modules\Student\Http\Requests\StoreIndividualRequest;
use Modules\Student\Http\Requests\UpdateIndividualRequest;
use Modules\Student\Http\Requests\StoreIndividualMultiStepRequest;
use Modules\Student\Http\Requests\UpdateIndividualMultiStepRequest;
use Auth;

class StudentController extends Controller
{
    /**
     * Display the student dashboard with available applications.
     */
    public function index(): Response
    {
        // Get applications that are enabled for BCSC (Student users)
        // Include both active and offline applications, and have both security and privacy approvals
        $applications = Application::where('bcsc_enabled', true)
            ->whereIn('status', ['active', 'offline'])
            ->where('security_approval_status', 'approved')
            ->where('privacy_approval_status', 'approved')
            ->with('dataPermissions')
            ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
            ->orderBy('name', 'asc')
            ->select([
                'id',
                'name',
                'description',
                'bcsc_redirect_url',
                'status',
                'security_approval_status',
                'privacy_approval_status',
                'active_alert_message',
                'offline_alert_message',
                'offline_start_time',
                'offline_end_time',
                'info_label', 
                'info_url'
            ])
            ->get()
            ->map(function ($app) {
                // Group data permissions by table for better display
                $permissionGroups = [];
                foreach ($app->dataPermissions as $permission) {
                    $tableName = $permission->table_name;
                    if (!isset($permissionGroups[$tableName])) {
                        $permissionGroups[$tableName] = [
                            'table_name' => $tableName,
                            'table_label' => $this->getTableLabel($tableName),
                            'permissions' => []
                        ];
                    }
                    $permissionGroups[$tableName]['permissions'][] = [
                        'column_name' => $permission->column_name,
                        'display_name' => $permission->display_name,
                        'can_read' => $permission->can_read,
                        'can_write' => $permission->can_write,
                    ];
                }

                return [
                    'id' => $app->id,
                    'name' => $app->name,
                    'description' => $app->description,
                    'redirect_url' => $app->bcsc_redirect_url,
                    'status' => $app->status,
                    'info_label' => $app->info_label,
                    'info_url' => $app->info_url,
                    'alert_message' => $app->status === 'offline' 
                        ? $app->offline_alert_message 
                        : $app->active_alert_message,
                    'data_permission_groups' => array_values($permissionGroups),
                    'profile_complete' => $this->checkProfileCompleteness($app),
                    'missing_data_message' => $this->getMissingDataMessage($app),
                ];
            });

        return Inertia::render('Student::Dashboard', [
            'applications' => $applications,
            'user' => auth()->user()->only(['name', 'email']),
        ]);
    }

    /**
     * Display the student's profile.
     */
    public function profile()
    {
        $individual = Individual::where('user_guid', Auth::user()->guid)->first();

        // If no profile exists, redirect to create one
        if (!$individual) {
            return redirect()->route('student.profile.create')
                ->with('info', 'Please create your profile to get started.');
        }

        return Inertia::render('Student::Profile/Index', [
            'individual' => $individual,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Individual::class);
        
        // Get all active countries for the form
        $countries = Country::getActiveCountries();

        // Create empty form structure for new individual using fillable fields
        $individual = [
            'general' => (new Individual())->getFillable(),
            'address' => (new IndividualAddress())->getFillable(),
            'employment' => (new IndividualEmployment())->getFillable(),
            'identity' => (new IndividualIdentity())->getFillable(),
        ];

        return Inertia::render('Student::Profile/CreateMultiStep', [
            'countries' => $countries,
            'individual' => $individual,
        ]);
    }    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIndividualMultiStepRequest $request)
    {
        $this->authorize('create', Individual::class);
        
        $validated = $request->validated();
        
        // Extract nested data
        $currentAddress = $validated['current_address'] ?? null;
        $mailingAddress = $validated['mailing_address'] ?? null;
        $currentEmployment = $validated['current_employment'] ?? null;
        $identity = $validated['identity'] ?? null;
        
        // Remove nested data from main validated array
        unset($validated['current_address'], $validated['mailing_address'], $validated['current_employment'], $validated['identity']);
        unset($validated['use_different_mailing_address'], $validated['career_goals'], $validated['preferred_work_location']);
        
        // Ensure the user_guid is set to the current authenticated user
        $validated['user_guid'] = auth()->user()->guid;

        try {
            // Create the main Individual record
            $individual = Individual::create($validated);

            // Create current address if provided
            if ($currentAddress) {
                $currentAddress['individual_id'] = $individual->id;
                $currentAddress['user_id'] = auth()->user()->id;
                $currentAddress['is_primary'] = true;
                
                IndividualAddress::create($currentAddress);
            }

            // Create mailing address if provided
            if ($mailingAddress && ($validated['use_different_mailing_address'] ?? false)) {
                $mailingAddress['individual_id'] = $individual->id;
                $mailingAddress['user_id'] = auth()->user()->id;
                $mailingAddress['is_primary'] = false;
                
                IndividualAddress::create($mailingAddress);
            }

            // Create employment record if provided
            if ($currentEmployment) {
                $currentEmployment['individual_id'] = $individual->id;
                $currentEmployment['user_id'] = auth()->user()->id;
                $currentEmployment['is_current'] = true;
                $currentEmployment['is_active'] = true;
                
                IndividualEmployment::create($currentEmployment);
            }

            // Create identity record if provided
            if ($identity) {
                $identity['individual_id'] = $individual->id;
                $identity['user_id'] = auth()->user()->id;
                
                IndividualIdentity::create($identity);
            }

            // Fire the IndividualCreated event
            IndividualCreated::dispatch($individual, auth()->user());

            Log::info('Individual created successfully', [
                'individual_guid' => $individual->guid,
                'created_by' => auth()->user()?->guid,
            ]);

            return redirect()
                ->route('student.dashboard')
                ->with('success', 'Individual profile created successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to create individual', [
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create individual profile. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $individual = Individual::where('user_guid', Auth::user()->guid)->first();

        $this->authorize('view', $individual);

        return Inertia::render('Student::Profile/Index', [
            'individual' => $individual,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $individual = Individual::where('user_guid', Auth::user()->guid)
            ->with(['addresses', 'employments', 'identities'])
            ->first();

        $this->authorize('update', $individual);

        // Get all active countries for the form
        $countries = Country::getActiveCountries();

        return Inertia::render('Student::Profile/EditMultiStep', [
            'individual' => $individual,
            'countries' => $countries,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIndividualMultiStepRequest $request)
    {
        $individual = Individual::where('user_guid', Auth::user()->guid)->first();

        $this->authorize('update', $individual);

        // Store original data for the event
        $originalData = $individual->toArray();
        $validated = $request->validated();

        try {
            // Create a version with current data before updating
            $individual->createVersionBeforeUpdate(
                'Profile updated by user',
                auth()->user()?->guid
            );

            // Extract nested data
            $currentAddress = $validated['current_address'] ?? null;
            $mailingAddress = $validated['mailing_address'] ?? null;
            $currentEmployment = $validated['current_employment'] ?? null;
            $identity = $validated['identity'] ?? null;
            $useDifferentMailing = $validated['use_different_mailing_address'] ?? false;
            
            // Remove nested data from main validated array
            unset($validated['current_address'], $validated['mailing_address'], $validated['current_employment'], $validated['identity']);
            unset($validated['use_different_mailing_address']);

            // Update the individual record
            $individual->update($validated);

            // Update or create current address
            if ($currentAddress) {
                $existingCurrentAddress = $individual->addresses()->where('is_primary', true)->first();
                if ($existingCurrentAddress) {
                    $existingCurrentAddress->update($currentAddress);
                } else {
                    $currentAddress['individual_id'] = $individual->id;
                    $currentAddress['user_id'] = auth()->user()->id;
                    $currentAddress['is_primary'] = true;
                    IndividualAddress::create($currentAddress);
                }
            }

            // Handle mailing address
            $existingMailingAddress = $individual->addresses()->where('is_primary', false)->first();
            if ($useDifferentMailing && $mailingAddress) {
                // Create or update mailing address
                if ($existingMailingAddress) {
                    $existingMailingAddress->update($mailingAddress);
                } else {
                    $mailingAddress['individual_id'] = $individual->id;
                    $mailingAddress['user_id'] = auth()->user()->id;
                    $mailingAddress['is_primary'] = false;
                    IndividualAddress::create($mailingAddress);
                }
            } else {
                // Remove mailing address if not using different mailing address
                if ($existingMailingAddress) {
                    $existingMailingAddress->delete();
                }
            }

            // Update or create employment record
            if ($currentEmployment && !empty(array_filter($currentEmployment))) {
                $existingEmployment = $individual->employments()->where('is_current', true)->first();
                if ($existingEmployment) {
                    $existingEmployment->update($currentEmployment);
                } else {
                    $currentEmployment['individual_id'] = $individual->id;
                    $currentEmployment['user_id'] = auth()->user()->id;
                    $currentEmployment['is_current'] = true;
                    IndividualEmployment::create($currentEmployment);
                }
            }

            // Update or create identity record
            if ($identity && !empty(array_filter($identity))) {
                $existingIdentity = $individual->identities()->first();
                if ($existingIdentity) {
                    $existingIdentity->update($identity);
                } else {
                    $identity['individual_id'] = $individual->id;
                    $identity['user_id'] = auth()->user()->id;
                    IndividualIdentity::create($identity);
                }
            }

            // Fire the IndividualUpdated event
            IndividualUpdated::dispatch($individual, $originalData, auth()->user());

            Log::info('Individual updated successfully with version created', [
                'individual_guid' => $individual->guid,
                'updated_by' => auth()->user()?->guid,
            ]);

            return redirect()
                ->route('student.dashboard')
                ->with('success', 'Individual profile updated successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to update individual', [
                'individual_guid' => $individual->guid,
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update individual profile. Please try again.']);
        }
    }

    /**
     * Display version history for the user's profile.
     */
    public function versions()
    {
        $individual = Individual::where('user_guid', Auth::user()->guid)->first();

        if (!$individual) {
            return redirect()->route('student.profile.create')
                ->with('info', 'Please create your profile first.');
        }

        $versions = $individual->getVersionHistory();

        return Inertia::render('Student::Profile/Versions', [
            'individual' => $individual,
            'versions' => $versions,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
        $individual = Individual::where('user_guid', Auth::user()->guid)->first();

            $this->authorize('delete', $individual);
            
            Log::info('Individual deleted', [
                'individual_guid' => $individual->guid,
                'deleted_by' => auth()->user()?->guid,
            ]);

            $individual->delete();

            return redirect()
                ->route('student.profile.index')
                ->with('success', 'Individual profile deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to delete individual', [
                'individual_guid' => $guid,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withErrors(['error' => 'Failed to delete individual profile. Please try again.']);
        }
    }

    /**
     * Get user-friendly label for database table names
     */
    private function getTableLabel(string $tableName): string
    {
        $availableTables = ApplicationDataPermission::getAvailableTables();
        return $availableTables[$tableName]['label'] ?? ucfirst(str_replace('_', ' ', $tableName));
    }

    /**
     * Check if user's profile is complete for the application's data permissions
     */
    private function checkProfileCompleteness(Application $application): bool
    {
        $user = auth()->user();
        $individual = Individual::where('user_guid', $user->guid)->first();
        
        // If no individual profile exists, profile is incomplete
        if (!$individual) {
            return false;
        }

        // Check if profile requires updating based on CheckProfile middleware logic
        $lastUpdated = $individual->updated_at ?: $individual->created_at;
        $timeAgo = \Carbon\Carbon::now()->subMonths(env('PROFILE_UPDATE_GRACE_PERIOD', 6));
        
        if ($lastUpdated->lt($timeAgo)) {
            return false;
        }

        // Check if all required data permissions fields are populated
        foreach ($application->dataPermissions as $permission) {
            if ($permission->can_read && $this->isFieldRequired($permission)) {
                $value = $this->getFieldValue($individual, $permission);
                if (empty($value)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get missing data message for incomplete profiles
     */
    private function getMissingDataMessage(Application $application): string
    {
        $user = auth()->user();
        $individual = Individual::where('user_guid', $user->guid)->first();
        
        if (!$individual) {
            return 'Profile required - Please create your profile to access this application.';
        }

        // Check if profile needs updating due to age
        $lastUpdated = $individual->updated_at ?: $individual->created_at;
        $timeAgo = \Carbon\Carbon::now()->subMonths(env('PROFILE_UPDATE_GRACE_PERIOD', 6));
        
        if ($lastUpdated->lt($timeAgo)) {
            $months = env('PROFILE_UPDATE_GRACE_PERIOD', 6);
            return "Profile update required - Your profile is more than {$months} months old and needs to be updated.";
        }

        // Check for missing required fields
        $missingFields = [];
        foreach ($application->dataPermissions as $permission) {
            if ($permission->can_read && $this->isFieldRequired($permission)) {
                $value = $this->getFieldValue($individual, $permission);
                if (empty($value)) {
                    $fieldName = $permission->display_name ?: $this->formatFieldName($permission->column_name);
                    $missingFields[] = $fieldName;
                }
            }
        }

        if (!empty($missingFields)) {
            $fields = implode(', ', $missingFields);
            return "Missing required information - Please complete these fields: {$fields}";
        }

        return 'Profile incomplete - Please update your profile to access this application.';
    }

    /**
     * Check if a data permission field is required (exclude optional fields)
     */
    private function isFieldRequired($permission): bool
    {
        // Define optional fields that are not required for application access
        $optionalFields = [
            'id', 'guid', 'user_guid', 'created_at', 'updated_at', 'deleted_at',
            'preferred_name', 'middle_name', 'suffix', 'nickname'
        ];

        return !in_array($permission->column_name, $optionalFields);
    }

    /**
     * Get the value of a field from the individual's profile data
     */
    private function getFieldValue($individual, $permission)
    {
        $tableName = $permission->table_name;
        $columnName = $permission->column_name;

        switch ($tableName) {
            case 'individuals':
                return $individual->{$columnName} ?? null;
            
            case 'individual_addresses':
                $address = $individual->addresses()->first();
                return $address ? $address->{$columnName} : null;
            
            case 'individual_employments':
                $employment = $individual->employments()->first();
                return $employment ? $employment->{$columnName} : null;
            
            case 'individual_identities':
                $identity = $individual->identities()->first();
                return $identity ? $identity->{$columnName} : null;
            
            default:
                return null;
        }
    }

    /**
     * Format field name for display
     */
    private function formatFieldName(string $fieldName): string
    {
        return ucwords(str_replace('_', ' ', $fieldName));
    }
}
