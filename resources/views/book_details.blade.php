@extends('layouts.app')

@section('content')
    <div class="col-lg-8 border main-section bg-white">
        <span> << <a href="{{ route('main_listing') }}">Go Back to Main Listings</a></span>
    </div>
    <div class="col-lg-8 border p-3 main-section bg-white">
        <div class="row hedding m-0 pl-3 pt-0 pb-3">
            {{ $book['title'] }}
        </div>
        <div class="row m-0">
            <div class="col-lg-4 left-side-product-box pb-3">
                @if(isset($book['images']['cover']) && !empty($book['images']['cover']))
                    <a href="{{ $book['url'] }}">
                        <img
                            src="{{ 'https://api.packt.com/api/v1/products/'.$book['id'].'/cover/small?token='.config('packt.api_key') }}">
                    </a>
                @endif
                <span class="sub-img">
                    <div class="col-lg-12">
                            @if(isset($book['authors']) && !empty($book['authors']))
                            <span><u><b>Author(s):</b></u></span><br>
                            <ul>
                                    @foreach($book['authors'] as $author)
                                    @if(isset($author['profile_url']) && !empty($author['profile_url']))
                                        <li><strong>Name: </strong> <a href="{{ $author['profile_url'] }}"
                                                                       target="_blank">{{ $author['name'] }}</a></li>
                                    @else
                                        <li><strong>Name: </strong> {{ $author['name'] }}</li>
                                    @endif
                                    <li><strong>About: </strong> {{ strip_tags($author['about']) }}</li>
                                @endforeach
                                </ul>
                        @endif
                            <hr class="m-0 pt-2 mt-2">
                        </div>
                </span>
            </div>
            <div class="col-lg-8">
                <div class="right-side-pro-detail border p-3 m-0">
                    <div class="row">
                        <div class="col-lg-12">
                            @if(isset($book['isbns']) && is_array($book['isbns']))
                                @foreach($book['isbns'] as $key => $isbn)
                                    <span><strong> {{ \Illuminate\Support\Str::ucfirst($key) }} </strong> - {{ $isbn }}</span>
                                    <br>
                                @endforeach
                            @else
                                <span>{{ $book['isbn'] }}</span> <br>
                            @endif
                            <p class="m-0 p-0"><a href="{{ $book['url'] }}">{{ $book['title'] }}</a></p>
                            <span><strong>Product type: </strong>{{ $book['product_type'] }}</span><br>
                            @if(isset($book['tagline']) && !empty($book['tagline']))
                                <i>{{ $book['tagline'] }}</i><br>
                            @endif
                            @if(isset($book['pages']) && !empty($book['pages']))
                                <span><strong>Total pages: </strong> {{ $book['pages'] }}</span><br>
                            @endif
                            <span><strong>Published on: </strong> {{ \Illuminate\Support\Carbon::parse($book['publication_date'])->format('d M, D Y') }}</span><br>
                            @if(isset($book['length']) && !empty($book['length']))
                                <span><strong>Total Duration: </strong> {{ $book['length'] }}</span><br>
                            @endif
                        </div>
                        @if(isset($pricing['prices']) && !empty($pricing['prices']))
                            <div class="col-lg-12">
                                @foreach($pricing['prices'] as $type => $price)
                                    @if(isset($price[env('CURRENCY', 'GBP')]))
                                        <p class="m-0 p-0 price-pro">
                                            <strong>{{ \Illuminate\Support\Str::ucfirst($type) }}
                                                : </strong>{{ $price[env('CURRENCY', 'GBP')] . " ". env('CURRENCY', 'GBP')}}
                                        </p>
                                        <hr class="p-0 m-0">
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <div class="col-lg-12 pt-2">
                            @if(isset($book['learn']) && !empty($book['learn']))
                                <span><u><b>Key Learnings:</b></u></span><br>
                                {!! $book['learn'] !!}
                            @endif
                            @if(isset($book['features']) && !empty($book['features']))
                                <span><u><b>Key Features:</b></u></span><br>
                                {!! $book['features'] !!}
                            @endif
                            <hr class="m-0 pt-2 mt-2">
                        </div>
                        <div class="col-lg-12">
                            @if(isset($book['description']) && !empty($book['description']))
                                <span><u><b>Description: </b></u></span><br>
                                {{ strip_tags($book['description']) }}
                            @endif
                            <hr class="m-0 pt-2 mt-2">
                        </div>
                        <div class="col-lg-12">
                            @if(isset($book['concept']) && is_array($book['concept']) && !empty($book['concept']))
                                <strong>Tags :</strong><br>
                                <span>{{ implode(',', $book['concept']) }}</span>
                            @else
                                <strong>Tags :</strong><br>
                                <span>{{ $book['concept'] }}</span>
                            @endif
                        </div>
                        <div class="col-lg-12 mt-3">
                            <div class="row">
                                <div class="col-lg-6 pb-2">
                                    <a href="{{ $book['url'] }}" target="_blank" class="btn btn-danger w-100">Access
                                        from
                                        Packt WebSite</a>
                                </div>
                                <div class="col-lg-6">
                                    <a href="{{ route('main_listing') }}" class="btn btn-success w-100">Go
                                        Back to Listings Page</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
