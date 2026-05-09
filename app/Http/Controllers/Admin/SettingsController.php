<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingsService $settingsService
    ) {}

    public function index()
    {
        return view('admin.settings.index', [
            'settings' => $this->settingsService->getAllSettings(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'        => ['nullable', 'string', 'max:100'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'site_email'       => ['nullable', 'email'],
            'posts_per_page'   => ['nullable', 'integer', 'min:1', 'max:100'],
            'theme_mode'       => ['nullable', 'in:light,dark,system'],
            'theme_color'      => ['nullable', 'string', 'max:20'],
            'admin_theme'      => ['nullable', 'in:default,minimal,dark'],
            'site_logo'        => ['nullable', 'image', 'max:2048'],
            'site_favicon'     => ['nullable', 'image', 'max:512'],
        ]);

        $this->settingsService->updateFromRequest($request);

        return back()->with('success', '✅ Settings saved successfully!');
    }

    public function clearCache(Request $request)
    {
        $message = $this->settingsService->clearCache(
            $request->input('type', 'all')
        );

        return response()->json(['message' => $message]);
    }
}
