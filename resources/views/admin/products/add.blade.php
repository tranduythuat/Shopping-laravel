@extends('admin.layouts.master')

@prepend('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admins/color.css') }}">
@endprepend

@prepend('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $("#category").select2({
            tags: true
        });

        $("#supplier").select2({
            tags: true
        });

        $("#tags").select2({
            tags: true,
            multiple: true,
            tokenSeparators: [',']
        });

    </script>
    <script src="//cdn.ckeditor.com/4.15.1/full/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('contentProduct', {
            uiColor: '#20c997',
            height : 400,
            toolbarCanCollapse: true,
            filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        });
    </script>
    @include('ckfinder::setup')
    <script src="{{ asset('js/admins/product.js') }}"  type="text/javascript"></script>
@endprepend

@section('title')
    <title>Add Product</title>
@endsection

@section('content')
    <div class="breadcrumb-box">
        <ul class="breadcrumb-wrapper d-flex justify-content-end">
            <li><a href="{{ route('admin.product.list') }}">Product</a></li>
            <li>Add product</li>
        </ul>
        <div class="line"></div>
        <div class="trapezoid"></div>
        <div class="title-content pl-2">
            <h4 class="text-secondary"><i class="fa fa-bullseye"></i> ADD PRODUCT</h4>
        </div>
    </div>
    <div class="container">
        @if($errors->any())
            {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
        @endif
        <form action="{{ route('admin.product.add') }}" class="my-3" id="formStoreProduct" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-5 col-sm-6">
                    <div class="custom-file">
                        <input type="file" name="image_path" class="custom-file-input" id="image_path">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                    <div class="box-image my-2">
                        <img src="{{ asset('images/img01.png') }}" id="imageShow" class="rounded mx-auto d-block" alt="Avatar Product" style="max-width: 200px">
                    </div>
                </div>
                <div class="col-md-7 col-sm-6 mt-5">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                        <small class="error error-name"></small>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-7">
                            <label for="price">Price (USD)</label>
                            <input type="number" class="form-control" name="price" id="price" value="{{ old('price') }}">
                            <small class="error error-price"></small>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="quanity">Quanity</label>
                            <input type="number" class="form-control" name="quanity" id="quanity" value="{{ old('quanity') }}">
                            <small class="error error-quanity"></small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="sale">Sale (%)</label>
                            <input type="number" class="form-control" name="sale" id="sale" value="{{ old('sale') }}">
                            <small class="error error-sale"></small>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Status</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input sta-active" type="radio" name="status" id="statusActive"  value="active"
                                    @if (!old('status'))
                                        checked
                                    @else
                                        @if (old('status') === 'active') checked @else null @endif
                                    @endif
                                    >
                                    <label class="form-check-label" for="statusActive">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input sta-inactive" type="radio" name="status" id="statusInactive" value="inactive"
                                    @if (!old('status'))
                                        null
                                    @else
                                        @if (old('status') === 'inactive') checked @else null @endif
                                    @endif
                                    >
                                    <label class="form-check-label" for="statusInactive">Inactive</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Hot</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input sta-active" type="radio" name="hot" id="noHot" value="no"
                                    @if (!old('hot'))
                                        checked
                                    @else
                                        @if (old('hot') === 'no') checked @else null @endif
                                    @endif
                                    >
                                    <label class="form-check-label" for="statusActive">No</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input sta-inactive" type="radio" name="hot" id="yesHot" value="yes"
                                    @if (!old('hot'))
                                        null
                                    @else
                                        @if (old('hot') === 'yes') checked @else null @endif
                                    @endif
                                    >
                                    <label class="form-check-label" for="statusInactive">Yes</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="category">Category</label>
                    <select name="category" id="category" style="width: 100%">
                        <option value="">Select</option>
                        @foreach ($categories as $category)
                            @if (old('category') == $category->id)
                                <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                            @else
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <small class="error error-category"></small>
                </div>
                <div class="form-group col-md-8">
                    <label for="supplier">Supplier</label>
                    <select name="supplier" id="supplier" style="width: 100%">
                        <option value="">Selection</option>
                        @foreach ($suppliers as $supplier)
                            @if (old('supplier') == $supplier->id)
                                <option value="{{ $supplier->id }}" selected>{{ $supplier->name }}</option>
                            @else
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <small class="error error-tag"></small>
                </div>
            </div>
            <div class="form-group">
                <label for="tags">Tag</label>
                <select name="tags[]" id="tags" style="width: 100%" multiple="multiple">
                    @foreach ($tags as $tag)
                        @if (!is_array(old('tags')))
                            <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                        @else
                            <option value="{{ $tag->name }}"
                                {{ (in_array($tag->name, old("tags")) ? "selected":"") }}
                            >
                                {{ $tag->name }}
                            </option>
                        @endif
                    @endforeach
                </select>

                <small class="error error-tag"></small>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                <small class="error error-description"></small>
            </div>
            <div class="form-group">
                <label for="contentProduct">Content</label>
                <textarea name="contentProduct" id="contentProduct">{{ old('contentProduct') }}</textarea>
                <small class="error error-contentProduct"></small>
            </div>
            <button class="form-control bg-primary text-white add-product-btn" type="submit">Add</button>
        </form>
    </div>
@endsection
