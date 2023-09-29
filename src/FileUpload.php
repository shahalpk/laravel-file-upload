<?php

namespace Shahalpk\FileUpload;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileUpload extends Model
{
    const CONFIRMATION_STATUS_PENDING = 1;
    const CONFIRMATION_STATUS_CONFIRMED = 2;

    use SoftDeletes;
}
