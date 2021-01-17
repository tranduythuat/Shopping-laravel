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

class ProductDetailController extends Controller
{
    use JsonData;

    protected $transactionsRepo;
    protected $ordersRepo;
    protected $colorSizesRepo;

    public function __construct(
        TransactionsRepositoryInterface $transactionsRepo,
        OrdersRepositoryInterface $ordersRepo,
        ColorSizesRepositoryInterface $colorSizesRepo)
    {
        $this->transactionsRepo = $transactionsRepo;
        $this->ordersRepo = $ordersRepo;
        $this->colorSizesRepo = $colorSizesRepo;
    }

    public function jsonProductAddToCart(Request $request)
    {
        try {
            $result = $this->colorSizesRepo->jsonProductAddToCart($request->all());

            if(isset($result['errorMsg'])){
                return $this->jsonMsgResult($result['errorMsg'], false, 500);
            }

            $html = '';
            foreach($result['cart'] as $key => $val){
                $html .=
                '<li class="header-cart-item flex-w flex-t m-b-12" id="cart-item-'.$key.'">
                    <div class="header-cart-item-img" data-id="'.$key.'">
                        <img src="'.$val['photo'].'" alt="IMG">
                    </div>

                    <div class="header-cart-item-txt p-t-8">
                        <a href="#" class="header-cart-item-name m-b-10 hov-cl1 trans-04">
                            '.$val['name'].'
                        </a>

                        <span class="header-cart-item-info">
                            <p class="m-b-10">'.$val['color'].' Size: '.$val['size'].'</p>
                            <p>'.$val['qty'].' x $'.number_format($val['price'], '2', ',', '.').'</p>
                        </span>
                    </div>
                </li>';
            }
            $result['cart'] = $html;
            $result['totalPrice'] = number_format($result['totalPrice'], '2', ',', '.');
            $result['totalQuanity'] = floor($result['totalQuanity']);

            return $this->jsonDataResult($result, 200);
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
}
