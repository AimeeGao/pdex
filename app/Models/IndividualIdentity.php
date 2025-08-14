<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasVersionTracking;

class IndividualIdentity extends Model
{
    use HasFactory, HasVersionTracking;

    protected $fillable = [
        'individual_id',
        'user_id',
        'version_number',
        'citizenship_status',
        'country_of_birth',
        'language_spoken_at_home',
        'indigenous_status',
        'indigenous_group',
        'band_affiliation',
        'indigenous_status_card_number',
        'is_registered_with_band',
        'on_reserve_resident',
        'racial_identity',
        'is_visible_minority',
        'years_in_country',
        'refugee_status',
        'immigration_status',
        'receives_indigenous_support_services',
        'receives_minority_support_services',
        'latest_version',
    ];

    public function individual()
    {
        return $this->belongsTo(Individual::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
