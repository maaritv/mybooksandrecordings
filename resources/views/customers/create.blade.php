@extends('base')@section('main')<div class="row">
    <div class="col-sm-8 offset-sm-2">
        <h1 class="display-3">Lisää asiakas</h1>
        <div> @if ($errors->any()) <div class="alert alert-danger">
                <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
            </div><br /> @endif 
            <form method="post" action="{{ route('customers.store') }}"> @csrf 
                <div class="form-group"> 
            <label for="name">Etunimi:</label> 
            <input type="text" class="form-control" name="first_name" /> </div>
            <div class="form-group"> 
            <label for="author">Last name:</label> 
            <input type="text" class="form-control" name="last_name" /> </div>

            <button type="submit" class="btn btn-primary">Lisää asiakas</button>
            </form>
        </div>
    </div>
</div>@endsection