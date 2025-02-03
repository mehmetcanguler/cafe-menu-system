@extends('client.layout.master')


@section('content')
    <div class="card">
        <div class="card-title">
            <h3 class="text-center">Kategoriler</h3>
        </div>

        <div class="card-body">
            <div class="row">
                @foreach ($categories as $category)
                    <a class="col mt-1 text-decoration-none" href="">
                        <div class="card" style="width: 18rem;">
                            @if ($category->files->count() > 0)
                                @foreach ($category->files as $key => $image)
                                    <img data-fancybox="gallery-{{ $category->id }}" data-caption="{{ $category->name }}"
                                        src="@if ($image) {{ $image->url }} @else {{ asset('client/img/default.jpg') }} @endif"
                                        class="card-img-top img-fluid @if($key != 0) d-none @endif" alt="{{ $category->name }}">
                                @endforeach
                            @else
                                <img src="{{ asset('client/img/default.jpg') }}" class="card-img-top img-fluid "
                                    alt="{{ $category->name }}">
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $category->name }}</h5>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

    </div>
@endsection
