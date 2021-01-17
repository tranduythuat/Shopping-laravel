<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Tags\TagsRepositoryInterface;
use App\Traits\JsonData;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    use JsonData;

    protected $tagsRepo;

    public function __construct(TagsRepositoryInterface $tagsRepo)
    {
        $this->tagsRepo = $tagsRepo;
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
