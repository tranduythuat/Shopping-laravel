<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Sliders\SlidersRepositoryInterface;
use App\Traits\JsonData;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    use JsonData;

    protected $slidersRepo;

    public function __construct(SlidersRepositoryInterface $slidersRepo)
    {
        $this->slidersRepo = $slidersRepo;
    }

    public function index()
    {
        return view('admin.sliders.index');
    }

    public function getAll()
    {
        try {
            $data = $this->slidersRepo->getAll();

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function getNumber()
    {
        try {
            $data = $this->slidersRepo->getNumberSlider();

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function store(Request $request)
    {
        try {
            if(isset($request->slider_id) && $request->slider_id !== null){
                $validator = Validator::make($request->all(), [
                    'image_path' => 'image:jpg,png,jpeg',
                    'title_01' => 'required|min:2',
                    'title_02' => 'required|min:2',
                    'link' => 'required'
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'image_path' => 'required|image:jpg,png,jpeg',
                    'title_01' => 'required|min:2',
                    'title_02' => 'required|min:2',
                    'link' => 'required'
                ]);
            }


            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $result = $this->slidersRepo->storeSliderJson($request);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'slider_id' => $result['slider']->id,
                'success' => $result['message']
            ], 201);

        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $data = $this->slidersRepo->getDetailSlider($request->all());

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $result = $this->slidersRepo->deleteSlider($request->slider_id);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonMsgResult(false, $result['message'], 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    private function jsonMsgResult($errors, $success, $statusCode)
    {
        $result = array(
            'errors' => $errors,
            'success' => $success,
            'statusCode' => $statusCode
        );

        return response()->json($result, $result['statusCode']);
    }
}
