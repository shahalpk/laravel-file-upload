<?php


namespace Shahalpk\FileUpload;


use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    const CONFIRMATION_STATUS_PENDING = 1;
    const CONFIRMATION_STATUS_CONFIRMED = 2;

}
