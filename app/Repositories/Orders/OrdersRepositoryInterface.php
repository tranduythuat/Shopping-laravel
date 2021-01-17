<?php

namespace App\Repositories\Orders;

interface OrdersRepositoryInterface
{
    public function addOrdersByTransaction($id);
}
