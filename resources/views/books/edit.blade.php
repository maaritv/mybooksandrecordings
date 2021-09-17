@extends('base') @section('main')<div class="row">
    <div class="col-sm-8 offset-sm-2" style="">
        <h1 class="display-3">Muokkaa kirjaa</h1> @if ($errors->any()) <div class="alert alert-danger">
            <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
        </div> <br /> @endif <form method="post" action="{{ route('books.update', $book->id) }}"> @method('PATCH') @csrf
            <div>
                <div class="form-group">
                    <label for="name">Nimi:</label>
                    <input type="text" class="form-control" name="name" value="{{ $book->name }}" />
                </div>
                <div class="form-group">
                    <label for="author">Kirjailija:</label>
                    <input type="text" class="form-control" name="author" value="{{ $book->author }}" />
                </div>
                <div class="form-group">
                    <label for="pages">Hinta:</label>
                    <input type="text" class="form-control" name="price" value={{ $book->price }} />
                </div>
                 {{-- tämä on kommentoitu koodi, joten sitä ei 
                      suoriteta.
                @if($book->current_editor==$username)
                --}}
                <button type="submit" class="btn btn-primary" name="submit_button" value="update">Päivitä</button>
                {{-- @endif  myös tämä on kommentoitu--}}  
                <button type="submit" class="btn btn-secondary" name="submit_button" value="cancel">Peruuta</button>
        </form>
    </div>
</div>
</div>@endsection