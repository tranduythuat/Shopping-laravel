<?php

namespace App\Repositories\Sizes;

use App\Models\Size;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Str;

class SizesRepository implements SizesRepositoryInterface
{
    protected $model;

    public function __construct(Size $model) {
        $this->model = $model;
    }

    public function getAll() {
        try {
            $data = $this->model::orderBy('id', 'desc')->get();
            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getSizeActive()
    {
        try {
            $data = $this->model::where('status', 'active')->orderBy('id', 'desc')->get();
            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function find($id)
    {
        try {
            $data = $this->model::findOrFail($id);

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getNumberSize()
    {
        try {
            $data = [];
            $data['sizeActive'] = $this->model::where('status', 'active')->count();
            $data['sizeInactive'] = $this->model::where('status', 'inactive')->count();
            $data['sizeSum'] = $data['sizeActive'] + $data['sizeInactive'];
            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function storeSizeJson($request)
    {
        try {
            $data = [];

            if(isset($request['size_id']) && $request['size_id'] !== 0){
                $size_id = $request['size_id'];
                $size = $this->model::findOrFail($size_id);
                $size->name = $request['name'];
                $size->slug = Str::slug($request['name']);
                $size->status = $request['status'];
                $size->save();

                $message = "Updated cuccessfully!";
            }else{
                $size = new $this->model;
                $size->name = $request['name'];
                $size->slug = Str::slug($request['name']);
                $size->status = $request['status'];
                $size->save();

                $message = "Created cuccessfully!";
            }

            $data = [
                'size' => $size,
                'message' => $message
            ];

            return $data;

        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getDetailSize($request)
    {
        try {
            if(isset($request['size_id'])){
                $id = $request['size_id'];
                $size = $this->model::findOrFail($id);
            }

            return $size;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function deleteSize($id)
    {
        try {
            $data = [];
            if(isset($id)){
                $size = $this->model::findOrFail($id);
                $size->delete();
                $message = 'Deleted successfully!';
            }
            $data = [
                'message' => $message
            ];

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function updateStatus($request)
    {
        try {
            $data = [];
            if(isset($request['size_id']) && isset($request['isChecked'])){
                $id = $request['size_id'];
                if($request['isChecked'] == 'true'){
                    $status = 'active';
                }else{
                    $status = 'inactive';
                }
                $size = $this->model::findOrFail($id);
                $size->status = $status;
                $size->save();

                $message = 'Updated status successfully!';
            }

            $data = [
                'size' => $size,
                'message' => $message
            ];

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function deleteMutilRow($request)
    {
        try {
            $data = [];
            if(isset($request['sizeIds'])){
                $size_ids = $request['sizeIds'];
                if (count($size_ids) === 0) {
                    $data['code'] = 500;
                    $data['message'] = 'Please Select atleast one checkbox';
                }else{
                    $deleteSizes = $this->model::whereIn('id', $size_ids)->delete();
                    $data['message'] = 'Deleted successfully';
                    $data['code'] = 200;
                    $data['number_size'] = $deleteSizes;
                }

                return $data;
            }
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
