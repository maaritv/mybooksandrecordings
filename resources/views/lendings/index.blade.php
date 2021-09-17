@include('headers.main')
<div style="margin-left: 30px">
<div> 
    <a style="margin: 19px;" href="{{ route('lendings.create')}}" class="btn btn-primary">
    Uusi lainaus</a>
</div>

<div class="row">
    <div class="col-sm-12">
        <h1 class="display-3">Lainat</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Asiakas</th>
                    <th>Kirja</th>
                    <th>Lainauspvm</th>
                    <th>Palautuspvm</th>
                    <th colspan=2 style="vertical-align: center">Toimenpiteet</th>
                </tr>
            </thead>
            <tbody> @foreach($lendings as $lending) <tr>
                    <td>{{$lending->id}}</td>
                    <td>{{$lending->customer['first_name']}} {{$lending->customer['last_name']}}</td>
                    <td>{{$lending->book['name']}}</td>
                    <td>{{$lending->lending_date}}</td>
                    <td>{{$lending->return_date}}</td>
                    <td>
                        @if (!isset($lending->return_date))
                    <form action="{{ route('returnbook', $lending->id)}}" method="post"> @csrf @method('PUT')
                            <button class="btn btn-danger" type="submit">Palauta</button> </form>
                        @endif
                    </td>
                </tr> @endforeach </tbody>
        </table>
        <div class="col-sm-12">
            @if(session()->get('success'))
            <div class="alert alert-success"> {{ session()->get('success') }} </div>
            @endif
        </div>
    </div>
</div>