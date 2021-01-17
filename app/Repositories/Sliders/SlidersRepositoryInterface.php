<?php

namespace App\Repositories\Sliders;

interface SlidersRepositoryInterface
{

    public function getAll();

    public function getNumberSlider();

    public function storeSliderJson($data);

    public function getDetailSlider($id);

    public function deleteSlider($id);
}
