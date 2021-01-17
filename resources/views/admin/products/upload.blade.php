@extends('admin.layouts.master')

@prepend('css')
    <link rel="stylesheet" href="{{ asset('css/admins/color.css') }}">
@endprepend

@prepend('scripts')
    <script src="{{ asset('js/admins/upload.js') }}"  type="text/javascript"></script>
@endprepend

@section('title')
    <title>Upload Image</title>
@endsection

@section('content')
    <div class="breadcrumb-box">
        <ul class="breadcrumb-wrapper d-flex justify-content-end">
            <li><a href="{{ route('admin.product.list') }}">Product</a></li>
            <li><a href="{{ route('admin.product.item', ['id' => $productId]) }}">Item</a></li>
            <li>Upload</li>
        </ul>
        <div class="line"></div>
        <div class="trapezoid"></div>
        <div class="title-content pl-2">
            <h4 class="text-secondary"><i class="fa fa-bullseye"></i> UPLOAD</h4>
        </div>
    </div>
    <div class="container">

        @if($errors->any())
            {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ url('/admin/product/item/'.$productId.'/product-color/'.$colorId.'/upload-image') }}" class="my-3" id="formStoreProduct" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="productColorId" value="{{ $colorId }}">
            <input type="file" name="upload_image[]" id="upload_image" multiple>
            <button class="bg-primary text-white add-product-btn btn btn-primary" type="submit">UPLOAD</button>
            <hr>
            <div class="gallery">
                @foreach ($images as $image)
                    <img src="{{ $image->image_path }}" alt="{{ $image->image_name}}" style="width: 40%" class="img-thumbnail">
                @endforeach
            </div>
        </form>
    </div>
@endsection
