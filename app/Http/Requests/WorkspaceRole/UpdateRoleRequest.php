<?php

namespace App\Http\Requests\WorkspaceRole;

use App\Enums\WorkspacePermission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $workspace = $this->route('workspace') ?? $this->user()->currentWorkspace;
        return $this->user()->can('manageRoles', $workspace);
    }

    public function rules(): array
    {
        $validPermissions = array_column(WorkspacePermission::cases(), 'value');

        return [
            'name'          => ['sometimes', 'string', 'max:100'],
            'permissions'   => ['required', 'array'],
            'permissions.*' => ['string', Rule::in($validPermissions)],
        ];
    }
}
