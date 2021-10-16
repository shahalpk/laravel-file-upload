<?php


namespace Shahalpk\FileUpload;


use Illuminate\Support\Facades\Facade;

class FileUploadFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'file-upload';
    }
}
