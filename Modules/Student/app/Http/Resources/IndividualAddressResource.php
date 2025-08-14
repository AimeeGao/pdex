<?php

namespace Modules\Student\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="IndividualAddress",
 *     type="object",
 *     title="Individual Address",
 *     description="Individual address information",
 *     @OA\Property(property="id", type="integer", description="Unique identifier"),
 *     @OA\Property(property="individual_id", type="integer", description="Individual ID"),
 *     @OA\Property(property="address_line1", type="string", description="Address line 1"),
 *     @OA\Property(property="address_line2", type="string", description="Address line 2"),
 *     @OA\Property(property="city", type="string", description="City"),
 *     @OA\Property(property="province", type="string", description="Province/State"),
 *     @OA\Property(property="postal_code", type="string", description="Postal/ZIP code"),
 *     @OA\Property(property="country", type="string", description="Country"),
 *     @OA\Property(property="is_primary", type="boolean", description="Is primary address"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 */
class IndividualAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'individual_id' => $this->individual_id,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'city' => $this->city,
            'province' => $this->province,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'is_primary' => (bool) $this->is_primary,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
