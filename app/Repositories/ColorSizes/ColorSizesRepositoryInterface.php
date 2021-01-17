<?php

namespace App\Repositories\ColorSizes;

interface ColorSizesRepositoryInterface
{
    public function updateQuanity($data);

    public function getQuanity($id);

    public function jsonNumProductItem($data);

    public function jsonProductAddToCart($data);

}
