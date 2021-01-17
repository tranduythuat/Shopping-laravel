<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Suppliers\SuppliersRepositoryInterface;
use App\Traits\JsonData;
use Yajra\DataTables\DataTables;
use App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{
    use JsonData;

    protected $suppliersRepo;

    public function __construct(SuppliersRepositoryInterface $suppliersRepo)
    {
        $this->suppliersRepo = $suppliersRepo;
    }

    public function index()
    {
        return view('admin.suppliers.index');
    }

    public function getAll()
    {
        try {
            $data = $this->suppliersRepo->getAll();

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function getNumber()
    {
        try {
            $data = $this->suppliersRepo->getNumberSupplier();

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function store(SupplierRequest $request)
    {
        try {

            // dd($request->all());
            $result = $this->suppliersRepo->storeSupplierJson($request);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'supplier_id' => $result['supplier']->id,
                'success' => $result['message']
            ], 201);

        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $data = $this->suppliersRepo->getDetailSupplier($request->all());

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $result = $this->suppliersRepo->deleteSupplier($request->supplier_id);

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
