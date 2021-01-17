<?php

namespace App\Repositories\Products;

use App\Models\ColorSize;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\Size;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Traits\UploadImageTrait;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;

class ProductsRepository implements ProductsRepositoryInterface
{
    use UploadImageTrait;

    protected $model;
    protected $tag;

    public function __construct(Product $model, Tag $tag) {
        $this->model = $model;
        $this->tag = $tag;
    }

    public function getAll() {
        try {
            $data = $this->model::join('categories', 'categories.id', '=', 'products.category_id')
            ->select('products.*', 'categories.name as category')
            ->orderBy('products.id', 'desc')
            ->get();

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function find($id)
    {
        try {
            $data = $this->model::findOrFail($id);

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data = [];
            $data['product'] = $this->model::where('id', $id)->get();

            foreach($data['product'] as $item){

                $item->category->name;
                $item->supplier->name;
                $item->tags;

                $productColors = $item->productColors()->get();

                foreach($productColors  as $key => $productColor){
                    $data['color'][$key][] = $productColor->color;
                    $data['color'][$key]['size'][] = $productColor->sizes;
                    $data['color'][$key]['image'][] = $productColor->images;
                }
            }

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getNumberProduct()
    {
        try {
            $data = [];
            $data['productActive'] = $this->model::where('status', 'active')->count();
            $data['productInactive'] = $this->model::where('status', 'inactive')->count();
            $data['productSum'] = $data['productActive'] + $data['productInactive'];
            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function createProduct($request)
    {
        try {
            DB::beginTransaction();
            $data = new $this->model;
            $data->name = $this->test_input($request->name);
            $data->slug = Str::slug($request->name);
            $data->price = $request->price;
            $data->sale = $request->sale;
            $data->status = $request->status;
            $data->hot = $request->hot;
            $data->quanity = $request->quanity;
            $data->description = $this->test_input($request->description);
            $data->content = $request->contentProduct;
            $data->category_id = $request->category;
            $data->supplier_id = $request->supplier;

            $image_path = $this->storageTraitUpLoad($request, 'image_path');

            if(!empty($image_path)){
                $data->image_path = $image_path['image_path'];
                $data->publicId = $image_path['publicId'];
            }

            $data->save();

            if(!empty($request['tags'])){
                foreach($request['tags'] as $tagItem){
                    $tagsIntance = $this->tag->firstOrCreate([
                        'name' => $tagItem,
                        'slug' => Str::slug($tagItem),
                        'status' => 'active'
                    ]);
                    $tagsId[] = $tagsIntance->id;
                }
                $data->tags()->attach($tagsId);
            }
            DB::commit();
            return $data;
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResult($e->getMessage());
        }
    }

    public function update($id, $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->model::findOrFail($id);

            $data->name = $this->test_input($request->name);
            $data->slug = Str::slug($request->name);
            $data->price = $request->price;
            $data->sale = $request->sale;
            $data->hot = $request->hot;
            $data->quanity = $request->quanity;
            $data->description = $this->test_input($request->description);
            $data->content = $request->contentProduct;
            $data->category_id = $request->category;
            $data->supplier_id = $request->supplier;

            $categoryStatus = $data->category->status;

            if($categoryStatus == 'inactive'){
                $data->status = 'inactive';
            }else{
                $data->status = $request->status;
            }

            $image_path = $this->updateTraitUpLoad($request, 'image_path');

            if(!empty($image_path)){
                $data->image_path = $image_path['image_path'];
                $data->publicId = $image_path['publicId'];
            }

            $data->save();

            if(!empty($request['tags'])){
                foreach($request['tags'] as $tagItem){
                    $tagsIntance = $this->tag->updateOrCreate([
                        'name' => $tagItem,
                        'slug' => Str::slug($tagItem),
                        'status' => 'active'
                    ]);
                    $tagsId[] = $tagsIntance->id;
                }
                $data->tags()->sync($tagsId);
            }
            DB::commit();
            return $data;
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResult($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $data = [];
            if(isset($id)){
                $product = $this->model::findOrFail($id);
                $product->delete();
                $message = 'Deleted successfully!';
            }
            $data = [
                'message' => $message
            ];

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getTrash()
    {
        try {
            $data = $this->model::onlyTrashed()->get();

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getNumberTrashProduct()
    {
        try {
            $data = [];
            $data['trashProductSum'] = $this->model::onlyTrashed()->count();
            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function destroy($id, $image)
    {
        try {
            $data = [];
            if(isset($id)){
                $product = $this->model::withTrashed()->where('id', $id)->get();
                foreach($product as $product_item){
                    $product_colors = $product_item->productColors;
                    foreach($product_colors as $product_color){
                        $images_array[] = $product_color->images;
                    }
                }

                foreach($images_array as $image_item){
                    foreach($image_item as $item){
                        $publicIds[] = $item->publicId;
                    }
                }
                if(!empty($publicIds)){
                    $this->removeImages($publicIds);
                }
                $this->removeImage($image);
                $this->model::withTrashed()->where('id', $id)->forceDelete();

                $message = 'Deleted successfully!';
                $data = [
                    'message' => $message
                ];
            }

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $data = [];
            if(isset($id)){
                $this->model::withTrashed()->where('id', $id)->restore();

                $message = 'Restored successfully!';
                $data = [
                    'message' => $message
                ];
            }

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function deleteMutilRow($request)
    {
        try {
            $data = [];
            if(isset($request['productIds'])){
                $productIds = $request['productIds'];
                if (count($productIds) === 0) {
                    $data['code'] = 500;
                    $data['message'] = 'Please Select atleast one checkbox';
                }else{
                    $deleteProducts = $this->model::whereIn('id', $productIds)->delete();
                    $data['message'] = 'Deleted successfully';
                    $data['code'] = 200;
                    $data['number_product'] = $deleteProducts;
                }

                return $data;
            }
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function destroyMutilRow($request)
    {
        try {
            $data = [];

            if(isset($request['productIds'])){
                $productIds = $request['productIds'];
                if (count($productIds) === 0) {
                    $data['code'] = 500;
                    $data['message'] = 'Please Select atleast one checkbox';
                }else{
                    $destroyProducts = $this->model::withTrashed()->whereIn('id', $productIds)->forceDelete();
                    if(isset($request['publicIds'])){
                        $this->removeImages($request['publicIds']);
                    }
                    $data['message'] = 'Deleted successfully';
                    $data['code'] = 200;
                    $data['number_product'] = $destroyProducts;
                }

                return $data;
            }
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function restoreRows($request)
    {
        try {
            $data = [];
            if(isset($request['productIds'])){
                $productIds = $request['productIds'];
                if (count($productIds) === 0) {
                    $data['code'] = 500;
                    $data['message'] = 'Please Select atleast one checkbox';
                }else{
                    $restoreProducts = $this->model::withTrashed()->whereIn('id', $productIds)->restore();
                    $data['message'] = 'Restored successfully';
                    $data['code'] = 200;
                    $data['number_product'] = $restoreProducts;
                }

                return $data;
            }
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function updateStatus($request)
    {
        try {
            $data = [];
            if(isset($request['product_id']) && isset($request['isChecked'])){
                $id = $request['product_id'];
                if($request['isChecked'] == 'true'){
                    $status = 'active';
                }else{
                    $status = 'inactive';
                }
                $product = $this->model::findOrFail($id);

                $statusCategory = $product->category->status;

                if($statusCategory == 'inactive'){
                    return false;
                }

                $product->status = $status;
                $product->save();

                $message = 'Updated status successfully!';
            }

            $data = [
                'product' => $product,
                'message' => $message
            ];

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getNumberItem($product)
    {
        try {
            $productColors = $product->productColors()->get();
            $count = [];
            // dd($productColors);
            foreach($productColors as $productColor){
                foreach($productColor->colorSizes as $item){
                    $count[] = $item->id;
                }
            }
            $countItem = ColorSize::whereIn('id', $count)->sum('quanity');
            $quanity = $product->quanity;

            $data = [
                'countItem' => $countItem,
                'quanity' => $quanity
            ];
            // dd($data);
            return $data;
        } catch (\Exception  $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getNumberProductItem($productId)
    {
        try {
            $product = $this->model::find($productId);
            $productColors = $product->productColors()->get();

            foreach($productColors as $productColor){
                $colorSizes[] = $productColor->colorSizes()->get();
            }
            $countItem = 0;
            foreach($colorSizes as $colorSize){
                foreach($colorSize as $item){
                    $countItem = $countItem + $item->quanity;
                }
            }

            return $countItem;
        } catch (\Exception  $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getColorByProudct($productId)
    {
        try{
            $data = $this->model::find($productId)->colors()->get();

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getProductList($request)
    {
        try{
            $data = [];
            $limit = 8;

            if($request['page']){
                $page = $request['page'];
            }

            $products = $this->model::where('status', 'active')->offset(($page-1)*$limit)->limit($limit)->get();

            $totalProduct = $this->model::count();

            if($totalProduct > 0){
                $a = $totalProduct/$limit;
                if($a < 1){
                    $numPage = 1;
                }else{
                    if($totalProduct % $limit == 0){
                        $numPage = floor($a);
                    }else{
                        $numPage = floor($a) + 1;
                    }
                }
            }

            if($page >= $numPage){
                $readMore = false;
            }else{
                $readMore = true;
            }

            $data = [
                'products' => $products,
                'readMore' => $readMore,
            ];

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function filterProduct($request)
    {
        try {
            $data = [];
            $limit = 8;

            if($request['page']){
                $page = $request['page'];
            }

            $products = $this->model::where('status', 'active');

            if(isset($request['filter'])){
                $filter = $request['filter'];

                if(array_key_exists('category', $filter) && $filter['category'] !== ''){
                    $category_id = $filter['category'];
                    if($filter['category'] === '*'){
                        $products = $products->where('category_id', '>', 0);
                    }else{
                        $products = $products->where('category_id', $category_id);
                    }
                }

                if(array_key_exists('sortBy', $filter) && $filter['sortBy'] !== ''){
                    $sortBy = $filter['sortBy'];

                    switch ($sortBy) {
                        case 'default':
                            $products = $products->orderBy('id', 'desc');
                            break;
                        case 'low-to-high':
                            $products = $products->orderBy('price', 'asc');
                            break;
                        case 'high-to-low':
                            $products = $products->orderBy('price', 'desc');
                            break;
                    }
                }else{
                    $products = $products->orderBy('id', 'desc');
                }

                if(array_key_exists('filterByPriceMin', $filter) && array_key_exists('filterByPriceMax', $filter)){
                    if($filter['filterByPriceMin'] === null && $filter['filterByPriceMax'] === null){
                        $products = $products->where('price', '>', 0);
                    }elseif($filter['filterByPriceMin'] !== null && $filter['filterByPriceMax'] === null){
                        $products = $products->where('price', '>' , $filter['filterByPriceMin']);
                    }else{
                        $products = $products->whereBetween('price', [$filter['filterByPriceMin'], $filter['filterByPriceMax']]);
                    }
                }

                if(array_key_exists('filterByColor', $filter) && $filter['filterByColor'] !== ''){
                    $products = $products->whereHas('productColors', function (Builder $query) use($filter){
                        if($filter['filterByColor'] === '*'){
                            $query->where('color_id', '>', 0);
                        }else{
                            $query->where('color_id', $filter['filterByColor']);
                        }
                    });
                }

                if(array_key_exists('filterByTag', $filter) && $filter['filterByTag'] !== ''){
                    $products = $products->whereHas('tags', function (Builder $query) use($filter){
                        if($filter['filterByTag'] === '*'){
                            $query->where('tag_id', '>', 0);
                        }else{
                            $query->where('tag_id', $filter['filterByTag']);
                        }
                    });
                }
                $totalProduct = $products->count();
            }else{
                $totalProduct = $products->count();
            }
            // dd($totalProduct);
            $products = $products->offset(($page-1)*$limit)->limit($limit)->get();
            // dd($products);
            if($totalProduct > 0){
                $a = $totalProduct/$limit;
                if($a < 1){
                    $numPage = 1;
                }else{
                    if($totalProduct % $limit == 0){
                        $numPage = floor($a);
                    }else{
                        $numPage = floor($a) + 1;
                    }
                }
            }

            if($page >= $numPage){
                $readMore = false;
            }else{
                $readMore = true;
            }

            $data = [
                'products' => $products,
                'readMore' => $readMore,
                'page' => $page
            ];
            // dd($data);
            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function searchProduct($request)
    {
        try{
            $data = [];
            $limit = 8;
            // dd($request);
            if($request['page']){
                $page = $request['page'];
            }

            $products = $this->model::where('status', 'active');

            if($request['val_search']){
                $products = $this->model::where('name', 'like', '%'.$request['val_search'].'%');
                $totalProduct = $products->count();
            }else{
                $products = $products->where('name', 'like', '%');
                $totalProduct = $products->count();
            }
            $products = $products->offset(($page-1)*$limit)->limit($limit)->orderBy('id', 'desc')->get();

            if($totalProduct > 0){
                $a = $totalProduct/$limit;
                if($a < 1){
                    $numPage = 1;
                }else{
                    if($totalProduct % $limit == 0){
                        $numPage = floor($a);
                    }else{
                        $numPage = floor($a) + 1;
                    }
                }
            }

            if($page >= $numPage){
                $readMore = false;
            }else{
                $readMore = true;
            }

            $data = [
                'products' => $products,
                'readMore' => $readMore,
                'page' => $page
            ];

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function productOverview($request)
    {
        try{
            $data = $this->model::find($request['product_id']);
            $data->price = number_format($data->price, 2, ',', '.');
            $product_colors = $data->productColors;
            $sizes = [];
            $product_color_tick = [];
            foreach($product_colors as $product_color){
                if($product_color->has('color')){
                    $product_color->color;
                    $number_color_sizes = $product_color->colorSizes()->sum('quanity');
                    $product_color->sum = $number_color_sizes;

                    if($number_color_sizes != 0){
                        $product_color_tick[] = $product_color->id;
                    }
                }
                if($product_color->has('colorSizes')){
                    $color_sizes = $product_color->colorSizes ;
                    foreach($color_sizes as $color_size){
                        $sizes[] = $color_size->size_id;
                    }
                }
                if($product_color->has('images')){
                    $product_color->images;
                }
            }

            if(!empty($product_color_tick)){
                $color_tick = ColorSize::whereIn('product_color_id', $product_color_tick)->get();
                if(isset($color_tick)){
                    foreach($color_tick as $item){
                        $size_tick[] = $item->size_id;
                    }
                }
                $size_tick = array_unique($size_tick);
            }
            $sizes = array_unique($sizes);

            if(!empty($sizes)){
                $data['sizes'] = Size::whereIn('id', $sizes)->get();

                foreach($data['sizes'] as $size_item){
                    if(in_array($size_item->id, $size_tick)){
                        $size_item->is_tick = 'true';
                    }else{
                        $size_item->is_tick = 'false';
                    }
                }
            }

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function productQuanity($request)
    {
        try{
            if(isset($request['productQuanity'])){
                $product_color_id = $request['productQuanity']['product_color_id'];
                $color_size_id = $request['productQuanity']['color_size_id'];

                $color_sizes = ColorSize::where('product_color_id', $product_color_id)
                    ->where('size_id', $color_size_id)
                    ->get();

                if($color_sizes->first()){
                    foreach($color_sizes as $color_size){
                        $quanity = $color_size->quanity;
                    }
                }else{
                    $quanity = 0;
                }

                return $quanity;
            }
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function addProductToCart($request)
    {
        try {
            $data = [];
            $errors = [];

            if(isset($request['product_detail'])){

                $pro_detail = $request['product_detail'];

                if($pro_detail['product_color'] === null || $pro_detail['color_size'] === null){
                    $errors[] = "You have not selected color and size!";
                }else{
                    if($pro_detail['quanity'] === null || $pro_detail['quanity'] === '0'){
                        $errors[] = 'Color and size do not exist';
                    }else{
                        if($pro_detail['num_product'] > $pro_detail['quanity'] || $pro_detail['num_product'] === '0'){
                            $errors['too_much'] = 'Too much allowed';
                        }
                    }
                }

                if(!empty($errors)){
                    $data = [
                        'errors' => $errors,
                    ];
                    return $data;
                }

                $product_detail = ColorSize::where('product_color_id', $pro_detail['product_color'])
                    ->where('size_id', $pro_detail['color_size'])
                    ->get();

                if($product_detail->first()){
                    foreach($product_detail as $item){
                        $product = [
                            'id' => $item->id,
                            'name' => $item->productColor->product->name,
                            'price' => $item->productColor->product->price,
                            'image_path' => $item->productColor->product->image_path,
                            'color' => $item->productColor->color->name,
                            'size' => $item->size->name,
                            'quanity' => $item->quanity,
                        ];
                    }
                }

                $cart =session()->get('cart');
                $totalQuanity = session()->get('totalQuanity', 0);
                $totalPrice = session()->get('totalPrice', 0);
                if(!$cart){
                    $cart = [
                        $product['id'] => [
                            "name" => $product['name'],
                            "qty" => intval($pro_detail['num_product']),
                            "price" => $product['price'],
                            "photo" => $product['image_path'],
                            "color" => $product['color'],
                            "size" => $product['size']
                        ],
                    ];

                    $totalQuanity += $pro_detail['num_product'];
                    $totalPrice += $product['price']*$pro_detail['num_product'];

                    session()->put([
                        'cart'=> $cart,
                        'product' => $product['name'],
                        'totalQuanity' => $totalQuanity,
                        'totalPrice' => $totalPrice
                    ]);

                    $new_cart = session()->all();

                    return $new_cart;
                }

                if(array_key_exists($product['id'], $cart)){

                    $qty_item = $cart[$product['id']]['qty'];

                    $checkQty =  intval($qty_item) + intval($pro_detail['num_product']);

                    if($checkQty > $product['quanity']){
                        $errors[] = 'The product in the shopping cart has exceeded the allowed number';
                    }

                    if(!empty($errors)){
                        $data = ['errors' => $errors];

                        return $data;
                    }

                    $cart[$product['id']]['qty'] += intval($pro_detail['num_product']);

                    $totalQuanity += intval($pro_detail['num_product']);
                    $totalPrice += $product['price']*intval($pro_detail['num_product']);

                    session()->put([
                        'cart'=> $cart,
                        'product' => $product['name'],
                        'totalQuanity' => $totalQuanity,
                        'totalPrice' => $totalPrice
                    ]);

                    $new_cart = session()->all();

                    return $new_cart;
                }

                $cart[$product['id']] = [
                    "name" => $product['name'],
                    "qty" => intval($pro_detail['num_product']),
                    "price" => $product['price'],
                    "photo" => $product['image_path'],
                    "color" => $product['color'],
                    "size" => $product['size']
                ];

                $totalQuanity += $pro_detail['num_product'];
                $totalPrice += $product['price']*$pro_detail['num_product'];

                session()->put([
                    'cart'=> $cart,
                    'product' => $product['name'],
                    'totalQuanity' => $totalQuanity,
                    'totalPrice' => $totalPrice
                ]);
                $new_cart = session()->all();

                return $new_cart;
            }
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function deleteProductFromCart($cart_id)
    {
        try{
            if($cart_id){
                $cart = session()->get('cart');
                $totalQuanity = session()->get('totalQuanity');
                $totalPrice = session()->get('totalPrice');

                if(isset($cart[$cart_id])){
                    $totalQuanity -= $cart[$cart_id]['qty'];
                    $totalPrice -= $cart[$cart_id]['qty']*$cart[$cart_id]['price'];
                    $product_name = $cart[$cart_id]['name'];

                    unset($cart[$cart_id]);
                    session()->put([
                        'cart' => $cart,
                        'totalQuanity' => $totalQuanity,
                        'totalPrice' => $totalPrice
                    ]);

                    $message = 'Deleted ' . $product_name . ' successfully!';

                    $data = [
                        'message' => $message,
                        'totalQuanity' => $totalQuanity,
                        'totalPrice' => $totalPrice
                    ];

                    return $data;
                }
            }
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    public function getProductDetail($productId)
    {
        try{
            $data= [];

            $product = $this->model::findOrFail($productId);

            $productColors = $product->productColors;

            if($productColors){
                foreach($productColors as $productColor){
                    $sizes[] = $productColor->sizes;
                    $colors[] = $productColor;
                    $images[] = $productColor->images;
                }

                if(!empty($colors)){
                    foreach($colors as $key => $color){
                        $colorIds[$key]['id'] = $color->id;
                        $colorIds[$key]['name'] = $color->color->name;

                        $color_sizes[] = $color->colorSizes;
                        if(!empty($color_sizes)){
                            foreach($color_sizes as $color_size){
                                if(count($color_size) > 0){
                                    $colorIds[$key]['disabled'] = 'false';
                                }else{
                                    $colorIds[$key]['disabled'] = 'true';
                                }
                            }
                        }
                    }
                }
                if(!empty($sizes)){
                    foreach($sizes as $size){
                        foreach($size as $key => $size_item){
                            $sizeIds[$size_item->id] = $size_item->name;
                        }
                    }
                }

                if(!empty($images)){
                    foreach($images as $image){
                        foreach($image as $image_item){
                            $imageIds[$image_item->id] = $image_item->image_path;
                        }
                    }
                }
            }

            $data = [
                'product' => $product,
                'colors' => $colorIds,
                'sizes' => $sizeIds,
                'images' => $imageIds
            ];

            return $data;
        } catch (\Exception $e) {
            return $this->errorResult($e->getMessage());
        }
    }

    protected function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    protected function errorResult($msg)
    {
        $message = array(
            'errorMsg' => $msg
        );

        return $message;
    }
}
