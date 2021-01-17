<?php

namespace App\Repositories\Transactions;

use App\Models\Transaction;
use Illuminate\Auth\Events\Failed;

class TransactionsRepository implements TransactionsRepositoryInterface
{
    protected $model;

    public function __construct(Transaction $model) {
        $this->model = $model;
    }

    public function createTransaction($request)
    {
        try {
            if(session()->has('cart')){
                $totalPrice = session()->get('totalPrice');

                $data = $this->model->create([
                    'email' => $request['email'],
                    'name' => $this->test_input($request['name']),
                    'phone' => $this->test_input($request['phone']),
                    'address' => $this->test_input($request['address']),
                    'message' => $this->test_input($request['note']),
                    'amount' => $totalPrice + config('transaction.fees.AD00-11'),
                    'status' => 1,
                    'payment' => 'AD00-11',
                ]);

                return $data;
            }else{
                return false;
            }
        }catch(\Exception $e){
            return $this->errorResult($e->getMessage());
        }
    }

    public function getNewTransaction()
    {
        try {
            $data = $this->model::orderBy('id', 'asc')->get();

            if($data->first()){
                return $data;
            }

            return false;
        }catch(\Exception $e){
            return $this->errorResult($e->getMessage());
        }
    }

    public function getTransPendingInfo($trans_id)
    {
        try{
            $data = [];
            if($trans_id){
                $transaction = $this->model::findOrFail($trans_id);

                if($transaction->first()){
                    $data['transaction'] = [
                        'name' => $transaction->name,
                        'email' => $transaction->email,
                        'phone' => $transaction->phone,
                        'address' => $transaction->address,
                        'payment' => $transaction->payment,
                        'message' => $transaction->message,
                        'status' => $transaction->status,
                        'amount' => $transaction->amount
                    ];

                    $orders = $transaction->orders;
                }

                if(!empty($orders)){
                    foreach($orders as $key => $order){
                        $color_sizes[$key]['price'] = number_format($order->price, '2', ',', '.');
                        $color_sizes[$key]['qty'] = $order->quanity;
                        $color_sizes[$key]['size'] = $order->colorSize->size->name;
                        $color_sizes[$key]['color'] = $order->colorSize->productColor->color->name;
                        $color_sizes[$key]['product'] = $order->colorSize->productColor->product->name;
                        $color_sizes[$key]['image'] = $order->colorSize->productColor->product->image_path;
                        $color_sizes[$key]['sub_total'] = number_format($order->quanity*$order->price, '2', ',', '.');
                    }

                    $data['product'] = $color_sizes;

                }
            }

            return $data;
        }catch(\Exception $e){
            return $this->errorResult($e->getMessage());
        }
    }

    public function getTransOverview()
    {
        try {
            $transPending = $this->model::where('status', 0)->count();
            $transComplete = $this->model::where('status', 1)->count();
            $transDestroy = $this->model::where('status', 2)->count();

            $totalRevenue = $this->model::where('status', 1)->sum('amount');

            return $data = [
                'transPending' => $transPending,
                'transComplete' => $transComplete,
                'transDestroy' => $transDestroy,
                'totalRevenue' => $totalRevenue,
            ];
        }catch(\Exception $e){
            return $this->errorResult($e->getMessage());
        }
    }

    public function updateTransStatus($id, $status)
    {
        try {
            $status = intval($status);
            $transaction = $this->model::find($id);

            if($status === 2){
                $orders = $transaction->orders;
                foreach($orders as $order){
                    $order->colorSize->update([
                        'quanity' => $order->colorSize->quanity + $order->quanity
                    ]);

                    $a = $order->colorSize->productColor->product->quanity;
                    $order->colorSize->productColor->product->update([
                        'quanity' =>  $a + $order->quanity
                    ]);
                }
            }

            $transaction->status = $status;
            $transaction->save();

            $message = 'Updated status success!';

            return $message;
        }catch(\Exception $e){
            return $this->errorResult($e->getMessage());
        }
    }

    public function deleteTransaction($id)
    {
        try{
            $transaction = $this->model::find($id);
            $transaction->delete();

            $message = "Deleted successfully!";

            return $message;
        }catch(\Exception $e){
            return $this->errorResult($e->getMessage());
        }
    }

    protected function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    protected function errorResult($msg)
    {
        $message = array(
            'errorMsg' => $msg
        );

        return $message;
    }
}


