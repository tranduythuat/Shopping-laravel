<?php

namespace App\Repositories\Suppliers;

interface SuppliersRepositoryInterface
{

    public function getAll();

    public function getNumberSupplier();

    public function storeSupplierJson($data);

    public function getDetailSupplier($id);

    public function deleteSupplier($data);

    // public function updateStatus($data);

    // public function deleteMutilRow($data);
}
