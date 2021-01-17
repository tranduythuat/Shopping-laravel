@extends('admin.layouts.master')

@prepend('css')
    <link rel="stylesheet" href="{{ asset('css/admins/color.css') }}">
@endprepend

@prepend('scripts')
    <script src="{{ asset('js/admins/color.js') }}"></script>
@endprepend

@section('title')
    <title>Color</title>
@endsection

@section('content')
    <div class="breadcrumb-box">
        <ul class="breadcrumb-wrapper d-flex justify-content-end">
            <li><a href="{{ route('admin.product.list') }}">Product</a></li>
            <li>Color</li>
        </ul>
        <div class="line"></div>
        <div class="trapezoid"></div>
        <div class="title-content pl-2">
            <h4 class="text-secondary"><i class="fa fa-bullseye"></i> Color</h4>
        </div>
    </div>
    <div class="container">
        <div class="box-button d-flex justify-content-between">
            <small id="numberColor" class="text-secondary">
                <span>All(<span id="sumColor"></span>) |</span>
                <span>Active(<span id="activeColor"></span>) |</span>
                <span>Inactive(<span id="inactiveColor"></span>)</span>
            </small>
            <div>
                <button class="btn btn-danger btn-sm" id="deleteMutipleColor">Delete <i class="fa fa-minus-square"></i></button>
                <button class="btn btn-primary btn-sm" id="addColor"> Add <i class="fa fa-plus-circle"></i></button>
                {{-- <button class="btn btn-secondary">Trash <i class="fa fa-arrow-circle-right"></i></button> --}}
            </div>
        </div>

        <br>

        <table class="table table-responsive-sm table-hover table-sm" id="colorTable" style="width: 100%">
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox" id="parent"></th>
                    <th scope="col">Name</th>
                    <th scope="col">Code</th>
                    <th scope="col">Color</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tfoot></tfoot>
        </table>
    </div>

    <div class="modal fade" id="storeColorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Color</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formStoreColor" method="POST">
                        @csrf
                        <input type="hidden" name="color_id" value="">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <small class="error error-name"></small>
                        </div>
                        <div class="form-group">
                            <label for="color">Color</label>
                            <input type="color" name="color" id="color" value="#000000">
                        </div>
                        <input type="text" disabled class="form-control color-text" value="#000000">
                        <br>
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary store-color-btn">Add</button>
                </div>
            </div>
        </div>
    </div>
@endsection
