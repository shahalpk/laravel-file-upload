<?php

return [
    'disk' => 'public',
    'model_class' => \Shahalpk\FileUpload\FileUpload::class,
    'base_prefix' => env('FILE_UPLOAD_BASE_PREFIX', env('APP_ENV', 'local') . '/' . env('APP_NAME', 'laravel')),
];
