@extends('base')@section('main')<div class="row">
    <div class="col-sm-8 offset-sm-2">
        <h1 class="display-3">Lisää äänite</h1>
        <div> @if ($errors->any()) <div class="alert alert-danger">
                <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
            </div><br /> @endif 
            <form method="post" action="{{ route('recordings.store') }}"> @csrf 
                <div class="form-group"> 
            <label for="name">Nimi:</label> 
            <input type="text" class="form-control" name="name" /> </div>
            <div class="form-group"> 
            <label for="author">Artisti:</label> 
            <input type="text" class="form-control" name="artist" /> </div>
            <div class="form-group"> 
            <label for="price">Julkaisuvuosi:</label> 
            <input type="text" class="form-control" name="published" /> </div>
            <button type="submit" class="btn btn-primary">Lisää äänite</button>
            </form>
        </div>
    </div>
</div>@endsection