<?php

namespace Shahalpk\FileUpload;

use Illuminate\Support\ServiceProvider;

class FileUploadServiceProvider extends ServiceProvider
{

    public $singletons = [
        'file-upload' => FileUploadManager::class
    ];

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/file-upload.php' => config_path('file-upload.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/file-upload.php', 'file-upload'
        );
    }


}
