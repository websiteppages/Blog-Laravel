@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('topbar-actions')
    <button form="settings-form" type="submit"
            class="btn-primary text-xs py-2 px-4">
        💾 Save All Settings
    </button>
@endsection

@push('styles')
<style>
    /* ── Settings tabs ──────────────────────────────── */
    .settings-tabs {
        display: flex;
        gap: 0;
        border-bottom: 1px solid rgba(13,13,13,0.10);
        margin-bottom: 20px;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .settings-tabs::-webkit-scrollbar { display: none; }
    .settings-tab {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 500;
        color: #7a7a7a;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        background: none;
        border-top: none;
        border-left: none;
        border-right: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: color 0.15s;
        white-space: nowrap;
    }
    .settings-tab:hover { color: #0d0d0d; }
    .settings-tab.active {
        color: #0d0d0d;
        border-bottom-color: #c9883a;
        font-weight: 600;
    }
    .settings-panel { display: none; }
    .settings-panel.active { display: block; }

    /* ── Toggle switch ──────────────────────────────── */
    .toggle-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid rgba(13,13,13,0.06);
    }
    .toggle-wrap:last-child { border-bottom: none; }
    .toggle-label { flex: 1; }
    .toggle-label p { font-size: 13px; font-weight: 500; color: #0d0d0d; }
    .toggle-label span { font-size: 11px; color: #7a7a7a; }

    .toggle {
        position: relative;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }
    .toggle input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute;
        inset: 0;
        background: #d1d5db;
        border-radius: 99px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .toggle-slider::before {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        left: 3px;
        top: 3px;
        background: #fff;
        border-radius: 50%;
        transition: transform 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }
    .toggle input:checked + .toggle-slider { background: #c9883a; }
    .toggle input:checked + .toggle-slider::before { transform: translateX(20px); }

    /* ── Color theme options ────────────────────────── */
    .theme-opt {
        border: 2px solid rgba(13,13,13,0.10);
        border-radius: 12px;
        padding: 14px;
        cursor: pointer;
        transition: all 0.15s;
        text-align: center;
    }
    .theme-opt:hover { border-color: #c9883a; }
    .theme-opt.selected {
        border-color: #c9883a;
        background: #f5e4c3;
    }
    .theme-opt-icon { font-size: 28px; margin-bottom: 6px; }
    .theme-opt-label { font-size: 11px; font-weight: 600; color: #4a4a4a; }
    .theme-opt.selected .theme-opt-label { color: #c9883a; }

    /* ── Color swatches ─────────────────────────────── */
    .color-swatch {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.15s;
    }
    .color-swatch:hover,
    .color-swatch.selected { border-color: #0d0d0d; transform: scale(1.1); }

    /* ── Section card ───────────────────────────────── */
    .settings-card {
        background: #fff;
        border: 1px solid rgba(13,13,13,0.08);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 16px;
    }
    .settings-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        border-bottom: 1px solid rgba(13,13,13,0.06);
        background: #fafaf8;
    }
    .settings-card-header h3 {
        font-size: 13px;
        font-weight: 700;
        color: #0d0d0d;
    }
    .settings-card-body { padding: 20px; }
</style>
@endpush

@section('admin-content')

<form id="settings-form" method="POST" action="{{ route('admin.settings.update') }}"
      enctype="multipart/form-data">
@csrf
@method('POST')

{{-- Tabs navigation --}}
<div class="settings-tabs">
    @foreach([
        ['general',    '⚙️', 'General'],
        ['appearance', '🎨', 'Appearance'],
        ['posts',      '📝', 'Posts'],
        ['comments',   '💬', 'Comments'],
        ['seo',        '🔍', 'SEO'],
        ['social',     '🌐', 'Social'],
        ['advanced',   '🔧', 'Advanced'],
    ] as [$id, $icon, $label])
    <button type="button"
            class="settings-tab {{ $id === 'general' ? 'active' : '' }}"
            onclick="switchTab('{{ $id }}')">
        {{ $icon }} {{ $label }}
    </button>
    @endforeach
</div>

{{-- ══════════════════════════════════════════
     TAB: GENERAL
══════════════════════════════════════════ --}}
<div id="panel-general" class="settings-panel active">

    <div class="settings-card">
        <div class="settings-card-header">
            <span class="text-lg">🏠</span>
            <h3>Site Information</h3>
        </div>
        <div class="settings-card-body grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="form-label">Site Name</label>
                <input name="site_name" type="text"
                       value="{{ $settings['site_name'] ?? config('app.name') }}"
                       class="form-input"
                       placeholder="Inkwell Blog">
            </div>

            <div>
                <label class="form-label">Contact Email</label>
                <input name="site_email" type="email"
                       value="{{ $settings['site_email'] ?? '' }}"
                       class="form-input"
                       placeholder="hello@yourblog.com">
            </div>

            <div class="md:col-span-2">
                <label class="form-label">Site Description</label>
                <textarea name="site_description" rows="2"
                          class="form-textarea resize-none"
                          placeholder="A short description of your blog…">{{ $settings['site_description'] ?? '' }}</textarea>
            </div>

            <div>
                <label class="form-label">Timezone</label>
                <select name="timezone" class="form-select">
                    @foreach([
                        'UTC', 'Asia/Kolkata', 'Asia/Colombo',
                        'America/New_York', 'America/Los_Angeles',
                        'Europe/London', 'Europe/Paris',
                        'Asia/Tokyo', 'Asia/Singapore',
                        'Australia/Sydney',
                    ] as $tz)
                    <option value="{{ $tz }}"
                            {{ ($settings['timezone'] ?? 'UTC') === $tz ? 'selected' : '' }}>
                        {{ $tz }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Date Format</label>
                <select name="date_format" class="form-select">
                    @foreach([
                        'M j, Y'    => 'Jan 1, 2024',
                        'd/m/Y'     => '01/01/2024',
                        'm/d/Y'     => '01/01/2024 (US)',
                        'Y-m-d'     => '2024-01-01',
                        'j F Y'     => '1 January 2024',
                        'D, d M Y'  => 'Mon, 01 Jan 2024',
                    ] as $fmt => $example)
                    <option value="{{ $fmt }}"
                            {{ ($settings['date_format'] ?? 'M j, Y') === $fmt ? 'selected' : '' }}>
                        {{ $example }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Logo & Favicon --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <span class="text-lg">🖼</span>
            <h3>Logo & Favicon</h3>
        </div>
        <div class="settings-card-body grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="form-label">Site Logo</label>
                @if(!empty($settings['site_logo']))
                <div class="mb-3 p-3 bg-[#f7f6f3] rounded-xl inline-block">
                    <img src="{{ \Storage::disk('public')->url($settings['site_logo']) }}"
                         alt="Logo" class="h-10 object-contain">
                </div>
                @endif
                <input type="file" name="site_logo" accept="image/*"
                       class="form-input text-sm py-2">
                <p class="text-[11px] text-gray-400 mt-1">
                    PNG, SVG recommended · Max 2MB
                </p>
            </div>

            <div>
                <label class="form-label">Favicon</label>
                @if(!empty($settings['site_favicon']))
                <div class="mb-3 p-3 bg-[#f7f6f3] rounded-xl inline-block">
                    <img src="{{ \Storage::disk('public')->url($settings['site_favicon']) }}"
                         alt="Favicon" class="w-8 h-8 object-contain">
                </div>
                @endif
                <input type="file" name="site_favicon" accept="image/*"
                       class="form-input text-sm py-2">
                <p class="text-[11px] text-gray-400 mt-1">
                    ICO, PNG · 32×32px recommended
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     TAB: APPEARANCE
══════════════════════════════════════════ --}}
<div id="panel-appearance" class="settings-panel">

    {{-- Theme Mode --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <span class="text-lg">🌓</span>
            <h3>Theme Mode</h3>
        </div>
        <div class="settings-card-body">
            <p class="text-sm text-gray-500 mb-4">
                Choose how the site appears to visitors.
            </p>
            <input type="hidden" name="theme_mode" id="theme-mode-input"
                   value="{{ $settings['theme_mode'] ?? 'light' }}">

            <div class="grid grid-cols-3 gap-3">
                @foreach([
                    ['light',  '☀️',  'Light',  'Clean bright interface'],
                    ['dark',   '🌙',  'Dark',   'Easy on the eyes at night'],
                    ['system', '💻',  'System', 'Follows OS preference'],
                ] as [$val, $icon, $label, $desc])
                <div class="theme-opt {{ ($settings['theme_mode'] ?? 'light') === $val ? 'selected' : '' }}"
                     onclick="selectThemeMode('{{ $val }}', this)">
                    <div class="theme-opt-icon">{{ $icon }}</div>
                    <div class="theme-opt-label">{{ $label }}</div>
                    <div class="text-[10px] text-gray-400 mt-1">{{ $desc }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Admin Theme --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <span class="text-lg">🖥</span>
            <h3>Admin Panel Style</h3>
        </div>
        <div class="settings-card-body">
            <input type="hidden" name="admin_theme" id="admin-theme-input"
                   value="{{ $settings['admin_theme'] ?? 'default' }}">

            <div class="grid grid-cols-3 gap-3">
                @foreach([
                    ['default', '✨', 'Default',  '#f7f6f3 cream background'],
                    ['minimal', '⬜', 'Minimal',  'Pure white, no noise'],
                    ['dark',    '⬛', 'Dark Mode', 'Full dark admin panel'],
                ] as [$val, $icon, $label, $desc])
                <div class="theme-opt {{ ($settings['admin_theme'] ?? 'default') === $val ? 'selected' : '' }}"
                     onclick="selectAdminTheme('{{ $val }}', this)">
                    <div class="theme-opt-icon">{{ $icon }}</div>
                    <div class="theme-opt-label">{{ $label }}</div>
                    <div class="text-[10px] text-gray-400 mt-1">{{ $desc }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Brand Color --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <span class="text-lg">🎨</span>
            <h3>Brand Color</h3>
        </div>
        <div class="settings-card-body">
            <p class="text-sm text-gray-500 mb-4">
                Primary accent color used throughout the site.
            </p>
            <input type="hidden" name="theme_color" id="theme-color-input"
                   value="{{ $settings['theme_color'] ?? '#c9883a' }}">

            {{-- Presets --}}
            <div class="flex flex-wrap gap-3 mb-5">
                @foreach([
                    '#c9883a' => 'Amber (Default)',
                    '#3b82f6' => 'Blue',
                    '#10b981' => 'Green',
                    '#8b5cf6' => 'Purple',
                    '#ef4444' => 'Red',
                    '#f59e0b' => 'Yellow',
                    '#ec4899' => 'Pink',
                    '#06b6d4' => 'Cyan',
                    '#0d0d0d' => 'Black',
                ] as $color => $name)
                <button type="button"
                        onclick="selectColor('{{ $color }}')"
                        title="{{ $name }}"
                        class="color-swatch {{ ($settings['theme_color'] ?? '#c9883a') === $color ? 'selected' : '' }}"
                        style="background: {{ $color }}">
                </button>
                @endforeach
            </div>

            {{-- Custom color picker --}}
            <div class="flex items-center gap-3">
                <input type="color"
                       id="custom-color-picker"
                       value="{{ $settings['theme_color'] ?? '#c9883a' }}"
                       class="w-10 h-10 rounded-lg border border-black/12 cursor-pointer p-1"
                       oninput="selectColor(this.value)">
                <input type="text"
                       id="custom-color-hex"
                       value="{{ $settings['theme_color'] ?? '#c9883a' }}"
                       class="form-input w-36 font-mono text-sm"
                       placeholder="#c9883a"
                       oninput="selectColor(this.value)">
                <span class="text-sm text-gray-400">Custom color</span>
            </div>
        </div>
    </div>

    {{-- Layout --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <span class="text-lg">📐</span>
            <h3>Layout Options</h3>
        </div>
        <div class="settings-card-body space-y-4">

            <div>
                <label class="form-label">Sidebar Position</label>
                <div class="grid grid-cols-2 gap-3 max-w-xs">
                    @foreach(['left' => '⬅ Left', 'right' => '➡ Right'] as $val => $label)
                    <div class="theme-opt {{ ($settings['sidebar_position'] ?? 'right') === $val ? 'selected' : '' }}"
                         onclick="document.getElementById('sidebar-pos-input').value='{{ $val }}';
                                  this.parentElement.querySelectorAll('.theme-opt').forEach(o=>o.classList.remove('selected'));
                                  this.classList.add('selected')">
                        <div class="theme-opt-label py-1">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="sidebar_position" id="sidebar-pos-input"
                       value="{{ $settings['sidebar_position'] ?? 'right' }}">
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     TAB: POSTS
══════════════════════════════════════════ --}}
<div id="panel-posts" class="settings-panel">

    <div class="settings-card">
        <div class="settings-card-header">
            <span class="text-lg">📝</span>
            <h3>Post Display Settings</h3>
        </div>
        <div class="settings-card-body space-y-4">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Posts Per Page</label>
                    <input type="number" name="posts_per_page"
                           value="{{ $settings['posts_per_page'] ?? 9 }}"
                           min="1" max="100"
                           class="form-input">
                </div>

                <div>
                    <label class="form-label">Default Sort Order</label>
                    <select name="posts_sort" class="form-select">
                        <option value="latest"  {{ ($settings['posts_sort'] ?? 'latest') === 'latest'  ? 'selected' : '' }}>Latest First</option>
                        <option value="oldest"  {{ ($settings['posts_sort'] ?? '') === 'oldest'  ? 'selected' : '' }}>Oldest First</option>
                        <option value="popular" {{ ($settings['posts_sort'] ?? '') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="likes"   {{ ($settings['posts_sort'] ?? '') === 'likes'   ? 'selected' : '' }}>Most Liked</option>
                    </select>
                </div>
            </div>

            {{-- Toggles --}}
            <div class="divide-y divide-black/5">
                @foreach([
                    ['show_reading_time', 'Show Reading Time', 'Display estimated reading time on posts', '1'],
                    ['show_views_count',  'Show View Count',   'Show how many people viewed each post',  '1'],
                    ['show_likes_count',  'Show Like Count',   'Display like count on posts',            '1'],
                    ['show_author_bio',   'Show Author Bio',   'Display author bio below each post',     '1'],
                    ['show_related_posts','Show Related Posts','Display related posts section',          '1'],
                    ['show_social_share', 'Social Share Buttons','Show share buttons on posts',         '1'],
                ] as [$key, $label, $desc, $default])
                <div class="toggle-wrap">
                    <div class="toggle-label">
                        <p>{{ $label }}</p>
                        <span>{{ $desc }}</span>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="{{ $key }}" value="1"
                               {{ ($settings[$key] ?? $default) === '1' ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     TAB: COMMENTS
══════════════════════════════════════════ --}}
<div id="panel-comments" class="settings-panel">

    <div class="settings-card">
        <div class="settings-card-header">
            <span class="text-lg">💬</span>
            <h3>Comment Settings</h3>
        </div>
        <div class="settings-card-body">
            <div class="divide-y divide-black/5">
                @foreach([
                    ['comments_enabled',      'Enable Comments',          'Allow users to comment on posts',        '1'],
                    ['comments_moderation',   'Require Moderation',       'Comments need approval before appearing', '1'],
                    ['allow_guest_comments',  'Allow Guest Comments',     'Non-logged-in users can comment',         '0'],
                    ['notify_on_comment',     'Email Notifications',      'Get email when new comment is posted',    '1'],
                    ['close_old_comments',    'Close Old Post Comments',  'Auto-close comments on old posts',        '0'],
                    ['show_comment_count',    'Show Comment Count',       'Display comment count on post cards',     '1'],
                ] as [$key, $label, $desc, $default])
                <div class="toggle-wrap">
                    <div class="toggle-label">
                        <p>{{ $label }}</p>
                        <span>{{ $desc }}</span>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="{{ $key }}" value="1"
                               {{ ($settings[$key] ?? $default) === '1' ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                <label class="form-label">Close Comments After (Days)</label>
                <input type="number" name="close_comments_days"
                       value="{{ $settings['close_comments_days'] ?? 90 }}"
                       min="1" max="3650"
                       class="form-input max-w-xs">
                <p class="text-[11px] text-gray-400 mt-1">
                    Only applies if "Close Old Post Comments" is enabled.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     TAB: SEO
══════════════════════════════════════════ --}}
<div id="panel-seo" class="settings-panel">

    <div class="settings-card">
        <div class="settings-card-header">
            <span class="text-lg">🔍</span>
            <h3>SEO Settings</h3>
        </div>
        <div class="settings-card-body space-y-4">

            <div>
                <label class="form-label">Google Analytics ID</label>
                <input name="analytics_id" type="text"
                       value="{{ $settings['analytics_id'] ?? '' }}"
                       class="form-input"
                       placeholder="G-XXXXXXXXXX">
                <p class="text-[11px] text-gray-400 mt-1">
                    Your Google Analytics 4 Measurement ID
                </p>
            </div>

            <div>
                <label class="form-label">Robots.txt Content</label>
                <textarea name="seo_robots" rows="5"
                          class="form-textarea font-mono text-sm"
                          placeholder="User-agent: *&#10;Allow: /">{{ $settings['seo_robots'] ?? "User-agent: *\nAllow: /" }}</textarea>
            </div>

            <div>
                <label class="form-label">Meta Keywords (site-wide)</label>
                <input name="meta_keywords" type="text"
                       value="{{ $settings['meta_keywords'] ?? '' }}"
                       class="form-input"
                       placeholder="blog, technology, design, Laravel">
            </div>

            <div class="divide-y divide-black/5">
                @foreach([
                    ['seo_open_graph',   'Open Graph Tags',      'Enable Facebook/LinkedIn preview cards',  '1'],
                    ['seo_twitter_card', 'Twitter Cards',         'Enable Twitter preview cards',            '1'],
                    ['seo_schema',       'JSON-LD Schema',        'Add structured data for search engines',  '1'],
                    ['seo_sitemap',      'Auto Sitemap',          'Generate sitemap.xml automatically',      '1'],
                ] as [$key, $label, $desc, $default])
                <div class="toggle-wrap">
                    <div class="toggle-label">
                        <p>{{ $label }}</p>
                        <span>{{ $desc }}</span>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="{{ $key }}" value="1"
                               {{ ($settings[$key] ?? $default) === '1' ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     TAB: SOCIAL
══════════════════════════════════════════ --}}
<div id="panel-social" class="settings-panel">

    <div class="settings-card">
        <div class="settings-card-header">
            <span class="text-lg">🌐</span>
            <h3>Social Media Links</h3>
        </div>
        <div class="settings-card-body space-y-4">

            @foreach([
                ['social_twitter',   '🐦', 'Twitter / X',  'https://twitter.com/username'],
                ['social_github',    '🐙', 'GitHub',        'https://github.com/username'],
                ['social_linkedin',  '💼', 'LinkedIn',      'https://linkedin.com/in/username'],
                ['social_instagram', '📸', 'Instagram',     'https://instagram.com/username'],
                ['social_facebook',  '📘', 'Facebook',      'https://facebook.com/page'],
                ['social_youtube',   '📺', 'YouTube',       'https://youtube.com/@channel'],
                ['social_discord',   '🎮', 'Discord',       'https://discord.gg/invite'],
                ['social_rss',       '📡', 'RSS Feed URL',  '/feed.xml'],
            ] as [$key, $icon, $label, $placeholder])
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center
                             text-lg flex-shrink-0">
                    {{ $icon }}
                </div>
                <div class="flex-1">
                    <label class="form-label mb-1">{{ $label }}</label>
                    <input type="text" name="{{ $key }}"
                           value="{{ $settings[$key] ?? '' }}"
                           class="form-input text-sm"
                           placeholder="{{ $placeholder }}">
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     TAB: ADVANCED
══════════════════════════════════════════ --}}

    @include('admin.settings.advancedTab')


{{-- Sticky save bar --}}
<div class="fixed bottom-0 left-256px right-0 z-40 bg-white border-t
             border-black/10 px-6 py-3 flex items-center justify-between"
     style="left: 256px">
    <p class="text-xs text-gray-400">
        Changes will be applied immediately after saving.
    </p>
    <button form="settings-form" type="submit"
            class="btn-primary text-sm py-2.5 px-8">
        💾 Save All Settings
    </button>
</div>

<div style="height: 60px"></div>

</form>

@endsection

@push('scripts')
<script>
// ── Tab switching ─────────────────────────────────────────────
    function switchTab(id, element = null) {

        // Hide all panels
        document.querySelectorAll('.settings-panel').forEach(panel => {
            panel.classList.remove('active');
        });

        // Remove active from all tabs
        document.querySelectorAll('.settings-tab').forEach(tab => {
            tab.classList.remove('active');
        });

        // Show selected panel
        document.getElementById('panel-' + id)?.classList.add('active');

        // Activate clicked tab
        if (element) {
            element.classList.add('active');
        }

        // Save active tab
        localStorage.setItem('activeSettingsTab', id);
    }

    // Restore tab after refresh
    document.addEventListener('DOMContentLoaded', () => {

        const savedTab = localStorage.getItem('activeSettingsTab') || 'general';

        const activeButton = document.querySelector(
            `.settings-tab[data-tab="${savedTab}"]`
        );

        switchTab(savedTab, activeButton);
    });

// ── Theme mode ────────────────────────────────────────────────
function selectThemeMode(val, el) {
    document.getElementById('theme-mode-input').value = val;
    el.closest('.grid').querySelectorAll('.theme-opt')
      .forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
}

// ── Admin theme ───────────────────────────────────────────────
function selectAdminTheme(val, el) {
    document.getElementById('admin-theme-input').value = val;
    el.closest('.grid').querySelectorAll('.theme-opt')
      .forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
}

// ── Brand color ───────────────────────────────────────────────
function selectColor(hex) {
    if (!hex.startsWith('#')) hex = '#' + hex;
    document.getElementById('theme-color-input').value = hex;
    document.getElementById('custom-color-picker').value = hex;
    document.getElementById('custom-color-hex').value   = hex;

    document.querySelectorAll('.color-swatch').forEach(s => {
        s.classList.toggle('selected', s.style.background === hex
            || s.style.backgroundColor === hex);
    });
}

// ── Cache clear ───────────────────────────────────────────────
async function clearCache(type) {
    const result = document.getElementById('cache-result');
    result.textContent = 'Clearing…';
    result.className = 'mt-3 p-3 rounded-lg text-sm bg-blue-50 text-blue-700';
    result.classList.remove('hidden');

    try {
        const res = await fetch(`/admin/settings/cache?type=${type}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const json = await res.json();
        result.textContent = json.message || 'Cache cleared!';
        result.className = 'mt-3 p-3 rounded-lg text-sm bg-green-50 text-green-700';
    } catch (e) {
        result.textContent = 'Failed to clear cache.';
        result.className = 'mt-3 p-3 rounded-lg text-sm bg-red-50 text-red-700';
    }
}
</script>
@endpush
