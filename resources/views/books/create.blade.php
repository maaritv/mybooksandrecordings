@extends('base')@section('main')<div class="row">
    <div class="col-sm-8 offset-sm-2">
        <h1 class="display-3">Lisää kirja</h1>
        <div> @if ($errors->any()) <div class="alert alert-danger">
                <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
            </div><br /> @endif 
            <form method="post" action="{{ route('books.store') }}"> @csrf 
                <div class="form-group"> 
            <label for="name">Nimi:</label> 
            <input type="text" class="form-control" name="name" /> </div>
            <div class="form-group"> 
            <label for="author">Kirjailija:</label> 
            <input type="text" class="form-control" name="author" /> </div>
            <div class="form-group"> 
            <label for="price">Hinta:</label> 
            <input type="text" class="form-control" name="price" /> </div>
            <button type="submit" class="btn btn-primary">Lisää kirja</button>
            </form>
        </div>
    </div>
</div>@endsection