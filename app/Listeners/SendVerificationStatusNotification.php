<?php

namespace App\Listeners;

use App\Events\IndividualUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVerificationStatusNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(IndividualUpdated $event): void
    {
        // TODO: Implement verification status change notifications
        // Only trigger if verification status actually changed
        if (!$event->verificationStatusChanged()) {
            return;
        }
        
        $individual = $event->individual;
        $updatedBy = $event->updatedBy;
        $changes = $event->getChangedFields();
        $verificationChange = $changes['verification_status'] ?? null;
        
        // Possible actions based on verification status:
        // - 'verified': Send congratulations email, unlock features
        // - 'rejected': Send explanation email with next steps
        // - 'pending': Send acknowledgment that review is in progress
        // - 'unverified': Send reminder about verification requirements
        
        // Example placeholder for future implementation:
        // switch ($individual->verification_status) {
        //     case Individual::VERIFICATION_VERIFIED:
        //         Mail::to($individual->email_address)
        //             ->send(new ProfileVerifiedNotification($individual));
        //         break;
        //         
        //     case Individual::VERIFICATION_REJECTED:
        //         Mail::to($individual->email_address)
        //             ->send(new ProfileRejectedNotification($individual, $verificationChange['reason'] ?? null));
        //         break;
        //         
        //     case Individual::VERIFICATION_PENDING:
        //         Mail::to($individual->email_address)
        //             ->send(new ProfileUnderReviewNotification($individual));
        //         break;
        // }
        
        // Log::info('Verification status notification processed', [
        //     'individual_guid' => $individual->guid,
        //     'old_status' => $verificationChange['from'],
        //     'new_status' => $verificationChange['to'],
        //     'updated_by' => $updatedBy?->name
        // ]);
    }
}
