@extends('base')@section('main')
<div>
<a style="margin: 19px;" href="{{ route('lendings.index')}}" class="btn btn-secondary">
    Lainaukset</a>

    <a style="margin: 19px;" href="{{ route('books.index')}}" class="btn btn-secondary">
    Kirjat</a>

    <a style="margin: 19px;" href="{{ route('recordings.index')}}" class="btn btn-secondary">
    Äänitteet</a>

    <a style="margin: 19px;" href="{{ route('customers.index')}}" class="btn btn-secondary">
    Asiakkaat</a>
</div>