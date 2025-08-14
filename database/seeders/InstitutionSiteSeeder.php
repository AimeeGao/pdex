<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\InstitutionSite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstitutionSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seed the following only on dev or local environments
        if( app()->environment() === ('production' || 'testing')){
            return;
        }

        // Get existing institutions or create some
        $institutions = Institution::all();
        
        if ($institutions->isEmpty()) {
            $institutions = Institution::factory(3)->create();
        }

        // Create sites for each institution
        foreach ($institutions as $institution) {
            // Create 1-3 sites per institution
            $siteCount = rand(1, 3);
            
            for ($i = 0; $i < $siteCount; $i++) {
                InstitutionSite::factory()->create([
                    'institution_guid' => $institution->guid,
                    'operating_name' => $institution->legal_operating_name . ' - ' . ['Main Campus', 'Downtown Campus', 'North Campus', 'South Campus'][rand(0, 3)],
                ]);
            }
        }
    }
}
