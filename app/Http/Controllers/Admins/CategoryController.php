<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Categories\CategoriesRepositoryInterface;
use App\Traits\JsonData;

class CategoryController extends Controller
{
    use JsonData;

    protected $categoriesRepo;

    public function __construct(CategoriesRepositoryInterface $categoriesRepo)
    {
        $this->categoriesRepo = $categoriesRepo;
    }

    public function index()
    {
        return view('admin.categories.index');
    }

    public function getAll()
    {
        try {
            $data = $this->categoriesRepo->getAll();

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function getNumber()
    {
        try {
            $data = $this->categoriesRepo->getNumberCategory();

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function store(Request $request)
    {
        try {
            if(isset($request->category_id) && $request->category_id !== null){
                $request->validate([
                    'image_path' => 'image:jpg,png,jpeg',
                    'name' => 'required|min:2|unique:categories,name,'.$request->category_id.',id'
                ]);
            }else{
                $request->validate([
                    'image_path' => 'required|image:jpg,png,jpeg',
                    'name' => 'required|min:2|unique:categories,name,'.$request->category_id.',id'
                ]);
            }

            $result = $this->categoriesRepo->storeCategoryJson($request);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'category_id' => $result['category']->id,
                'success' => $result['message']
            ], 201);

        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $data = $this->categoriesRepo->getDetailCategory($request->all());

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $result = $this->categoriesRepo->deleteCategory($request->category_id);

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
            $result = $this->categoriesRepo->updateStatus($request->all());

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'category_id' => $result['category']->id,
                'success' => $result['message']
            ], 201);
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
