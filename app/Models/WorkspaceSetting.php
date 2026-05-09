<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkspaceSetting extends Model
{
    protected $fillable = ['workspace_id', 'key', 'value'];

    protected function casts(): array
    {
        return [
            // JSON cast handles booleans, numbers, strings, arrays, and null.
            // The 'value' column is nullable in the DB (e.g. max_users = null means unlimited).
            // Laravel's json cast correctly serialises null as SQL NULL when writing,
            // and returns null when reading — no special handling required.
            'value' => 'json',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
}
