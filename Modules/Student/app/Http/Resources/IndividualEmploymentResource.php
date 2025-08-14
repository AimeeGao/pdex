<?php

namespace Modules\Student\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="IndividualEmployment",
 *     type="object",
 *     title="Individual Employment",
 *     description="Individual employment information",
 *     @OA\Property(property="id", type="integer", description="Unique identifier"),
 *     @OA\Property(property="individual_id", type="integer", description="Individual ID"),
 *     @OA\Property(property="employment_status", type="string", description="Employment status"),
 *     @OA\Property(property="is_looking_for_work", type="boolean", description="Looking for work"),
 *     @OA\Property(property="job_title", type="string", description="Job title"),
 *     @OA\Property(property="employer_name", type="string", description="Employer name"),
 *     @OA\Property(property="employer_industry", type="string", description="Employer industry"),
 *     @OA\Property(property="employment_start_date", type="string", format="date", description="Employment start date"),
 *     @OA\Property(property="employment_end_date", type="string", format="date", description="Employment end date"),
 *     @OA\Property(property="work_hours_per_week", type="integer", description="Work hours per week"),
 *     @OA\Property(property="monthly_income", type="number", format="float", description="Monthly income"),
 *     @OA\Property(property="is_job_related_to_program", type="boolean", description="Job related to program"),
 *     @OA\Property(property="is_current", type="boolean", description="Is current employment"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 */
class IndividualEmploymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'individual_id' => $this->individual_id,
            'employment_status' => $this->employment_status,
            'is_looking_for_work' => (bool) $this->is_looking_for_work,
            'job_title' => $this->job_title,
            'employer_name' => $this->employer_name,
            'employer_industry' => $this->employer_industry,
            'employment_start_date' => $this->employment_start_date,
            'employment_end_date' => $this->employment_end_date,
            'work_hours_per_week' => $this->work_hours_per_week,
            'monthly_income' => $this->monthly_income,
            'is_job_related_to_program' => (bool) $this->is_job_related_to_program,
            'previous_job_title' => $this->previous_job_title,
            'previous_employer_name' => $this->previous_employer_name,
            'career_interest_area' => $this->career_interest_area,
            'desired_job_title' => $this->desired_job_title,
            'career_readiness_level' => $this->career_readiness_level,
            'has_career_plan' => (bool) $this->has_career_plan,
            'is_current' => (bool) $this->is_current,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
