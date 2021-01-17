<?php

namespace App\Repositories\Colors;

interface ColorsRepositoryInterface
{

    public function getAll();

    public function find($id);

    public function getColorActive();

    public function getNumberColor();

    public function storeColorJson($data);

    public function getDetailColor($data);

    public function deleteColor($data);

    public function updateStatus($data);

    public function deleteMutilRow($data);

    public function getColorByProductColor($data = []);

}
