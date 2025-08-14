<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Individual;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TestStudentSeeder extends Seeder
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

        // Create test student user
        $studentUser = User::updateOrCreate(
            ['email' => 'test.student@bcsc.example.com'],
            [
                'guid' => Str::orderedUuid()->getHex(),
                'first_name' => 'Test',
                'last_name' => 'Student',
                'name' => 'Test Student',
                'email' => 'test.student@bcsc.example.com',
                'bcsc_username' => 'test_student_bcsc',
                'bcsc_user_guid' => Str::orderedUuid()->getHex(),
                'keycloak_id' => 'test-student-kc-id',
                'identity_provider' => 'bcsc',
                'display_name' => 'Test Student',
                'given_name' => 'Test',
                'family_name' => 'Student',
                'is_active' => true,
            ]
        );

        // Assign student role
        $studentRole = Role::where('name', Role::STUDENT)->first();
        if ($studentRole) {
            $studentUser->roles()->syncWithoutDetaching([$studentRole->id]);
        }

        // Create an individual profile for testing (with different scenarios)
        
        // Scenario 1: Fresh profile (created today)
        $freshStudent = User::updateOrCreate(
            ['email' => 'fresh.student@bcsc.example.com'],
            [
                'guid' => Str::orderedUuid()->getHex(),
                'first_name' => 'Fresh',
                'last_name' => 'Student',
                'name' => 'Fresh Student',
                'email' => 'fresh.student@bcsc.example.com',
                'bcsc_username' => 'fresh_student_bcsc',
                'bcsc_user_guid' => Str::orderedUuid()->getHex(),
                'keycloak_id' => 'fresh-student-kc-id',
                'identity_provider' => 'bcsc',
                'display_name' => 'Fresh Student',
                'given_name' => 'Fresh',
                'family_name' => 'Student',
                'is_active' => true,
            ]
        );

        if ($studentRole) {
            $freshStudent->roles()->syncWithoutDetaching([$studentRole->id]);
        }

        Individual::updateOrCreate(
            ['user_guid' => $freshStudent->guid],
            [
                'guid' => Str::orderedUuid()->getHex(),
                'user_guid' => $freshStudent->guid,
                'first_name' => 'Fresh',
                'last_name' => 'Student',
                'email_address' => 'fresh.student@bcsc.example.com',
                'phone_number' => '250-555-0001',
                'date_of_birth' => '1995-05-15',
                'gender' => 'Other',
                'citizenship_status' => 'Canadian Citizen',
                'current_city' => 'Victoria',
                'current_province_state' => 'BC',
                'current_country' => 'Canada',
                'status' => Individual::STATUS_ACTIVE,
                'verification_status' => Individual::VERIFICATION_VERIFIED,
                'created_at' => now(),
                'updated_at' => now(), // Fresh profile
            ]
        );

        // Scenario 2: Outdated profile (updated 8 months ago)
        $outdatedStudent = User::updateOrCreate(
            ['email' => 'outdated.student@bcsc.example.com'],
            [
                'guid' => Str::orderedUuid()->getHex(),
                'first_name' => 'Outdated',
                'last_name' => 'Student',
                'name' => 'Outdated Student',
                'email' => 'outdated.student@bcsc.example.com',
                'bcsc_username' => 'outdated_student_bcsc',
                'bcsc_user_guid' => Str::orderedUuid()->getHex(),
                'keycloak_id' => 'outdated-student-kc-id',
                'identity_provider' => 'bcsc',
                'display_name' => 'Outdated Student',
                'given_name' => 'Outdated',
                'family_name' => 'Student',
                'is_active' => true,
            ]
        );

        if ($studentRole) {
            $outdatedStudent->roles()->syncWithoutDetaching([$studentRole->id]);
        }

        Individual::updateOrCreate(
            ['user_guid' => $outdatedStudent->guid],
            [
                'guid' => Str::orderedUuid()->getHex(),
                'user_guid' => $outdatedStudent->guid,
                'first_name' => 'Outdated',
                'last_name' => 'Student',
                'email_address' => 'outdated.student@bcsc.example.com',
                'phone_number' => '250-555-0002',
                'date_of_birth' => '1992-08-20',
                'gender' => 'Female',
                'citizenship_status' => 'Canadian Citizen',
                'current_city' => 'Vancouver',
                'current_province_state' => 'BC',
                'current_country' => 'Canada',
                'status' => Individual::STATUS_ACTIVE,
                'verification_status' => Individual::VERIFICATION_VERIFIED,
                'created_at' => Carbon::now()->subMonths(10),
                'updated_at' => Carbon::now()->subMonths(8), // Outdated profile
            ]
        );

        $this->command->info('Test students created:');
        $this->command->info('1. test.student@bcsc.example.com - No profile (will be redirected to create)');
        $this->command->info('2. fresh.student@bcsc.example.com - Fresh profile (can access dashboard)');
        $this->command->info('3. outdated.student@bcsc.example.com - Outdated profile (will be redirected to update)');
    }
}
