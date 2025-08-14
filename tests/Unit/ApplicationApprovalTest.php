<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApplicationApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function testIsFinallyApprovedReturnsTrueWhenBothApproved()
    {
        $application = Application::factory()->create([
            'security_approval_status' => 'approved',
            'privacy_approval_status' => 'approved',
        ]);

        $this->assertTrue($application->isFinallyApproved());
    }

    public function testIsFinallyApprovedReturnsFalseWhenOnlySecurityApproved()
    {
        $application = Application::factory()->create([
            'security_approval_status' => 'approved',
            'privacy_approval_status' => 'pending',
        ]);

        $this->assertFalse($application->isFinallyApproved());
    }

    public function testIsFinallyApprovedReturnsFalseWhenOnlyPrivacyApproved()
    {
        $application = Application::factory()->create([
            'security_approval_status' => 'pending',
            'privacy_approval_status' => 'approved',
        ]);

        $this->assertFalse($application->isFinallyApproved());
    }

    public function testIsFinallyApprovedReturnsFalseWhenBothPending()
    {
        $application = Application::factory()->create([
            'security_approval_status' => 'pending',
            'privacy_approval_status' => 'pending',
        ]);

        $this->assertFalse($application->isFinallyApproved());
    }

    public function testCanModifyApprovalsReturnsFalseWhenFinallyApproved()
    {
        $application = Application::factory()->create([
            'security_approval_status' => 'approved',
            'privacy_approval_status' => 'approved',
        ]);

        $this->assertFalse($application->canModifyApprovals());
    }

    public function testCanModifyApprovalsReturnsTrueWhenNotFinallyApproved()
    {
        $application = Application::factory()->create([
            'security_approval_status' => 'pending',
            'privacy_approval_status' => 'approved',
        ]);

        $this->assertTrue($application->canModifyApprovals());
    }

    public function testIsAccessibleRequiresBothApprovals()
    {
        // Test with both approvals and active status
        $application = Application::factory()->create([
            'security_approval_status' => 'approved',
            'privacy_approval_status' => 'approved',
            'status' => 'active',
        ]);

        $this->assertTrue($application->isAccessible());

        // Test with only security approval
        $application->update(['privacy_approval_status' => 'pending']);
        $this->assertFalse($application->isAccessible());

        // Test with only privacy approval
        $application->update([
            'security_approval_status' => 'pending',
            'privacy_approval_status' => 'approved'
        ]);
        $this->assertFalse($application->isAccessible());

        // Test with both approvals but inactive status
        $application->update([
            'security_approval_status' => 'approved',
            'privacy_approval_status' => 'approved',
            'status' => 'inactive'
        ]);
        $this->assertFalse($application->isAccessible());
    }
}
