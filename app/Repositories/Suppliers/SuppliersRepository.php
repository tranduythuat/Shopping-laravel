<?php

namespace App\Repositories\Suppliers;

use App\Models\Supplier;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Str;

class SuppliersRepository implements SuppliersRepositoryInterface
{
    protected $model;

    public function __construct(Supplier $model) {
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

    public function getNumberSupplier()
    {
        try {
            $data = [];
            $data['supplierSum'] = $this->model::all()->count();
            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function storeSupplierJson($request)
    {
        try {
            $data = [];

            if(isset($request['supplier_id']) && $request['supplier_id'] !== null){
                $supplier_id = $request->supplier_id;

                $supplier = $this->model::findOrFail($supplier_id);

                $supplier->name = $request->name;
                $supplier->email = $request->email;
                $supplier->phone = $request->phone;
                $supplier->website = $request->website;

                $supplier->save();

                $message = "Updated cuccessfully!";
            }else{
                $supplier = new $this->model;

                $supplier->name = $request->name;
                $supplier->email = $request->email;
                $supplier->phone = $request->phone;
                $supplier->website = $request->website;
                $supplier->save();

                $message = "Created cuccessfully!";
            }

            $data = [
                'supplier' => $supplier,
                'message' => $message
            ];

            return $data;

        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getDetailSupplier($request)
    {
        try {
            if(isset($request['supplier_id'])){
                $id = $request['supplier_id'];
                $supplier = $this->model::findOrFail($id);
            }

            return $supplier;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function deleteSupplier($id)
    {
        try {
            $data = [];
            if(isset($id)){
                $supplier = $this->model::findOrFail($id);
                $supplier->delete();

                $message = 'Deleted successfully!';

                $data['message'] = $message;
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
