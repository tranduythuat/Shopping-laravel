<?php

namespace App\Repositories\Categories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Traits\UploadImageTrait;

class CategoriesRepository implements CategoriesRepositoryInterface
{
    use UploadImageTrait;

    protected $model;

    public function __construct(Category $model) {
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

    public function getActive()
    {
        try {
            $data = $this->model::where('status', 'active')->orderBy('id', 'asc')->get();

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getNumberCategory()
    {
        try {
            $data = [];
            $data['categoryActive'] = $this->model::where('status', 'active')->count();
            $data['categoryInactive'] = $this->model::where('status', 'inactive')->count();
            $data['categorySum'] = $data['categoryActive'] + $data['categoryInactive'];
            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function storeCategoryJson($request)
    {
        try {
            $data = [];

            if(isset($request['category_id']) && $request['category_id'] !== null){
                //  dd($request->file('image_path'));
                $category_id = $request->category_id;

                $category = $this->model::findOrFail($category_id);

                $this->updateStatusProduct($category_id, $request->status);

                $category->name = $request->name;
                $category->slug = Str::slug($request->name);
                $category->status = $request->status;

                $image_path = $this->updateTraitUpLoad($request, 'image_path');
                if(!empty($image_path)){
                    $category->image_path = $image_path['image_path'];
                    $category->publicId = $image_path['publicId'];
                }
                $category->save();

                $message = "Updated cuccessfully!";
            }else{
                // dd('fds');
                $category = new $this->model;

                $category->name = $request->name;
                $category->slug = Str::slug($request->name);
                $category->status = $request->status;
                $image_path = $this->storageTraitUpLoad($request, 'image_path');

                if(!empty($image_path)){
                    $category->image_path = $image_path['image_path'];
                    $category->publicId = $image_path['publicId'];
                }

                $category->save();

                $message = "Created cuccessfully!";
            }

            $data = [
                'category' => $category,
                'message' => $message
            ];

            return $data;

        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    protected function updateStatusProduct($category_id, $status)
    {
        try {
            Product::where('category_id', $category_id)->update(['status' => $status]);
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function updateStatus($request)
    {
        try {
            $data = [];
            if(isset($request['category_id']) && isset($request['isChecked'])){
                $id = $request['category_id'];
                if($request['isChecked'] == 'true'){
                    $status = 'active';
                }else{
                    $status = 'inactive';
                }
                $category = $this->model::findOrFail($id);
                $this->updateStatusProduct($id, $status);

                $category->status = $status;
                $category->save();

                $message = 'Updated status successfully!';
            }

            $data = [
                'category' => $category,
                'message' => $message
            ];

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getDetailCategory($request)
    {
        try {
            if(isset($request['category_id'])){
                $id = $request['category_id'];
                $category = $this->model::findOrFail($id);
            }

            return $category;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function deleteCategory($id)
    {
        try {
            $data = [];
            if(isset($id)){
                $category = $this->model::findOrFail($id);
                $publicId = $category->publicId;
                if(!empty($publicId)){
                    $this->removeImage($publicId);
                }
                $category->delete();
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


