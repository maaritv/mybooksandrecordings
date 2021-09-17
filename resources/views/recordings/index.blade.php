@include('headers.main')
<div style="margin-left: 30px">
<div> 
    <a style="margin: 19px;" href="{{ route('recordings.create')}}" class="btn btn-primary">
    Uusi äänite</a>
</div>

<div>
<!-- kuva voi olla talletettuna public-hakemistossa, mutta tässä se on kommentoitu pois, eli se ei ole käytössä-->
<!--img src="{{URL::asset('/images/syksy.jpg')}}" alt="Syksy"/-->

</div>

<div class="row">
    <div class="col-sm-12">
        <h1 class="display-3">Äänitteet</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nimi</th>
                    <th>Artisti</th>
                    <th>Julkaisuvuosi</th>
                    <th colspan=2 style="vertical-align: center">Toimenpiteet</th>
                </tr>
            </thead>
            <tbody> @foreach($recordings as $recording) <tr>
                    <td>{{$recording->id}}</td>
                    <td>{{$recording->name}}</td>
                    <td>{{$recording->artist}}</td>
                    <td>{{$recording->published}}</td>
                    <td> <a href="{{ route('recordings.edit',$recording->id)}}" class="btn btn-primary">Muokkaa</a> </td>
                    <td>
                        <form action="{{ route('recordings.destroy', $recording->id)}}" method="post"> @csrf @method('DELETE')
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