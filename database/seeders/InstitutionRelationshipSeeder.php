<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\InstitutionRelationship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstitutionRelationshipSeeder extends Seeder
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

        // Get or create some institutions for realistic relationships
        $institutions = Institution::all();
        
        if ($institutions->count() < 6) {
            // Create some well-known BC institutions for demo
            $institutions = collect([
                Institution::factory()->create([
                    'legal_operating_name' => 'University of British Columbia',
                    'institution_type' => Institution::TYPE_UNIVERSITY,
                ]),
                Institution::factory()->create([
                    'legal_operating_name' => 'Simon Fraser University', 
                    'institution_type' => Institution::TYPE_UNIVERSITY,
                ]),
                Institution::factory()->create([
                    'legal_operating_name' => 'University of Victoria',
                    'institution_type' => Institution::TYPE_UNIVERSITY,
                ]),
                Institution::factory()->create([
                    'legal_operating_name' => 'Camosun College',
                    'institution_type' => Institution::TYPE_COLLEGE,
                ]),
                Institution::factory()->create([
                    'legal_operating_name' => 'Douglas College',
                    'institution_type' => Institution::TYPE_COLLEGE,
                ]),
                Institution::factory()->create([
                    'legal_operating_name' => 'British Columbia Institute of Technology',
                    'institution_type' => Institution::TYPE_INSTITUTE,
                ]),
            ]);
        }

        $ubc = $institutions->firstWhere('legal_operating_name', 'University of British Columbia');
        $sfu = $institutions->firstWhere('legal_operating_name', 'Simon Fraser University');
        $uvic = $institutions->firstWhere('legal_operating_name', 'University of Victoria');
        $camosun = $institutions->firstWhere('legal_operating_name', 'Camosun College');
        $douglas = $institutions->firstWhere('legal_operating_name', 'Douglas College');
        $bcit = $institutions->firstWhere('legal_operating_name', 'British Columbia Institute of Technology');

        // Create realistic relationships based on your examples
        
        if ($ubc && $sfu) {
            // UBC and SFU - Vancouver area universities
            InstitutionRelationship::createBidirectional(
                $ubc->guid,
                $sfu->guid,
                InstitutionRelationship::TYPE_GEOGRAPHIC,
                'Both located in Greater Vancouver area',
                [
                    'description' => 'Major research universities in the Vancouver metropolitan region',
                    'metadata' => ['region' => 'Metro Vancouver', 'collaboration_level' => 'high']
                ]
            );
        }

        if ($uvic && $camosun) {
            // UVic and Camosun - Victoria area
            InstitutionRelationship::createBidirectional(
                $uvic->guid,
                $camosun->guid,
                InstitutionRelationship::TYPE_GEOGRAPHIC,
                'Both located in Victoria area',
                [
                    'description' => 'University and college serving the Greater Victoria region',
                    'metadata' => ['region' => 'Greater Victoria']
                ]
            );
        }

        if ($ubc && $douglas) {
            // UBC and Douglas - Transfer agreements
            InstitutionRelationship::createBidirectional(
                $ubc->guid,
                $douglas->guid,
                InstitutionRelationship::TYPE_TRANSFER_AGREEMENT,
                'Credit transfer pathways for students',
                [
                    'description' => 'Established pathways for Douglas College students to transfer to UBC',
                    'effective_date' => '2020-01-01',
                    'metadata' => ['transfer_credits_max' => 60, 'programs' => ['Arts', 'Sciences']]
                ]
            );
        }

        if ($sfu && $douglas) {
            // SFU and Douglas - Same region + transfer
            InstitutionRelationship::createBidirectional(
                $sfu->guid,
                $douglas->guid,
                InstitutionRelationship::TYPE_GEOGRAPHIC,
                'Both serve the Fraser Valley region',
                [
                    'description' => 'Regional partners in post-secondary education delivery'
                ]
            );
        }

        if ($uvic && $camosun && $institutions->count() > 5) {
            // Add Royal Roads if we have more institutions
            $royalRoads = $institutions->skip(5)->first();
            if ($royalRoads) {
                $royalRoads->update(['legal_operating_name' => 'Royal Roads University']);
                
                // Victoria area consortium
                InstitutionRelationship::createBidirectional(
                    $uvic->guid,
                    $royalRoads->guid,
                    InstitutionRelationship::TYPE_CONSORTIUM,
                    'Victoria Higher Education Consortium',
                    [
                        'description' => 'Collaborative consortium of Victoria-area institutions'
                    ]
                );
                
                InstitutionRelationship::createBidirectional(
                    $camosun->guid,
                    $royalRoads->guid,
                    InstitutionRelationship::TYPE_CONSORTIUM,
                    'Victoria Higher Education Consortium',
                    [
                        'description' => 'Collaborative consortium of Victoria-area institutions'
                    ]
                );
            }
        }

        if ($ubc && $bcit) {
            // UBC and BCIT - Academic partnership
            InstitutionRelationship::createBidirectional(
                $ubc->guid,
                $bcit->guid,
                InstitutionRelationship::TYPE_ACADEMIC,
                'Research and technology collaboration',
                [
                    'description' => 'Joint research initiatives and technology transfer programs',
                    'metadata' => ['focus_areas' => ['Engineering', 'Applied Sciences', 'Technology']]
                ]
            );
        }

        // Add some random relationships for remaining institutions
        $remainingInstitutions = $institutions->reject(function($inst) use ($ubc, $sfu, $uvic, $camosun, $douglas, $bcit) {
            return in_array($inst->guid, [$ubc?->guid, $sfu?->guid, $uvic?->guid, $camosun?->guid, $douglas?->guid, $bcit?->guid]);
        });

        // Create a few more random relationships
        for ($i = 0; $i < min(3, $remainingInstitutions->count()); $i++) {
            $inst1 = $institutions->random();
            $inst2 = $institutions->where('guid', '!=', $inst1->guid)->random();
            
            // Check if relationship already exists
            $existsAlready = InstitutionRelationship::where(function($query) use ($inst1, $inst2) {
                $query->where('institution_a_guid', $inst1->guid)
                      ->where('institution_b_guid', $inst2->guid);
            })->orWhere(function($query) use ($inst1, $inst2) {
                $query->where('institution_a_guid', $inst2->guid)
                      ->where('institution_b_guid', $inst1->guid);
            })->exists();
            
            if (!$existsAlready) {
                InstitutionRelationship::factory()->create([
                    'institution_a_guid' => $inst1->guid,
                    'institution_b_guid' => $inst2->guid,
                ]);
            }
        }

        $this->command->info('Created institution relationships with realistic BC examples');
    }
}
