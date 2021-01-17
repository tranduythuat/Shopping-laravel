@extends('admin.layouts.master')

@prepend('css')
    <link rel="stylesheet" href="{{ asset('css/admins/color.css') }}">
@endprepend

@prepend('scripts')
    <script src="{{ asset('js/admins/trashProduct.js') }}"></script>
@endprepend

@section('title')
    <title>Product Trash</title>
@endsection

@section('content')
    <div class="breadcrumb-box">
        <ul class="breadcrumb-wrapper d-flex justify-content-end">
            <li><a href="{{ route('admin.product.list') }}">Product</a></li>
            <li>Trash</li>
        </ul>
        <div class="line"></div>
        <div class="trapezoid"></div>
        <div class="title-content pl-2">
            <h4 class="text-secondary"><i class="fa fa-bullseye"></i> Trash</h4>
        </div>
    </div>
    <div class="container">
        <div class="box-button d-flex justify-content-between">
            <small id="numberProduct" class="text-secondary">
                <span>All(<span id="sumProductTrash"></span>)</span>
            </small>
            <div>
                <button class="btn btn-danger btn-sm" id="destroyAllProduct">Destroy All</button>
                <button class="btn btn-primary btn-sm" id="restoreAllProduct"> Restore All</button>
            </div>
        </div>

        <br>
        <div class="table-product" style="overflow-x: auto;max-width:100%">
            <table class="table table-hover table-striped" id="trashProductTable" style="overflow: scroll;">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" id="parent"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Image</th>
                        <th scope="col">Prie</th>
                        <th scope="col">Quanity</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tfoot></tfoot>
            </table>
        </div>
    </div>
@endsection
