@extends('layout')

@section('content')
    <h1 class="mt-5 mb-5">{{$match->name}}</h1>

        <div class="mt-3">
            <p>{!! $matchContent->content !!}</p>
        </div>

@endsection
