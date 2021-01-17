@extends('admin.layouts.master')

@prepend('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admins/color.css') }}">
@endprepend

@prepend('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/admins/productItems.js') }}"  type="text/javascript"></script>
@endprepend

@section('title')
    <title>Product Items</title>
@endsection

@section('content')
    <div class="breadcrumb-box">
        <ul class="breadcrumb-wrapper d-flex justify-content-end">
            <li><a href="{{ route('admin.product.list') }}">Product</a></li>
            <li>Items</li>
        </ul>
        <div class="line"></div>
        <div class="trapezoid"></div>
        <div class="title-content pl-2">
            <h4 class="text-secondary"><i class="fa fa-bullseye"></i> ADD PRODUCT ITEMS</h4>
        </div>
    </div>
    <div class="container">
        <input type="hidden" name="productId" id="productId" value="{{ $productId }}">
        <div class="box-button d-flex justify-content-between">
            <small id="numberProduct" class="text-secondary">
                <span>All(<span id="sumProductItem"></span>)</span>
            </small>
            <div>
                <a href="javascript:;" class="btn btn-primary btn-sm" id="addProductItem"> Add <i class="fa fa-plus-circle"></i></a>
            </div>
        </div>

        <br>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-product" style="overflow-x: auto;max-width:100%">
            <table class="table table-hover table-striped" id="productItemTable" style="overflow: scroll;">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Color</th>
                        <th scope="col">Image</th>
                        <th scope="col">Sizes</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="tbProductItem">

                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="storeProductItemModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Product Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formStoreProductItem" method="POST">
                        @csrf
                        <input type="hidden" name="product_color_id" value="">
                        <div class="form-group">
                            <label for="colors">Color</label>
                            <select name="colors" id="colors" style="width: 100%" class="roduct-items">
                            </select>
                            <small class="error error-name"></small>
                        </div>
                        <div class="form-group">
                            <label for="sizes">Size</label>
                            <input type="hidden" name="size_id" value="">
                            <select name="sizes" id="sizes" style="width: 100%" class="roduct-items">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quanity">Quanity</label>
                            <input type="number" name="quanity" id="quanity" class="form-control" min="0">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary store-product-item-btn">Add</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formUploadImage" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="product-color-id" value="">
                        <input type="file" name="image_upload[]" id="image_upload" multiple>
                        <span id="error_multiple_files"></span>
                        <div class="gallery px-auto"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary upload-image-btn">Upload</button>
                </div>
            </div>
        </div>
    </div>
@endsection
