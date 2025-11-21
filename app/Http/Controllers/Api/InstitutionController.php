<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class InstitutionController extends Controller
{
    /**
     * Get all institutions with permission filtering
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $tokenData = $request->input('token_data');
            $registeredApp = $this->getRegisteredApplication($tokenData['sub'] ?? null);

            if (!$registeredApp) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => 'Application not registered for API access'
                ], 403);
            }

            // Check if this app has permission to read institutions data
            if (!$this->hasPermission($registeredApp->id, 'institutions', 'read')) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => 'Insufficient permissions to access institutions data'
                ], 403);
            }

            $allowedFields = $this->getAllowedFields($registeredApp->id, 'institutions');
            
            $institutions = Institution::select($allowedFields)->get();

            return response()->json([
                'data' => $institutions,
                'meta' => [
                    'count' => $institutions->count(),
                    'registered_app' => $registeredApp->name,
                    'allowed_fields' => $allowedFields,
                    'permissions' => $this->getAppPermissions($registeredApp->id, 'institutions')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('API Institutions index error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'An error occurred while fetching institutions'
            ], 500);
        }
    }

    /**
     * Get a specific institution by ID
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $tokenData = $request->input('token_data');
            $registeredApp = $this->getRegisteredApplication($tokenData['sub'] ?? null);

            if (!$registeredApp) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => 'Application not registered for API access'
                ], 403);
            }

            if (!$this->hasPermission($registeredApp->id, 'institutions', 'read')) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => 'Insufficient permissions to access institution data'
                ], 403);
            }

            $allowedFields = $this->getAllowedFields($registeredApp->id, 'institutions');
            
            $institution = Institution::select($allowedFields)
                ->where('id', $id)
                ->first();

            if (!$institution) {
                return response()->json([
                    'error' => 'Not Found',
                    'message' => 'Institution not found'
                ], 404);
            }

            return response()->json([
                'data' => $institution,
                'meta' => [
                    'registered_app' => $registeredApp->name,
                    'allowed_fields' => $allowedFields,
                    'permissions' => $this->getAppPermissions($registeredApp->id, 'institutions')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('API Institution show error', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'An error occurred while fetching the institution'
            ], 500);
        }
    }

    /**
     * Get the registered application for the current token
     */
    private function getRegisteredApplication(?string $sub): ?Application
    {
        if (!$sub) {
            return null;
        }

        return Application::where('client_id', $sub)->first();
    }

    /**
     * Check if the registered application has a specific permission
     */
    private function hasPermission(int $appId, string $tableName, string $action): bool
    {
        $column = $action === 'read' ? 'can_read' : 'can_write';
        
        return \DB::table('application_api_permissions')
            ->where('application_id', $appId)
            ->where('table_name', $tableName)
            ->where($column, true)
            ->exists();
    }

    /**
     * Get allowed fields for a specific table based on application permissions
     */
    private function getAllowedFields(int $appId, string $tableName): array
    {
        $permissions = \DB::table('application_api_permissions')
            ->where('application_id', $appId)
            ->where('table_name', $tableName)
            ->where('can_read', true)
            ->pluck('column_name')
            ->toArray();

        // If no specific field permissions, return basic fields
        if (empty($permissions)) {
            return $this->getBasicFields($tableName);
        }

        // Always include ID field if not already present
        if (!in_array('id', $permissions)) {
            array_unshift($permissions, 'id');
        }

        return $permissions;
    }

    /**
     * Get basic fields when no specific permissions are set
     */
    private function getBasicFields(string $tableName): array
    {
        $basicFields = [
            'institutions' => ['id', 'legal_operating_name', 'status', 'created_at'],
            'applications' => ['id', 'name', 'status', 'created_at'],
            'students' => ['id', 'first_name', 'last_name', 'created_at']
        ];

        return $basicFields[$tableName] ?? ['id', 'created_at'];
    }

    /**
     * Get permissions for a specific table
     */
    private function getAppPermissions(int $appId, string $tableName): array
    {
        $permissions = \DB::table('application_api_permissions')
            ->where('application_id', $appId)
            ->where('table_name', $tableName)
            ->select('column_name', 'can_read', 'can_write', 'display_name')
            ->get();

        return [
            'readable_fields' => $permissions->where('can_read', true)->pluck('column_name')->values(),
            'writable_fields' => $permissions->where('can_write', true)->pluck('column_name')->values(),
            'field_details' => $permissions->map(function ($perm) {
                return [
                    'column' => $perm->column_name,
                    'display_name' => $perm->display_name,
                    'can_read' => $perm->can_read,
                    'can_write' => $perm->can_write
                ];
            })->values()
        ];
    }
}