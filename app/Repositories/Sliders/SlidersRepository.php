<?php

namespace App\Repositories\Sliders;

use App\Models\Slider;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Traits\UploadImageTrait;

class SlidersRepository implements SlidersRepositoryInterface
{
    use UploadImageTrait;

    protected $model;

    public function __construct(Slider $model) {
        $this->model = $model;
    }

    public function getAll()
    {
        try {
            $data = $this->model::orderBy('id', 'desc')->get();

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getNumberSlider()
    {
        try {
            $data = [];
            $data['sliderSum'] = $this->model::all()->count();

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function storeSliderJson($request)
    {
        try {
            $data = [];
            if(isset($request->slider_id) && $request->slider_id !== null){
                $slider_id = $request->slider_id;

                $slider = $this->model::findOrFail($slider_id);

                $slider->title_01 = $request->title_01;
                $slider->title_02 = $request->title_02;
                $slider->link = $request->link;

                $image_path = $this->updateTraitUpLoad($request, 'image_path');
                if(!empty($image_path)){
                    $slider->image_path = $image_path['image_path'];
                    $slider->publicId = $image_path['publicId'];
                }
                $slider->save();

                $message = "Updated cuccessfully!";
            }else{
                $slider = new $this->model;
                $slider->title_01 = $request->title_01;
                $slider->title_02 = $request->title_02;
                $slider->link = $request->link;

                $image_path = $this->storageTraitUpLoad($request, 'image_path');

                if(!empty($image_path)){
                    $slider->image_path = $image_path['image_path'];
                    $slider->publicId = $image_path['publicId'];
                }
                $slider->save();

                $message = "Created cuccessfully!";
            }

            $data = [
                'slider' => $slider,
                'message' => $message
            ];

            return $data;

        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getDetailSlider($request)
    {
        try {
            if(isset($request['slider_id'])){
                $id = $request['slider_id'];
                $data = $this->model::findOrFail($id);
            }

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function deleteSlider($id)
    {
        try {
            $data = [];
            if(isset($id)){
                $slider = $this->model::findOrFail($id);
                $publicId = $slider->publicId;
                if(!empty($publicId)){
                    $this->removeImage($publicId);
                }
                $slider->delete();
                $message = 'Deleted successfully!';

                $data = [
                    'message' => $message
                ];
            }

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    protected function errorResult($msg)
    {
        $message = array(
            'errorMsg' => $msg
        );

        return $message;
    }
}


