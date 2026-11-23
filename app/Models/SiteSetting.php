<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'site_name',
        'site_tagline',
        'phone',
        'whatsapp',
        'email',
        'address',
        'maps_embed_url',
        'opening_hours',
        'instagram',
        'tiktok',
        'logo',
        'bank_info',
    ];

    public static function getSettings()
    {
        return static::firstOrCreate([], [
            'site_name'    => 'Maybeads',
            'site_tagline' => 'Y2K Handcrafted Beads Accessories',
            'phone'        => '081234567890',
            'whatsapp'     => '6281234567890',
            'email'        => 'info@maybeads.com',
            'address'      => 'Jl. Pangeran Suriansyah, Loktabat Utara, Banjarbaru, Kalimantan Selatan',
            'opening_hours'=> 'Senin - Sabtu: 09:00 - 21:00 WITA',
            'instagram'    => 'maybeads.id',
            'tiktok'       => '@maybeads.official',
        ]);
    }
}

