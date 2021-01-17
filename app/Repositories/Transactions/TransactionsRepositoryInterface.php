<?php

namespace App\Repositories\Transactions;

interface TransactionsRepositoryInterface
{
    public function createTransaction($data);

    public function getNewTransaction();

    public function getTransPendingInfo($id);

    public function getTransOverview();

    public function updateTransStatus($status, $id);

    public function deleteTransaction($id);
}
