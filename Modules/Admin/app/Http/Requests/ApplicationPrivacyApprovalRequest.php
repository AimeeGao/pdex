<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationPrivacyApprovalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('approvePrivacy', $this->route('application'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'privacy_approval_status' => 'required|in:pending,approved,rejected',
            'privacy_approval_notes' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $application = $this->route('application');
            
            // Prevent modifying final approvals
            if (!$application->canModifyApprovals()) {
                $validator->errors()->add('privacy_approval_status', 'Cannot modify approvals once both security and privacy have approved.');
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'privacy_approval_status.required' => 'Privacy approval status is required.',
            'privacy_approval_status.in' => 'Invalid privacy approval status selected.',
        ];
    }
}
