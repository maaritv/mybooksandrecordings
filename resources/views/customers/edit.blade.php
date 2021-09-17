@extends('base') @section('main')<div class="row">
    <div class="col-sm-8 offset-sm-2">
        <h1 class="display-3">Muokkaa asiakasta</h1> @if ($errors->any()) <div class="alert alert-danger">
            <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
        </div> <br /> @endif <form method="post" action="{{ route('customers.update', $customer->id) }}"> @method('PATCH') @csrf <div class="form-group">
                <label for="first_name">Etunimi:</label>
                <input type="text" class="form-control" name="first_name" value="{{ $customer->first_name }}" /> </div>
            <div class="form-group">
                <label for="last_name">Sukunimi:</label>
                <input type="text" class="form-control" name="last_name" value="{{ $customer->last_name }}" /> </div>
            <div class="form-group">
                @if($customer->current_editor==$username)
                <button type="submit" class="btn btn-primary" name="submit_button" value="update">Päivitä</button>
                @endif
        </form>
    </div>
</div>@endsection