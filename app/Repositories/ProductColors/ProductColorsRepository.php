<?php

namespace App\Repositories\ProductColors;

use App\Models\ColorSize;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Str;
use App\Traits\UploadImageTrait;

class ProductColorsRepository implements ProductColorsRepositoryInterface
{
    protected $model;

    use UploadImageTrait;

    public function __construct(ProductColor $model) {
        $this->model = $model;
    }

    public function getAll() {
        try {
            $data = $this->model::orderBy('id', 'desc')->get();
            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getSizeByProductColor($productColorId, $productId)
    {
        try {
            $productColorByColors = $this->model::where('product_id', $productId)->where('color_id', $productColorId)->get();
            $data = [];
            foreach($productColorByColors as $productColorByColor){
                // $data['images'][] = $productColorByColor->image_path;
                foreach($productColorByColor->sizes as $size){
                    $data[$size->id][] = $size;
                    $data[$size->id]['quanity'] = $size->pivot->quanity;
                }
            }

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getImageByProductColor($productColorId, $productId)
    {
        try {
            $productColorByColors = $this->model::where('product_id', $productId)->where('color_id', $productColorId)->get();

            $data = [];
            foreach($productColorByColors as $productColorByColor){
                $data[] = $productColorByColor->images;
            }

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function storeProductColorJson($request, $productId)
    {
        try {
            $data = [];

            if(isset($request['product_color_id']) && $request['product_color_id'] !== null){
                $productColorByColor = ProductColor::find($request['product_color_id']);
                $productColor = $productColorByColor->update([
                    'color_id' => $request['colors'],
                ]);
                // dd($productColor);
                $colorSizes = ColorSize::where('product_color_id', $request['product_color_id'])
                    ->where('size_id', $request['size_id'])->get();

                foreach($colorSizes as $item){
                    $colorSize = $item->update([
                        'size_id' => $request['sizes'],
                        'quanity' => $request['quanity']
                    ]);
                }

                $message = "Updated cuccessfully!";
            }else{
                $product = Product::find($productId);
                $productColor = $product->productColors()->firstOrCreate([
                    'color_id' => $request['colors'],
                ]);
                $productColorByColors = ProductColor::where('product_id', $productId)->where('color_id', $request['colors'])->get();
                foreach($productColorByColors as $productColorByColor){
                    $colorSize = ColorSize::firstOrCreate([
                        'product_color_id' => $productColorByColor->id,
                        'size_id' => $request['sizes'],
                        'quanity' => $request['quanity']
                    ]);

                }
                $message = "Created cuccessfully!";
            }

            $data = [
                'productColor' => $productColor,
                // 'colorSize' => $colorSize,
                'message' => $message
            ];

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getDetailProductColor($productId, $colorId, $sizeId)
    {
        try {
            $data = [];
            $productColorByColor = $this->model::where('product_id', $productId)->where('color_id', $colorId)->get();

            foreach($productColorByColor as $productColorByColorItem){
                $productColorId = $productColorByColorItem->id;
                $size = $productColorByColorItem->colorSizes()->where('size_id', $sizeId)->get();
            }
            $data = [
                'productColorId' => $productColorId,
                'productColorByColor' => $productColorByColor,
                'size' => $size
            ];
            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function deleteProductItem($productId, $colorId, $sizeId)
    {
        try {
            $data = [];
            $productColors = $this->model::where('product_id', $productId)->where('color_id', $colorId)->get();
            // dd($productColors);
            foreach($productColors as $productColor){
                ColorSize::where('product_color_id', $productColor->id)
                                ->where('size_id', $sizeId)
                                ->delete();
            }

            $message = 'Deleted successfully!';

            $data = [
                'message' => $message
            ];

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function deleteProductColor($productId, $productColorId)
    {
        try {
            // dd($productColorId);
            $data = [];
            $this->model::where('product_id', $productId)->where('color_id', $productColorId)->delete();

            $message = 'Deleted successfully!';

            $data = [
                'message' => $message
            ];

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function listImageProductColor($productId, $colorId)
    {
        try {
            $productColors = $this->model::where('product_id', $productId)->where('color_id', $colorId)->get();

            foreach($productColors as $productColor){
                $image = $productColor->images()->get();
            }

            return $image;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function uploadImageProductColor($request, $productId, $colorId)
    {
        try {
            $productColors = $this->model::where('product_id', $productId)->where('color_id', $colorId)->get();

            foreach($productColors as $productColor){
                $productColorId = $productColor->id;
                $images = $productColor->images()->get();
                if($images->count() > 0){
                    foreach($images as $image){
                        $imageIds[] = $image->id;
                        $publicIds[] = $image->publicId;
                    }
                }
            }

            if($images->count() > 0 ){
                Image::whereIn('id', $imageIds)->delete();
                $this->removeImages($publicIds);
            }
            $imageResult = $this->updateMutipleTraitUpLoad($request, 'upload_image');

            foreach($imageResult as $imageResultItem){
                $imagesIntace = Image::create([
                    'image_path' => $imageResultItem['image_path'],
                    'publicId' => $imageResultItem['publicId']
                ]);
                $imagesId[] = $imagesIntace->id;
            }

            $productColor = $this->model::find($productColorId);
            $productColor->images()->attach($imagesId);

            return $productColor;

        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    protected function errorResult($msg)
    {
        $message = array(
            'errorMsg' => $msg
        );

        return $message;
    }
}
