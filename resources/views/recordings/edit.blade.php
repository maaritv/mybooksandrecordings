@extends('base') @section('main')<div class="row">
    <div class="col-sm-8 offset-sm-2" style="">
        <h1 class="display-3">Muokkaa äänitettä</h1> @if ($errors->any()) <div class="alert alert-danger">
            <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
        </div> <br /> @endif <form method="post" action="{{ route('recordings.update', $recording->id) }}"> @method('PATCH') @csrf
            <div>
                <div class="form-group">
                    <label for="name">Nimi:</label>
                    <input type="text" class="form-control" name="name" value="{{ $recording->name }}" />
                </div>
                <div class="form-group">
                    <label for="author">Artisti:</label>
                    <input type="text" class="form-control" name="artist" value="{{ $recording->artist }}" />
                </div>
                <div class="form-group">
                    <label for="pages">Julkaisuvuosi:</label>
                    <input type="text" class="form-control" name="published" value={{ $recording->published }} />
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