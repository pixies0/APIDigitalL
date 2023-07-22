<?php

use Illuminate\Support\HtmlString;

if (!function_exists('vite_assets')) {
    /**
     * @return HtmlString
     * @throws Exception
     */
    function vite_assets(): HtmlString
    {
        $devServerIsRunning = false;

        if (app()->environment('local')) {
            try {
                // $devServerIsRunning = file_get_contents(public_path('hot')) == 'dev';
                $devServerIsRunning = str_contains(file_get_contents(public_path('hot')), '127.0.0.1');
                // $devServerIsRunning = true;
            } catch (Exception) {
            }
        }

        if ($devServerIsRunning) {
            $dev_url = file_get_contents(public_path('hot'));
            return new HtmlString(<<<HTML
            <script type="module" src="$dev_url/@vite/client"></script>
            <script type="module" src="$dev_url/resources/js/app.js"></script>
        HTML);
        }
        $manifest = json_decode(file_get_contents(
            public_path('dist/manifest.json')
        ), true);
        return new HtmlString(<<<HTML
        <script type="module" src="/dist/{$manifest['resources/js/app.js']['file']}"></script>
        <link rel="stylesheet" href="/dist/{$manifest['resources/js/app.js']['css'][0]}">
    HTML);
    }
}