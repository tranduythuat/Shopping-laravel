<?php

namespace App\Repositories\Categories;

interface CategoriesRepositoryInterface
{

    public function getAll();

    public function getActive();

    public function getNumberCategory();

    public function storeCategoryJson($data);

    public function getDetailCategory($id);

    public function deleteCategory($id);

    public function updateStatus($data);

}
