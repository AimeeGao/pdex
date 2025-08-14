<?php

namespace App\Traits;

trait HasVersionTracking
{
    /**
     * Boot the trait.
     */
    protected static function bootHasVersionTracking()
    {
        static::creating(function ($model) {
            // Set version number from individual if not already set
            if (!$model->version_number && $model->individual) {
                $model->version_number = $model->individual->version_number;
            }
            
            // Set as latest version by default
            if (!isset($model->latest_version)) {
                $model->latest_version = true;
            }
        });

        static::created(function ($model) {
            // After creating, mark all other records for this individual as not latest
            $model->markOtherRecordsAsOldVersion();
        });
    }

    /**
     * Mark all other records for this individual as old versions.
     */
    protected function markOtherRecordsAsOldVersion()
    {
        if ($this->latest_version) {
            static::where('individual_id', $this->individual_id)
                ->where('id', '!=', $this->id)
                ->update(['latest_version' => false]);
        }
    }

    /**
     * Scope to get only latest versions.
     */
    public function scopeLatestVersion($query)
    {
        return $query->where('latest_version', true);
    }

    /**
     * Scope to get records for a specific version.
     */
    public function scopeForVersion($query, $versionNumber)
    {
        return $query->where('version_number', $versionNumber);
    }
}
