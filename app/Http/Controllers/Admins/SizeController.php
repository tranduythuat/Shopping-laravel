<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Sizes\SizesRepositoryInterface;
use App\Traits\JsonData;
use App\Http\Requests\SizeStoreRequest;

class SizeController extends Controller
{
    use JsonData;

    protected $sizesRepo;

    public function __construct(SizesRepositoryInterface $sizesRepo)
    {
        $this->sizesRepo = $sizesRepo;
    }

    public function index()
    {
        return view('admin.sizes.index');
    }

    public function getAll()
    {
        $sizes = $this->sizesRepo->getAll();

        return $this->jsonDataResult($sizes, 200);
    }

    public function getNumber()
    {
        try {
            $rowSize = $this->sizesRepo->getNumberSize();

            return $this->jsonDataResult($rowSize, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function store(SizeStoreRequest $request)
    {
        try {
            $result = $this->sizesRepo->storeSizeJson($request->all());

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'size_id' => $result['size']->id,
                'success' => $result['message']
            ], 201);

        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $data = $this->sizesRepo->getDetailSize($request->all());

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $result = $this->sizesRepo->deleteSize($request->size_id);

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
            $result = $this->sizesRepo->updateStatus($request->all());

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'size' => $result['size']->id,
                'success' => $result['message']
            ], 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function deleteRows(Request $request)
    {
        try {
            $result = $this->sizesRepo->deleteMutilRow($request->all());

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'number_size' => $result['number_size'],
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
