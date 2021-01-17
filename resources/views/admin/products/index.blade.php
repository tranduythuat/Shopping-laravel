@extends('admin.layouts.master')

@prepend('css')

    <link rel="stylesheet" href="{{ asset('css/admins/color.css') }}">
@endprepend

@prepend('scripts')
    <script src="{{ asset('js/admins/product.js') }}"></script>
@endprepend

@section('title')
    <title>Product</title>
@endsection

@section('content')
    <div class="breadcrumb-box">
        <ul class="breadcrumb-wrapper d-flex justify-content-end">
            <li><a href="{{ route('admin.product.list') }}">Product</a></li>
            <li>List</li>
        </ul>
        <div class="line"></div>
        <div class="trapezoid"></div>
        <div class="title-content pl-2">
            <h4 class="text-secondary"><i class="fa fa-bullseye"></i> Product List</h4>
        </div>
    </div>
    <div class="container">
        <div class="box-button d-flex justify-content-between">
            <small id="numberProduct" class="text-secondary">
                <span>All(<span id="sumProduct"></span>)</span>
                <span>Active(<span id="activeProduct"></span>) |</span>
                <span>Inactive(<span id="inactiveProduct"></span>)</span>
            </small>
            <div>
                <button class="btn btn-danger btn-sm" id="deleteAllProduct">Delete <i class="fa fa-minus-square"></i></button>
                <a href="{{ route('admin.product.add') }}" class="btn btn-primary btn-sm" id="addProduct"> Add <i class="fa fa-plus-circle"></i></a>
                <a href="{{ route('admin.product.trash') }}" class="btn btn-secondary btn-sm">Trash <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <br>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-product" style="overflow-x: auto;max-width:100%">
            <table class="table table-hover table-striped" id="productTable" style="overflow: scroll;">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" id="parent"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Image</th>
                        <th scope="col">Prie</th>
                        <th scope="col">Quanity</th>
                        <th scope="col">Category</th>
                        <th scope="col">Tag</th>
                        <th scope="col">Product Items</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tfoot></tfoot>
            </table>
        </div>
    </div>

    <div class="modal fade" id="infoRroductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Product Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
