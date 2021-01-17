<?php

namespace App\Repositories\Sizes;

interface SizesRepositoryInterface
{

    public function getAll();

    public function getSizeActive();

    public function find($id);

    public function getNumberSize();

    public function storeSizeJson($data);

    public function getDetailSize($data);

    public function deleteSize($data);

    public function updateStatus($data);

    public function deleteMutilRow($data);
}
