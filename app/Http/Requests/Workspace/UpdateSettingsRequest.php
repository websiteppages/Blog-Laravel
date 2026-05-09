<?php

namespace App\Http\Requests\Workspace;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $workspace = $this->route('workspace') ?? $this->user()->currentWorkspace;
        return $this->user()->can('updateSettings', $workspace);
    }

    public function rules(): array
    {
        return [
            'audit_logs'            => ['boolean'],
            'invites'               => ['boolean'],
            'max_users'             => ['nullable', 'integer', 'min:1', 'max:10000'],
            'email_notifications'   => ['boolean'],
            'notify_on_invite'      => ['boolean'],
            'notify_on_role_change' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Convert checkbox values to booleans
        $this->merge([
            'audit_logs'            => $this->boolean('audit_logs'),
            'invites'               => $this->boolean('invites'),
            'email_notifications'   => $this->boolean('email_notifications'),
            'notify_on_invite'      => $this->boolean('notify_on_invite'),
            'notify_on_role_change' => $this->boolean('notify_on_role_change'),
            // Convert empty string to null so "no limit" is stored as NULL not ""
            'max_users'             => $this->input('max_users') !== '' && $this->input('max_users') !== null
                                        ? (int) $this->input('max_users')
                                        : null,
        ]);
    }
}
