<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Center / Institution name (displayed on certificate)
    |--------------------------------------------------------------------------
    */
    'center_name' => env('CERTIFICATE_CENTER_NAME') ?: config('app.name'),
    'center_tagline' => env('CERTIFICATE_CENTER_TAGLINE', 'Authorized Training Center'),

    /*
    |--------------------------------------------------------------------------
    | Certificate image dimensions (PNG export)
    |--------------------------------------------------------------------------
    */
    'image_width' => (int) env('CERTIFICATE_IMAGE_WIDTH', 1200),
    'image_height' => (int) env('CERTIFICATE_IMAGE_HEIGHT', 1697),

    /*
    |--------------------------------------------------------------------------
    | Font path for certificate text (TTF)
    |--------------------------------------------------------------------------
    */
    'font_path' => env('CERTIFICATE_FONT_PATH') ?: public_path('fonts/certificate.ttf'),
];
