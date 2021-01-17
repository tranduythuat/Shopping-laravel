<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Categories\CategoriesRepositoryInterface;
use App\Traits\JsonData;
use App\Repositories\Sliders\SlidersRepositoryInterface;
use App\Repositories\Products\ProductsRepositoryInterface;
use App\Repositories\Colors\ColorsRepositoryInterface;
use App\Repositories\Sizes\SizesRepositoryInterface;
use App\Repositories\Tags\TagsRepositoryInterface;
use App\Repositories\ColorSizes\ColorSizesRepositoryInterface;

class HomeController extends Controller
{
    use JsonData;

    protected $categoriesRepo;
    protected $productsRepo;
    protected $slidersRepo;
    protected $colorsRepo;
    protected $sizesRepo;
    protected $tagsRepo;
    protected $colorSizesRepo;

    public function __construct(
        CategoriesRepositoryInterface $categoriesRepo,
        SlidersRepositoryInterface $slidersRepo,
        ColorsRepositoryInterface $colorsRepo,
        SizesRepositoryInterface $sizesRepo,
        TagsRepositoryInterface $tagsRepo,
        ProductsRepositoryInterface $productsRepo,
        ColorSizesRepositoryInterface $colorSizesRepo)
    {
        $this->categoriesRepo = $categoriesRepo;
        $this->slidersRepo = $slidersRepo;
        $this->colorsRepo = $colorsRepo;
        $this->sizesRepo = $sizesRepo;
        $this->tagsRepo = $tagsRepo;
        $this->productsRepo = $productsRepo;
        $this->colorSizesRepo = $colorSizesRepo;
    }

    public function index()
    {
        try{
            $categories = $this->categoriesRepo->getActive();
            $sliders = $this->slidersRepo->getAll();
            $colors = $this->colorsRepo->getColorActive();
            $tags = $this->tagsRepo->getTagLimit(10);

            return view('welcome')->with([
                'categories' => $categories,
                'sliders' => $sliders,
                'colors' => $colors,
                'tags' => $tags
            ]);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function loadJsonProduct(Request $request)
    {
        try {
            $data = $this->productsRepo->getProductList($request->all());

            $html = '';
            foreach($data['products'] as $product){
                $html .= '
                <div class="col-6 col-sm-6 col-lg-3 p-b-35 isotope-item">
                    <!-- Block2 -->
                    <div class="block2">
                        <div class="block2-pic hov-img0">
                            <img src="'.$product->image_path.'" alt="IMG-PRODUCT">

                            <a href="#" data-id="'.$product->id.'" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
                                Quick View
                            </a>
                        </div>

                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l ">
                                <a href="'.route("product.detail", ['productId' => $product->id]).'" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                    '.$product->name.'
                                </a>

                                <span class="stext-105 cl3">
                                    $'.number_format($product->price, 2, ',', '.').'
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }

            $data['products'] = $html;

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function jsonFilterProduct(Request $request)
    {
        try {
            $data = $this->productsRepo->filterProduct($request->all());

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            $html = '';
            foreach($data['products'] as $product){
                $html .= '
                <div class="col-6 col-sm-6 col-lg-3 p-b-35 isotope-item">
                    <!-- Block2 -->
                    <div class="block2">
                        <div class="block2-pic hov-img0">
                            <img src="'.$product->image_path.'" alt="IMG-PRODUCT">

                            <a href="#" data-id="'.$product->id.'" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
                                Quick View
                            </a>
                        </div>

                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l ">
                                <a href="'.route("product.detail", ['productId' => $product->id]).'" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                    '.$product->name.'
                                </a>

                                <span class="stext-105 cl3">
                                    $'.number_format($product->price, 2, ',', '.').'
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }

            $data['products'] = $html;

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function jsonSearchProduct(Request $request)
    {
        try{
            $data = $this->productsRepo->searchProduct($request->all());
            $html = '';
            foreach($data['products'] as $product){
                $html .= '
                <div class="col-6 col-sm-6 col-lg-3 p-b-35 isotope-item">
                    <!-- Block2 -->
                    <div class="block2">
                        <div class="block2-pic hov-img0">
                            <img src="'.$product->image_path.'" alt="IMG-PRODUCT">

                            <a href="#" data-id="'.$product->id.'" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
                                Quick View
                            </a>
                        </div>

                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l ">
                                <a href="'.route("product.detail", ['productId' => $product->id]).'" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                    '.$product->name.'
                                </a>

                                <span class="stext-105 cl3">
                                    $'.number_format($product->price, 2, ',', '.').'
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }

            $data['products'] = $html;
            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function loadJsonProductOverview(Request $request)
    {
        try{
            $data = $this->productsRepo->productOverview($request->all());

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function loadJsonProductQuanity(Request $request)
    {
        try{
            $data = $this->productsRepo->productQuanity($request->all());

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function jsonAddCartProduct(Request $request)
    {
        try{
            $data = $this->productsRepo->addProductToCart($request->all());

            if (isset($data['errors']) && !empty($data['errors'])) {
                return $this->jsonMsgResult($data['errors'], false, 500);
            }
            $html = '';
            foreach($data['cart'] as $key => $val){
                $html .=
                '<li class="header-cart-item flex-w flex-t m-b-12" id="cart-item-'.$key.'">
                    <div class="header-cart-item-img" data-id="'.$key.'">
                        <img src="'.$val['photo'].'" alt="IMG">
                    </div>

                    <div class="header-cart-item-txt p-t-8">
                        <a href="#" class="header-cart-item-name m-b-10 hov-cl1 trans-04">
                            '.$val['name'].'
                        </a>

                        <span class="header-cart-item-info">
                            <p class="m-b-10">'.$val['color'].' Size: '.$val['size'].'</p>
                            <p>'.$val['qty'].' x $'.number_format($val['price'], '2', ',', '.').'</p>
                        </span>
                    </div>
                </li>';
            }
            $data['cart'] = $html;
            $data['totalPrice'] = number_format($data['totalPrice'], '2', ',', '.');
            $data['totalQuanity'] = floor($data['totalQuanity']);

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function jsonDeleteCartItem($cart_id)
    {
        try{
            $data = $this->productsRepo->deleteProductFromCart($cart_id);

            $data['totalPrice'] = number_format($data['totalPrice'], '2', ',', '.');

            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function productDetailView($productId)
    {
        try {
            $result = $this->productsRepo->getProductDetail($productId);

            if(isset($result['errorMsg'])){
                return $this->jsonMsgResult($result, false, 500);
            }

            return view('product_detail', [
                'product' => $result['product'],
                'colors' => $result['colors'],
                'sizes' => $result['sizes'],
                'images' => $result['images'],
            ]);
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function jsonNumProductItem(Request $request)
    {
        try {
            $data = $this->colorSizesRepo->jsonNumProductItem($request->all());
            if(isset($data['errorMsg'])){
                return $this->jsonMsgResult($data['errorMsg'], false, 500);
            }
            return $this->jsonDataResult($data, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    private function errorResult($msg)
    {
        $message = array(
            'errorMsg' => $msg
        );

        return $message;
    }

    private function jsonMsgResult($errors, $success, $statusCode)
    {
        $result = array(
            'errors' => $errors,
            'success' => $success,
            'statusCode' => $statusCode
        );

        return response()->json($result, $result['statusCode']);
    }
}
