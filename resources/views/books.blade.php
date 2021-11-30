@extends('layouts.app')

@section('content')
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="{{ route('main_listing') }}"><img src="{{ url('images/packt_logo.png') }}" alt="Not found"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                @if(isset($search) && $search)
                    <li class="nav-item">
                        <a class="btn btn-outline-success my-2 my-sm-0" href="{{ route('main_listing') }}">Go back to
                            Main listing</a>
                    </li>
                @endif
            </ul>
            <form class="form-inline" action="{{ route('find') }}" method="GET">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search"
                       autocomplete="off" required="required" value="{{ old('search') }}">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
            <br>
        </div>
    </nav>
    <div class="row">
        @if($books instanceof \Illuminate\Pagination\LengthAwarePaginator && $books->isEmpty() || is_array($books) && empty($books))
            <div style="position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);">
                <img src="{{ url('images/404.jpg') }}" alt="Not found"><br>
                <a href="{{ route('main_listing') }}">Go back to Main listing</a>
            </div>
        @endif
    <!-- Card Projects -->
        @if(!is_null($books))
            @foreach($books as $book)
                <div class="card" style="width: 22rem; margin-bottom: 15px; margin-top: 10px; margin-left: 10px">
                    <a href="{{ route('get_product', ['id' => $book['id'] ]) }}">
                        <img class="card-img-top"
                             src="{{ 'https://api.packt.com/api/v1/products/'.$book['id'].'/cover/large?token='.config('packt.api_key') }}"
                             alt="Card image cap">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $book['title'] }}</h5>
                        <p class="card-text">
                            <strong>Author(s): </strong> {{ implode(',', $book['authors']) }}
                        </p>
                        <p class="card-text">
                            <strong>ISBN Number: </strong> {{ $book['isbn13'] }}
                        </p>
                        <p class="card-text">
                            <strong>Publication
                                Date: </strong> {{ \Illuminate\Support\Carbon::parse($book['publication_date'])->format('d M, D, Y') }}
                        </p>
                        @if(isset($book['categories']))
                            <p class="card-text">
                                <strong>Categories: </strong> {{ implode(',', $book['categories']) }}
                            </p>
                        @endif
                        @if(isset($book['concept']) && is_array($book['concept']) && !empty($book['concept']))
                            <p class="card-text">
                                <strong>Concept: </strong> {{ implode(',', $book['concept']) }}
                            </p>
                        @else
                            <p class="card-text">
                                <strong>Concept: </strong> {{ $book['concept'] }}
                            </p>
                        @endif
                        @if(isset($book['language']) && !empty($book['language']))
                            <p class="card-text">
                                <strong>Language: </strong> {{ $book['language'] }}
                            </p>
                        @endif
                        @if(isset($book['langauge_version']) && !empty($book['langauge_version']))
                            <p class="card-text">
                                <strong>Language Version: </strong> {{ $book['langauge_version'] }}
                            </p>
                        @endif
                        @if(isset($book['tool']) && !empty($book['tool']))
                            <p class="card-text">
                                <strong>Tools: </strong> {{ $book['tool'] }}
                            </p>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('get_product', ['id' => $book['id'] ]) }}" class="btn btn-primary">Read
                            More</a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    @if($books instanceof \Illuminate\Pagination\LengthAwarePaginator)
        {!! $books->links() !!}
    @endif
@endsection
