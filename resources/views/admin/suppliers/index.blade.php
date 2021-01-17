@extends('admin.layouts.master')

@prepend('css')
    <link rel="stylesheet" href="{{ asset('css/admins/color.css') }}">
@endprepend

@prepend('scripts')
    <script src="{{ asset('js/admins/supplier.js') }}"></script>
@endprepend

@section('title')
    <title>Supplier</title>
@endsection

@section('content')
    <div class="breadcrumb-box">
        <ul class="breadcrumb-wrapper d-flex justify-content-end">
            <li><a href="{{ route('admin.supplier.list') }}">Supplier</a></li>
            <li>List</li>
        </ul>
        <div class="line"></div>
        <div class="trapezoid"></div>
        <div class="title-content pl-2">
            <h4 class="text-secondary"><i class="fa fa-bullseye"></i> SUPPLIER</h4>
        </div>
    </div>
    <div class="container">
        <div class="box-button d-flex justify-content-between">
            <small id="numberCategory" class="text-secondary">
                <span>All(<span id="sumSupplier"></span>) </span>
            </small>
            <div>
                <button class="btn btn-primary btn-sm" id="addSupplier"> Add <i class="fa fa-plus-circle"></i></button>
            </div>
        </div>

        <br>

        <table class="table table-responsive-sm table-hover table-sm" id="supplierTable" style="width: 100%">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Website</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tfoot></tfoot>
        </table>
    </div>

    <div class="modal fade" id="storeSupplierModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formStoreSupplier" method="POST">
                        @csrf
                        <input type="hidden" name="supplier_id" value="">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <small class="error error-name"></small>
                        </div>
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                            <small class="error error-email"></small>
                        </div>
                        <div class="form-group">
                            <label for="name">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                            <small class="error error-phone"></small>
                        </div>

                        <div class="form-group">
                            <label for="name">Website</label>
                            <input type="text" name="website" id="website" class="form-control">
                            <small class="error error-website"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary store-supplier-btn">Add</button>
                </div>
            </div>
        </div>
    </div>
@endsection
