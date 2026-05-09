<?php

namespace App\Http\Requests\Invite;

use Illuminate\Foundation\Http\FormRequest;

class InviteMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        $workspace = $this->route('workspace') ?? $this->user()->currentWorkspace;
        return $this->user()->can('manageMembers', $workspace);
    }

    public function rules(): array
    {
        return [
            'email'             => ['required', 'email', 'max:255'],
            'workspace_role_id' => ['required', 'exists:workspace_roles,id'],
        ];
    }
}
