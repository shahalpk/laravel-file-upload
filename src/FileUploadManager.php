<?php


namespace Shahalpk\FileUpload;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadManager
{

    protected $disk;

    public function __construct()
    {
        $this->disk = config('file-upload.disk');
    }

    protected function _derivePath($module, $category, $subCategory){
        $path = null;
        if (is_string($module)){
            $path = $module ;
        } else {
            $path = 'default';
        }

        if (is_string($category)){
            $path = $path . '/' . $category;
        }

        if (is_string($subCategory)) {
            $path = $path . '/' . $subCategory;
        }

        return $path;
    }


    /**
     * Upload the files to file storage and insert record into
     * file info table with given `$module`. file status will be
     * pending until its confirmed later. Pending files be eventually
     * deleted.
     *
     * @param $file
     * @param $category
     * @param $subCategory
     * @param $module
     * @return mixed
     */
    public function uploadFile($file, $category = null, $subCategory = null, $module = null){
        $fileUpload = new FileUpload();
        $fileUpload->disk = $this->disk;
        $fileUpload->module = $module ?? 'default';
        $fileUpload->category = $category;
        $fileUpload->sub_category = $subCategory;
        $fileUpload->confirmation_status = FileUpload::CONFIRMATION_STATUS_PENDING;

        $uploadName = Carbon::now()->format("His-Ymd-")  . strtoupper(Str::random(5)) . 'I' . $fileUpload->id .  '.' . $file->extension();
        $uploadPath = $this->_derivePath($module, $category, $subCategory);
        $storagePath = Storage::disk($this->disk)->putFileAs($uploadPath, $file, $uploadName);

        $fileUpload->path = $storagePath;
        $fileUpload->file_id = Str::uuid();
        $fileUpload->save();
        return $fileUpload;
    }

    /**
     * Change the status of all uploaded files to `COMPLETED`
     *
     * @param $module
     * @param $fileIdArr
     */
    public function confirmFiles($fileIdArr){
        if (is_string($fileIdArr)){
            $fileIdArr = [$fileIdArr];
        }
        foreach ($fileIdArr as $fileId){
            FileUpload::where('file_id', $fileId)
                ->update(['status' => FileUpload::CONFIRMATION_STATUS_CONFIRMED]);
        }
    }

    public function getFileInfo($fileId){
        $fileUpload = FileUpload::where('file_id', $fileId)->first();
        return $fileUpload;
    }

    public function getFileUrl($fileId){
        if ($fileId == null){
            return null;
        }
        $fileUpload = $this->getFileInfo($fileId);
        if ($fileUpload == null){
            return null;
        }
        return Storage::disk($fileUpload->disk)->url($fileUpload->path);
    }

    public function getFileUrls($fileIds): \Illuminate\Support\Collection
    {
        return collect($fileIds)->map(function ($id){
            return $this->getFileUrl($id);
        });
    }
}
