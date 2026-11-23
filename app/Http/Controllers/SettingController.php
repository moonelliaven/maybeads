<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Tampilkan form pengaturan website
     */
    public function index(Request $request)
    {
        $settings = SiteSetting::getSettings();
        $currentTab = $request->query('tab', 'website');
        return view('admin.settings', compact('settings', 'currentTab'));
    }

    /**
     * Simpan pembaruan pengaturan website
     */
    public function update(Request $request)
    {
        $settings = SiteSetting::getSettings();

        $validated = $request->validate([
            'site_name'      => 'required|string|max:100',
            'site_tagline'   => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:30',
            'whatsapp'       => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:100',
            'address'        => 'nullable|string',
            'maps_embed_url' => 'nullable|string',
            'opening_hours'  => 'nullable|string|max:100',
            'instagram'      => 'nullable|string|max:100',
            'tiktok'         => 'nullable|string|max:100',
            'logo'           => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:3072',
            'bank_info'      => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $filename = 'site_logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $logoFile->storeAs('settings', $filename, 'public');
            $validated['logo'] = $filename;
        }

        $settings->update($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan website berhasil disimpan.');
    }
}

