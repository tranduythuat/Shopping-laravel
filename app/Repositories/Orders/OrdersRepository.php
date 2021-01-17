<?php

namespace App\Repositories\Orders;

use App\Models\Order;

class OrdersRepository implements OrdersRepositoryInterface
{
    protected $model;

    public function __construct(Order $model) {
        $this->model = $model;
    }

    public function addOrdersByTransaction($id)
    {
        try {
            if(session()->has('cart')){
                $cart = session()->get('cart');
            }

            foreach($cart as $key => $cart_item){
                $orders[] = $this->model->create([
                    'price' => $cart_item['price'],
                    'quanity' => $cart_item['qty'],
                    'transaction_id' => $id,
                    'color_size_id' => $key
                ]);
            }
            
            return $orders;
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
