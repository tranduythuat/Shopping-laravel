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

class CheckoutController extends Controller
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

    public function index()
    {
        return view('checkout');
    }

    public function transaction(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $transaction = $this->transactionsRepo->createTransaction($request->all());

        if($transaction){
            $orders = $this->ordersRepo->addOrdersByTransaction($transaction->id);
            if($orders){
                $request->session()->forget(['cart', 'totalPrice', 'totalQuanity']);
                $colorSizeUpdate = $this->colorSizesRepo->updateQuanity($orders);
            }

            if($colorSizeUpdate){
                $data = [
                    'transaction' => $transaction,
                    'orders' => $orders
                ];
                Mail::to($request->email)->send(new SendEmail($data));

                return redirect('checkout-success')->with('success', 'Thank you for your order in our system, an email has been sent to '.$data["transaction"]["email"].'. Please check email for more information about your order');
            }
        }
    }

    public function checkoutSuccess()
    {
        return view('checkout_success');
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
