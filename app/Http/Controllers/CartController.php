<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\JsonData;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Repositories\Transactions\TransactionsRepositoryInterface;
use App\Repositories\Orders\OrdersRepositoryInterface;
use App\Repositories\ColorSizes\ColorSizesRepositoryInterface;

class CartController extends Controller
{
    use JsonData;

    protected $colorSizesRepo;

    public function __construct(
        ColorSizesRepositoryInterface $colorSizesRepo)
    {
        $this->colorSizesRepo = $colorSizesRepo;
    }

    public function index()
    {
        try {
            $cart = [];
            $totalPrice = '';
            if(session()->exists('cart')){
                $cart = session()->get('cart')? session()->get('cart'): [];
                $totalPrice = session()->get('totalPrice')?session()->get('totalPrice'): [];
            }

            return view('cart', [
                'cart' => $cart,
                'totalPrice' => $totalPrice
            ]);
        }catch(\Exception $e){
            return $this->errorResult($e->getMessage());
        }
    }

    public function jsonUpdateCartItem(Request $request)
    {
        try {
            $data = [];
            $errors = [];
            if(isset($request->cart_id)){
                $cart_id = $request->cart_id;
                $numColorSizeById = $this->colorSizesRepo->getQuanity($cart_id);
                if(isset($request->num)){
                    $num = $request->num;
                    if($num === '0'){
                        $errors[] = 'Number of products must be greater than 0!';
                    }
                    if($num > $numColorSizeById){
                        $errors[] = 'Sorry, the allowed number is over!';
                    }
                }
                if(!empty($errors)){
                    return $this->jsonMsgResult($errors, false, 500);
                }
                $cart = session()->get('cart');

                if(array_key_exists($cart_id, $cart)){
                    $cart[$cart_id]['qty'] = $num;
                }

                session()->put('cart', $cart);
                $cart = session()->get('cart');
                $totalCartItem = $cart[$cart_id]['price']*$cart[$cart_id]['qty'];
                $total_cart_item = number_format($totalCartItem, '2', ',', '.');
            }

            $totalPrice = session()->get('totalPrice');

            $sub_total = 0;
            foreach ($cart as $cart_item) {
                $sub_total += $cart_item['qty']*$cart_item['price'];
            }

            session()->put('totalPrice', $sub_total);
            $totalPrice = session()->get('totalPrice');
            $total_price = number_format($totalPrice + 1.3, '2', ',', '.');
            $subTotal = number_format($totalPrice, '2', ',', '.');

            $data = [
                'subTotal' => $subTotal,
                'totalCartItem' => $total_cart_item,
                'totalPrice' => $total_price
            ];

            return $this->jsonDataResult($data, 200);
        }catch(\Exception $e){
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

    private function errorResult($msg)
    {
        $message = array(
            'errorMsg' => $msg
        );

        return $message;
    }
}
