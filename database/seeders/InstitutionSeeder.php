<?php

namespace Database\Seeders;

use App\Models\Institution;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
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

        // Create major BC institutions
        $majorInstitutions = [
            [
                'legal_operating_name' => 'University of British Columbia',
                'institution_type' => Institution::TYPE_UNIVERSITY,
                'dli' => 'O19391131552',
                'bceid_business_guid' => 'UBC-GUID-001',
                'active_status' => true,
            ],
            [
                'legal_operating_name' => 'Simon Fraser University',
                'institution_type' => Institution::TYPE_UNIVERSITY,
                'dli' => 'O19280113157',
                'bceid_business_guid' => 'SFU-GUID-002',
                'active_status' => true,
            ],
            [
                'legal_operating_name' => 'University of Victoria',
                'institution_type' => Institution::TYPE_UNIVERSITY,
                'dli' => 'O19391131553',
                'bceid_business_guid' => 'UVIC-GUID-003',
                'active_status' => true,
            ],
            [
                'legal_operating_name' => 'British Columbia Institute of Technology',
                'institution_type' => Institution::TYPE_INSTITUTE,
                'dli' => 'O19280189157',
                'bceid_business_guid' => 'BCIT-GUID-004',
                'active_status' => true,
            ],
            [
                'legal_operating_name' => 'Thompson Rivers University',
                'institution_type' => Institution::TYPE_TEACHING_UNIVERSITY,
                'dli' => 'O19280189158',
                'bceid_business_guid' => 'TRU-GUID-005',
                'active_status' => true,
            ],
        ];

        foreach ($majorInstitutions as $institutionData) {
            Institution::create($institutionData);
        }

        // Create additional random institutions using factory
        Institution::factory(15)->create();
        
        // Create some specific scenarios for testing
        Institution::factory(3)
            ->state([
                'institution_type' => Institution::TYPE_COLLEGE,
                'active_status' => true,
            ])
            ->create();
            
        Institution::factory(2)
            ->state([
                'institution_type' => Institution::TYPE_PRIVATE_CAREER_COLLEGE,
                'active_status' => false,
            ])
            ->create();
    }
}
