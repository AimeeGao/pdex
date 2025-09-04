<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IndividualVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'individual_id',
        'version_number',
        'individual_data',
        'individual_address',
        'individual_employment',
        'individual_identity',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'individual_data' => 'array',
        'individual_address' => 'array',
        'individual_employment' => 'array',
        'individual_identity' => 'array',
    ];

    // Relationships
    public function individual()
    {
        return $this->belongsTo(Individual::class);
    }

    public function individualAddress()
    {
        return $this->hasOne(IndividualAddress::class);
    }

    public function individualEmployment()
    {
        return $this->hasOne(IndividualEmployment::class);
    }

    public function individualIdentity()
    {
        return $this->hasOne(IndividualIdentity::class);
    }

    // Static method to create a new version before updating an individual
    public static function createVersion(Individual $individual, $notes = null, $createdBy = null)
    {
        try {
            $nextVersion = static::getNextVersionNumber($individual->id);
            
            return static::create([
                'individual_id' => $individual->id,
                'version_number' => $nextVersion,
                'individual_data' => $individual->toArray(),
                'individual_address' => $individual->addresses ? $individual->addresses->toArray() : null,
                'individual_employment' => $individual->employments ? $individual->employments->toArray() : null,
                'individual_identity' => $individual->identities ? $individual->identities->toArray() : null,
                'created_by' => $createdBy ?: auth()->user()?->guid,
                'notes' => $notes ?: 'Profile updated',
            ]);
        } catch (\Exception $e) {
            // If versioning fails, log the error but don't break the update process
            \Illuminate\Support\Facades\Log::warning('Could not create version, skipping versioning', [
                'error' => $e->getMessage(),
                'individual_id' => $individual->id,
                'notes' => $notes
            ]);
            return null;
        }
    }

    // Get the next version number for an individual
    public static function getNextVersionNumber($individualId)
    {
        try {
            $maxVersion = static::where('individual_id', $individualId)->max('version_number');
            return $maxVersion ? $maxVersion + 1 : 1;
        } catch (\Exception $e) {
            // If there's a database error (like missing table), return version 1
            \Illuminate\Support\Facades\Log::warning('Could not get version number, using default', [
                'error' => $e->getMessage(),
                'individual_id' => $individualId
            ]);
            return 1;
        }
    }

    // Get version history for an individual
    public static function getVersionHistory($individualId)
    {
        return static::where('individual_id', $individualId)
                    ->orderBy('version_number', 'desc')
                    ->get();
    }

    // Restore an individual to a specific version
    public function restoreToThisVersion()
    {
        $individual = $this->individual;
        
        // Create a new version with current data before restoring
        static::createVersion($individual, "Restored to version {$this->version_number}");
        
        // Update individual with this version's data
        $individualData = $this->individual_data;
        $employmentData = $this->individual_employment;
        $addressData = $this->individual_address;
        $identityData = $this->individual_identity;

        // Unset unnecessary fields
        unset($individualData['id'], $individualData['created_at'], $individualData['updated_at']);
        unset($employmentData['id'], $employmentData['created_at'], $employmentData['updated_at']);
        unset($addressData['id'], $addressData['created_at'], $addressData['updated_at']);
        unset($identityData['id'], $identityData['created_at'], $identityData['updated_at']);

        // Update individual with this version's data
        $individual->update($individualData);
        $individual->address()->update($addressData);
        $individual->employment()->update($employmentData);
        $individual->identity()->update($identityData);

        return $individual->fresh();
    }
}
