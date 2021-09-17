@include('headers.main')
<div style="margin-left: 30px">
<div> 
    <a style="margin: 19px;" href="{{ route('books.create')}}" class="btn btn-primary">
    Uusi kirja</a>
</div>

<div>
<!-- kuva voi olla talletettuna public-hakemistossa, mutta tässä se on kommentoitu pois, eli se ei ole käytössä-->
<!--img src="{{URL::asset('/images/syksy.jpg')}}" alt="Syksy"/-->

</div>

<div class="row">
    <div class="col-sm-12">
        <h1 class="display-3">Kirjat {{$ip}}</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nimi</th>
                    <th>Kirjailija</th>
                    <th>Hinta</th>
                    <th>Muokkaus käynnissä</th>
                    <th>Muokkaaja</th>
                    <th colspan=2 style="vertical-align: center">Toimenpiteet</th>
                </tr>
            </thead>
            <tbody> @foreach($books as $book) <tr>
                    <td>{{$book->id}}</td>
                    <td>{{$book->name}}</td>
                    <td>{{$book->author}}</td>
                    <td>{{$book->price}}</td>
                    <td>{{$book->inedit_since}}</td>
                    <td>{{$book->current_editor}}</td>
                    <td> <a href="{{ route('books.edit',$book->id)}}" class="btn btn-primary">Muokkaa</a> </td>
                    <td>
                        <form action="{{ route('books.destroy', $book->id)}}" method="post"> @csrf @method('DELETE')
                            <button class="btn btn-danger" type="submit">Poista</button> </form>
                    </td>
                </tr> @endforeach </tbody>
        </table>
        <div class="col-sm-12">
            @if(session()->get('success'))
            <div class="alert alert-success"> {{ session()->get('success') }} </div>
            @endif
        </div>
        <div class="col-sm-12">
            @if(session()->get('error'))
            <div class="alert alert-danger"> {{ session()->get('error') }} </div>
            @endif
        </div>
    </div>
</div>
</div>