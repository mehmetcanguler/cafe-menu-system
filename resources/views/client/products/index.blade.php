@extends('client.layout.master')


@section('content')
    <div class="card">
        <div class="card-title">
            <h3 class="text-center">{{ $category->name }} Ürünleri</h3>
        </div>

        <div class="card-body">
            <div class="row">
                @foreach ($products as $product)
                    <a class="col mt-1 text-decoration-none" href="">
                        <div class="card" style="width: 18rem;">
                            @if ($product->files->count() > 0)
                                @foreach ($product->files as $key => $image)
                                    <img data-fancybox="gallery-{{ $product->id }}" data-caption="{{ $product->name }}"
                                        src="@if ($image) {{ $image->url }} @else {{ asset('client/img/default.jpg') }} @endif"
                                        class="card-img-top img-fluid @if($key != 0) d-none @endif" alt="{{ $product->name }}">
                                @endforeach
                            @else
                                <img src="{{ asset('client/img/default.jpg') }}" class="card-img-top img-fluid"
                                    alt="{{ $product->name }}">
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ $product->description }}</p>
                                <p class="card-text">Fiyat: {{ $product->price }} TL</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

    </div>
@endsection