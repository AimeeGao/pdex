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
        'data',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    // Relationships
    public function individual()
    {
        return $this->belongsTo(Individual::class);
    }

    // Static method to create a new version before updating an individual
    public static function createVersion(Individual $individual, $notes = null, $createdBy = null)
    {
        try {
            $nextVersion = static::getNextVersionNumber($individual->id);
            
            return static::create([
                'individual_id' => $individual->id,
                'version_number' => $nextVersion,
                'data' => $individual->toArray(),
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
        $restoreData = $this->data;
        unset($restoreData['id'], $restoreData['created_at'], $restoreData['updated_at']);
        
        $individual->update($restoreData);
        
        return $individual->fresh();
    }
}
