<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\JsonData;
use Yajra\DataTables\Facades\DataTables;

use App\Repositories\Transactions\TransactionsRepositoryInterface;

class DashboardController extends Controller
{
    use JsonData;
    protected $transactionsRepo;

    public function __construct(TransactionsRepositoryInterface $transactionsRepo)
    {
        $this->transactionsRepo = $transactionsRepo;
    }

    public function index()
    {
        $result = $this->transactionsRepo->getTransOverview();

        return view('admin.dashboard', [
            'transPending' => $result['transPending'],
            'transComplete' => $result['transComplete'],
            'transDestroy' => $result['transDestroy'],
            'totalRevenue' => $result['totalRevenue'],
        ]);
    }

    public function jsonTransactionDatatable()
    {
        try {
            $transactions = $this->transactionsRepo->getNewTransaction();

            return Datatables::of($transactions)
            ->addColumn('action', function ($transaction) {
                if($transaction->status === 0){
                    return '
                        <a href="javascript:;" data-id="'.$transaction->id.'" class="transactionInfo ml-2" title="Info"><i class="fa fa-info"></i></a>
                    ';
                }

                return '
                    <a href="javascript:;" data-id="'.$transaction->id.'" class="transactionDelete ml-2" title="Delete"><i class="fa fa-trash"></i></a>
                    <a href="javascript:;" data-id="'.$transaction->id.'" class="transactionInfo ml-2" title="Info"><i class="fa fa-info"></i></a>
                ';
            })
            ->editColumn('name', function($transaction){
                return strtoupper($transaction->name);
            })
            ->editColumn('email', function($transaction){
                return $transaction->email;
            })
            ->editColumn('phone', function($transaction){
                return $this->format_phone($transaction->phone);
            })
            ->editColumn('created_at', function($transaction){
                return $transaction->created_at;
            })
            ->editColumn('status', function($transaction) {
                $status = $transaction->status;
                if($status === 0){
                    return '<span class="badge badge-pill badge-primary">Pending</span>';
                }
                if($status === 1){
                    return '<span class="badge badge-pill badge-success">Completed</span>';
                }
                if($status === 2){
                    return '<span class="badge badge-pill badge-danger">Canceled</span>';
                }

            })
            ->rawColumns(['name', 'action', 'status', 'email', 'phone', 'created_at'])
            ->make(true);

        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function jsonTransactionPendingInfo($trans_id)
    {
        try {
            $result = $this->transactionsRepo->getTransPendingInfo($trans_id);

            $result['transaction']['phone'] = $this->format_phone($result['transaction']['phone']);
            return $this->jsonDataResult($result, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function jsonUpdateTransactionStatus($id, $status)
    {
        try {
            $result = $this->transactionsRepo->updateTransStatus($id, $status);

            return $this->jsonDataResult($result, 200);
        }catch(\Exception $e){
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function jsonDeleteTransaction($id)
    {
        try{
            $result = $this->transactionsRepo->deleteTransaction($id);

            return $this->jsonDataResult($result, 200);
        }catch(\Exception $e){
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    private function format_phone($phone)
    {
        return '('.substr($phone, 0, 4).') '.substr($phone, 4, 3).'-'.substr($phone,7);
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
