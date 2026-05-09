{{-- Shared permissions form for role create/edit --}}
@props(['grouped', 'role' => null])

<div class="space-y-4">
    @foreach($grouped as $group => $permissions)
        @php
            $groupSlug = \Illuminate\Support\Str::slug($group);
        @endphp
        <div class="bg-white border border-black/10 rounded-2xl overflow-hidden">

            {{-- Group header --}}
            <div class="flex items-center justify-between px-5 py-3
                         border-b border-black/8 bg-[#fafaf8]">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-ink">{{ $group }}</h3>
                    <span class="text-[10px] text-gray-400 font-medium">
                        (<span id="count-{{ $groupSlug }}">0</span>/{{ count($permissions) }})
                    </span>
                </div>
                <button type="button"
                        onclick="toggleGroup('{{ $groupSlug }}')"
                        id="toggle-btn-{{ $groupSlug }}"
                        class="text-xs text-amber font-semibold hover:underline">
                    Select all
                </button>
            </div>

            {{-- Permissions grid --}}
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3"
                 data-group="{{ $groupSlug }}">

                @foreach($permissions as $permission)
                <label class="flex items-center gap-2.5 cursor-pointer group
                               p-2 rounded-lg hover:bg-cream-mid transition-colors">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="{{ $permission->value }}"
                        class="perm-check w-4 h-4 rounded accent-amber cursor-pointer flex-shrink-0"
                        data-group="{{ $groupSlug }}"
                        onchange="updateGroupCount('{{ $groupSlug }}')"
                        {{ ($role && in_array($permission->value, $role->permissions ?? [])) ? 'checked' : '' }}
                    >
                    <span class="text-sm text-gray-600 group-hover:text-ink
                                  transition-colors select-none leading-tight">
                        {{ ucwords(str_replace(['-'], ' ', $permission->label())) }}
                    </span>
                </label>
                @endforeach

            </div>
        </div>
        @endforeach
    </div>


    {{-- @foreach($grouped as $group => $permissions)
    <div class="border border-gray-100 rounded-lg p-4">
        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">{{ ucfirst($group) }}</h4>
        <div class="grid grid-cols-2 gap-2">
            @foreach($permissions as $permission)
            <label class="flex items-start gap-2.5 cursor-pointer group">
                <input type="checkbox" name="permissions[]" value="{{ $permission->value }}"
                    {{ ($role && in_array($permission->value, $role->permissions ?? [])) ? 'checked' : '' }}
                    class="mt-0.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <div>
                    <p class="text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition">
                        {{ $permission->label() }}
                    </p>
                    <p class="text-xs text-gray-400 font-mono">{{ $permission->value }}</p>
                </div>
            </label>
            @endforeach
        </div>
    </div>
    @endforeach --}}
</div>

