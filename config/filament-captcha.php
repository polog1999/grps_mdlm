<?php

use Filament\Support\Enums\Size;

return [

    // optional, default is 5
    'length' => 5,

    // optional, default is 'abcdefghijklmnpqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'
    'charset' => '23456789',

    'width' => 300,
    'lines' => 3,
    'distortion' => false,
    'height' => 100,

    'background_color' => [255, 255, 255],

    'refresh_button' => [
        'icon' => 'heroicon-o-arrow-path',
        'size' => Size::Medium,
    ],

];
