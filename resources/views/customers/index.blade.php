@include('headers.main')
<div style="margin-left: 30px">
<div> 
    <a style="margin: 19px;" href="{{ route('customers.create')}}" class="btn btn-primary">
    Uusi asiakas</a>
</div>

<div class="row">
    <div class="col-sm-12">
        <h1 class="display-3">Customers</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Etunimi</th>
                    <th>Sukunimi</th>
                    <th colspan=2 style="vertical-align: center">Toimenpiteet</th>
                </tr>
            </thead>
            <tbody> @foreach($customers as $customer) <tr>
                    <td>{{$customer->id}}</td>
                    <td>{{$customer->first_name}}</td>
                    <td>{{$customer->last_name}}</td>
                    <td> <a href="{{ route('customers.edit',$customer->id)}}" class="btn btn-primary">Muokkaa</a> </td>
                    <td>
                        <form action="{{ route('customers.destroy', $customer->id)}}" method="post"> @csrf @method('DELETE')
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