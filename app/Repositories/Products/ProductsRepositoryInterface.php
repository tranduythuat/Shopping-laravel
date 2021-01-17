<?php

namespace App\Repositories\Products;

interface ProductsRepositoryInterface
{

    public function getAll();

    public function getNumberProduct();

    public function createProduct($data);

    public function find($id);

    public function update($id, $data);

    public function show($id);

    public function delete($id);

    public function getTrash();

    public function getNumberTrashProduct();

    public function destroy($id, $image);

    public function restore($id);

    public function deleteMutilRow($data);

    public function destroyMutilRow($data);

    public function restoreRows($data);

    public function updateStatus($data);

    public function getNumberItem($data);

    public function getNumberProductItem($id);

    public function getColorByProudct($id);

    public function getProductList($data);

    public function productOverview($data);

    public function filterProduct($data);

    public function searchProduct($data);

    public function productQuanity($data);

    public function addProductToCart($data);

    public function deleteProductFromCart($id);

    public function getProductDetail($id);
}
