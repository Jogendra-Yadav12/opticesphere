<?php

use App\Models\Setting;

if (! function_exists('default_image')) {
    /**
     * Global fallback image URL, set from admin Global Settings.
     */
    function default_image(): string
    {
        static $cached = null;

        if ($cached !== null) {
            return $cached;
        }

        $image = Setting::where('key', 'default_image')->value('value');

        $cached = $image ? asset('images/'.$image) : asset('img/logos/logo.png');

        return $cached;
    }
}
