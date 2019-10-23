@extends('layout')

@section('content')
    <h1 class="mt-5 mb-5">Матчи</h1>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @foreach ($matches as $match)

        <div class="mt-3">
            <h3>{{$match->name}}</h3>
            <a href="/match/{{$match->id}}">Посмотреть</a>
        </div>

    @endforeach

    <div class="mt-5 mb-5">
        {{ $matches->links() }}
    </div>

@endsection
