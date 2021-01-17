<?php

namespace App\Repositories\ColorSizes;

use App\Models\ColorSize;

class ColorSizesRepository implements ColorSizesRepositoryInterface
{
    protected $model;

    public function __construct(ColorSize $model) {
        $this->model = $model;
    }

    public function updateQuanity($orders)
    {
        try {
            if(!empty($orders)){
                foreach($orders as $key => $order_item){
                    $color_size = $this->model::find($key);
                    if($color_size){
                        $color_size->update([
                            'quanity' => $color_size->quanity - $order_item['quanity']
                        ]);

                        $product = $color_size->productColor->product;

                        $product->update([
                            'quanity' => $product->quanity - $order_item['quanity']
                        ]);
                    }
                }

                return true;
            }else{
                return false;
            }
        }catch(\Exception $e){
            return $this->errorResult($e->getMessage());
        }
    }

    public function getQuanity($id)
    {
        try {
            if($id){
                $data = $this->model::find($id);
                $numColorSize = $data->quanity;

                return $numColorSize;
            }else{
                return false;
            }
        }catch(\Exception $e){
            return $this->errorResult($e->getMessage());
        }
    }

    public function jsonNumProductItem($request)
    {
        try {
            $errors = [];
            if($request['productItem']){
                $product_item = $request['productItem'];

                if(array_key_exists('product_color', $product_item) && array_key_exists('color_size', $product_item)){
                    $product_color = $product_item['product_color'];
                    $color_size = $product_item['color_size'];

                    $product_by_color_size = $this->model::where('product_color_id', $product_color)
                        ->where('size_id', $color_size)
                        ->get();

                    if(count($product_by_color_size) === 0){
                        $errors['exist'] = 'Item does not exist!';
                    }else{
                        foreach($product_by_color_size as $product_by_color_size_item){
                            $num = $product_by_color_size_item->quanity;
                            if($num === 0){
                                $errors['outOf'] = 'Currently, this products are out of stock!';
                            }
                        }
                    }

                    if(!empty($errors)){
                        return $this->errorResult($errors);;
                    }

                    return $num;
                }else{
                    return false;
                }
            }
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function jsonProductAddToCart($request)
    {
        try{
            $errors = [];
            if($request['num_product']){
                $num_request = $request['num_product'];
            }

            if(empty($request['productItem'])){
                $errors[] = 'Please select the product parameters above!';
            }

            $pro_item = $request['productItem'];

            if(array_key_exists('product_color', $pro_item)){
                $pro_color = $pro_item['product_color'];
            }else{
                $errors[] = 'Color no defined!';
            }

            if(array_key_exists('color_size', $pro_item)){
                $size = $pro_item['color_size'];
            }else{
                $errors[] = 'Size not defined!';
            }

            $product = $this->model::where('product_color_id', $pro_color)
                ->where('size_id', $size)
                ->get();

            if(count($product) === 0){
                $errors[] = 'Item does not exist!';
            }else{
                foreach($product as $product_item){
                    $product = [
                        'id' => $product_item->id,
                        'name' => $product_item->productColor->product->name,
                        'price' => $product_item->productColor->product->price,
                        'image_path' => $product_item->productColor->product->image_path,
                        'color' => $product_item->productColor->color->name,
                        'size' => $product_item->size->name,
                        'quanity' => $product_item->quanity,
                    ];
                    if($product['quanity'] === 0){
                        $errors[] = 'Currently, this products are out of stock!';
                    }
                }
            }

            if($num_request > $product['quanity']){
                $errors[] = 'This product is in excess of the allowed quantity!';
            }

            $cart =session()->get('cart');
            $totalQuanity = session()->get('totalQuanity', 0);
            $totalPrice = session()->get('totalPrice', 0);

            if(!$cart){
                $cart = [
                    $product['id'] => [
                        "name" => $product['name'],
                        "qty" => intval($num_request),
                        "price" => $product['price'],
                        "photo" => $product['image_path'],
                        "color" => $product['color'],
                        "size" => $product['size']
                    ],
                ];

                $totalQuanity += $num_request;
                $totalPrice += $product['price']*$num_request;

                session()->put([
                    'cart'=> $cart,
                    'product' => $product['name'],
                    'totalQuanity' => $totalQuanity,
                    'totalPrice' => $totalPrice
                ]);

                $new_cart = session()->all();

                return $new_cart;
            }

            if(array_key_exists($product['id'], $cart)){

                $qty_item = $cart[$product['id']]['qty'];

                $checkQty =  intval($qty_item) + intval($num_request);

                if($checkQty > $product['quanity']){
                    $errors['too_much'] = 'The product in the shopping cart has exceeded the allowed number';
                }

                if(!empty($errors)){

                    return $this->errorResult($errors);
                }

                $cart[$product['id']]['qty'] += intval($num_request);

                $totalQuanity += intval($num_request);
                $totalPrice += $product['price']*intval($num_request);

                session()->put([
                    'cart'=> $cart,
                    'product' => $product['name'],
                    'totalQuanity' => $totalQuanity,
                    'totalPrice' => $totalPrice
                ]);

                $new_cart = session()->all();

                return $new_cart;
            }

            $cart[$product['id']] = [
                "name" => $product['name'],
                "qty" => intval($num_request),
                "price" => $product['price'],
                "photo" => $product['image_path'],
                "color" => $product['color'],
                "size" => $product['size']
            ];

            $totalQuanity += $num_request;
            $totalPrice += $product['price']*$num_request;

            session()->put([
                'cart'=> $cart,
                'product' => $product['name'],
                'totalQuanity' => $totalQuanity,
                'totalPrice' => $totalPrice
            ]);
            $new_cart = session()->all();

            return $new_cart;

        }catch (\Exception $e) {
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
