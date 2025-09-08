<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndividualApplicationPermissionSelection extends Model
{
    protected $fillable = [
        'individual_id',
        'application_id',
        'application_individual_permission_id',
        'is_selected',
    ];

    protected $casts = [
        'is_selected' => 'boolean',
    ];

    /**
     * Get the individual that owns the permission selection.
     */
    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }

    /**
     * Get the application that owns the permission selection.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the individual permission that owns the permission selection.
     */
    public function applicationIndividualPermission(): BelongsTo
    {
        return $this->belongsTo(ApplicationIndividualPermission::class);
    }

    /**
     * Get or create permission selections for a specific user and application.
     */
    public static function getSelectionsForUserAndApplication($individualId, $applicationId)
    {
        return self::where('individual_id', $individualId)
            ->where('application_id', $applicationId)
            ->with('applicationIndividualPermission')
            ->get()
            ->keyBy('application_individual_permission_id');
    }

    /**
     * Update or create permission selections for a user and application.
     */
    public static function updateSelections($individualId, $applicationId, array $selections)
    {
        foreach ($selections as $permissionId => $isSelected) {
            self::updateOrCreate(
                [
                    'individual_id' => $individualId,
                    'application_id' => $applicationId,
                    'application_individual_permission_id' => $permissionId,
                ],
                [
                    'is_selected' => $isSelected,
                ]
            );
        }
    }
}
