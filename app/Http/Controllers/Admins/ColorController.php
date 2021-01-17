<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Colors\ColorsRepositoryInterface;
use App\Traits\JsonData;
use App\Http\Requests\ColorStoreRequest;

class ColorController extends Controller
{
    use JsonData;

    protected $colorsRepo;

    public function __construct(ColorsRepositoryInterface $colorsRepo)
    {
        $this->colorsRepo = $colorsRepo;
    }

    public function index()
    {
        return view('admin.colors.index');
    }

    public function getAll()
    {
        try {
            $colors = $this->colorsRepo->getAll();

            return $this->jsonDataResult($colors, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function store(ColorStoreRequest $request)
    {
        try {
            $result = $this->colorsRepo->storeColorJson($request->all());

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'color_id' => $result['color']->id,
                'success' => $result['message']
            ], 201);

        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function getNumber()
    {
        try {
            $rowColor = $this->colorsRepo->getNumberColor();

            return $this->jsonDataResult($rowColor, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $data = $this->colorsRepo->getDetailColor($request->all());

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $result = $this->colorsRepo->deleteColor($request->color_id);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonMsgResult(false, $result['message'], 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $result = $this->colorsRepo->updateStatus($request->all());

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'color_id' => $result['color']->id,
                'success' => $result['message']
            ], 201);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function deleteRows(Request $request)
    {
        try {
            $result = $this->colorsRepo->deleteMutilRow($request->all());

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'number_color' => $result['number_color'],
                'success' => $result['message']
            ], $result['code']);
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
