@extends('admin.layouts.master')

@prepend('css')
    <link rel="stylesheet" href="{{ asset('css/admins/color.css') }}">
@endprepend

@prepend('scripts')
    <script src="{{ asset('js/admins/slider.js') }}"></script>
@endprepend

@section('title')
    <title>Slider</title>
@endsection

@section('content')
    <div class="breadcrumb-box">
        <ul class="breadcrumb-wrapper d-flex justify-content-end">
            <li><a href="{{ route('admin.slider.list') }}">Slider</a></li>
            <li>List</li>
        </ul>
        <div class="line"></div>
        <div class="trapezoid"></div>
        <div class="title-content pl-2">
            <h4 class="text-secondary"><i class="fa fa-bullseye"></i> SLIDEER</h4>
        </div>
    </div>
    <div class="container">
        <div class="box-button d-flex justify-content-between">
            <small id="numberSlider" class="text-secondary">
                <span>All(<span id="sumSlider"></span>)</span>
            </small>
            <div>
                <button class="btn btn-primary btn-sm" id="addSlider"> Add <i class="fa fa-plus-circle"></i></button>
            </div>
        </div>

        <br>

        <table class="table table-responsive-sm table-hover table-sm" id="sliderTable" style="width: 100%">
            <thead>
                <tr>
                    <th scope="col">Title 1</th>
                    <th scope="col">Title 2</th>
                    <th scope="col">Link</th>
                    <th scope="col">Image</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tfoot></tfoot>
        </table>
    </div>

    <div class="modal fade" id="storeSliderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formStoreSlider" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Slider</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="slider_id" value="">
                        <div class="form-group">
                            <label for="title_01">Title 1</label>
                            <input type="text" name="title_01" id="title_01" class="form-control">
                            <small class="error error-title_01"></small>
                        </div>
                        <div class="form-group">
                            <label for="title_02">Title 2</label>
                            <input type="text" name="title_02" id="title_02" class="form-control">
                            <small class="error error-title_02"></small>
                        </div>
                        <div class="form-group">
                            <label for="link">Link</label>
                            <textarea name="link" id="link" cols="30" rows="3" class="form-control"></textarea>
                            <small class="error error-link"></small>
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
                        <button type="submit" class="btn btn-primary store-slider-btn">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
