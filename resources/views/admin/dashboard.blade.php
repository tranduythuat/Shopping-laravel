@extends('admin.layouts.master')

@section('title')
    <title>Dashboard</title>
@endsection

@prepend('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <style>
        .fa-info {
            color: rgb(11, 11, 228);
        }
        .fa-trash {
            color: red;
        }
    </style>
@endprepend

@prepend('scripts')
    <script src="{{ asset('js/admins/dashboard.js') }}"></script>
@endprepend

@section('content')
    <div class="breadcrumb-box">
        <div class="breadcrumb-box">
            <ul class="breadcrumb-wrapper d-flex justify-content-end">
                <li><a href="{{ route('admin.category.list') }}">Dashboard</a></li>
                {{-- <li>List</li> --}}
            </ul>
            <div class="line"></div>
            <div class="trapezoid"></div>
            <div class="title-content pl-2">
                <h4 class="text-secondary"><i class="fa fa-bullseye"></i> Dashboard</h4>
            </div>
        </div>
    </div>
    <div class="box-content mb-5">
        <div class="my-3 d-flex flex-wrap justify-content-center">
            <div class="card text-white bg-primary mb-3 mx-2" style="min-width: 14rem;">
                <div class="card-header">Successful transaction</div>
                <div class="card-body">
                  <h5 class="card-title">{{ $transComplete }}</h5>
                </div>
            </div>
            <div class="card text-white bg-secondary mb-3 mx-2" style="min-width: 14rem;">
                <div class="card-header">Transaction pending</div>
                <div class="card-body">
                  <h5 class="card-title">{{ $transPending }}</h5>
                </div>
            </div>
            <div class="card text-white bg-success mb-3 mx-2" style="min-width: 14rem;">
                <div class="card-header">Total revenue</div>
                <div class="card-body">
                  <h5 class="card-title">$ {{ number_format($totalRevenue, '2', ',', '.') }}</h5>
                </div>
              </div>
            <div class="card text-white bg-danger mb-3 mx-2" style="min-width: 14rem;">
                <div class="card-header">Transaction failed</div>
                <div class="card-body">
                  <h5 class="card-title">{{ $transDestroy }}</h5>
                </div>
            </div>
        </div>
        <hr>
        <h4 class="ml-3">Transaction</h4>
        <table class="table table-hover table-hover table-sm" id="transactionTable" style="width: 100%">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tfoot></tfoot>
        </table>
    </div>

    <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="transactionModalTitle">Transaction</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>
@endsection
