@extends('admin.layouts.master')

@prepend('css')
    <link rel="stylesheet" href="{{ asset('css/admins/color.css') }}">
@endprepend

@prepend('scripts')
    <script src="{{ asset('js/admins/category.js') }}"></script>
@endprepend

@section('title')
    <title>Category</title>
@endsection

@section('content')
    <div class="breadcrumb-box">
        <ul class="breadcrumb-wrapper d-flex justify-content-end">
            <li><a href="{{ route('admin.category.list') }}">Category</a></li>
            <li>List</li>
        </ul>
        <div class="line"></div>
        <div class="trapezoid"></div>
        <div class="title-content pl-2">
            <h4 class="text-secondary"><i class="fa fa-bullseye"></i> CATEGORY</h4>
        </div>
    </div>
    <div class="container">
        <div class="box-button d-flex justify-content-between">
            <small id="numberCategory" class="text-secondary">
                <span>All(<span id="sumCategory"></span>) |</span>
                <span>Active(<span id="activeCategory"></span>) |</span>
                <span>Inactive(<span id="inactiveCategory"></span>)</span>
            </small>
            <div>
                <button class="btn btn-primary btn-sm" id="addCategory"> Add <i class="fa fa-plus-circle"></i></button>
            </div>
        </div>

        <br>

        <table class="table table-responsive-sm table-hover table-sm" id="categoryTable" style="width: 100%">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tfoot></tfoot>
        </table>
    </div>

    <div class="modal fade" id="storeCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formStoreCategory" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="category_id" value="">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <small class="error error-name"></small>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input sta-active" type="radio" name="status" id="statusActive" value="active" checked>
                                <label class="form-check-label" for="statusActive">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input sta-inactive" type="radio" name="status" id="statusInactive" value="inactive">
                                <label class="form-check-label" for="statusInactive">Inactive</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="file" name="image_path" id="image_path">
                            <input type="hidden" name="hidden_publicId" id="hidden_publicId" value="">
                            <input type="hidden" name="hidden_image_path" id="hidden_image_path" value="">
                            <small class="error error-image_path"></small>
                        </div>
                        <div class="box-image my-2">
                            <img src="{{ asset('images/img01.png') }}" id="imageShow" class="rounded mx-auto d-block" alt="Avatar Product" style="max-width: 200px">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary store-category-btn">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
