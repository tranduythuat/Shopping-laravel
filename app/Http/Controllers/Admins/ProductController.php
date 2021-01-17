<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Products\ProductsRepositoryInterface;
use App\Repositories\Categories\CategoriesRepositoryInterface;
use App\Traits\JsonData;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\ColorSize;
use App\Models\Product;
use App\Repositories\Colors\ColorsRepositoryInterface;
use App\Repositories\Sizes\SizesRepositoryInterface;
use App\Repositories\Suppliers\SuppliersRepositoryInterface;
use App\Repositories\Tags\TagsRepositoryInterface;
use App\Repositories\ProductColors\ProductColorsRepositoryInterface;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    use JsonData;

    protected $productsRepo;
    protected $categoriesRepo;
    protected $suppliersRepo;
    protected $tagsRepo;
    protected $colorsRepo;
    protected $sizesRepo;
    protected $productColorsRepo;

    public function __construct(
        ProductsRepositoryInterface $productsRepo,
        CategoriesRepositoryInterface $categoriesRepo,
        SuppliersRepositoryInterface $suppliersRepo,
        TagsRepositoryInterface $tagsRepo,
        ColorsRepositoryInterface $colorsRepo,
        SizesRepositoryInterface $sizesRepo,
        ProductColorsRepositoryInterface $productColorsRepo)
    {
        $this->productsRepo = $productsRepo;
        $this->categoriesRepo= $categoriesRepo;
        $this->suppliersRepo = $suppliersRepo;
        $this->tagsRepo = $tagsRepo;
        $this->colorsRepo = $colorsRepo;
        $this->sizesRepo = $sizesRepo;
        $this->productColorsRepo = $productColorsRepo;
    }

    public function index()
    {
        return view('admin.products.index');
    }

    public function getAll()
    {
        $products = $this->productsRepo->getAll();
        // dd($products);

        return Datatables::of($products)
            ->addColumn('action', function ($product) {
                return '
                    <a href="javascript:;" data-id="'.$product->id.'" class="productInfo mr-1" title="info"><i class="fa fa-info"></i></a>
                    <a href="'.route('admin.product.edit', ['id' => $product->id]).'" class="productEdit mr-1" title="edit"><i class="fa fa-edit"></i></a>
                    <a href="#" data-id="'.$product->id.'" class="deleteProduct mr-1" title="delete"><i class="fa fa-trash"></i></a>
                ';
            })
            ->addColumn('product_item', function ($product) {
                $data = $this->productsRepo->getNumberItem($product);

                return '<a href="'.route('admin.product.item', ['id' => $product->id]).'" class="btn btn-info">Items ('.$data['countItem'].'/'.$data['quanity'].')</a>';
            })
            ->addColumn('checkbox', function ($product) {
                return '<input type="checkbox" class="child" data-id="'.$product->id.'">';
            })
            ->editColumn('image_path', function($product){
                return ' <img src="'.$product->image_path.'" alt="" height="100" style="object-fit:cover;width:100%">';
            })
            ->editColumn('status', function($product) {
                if($product->status === 'active'){
                    $isChecked = 'checked';
                }else {
                    $isChecked = '';
                }
                $html = '<input type="checkbox" '.$isChecked.' class="toggle-status" data-toggle="toggle"
                            data-id="'.$product->id.'" data-size="small"
                            data-onstyle="success" data-offstyle="secondary" data-toggle="toggle">';

                return $html;
            })
            ->editColumn('category', function($product){
                return $product->category;
            })
            ->editColumn('tag', function($product){
                $tags = $product->tags()->get();
                $html = '';
                foreach($tags as $item){
                    $html .=  '<span class="badge badge-pill badge-primary">'.$item->name.'</span>&nbsp';
                }
                return $html;
            })
            ->editColumn('price', function($product){
                return number_format($product->price, 2, ',', ' ') . " USD";
            })
            ->rawColumns(['checkbox', 'product_item', 'action', 'status', 'tag', 'image_path'])
            ->make(true);
    }

    public function getNumber()
    {
        try {
            $rowProducts = $this->productsRepo->getNumberProduct();

            return $this->jsonDataResult($rowProducts, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function store()
    {
        $categories = $this->categoriesRepo->getAll();
        $suppliers = $this->suppliersRepo->getAll();
        $tags = $this->tagsRepo->getAll();

        return view('admin.products.add', [
            'categories' => $categories,
            'suppliers' => $suppliers,
            'tags' => $tags
        ]);
    }

    public function add(ProductRequest $request)
    {
        $this->productsRepo->createProduct($request);

        return redirect()->route('admin.product.list')->with('success', 'Created Successfully!');
    }

    public function edit($id)
    {
        $categories = $this->categoriesRepo->getAll();
        $suppliers = $this->suppliersRepo->getAll();
        $tags = $this->tagsRepo->getAll();

        $product = $this->productsRepo->find($id);
        $tagsOfProduct = $product->tags()->get();
        $tagsPro = [];
        foreach( $tagsOfProduct as $item){
            $tagsPro[] = $item->name;
        }

        return view('admin.products.edit', [
            'categories' => $categories,
            'suppliers' => $suppliers,
            'tags' => $tags,
            'product' => $product,
            'tagsPro' => $tagsPro
        ]);
    }

    public function update($id, ProductUpdateRequest $request)
    {
        $data = $this->productsRepo->update($id, $request);

        return redirect()->route('admin.product.list')->with('success', 'Updated Successfully!');
    }

    public function getDetail($id)
    {
        try {
            $result = $this->productsRepo->show($id);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult($result, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $result = $this->productsRepo->delete($request->product_id);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonMsgResult(false, $result['message'], 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function trashIndex()
    {
        return view('admin.products.trash');
    }

    public function getNumberTrashProduct()
    {
        try {
            $rowProducts = $this->productsRepo->getNumberTrashProduct();

            return $this->jsonDataResult($rowProducts, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function trashAll()
    {
        try {
            $products = $this->productsRepo->getTrash();

            return Datatables::of($products)
            ->addColumn('checkbox', function ($product) {
                return '<input type="checkbox" class="child" data-id="'.$product->id.'" data-public_id="'.$product->publicId.'">';
            })
            ->addColumn('action', function ($product) {
                return '
                    <a href="#" data-id="'.$product->id.'" data-public_id="'.$product->publicId.'" class="destroy-trash" title="destroy"><i class="fa fa-times"></i></a>
                    <a href="#" data-id="'.$product->id.'" class="restore-trash" title="restore"><i class="fa fa-reply"></i></a>
                ';
            })
            ->editColumn('image_path', function($product){
                return ' <img src="'.$product->image_path.'" alt="" class="img-thumbnail" width="75">';
            })
            ->editColumn('price', function($product){
                return number_format($product->price, 2, ',', ' ') . " USD";
            })
            ->rawColumns(['checkbox', 'action', 'image_path'])
            ->make(true);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $product_id = $request->product_id;
            $publicId = $request->publicId;

            $result = $this->productsRepo->destroy($product_id, $publicId);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonMsgResult(false, $result['message'], 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function restore(Request $request)
    {
        try {
            $product_id = $request->product_id;

            $result = $this->productsRepo->restore($product_id);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonMsgResult(false, $result['message'], 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function deleteRows(Request $request)
    {
        try {
            $result = $this->productsRepo->deleteMutilRow($request->all());

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'number_product' => $result['number_product'],
                'success' => $result['message']
            ], $result['code']);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function destroyRows(Request $request)
    {
        try {
            $result = $this->productsRepo->destroyMutilRow($request->all());

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'number_product' => $result['number_product'],
                'success' => $result['message']
            ], $result['code']);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function restoreRows(Request $request)
    {
        try {
            $result = $this->productsRepo->restoreRows($request->all());

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonMsgResult(false, $result['message'], 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $result = $this->productsRepo->updateStatus($request->all());

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            if(!$result){
                $message = 'Products on inactive category';

                return $this->jsonMsgResult($message, false, 500);
            }

            return $this->jsonDataResult([
                'product_id' => $result['product']->id,
                'success' => $result['message']
            ], 201);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function indexItem($productId)
    {
        return view('admin.products.items', [
            'productId' => $productId,
        ]);
    }

    public function allProductItem($productId)
    {
        try {
            $colors = $this->colorsRepo->getColorActive();
            $sizes = $this->sizesRepo->getSizeActive();

            $colorByProducts = $this->productsRepo->getColorByProudct($productId);
            $colorIdByProducts= [];
            $sizesByProductColors = [];

            foreach($colorByProducts as $colorByProduct){
                $colorIdByProducts[] = $colorByProduct->id;
                $sizesByProductColors[$colorByProduct->id]['color'] = $this->colorsRepo->find($colorByProduct->id);
                $sizesByProductColors[$colorByProduct->id]['images'][] = $this->productColorsRepo->getImageByProductColor($colorByProduct->id, $productId);

                $sizesByProductColors[$colorByProduct->id][] = $this->productColorsRepo->getSizeByProductColor($colorByProduct->id, $productId);
            }

            return $this->jsonDataResult($sizesByProductColors, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function numberProductItem($productId)
    {
        try{
            $numberProductItem = $this->productsRepo->getNumberProductItem($productId);

            return $this->jsonDataResult($numberProductItem, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function storeProductItem(Request $request, $productId)
    {
        try {
            $result = $this->productColorsRepo->storeProductColorJson($request->all(), $productId);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult([
                'data' => $result,
                'success' => $result['message']
            ], 201);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function showProductItem($productId, $colorId, $sizeId)
    {
        try {
            $result = $this->productColorsRepo->getDetailProductColor($productId, $colorId, $sizeId);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonDataResult($result, 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function deleteProductItem($productId, $colorId, $sizeId)
    {
        try {
            $result = $this->productColorsRepo->deleteProductItem($productId, $colorId, $sizeId);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonMsgResult(false, $result['message'], 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function deleteProductColor($productId, $productColorId)
    {
        try {
            $result = $this->productColorsRepo->deleteProductColor($productId, $productColorId);

            if (isset($result['errorMsg'])) {
                return $this->jsonMsgResult($result, false, 500);
            }

            return $this->jsonMsgResult(false, $result['message'], 200);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }
    }

    public function listImageProductColor($productId, $colorId)
    {
        try {

            $images = $this->productColorsRepo->listImageProductColor($productId, $colorId);

            return view('admin.products.upload', [
                'productId' => $productId,
                'colorId' => $colorId,
                'images' => $images
            ]);
        } catch (\Exception $e) {
            return $this->jsonMsgResult($e->getMessage(), false, 500);
        }

    }

    public function uploadImageProductColor(Request $request, $productId, $colorId)
    {
        $request->validate([
            'upload_image' => 'required',
        ]);

        $this->productColorsRepo->uploadImageProductColor($request, $productId, $colorId);

        $request->session()->flash('success', 'Upload successfully!');
        return redirect('/admin/product/item/'.$productId.'/product-color/'.$colorId.'/list-image');

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
