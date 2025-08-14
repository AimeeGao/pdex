<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationSecurityApprovalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('approveSecurity', $this->route('application'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'security_approval_status' => 'required|in:pending,approved,rejected',
            'security_approval_notes' => 'nullable|string|max:2000',
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
                $validator->errors()->add('security_approval_status', 'Cannot modify approvals once both security and privacy have approved.');
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'security_approval_status.required' => 'Security approval status is required.',
            'security_approval_status.in' => 'Invalid security approval status selected.',
        ];
    }
}
