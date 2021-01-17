<?php

namespace App\Repositories\Colors;

use App\Models\Color;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Str;

class ColorsRepository implements ColorsRepositoryInterface
{
    protected $model;

    public function __construct(Color $model) {
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

    public function find($id)
    {
        try {
            $data = $this->model::findOrFail($id);

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getColorActive()
    {
        try {
            $data = $this->model::where('status', 'active')->orderBy('name', 'asc')->get();
            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getNumberColor()
    {
        try {
            $data = [];
            $data['colorActive'] = $this->model::where('status', 'active')->count();
            $data['colorInactive'] = $this->model::where('status', 'inactive')->count();
            $data['colorSum'] = $data['colorActive'] + $data['colorInactive'];
            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function storeColorJson($request)
    {
        try {
            $data = [];

            if(isset($request['color_id']) && $request['color_id'] !== 0){
                $color_id = $request['color_id'];
                $color = $this->model::findOrFail($color_id);
                $color->name = $request['name'];
                $color->slug = Str::slug($request['name']);
                $color->code = $request['color'];
                $color->status = $request['status'];
                $color->save();

                $message = "Updated cuccessfully!";
            }else{
                $color = new $this->model;
                $color->name = $request['name'];
                $color->slug = Str::slug($request['name']);
                $color->code = $request['color'];
                $color->status = $request['status'];
                $color->save();

                $message = "Created cuccessfully!";
            }

            $data = [
                'color' => $color,
                'message' => $message
            ];

            return $data;

        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getDetailColor($request)
    {
        try {
            if(isset($request['color_id'])){
                $id = $request['color_id'];
                $color = $this->model::findOrFail($id);
            }

            return $color;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function deleteColor($id)
    {
        try {
            $data = [];
            if(isset($id)){
                $color = $this->model::findOrFail($id);
                $color->delete();
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
            if(isset($request['color_id']) && isset($request['isChecked'])){
                $id = $request['color_id'];
                if($request['isChecked'] == 'true'){
                    $status = 'active';
                }else{
                    $status = 'inactive';
                }
                $color = $this->model::findOrFail($id);

                $color->status = $status;
                $color->save();

                $message = 'Updated status successfully!';
            }

            $data = [
                'color' => $color,
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
            if(isset($request['colorIds'])){
                $color_ids = $request['colorIds'];
                if (count($color_ids) === 0) {
                    $data['code'] = 500;
                    $data['message'] = 'Please Select atleast one checkbox';
                }else{
                    $deleteColors = $this->model::whereIn('id', $color_ids)->delete();
                    $data['message'] = 'Deleted successfully';
                    $data['code'] = 200;
                    $data['number_color'] = $deleteColors;
                }

                return $data;
            }
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getColorByProductColor($colorByProductIds = [])
    {
        try {
            $colors = $this->model::where('status', 'active')->get();
            $colorByProductColors = $colors->diff($this->model::whereIn('id', $colorByProductIds)->get());

            return $colorByProductColors;
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
