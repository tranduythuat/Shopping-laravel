<?php

namespace App\Repositories\ProductColors;

interface ProductColorsRepositoryInterface
{

    public function getAll();

    public function getSizeByProductColor($data, $id);

    public function getImageByProductColor($data, $id);

    public function storeProductColorJson($data, $id);

    public function getDetailProductColor($productId, $colorId, $sizeId);

    public function deleteProductItem($productId, $colorId, $sizeId);

    public function deleteProductColor($productId, $productColorId);

    public function listImageProductColor($productId, $colorId);

    public function uploadImageProductColor($request, $productId, $colorId);

    // public function find($id);

    // public function getColorActive();

    // public function getNumberColor();

    // public function storeColorJson($data);

    // public function getDetailColor($data);

    // public function deleteColor($data);

    // public function updateStatus($data);

    // public function deleteMutilRow($data);
}
