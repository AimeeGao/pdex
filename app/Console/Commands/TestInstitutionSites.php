<?php

namespace App\Console\Commands;

use App\Models\Institution;
use App\Models\InstitutionSite;
use App\Models\InstitutionRelationship;
use Illuminate\Console\Command;

class TestInstitutionSites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:institution-sites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the Institution, InstitutionSite, and InstitutionRelationship models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Institution and InstitutionSite Models...');
        
        // Test Institution count
        $institutionCount = Institution::count();
        $this->info("Total Institutions: {$institutionCount}");
        
        // Test InstitutionSite count  
        $siteCount = InstitutionSite::count();
        $this->info("Total Institution Sites: {$siteCount}");
        
        // Test InstitutionRelationship count
        $relationshipCount = InstitutionRelationship::count();
        $this->info("Total Institution Relationships: {$relationshipCount}");
        
        // Test relationships
        $institutions = Institution::with('sites')->get();
        
        $this->info("\nInstitution -> Sites Relationships:");
        foreach ($institutions as $institution) {
            $dli = $institution->dli ? " (DLI: {$institution->dli})" : " (No DLI)";
            $this->line("- {$institution->legal_operating_name}{$dli} has {$institution->sites->count()} sites");
            foreach ($institution->sites as $site) {
                $this->line("  * {$site->operating_name} ({$site->city}, {$site->province_state})");
            }
        }
        
        // Test reverse relationship
        if ($siteCount > 0) {
            $site = InstitutionSite::with('institution')->first();
            $this->info("\nSample Site -> Institution Relationship:");
            $this->line("- {$site->operating_name} belongs to {$site->institution->legal_operating_name}");
        }
        
        // Test institution relationships
        if ($relationshipCount > 0) {
            $this->info("\nInstitution Relationships:");
            $relationships = InstitutionRelationship::with(['institutionA', 'institutionB'])->get();
            
            foreach ($relationships as $relationship) {
                $typeLabel = InstitutionRelationship::getRelationshipTypes()[$relationship->relationship_type] ?? $relationship->relationship_type;
                $status = $relationship->isCurrentlyActive() ? '✅' : '❌';
                
                $this->line("{$status} {$relationship->institutionA->legal_operating_name} ↔ {$relationship->institutionB->legal_operating_name}");
                $this->line("   Type: {$typeLabel}");
                $this->line("   Reason: {$relationship->relationship_reason}");
                if ($relationship->description) {
                    $this->line("   Description: {$relationship->description}");
                }
                $this->line("");
            }
            
            // Test finding related institutions
            $firstInstitution = Institution::first();
            if ($firstInstitution) {
                $related = $firstInstitution->relatedInstitutions()->get();
                $this->info("Related institutions for '{$firstInstitution->legal_operating_name}':");
                foreach ($related as $relatedInst) {
                    $this->line("- {$relatedInst->legal_operating_name}");
                }
            }
            
            // Test grouping institutions by relationship reasons
            $this->info("\n🔍 Institutions Grouped by Relationship Reasons:");
            
            // Group by relationship reasons
            $reasonGroups = InstitutionRelationship::with(['institutionA', 'institutionB'])
                ->get()
                ->groupBy('relationship_reason');
                
            foreach ($reasonGroups as $reason => $relationships) {
                $this->line("\n📋 Reason: {$reason}");
                $institutions = collect();
                
                foreach ($relationships as $relationship) {
                    $institutions->push($relationship->institutionA);
                    $institutions->push($relationship->institutionB);
                }
                
                $uniqueInstitutions = $institutions->unique('id');
                $this->line("   Connected Institutions ({$uniqueInstitutions->count()}):");
                foreach ($uniqueInstitutions as $institution) {
                    $this->line("   • {$institution->legal_operating_name}");
                }
            }
            
            // Test specific relationship type patterns
            $this->info("\n🌐 Geographic Partnerships (Same Region):");
            $geographicRelationships = InstitutionRelationship::with(['institutionA', 'institutionB'])
                ->where('relationship_reason', 'like', '%same%')
                ->orWhere('relationship_reason', 'like', '%region%')
                ->orWhere('relationship_reason', 'like', '%area%')
                ->get();
                
            if ($geographicRelationships->count() > 0) {
                $regions = $geographicRelationships->groupBy('relationship_reason');
                foreach ($regions as $regionReason => $relationships) {
                    $this->line("  📍 {$regionReason}:");
                    foreach ($relationships as $relationship) {
                        $this->line("     → {$relationship->institutionA->legal_operating_name} ↔ {$relationship->institutionB->legal_operating_name}");
                    }
                }
            }
            
            // Test consortium memberships
            $this->info("\n🤝 Consortium Memberships:");
            $consortiumRelationships = InstitutionRelationship::with(['institutionA', 'institutionB'])
                ->where('relationship_type', 'consortium')
                ->get();
                
            if ($consortiumRelationships->count() > 0) {
                $consortiums = $consortiumRelationships->groupBy('relationship_reason');
                foreach ($consortiums as $consortiumName => $relationships) {
                    $this->line("  🏛️  {$consortiumName}:");
                    $members = collect();
                    foreach ($relationships as $relationship) {
                        $members->push($relationship->institutionA);
                        $members->push($relationship->institutionB);
                    }
                    $uniqueMembers = $members->unique('id');
                    foreach ($uniqueMembers as $member) {
                        $this->line("     • {$member->legal_operating_name}");
                    }
                }
            }
        }
        
        $this->info("\nAll tests completed successfully! ✅");
        
        return 0;
    }
}
