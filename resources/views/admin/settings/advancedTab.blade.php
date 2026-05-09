<div id="panel-advanced" class="settings-panel">

    {{-- Maintenance mode --}}
    <div class="settings-card border-amber/30"
         style="border-color: rgba(201,136,58,0.3)">
        <div class="settings-card-header" style="background: #fffbf0">
            <span class="text-lg">⚠️</span>
            <h3 style="color: #92400e">Maintenance Mode</h3>
        </div>
        <div class="settings-card-body">
            <div class="toggle-wrap">
                <div class="toggle-label">
                    <p>Enable Maintenance Mode</p>
                    <span>
                        Site will show maintenance page to visitors.
                        Admins can still access the site.
                    </span>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="maintenance_mode" value="1"
                           {{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="mt-4">
                <label class="form-label">Maintenance Message</label>
                <textarea name="maintenance_message" rows="2"
                          class="form-textarea resize-none"
                          placeholder="We're performing scheduled maintenance. We'll be back shortly!">{{ $settings['maintenance_message'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Cache --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <span class="text-lg">🗄</span>
            <h3>Cache Management</h3>
        </div>
        <div class="settings-card-body">
            <p class="text-sm text-gray-500 mb-4">
                Clear application caches to apply configuration changes.
            </p>
            <div class="flex flex-wrap gap-3">
                <button type="button" onclick="clearCache('all')"
                        class="btn-outline text-sm py-2 px-4">
                    🔄 Clear All Cache
                </button>
                <button type="button" onclick="clearCache('config')"
                        class="btn-outline text-sm py-2 px-4">
                    ⚙️ Config Cache
                </button>
                <button type="button" onclick="clearCache('views')"
                        class="btn-outline text-sm py-2 px-4">
                    🖼 View Cache
                </button>
                <button type="button" onclick="clearCache('permissions')"
                        class="btn-outline text-sm py-2 px-4">
                    🔐 Permission Cache
                </button>
                <button type="button" onclick="clearCache('sessions')"
                        class="btn-outline text-sm py-2 px-4">
                     🗑 Clear Expired Sessions
                </button>
            </div>

            <div id="cache-result" class="hidden mt-3 p-3 rounded-lg text-sm"></div>
        </div>
    </div>

    {{-- Danger zone --}}
    <div class="settings-card" style="border-color: rgba(226,75,74,0.25)">
        <div class="settings-card-header" style="background: #fff5f5">
            <span class="text-lg">🚨</span>
            <h3 style="color: #991b1b">Danger Zone</h3>
        </div>
        <div class="settings-card-body space-y-3">
            <div class="flex items-center justify-between p-3 bg-red-50
                         border border-red-100 rounded-xl">
                <div>
                    <p class="text-sm font-semibold text-red-800">
                        Reset All Settings
                    </p>
                    <p class="text-xs text-red-500">
                        Restore all settings to their defaults
                    </p>
                </div>
                <button type="button"
                        onclick="if(confirm('Reset ALL settings to defaults?')) window.location='/admin/settings/reset'"
                        class="px-4 py-2 rounded-lg text-xs font-semibold
                               border border-red-300 text-red-600
                               hover:bg-red-100 transition-colors">
                    Reset
                </button>
            </div>
        </div>
    </div>
</div>
