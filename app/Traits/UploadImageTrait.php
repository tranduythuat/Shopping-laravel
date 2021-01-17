<?php

namespace App\Traits;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

trait UploadImageTrait
{
    public function storageTraitUpLoad($request, $fieldName)
    {
        if($request->hasFile($fieldName)){
            $file = $request->file($fieldName);
            $result = $file->storeOnCloudinaryAs('storage/'.auth()->id());

            $data = [
                'image_path' => $result->getPath(),
                'publicId' => $result->getPublicId()
            ];

            return $data;
        }

        return null;
    }

    public function updateTraitUpLoad($request, $fieldName)
    {
        if($request->hasFile($fieldName)){
            $file = $request->file($fieldName);
            $result = $file->storeOnCloudinaryAs('storage/'.auth()->id());

            $data = [
                'image_path' => $result->getPath(),
                'publicId' => $result->getPublicId()
            ];

            Cloudinary::destroy($request->hidden_publicId);

            return $data;

        }else{
            $data = [
                'image_path' => $request->hidden_image_path,
                'publicId' => $request->hidden_publicId
            ];

            return $data;
        }
    }

    public function updateMutipleTraitUpLoad($request, $fieldName)
    {
        if($request->hasFile($fieldName)){
            $files = $request->file($fieldName);
            $data = [];
            foreach($files as $file){
                $result = $file->storeOnCloudinaryAs('storage/'.auth()->id().'/product-color');
                $data[] = [
                    'image_path' => $result->getPath(),
                    'publicId' => $result->getPublicId()
                ];
            }
            return $data;
        }

        return null;
    }

    public function removeImage($publicId)
    {
        if(isset($publicId)){
            return Cloudinary::destroy($publicId);
        }

        return false;
    }

    public function removeImages($publicIds = [])
    {
        foreach($publicIds as $publicId){
            Cloudinary::destroy($publicId);
        }

        return false;
    }
}
