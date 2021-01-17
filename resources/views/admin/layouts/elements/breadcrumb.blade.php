<ul class="breadcrumb-wrapper d-flex justify-content-end">
    <li><a href="#">{{ $key }}</a></li>
    @empty(!$name)
        <li>{{ $name }}</li>
    @endempty
</ul>
<div class="line"></div>
<div class="trapezoid"></div>
<div class="title-content pl-2">
    <h4 class="text-secondary"><i class="fa fa-bullseye"></i> {{ $name }}</h4>
</div>
