@extends('base')@section('main')<div class="row">
    <div class="col-sm-8 offset-sm-2">
        <h1 class="display-3">Tee lainaus</h1>
        <div> @if ($errors->any()) <div class="alert alert-danger">
                <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
            </div><br /> @endif 
            <form method="post" action="{{ route('lendings.store') }}"> @csrf 
            <div class="form-group"> 
            <label for="name">Customer:</label> 
            <select class="form-control" id="customerSelected" name="customer_id" required focus>
            <option value="" disabled selected>Customer</option>        
              @foreach($customers as $customer)
              <option value="{{$customer->id}}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
              @endforeach
            </select>
            </div>

            <div class="form-group"> 
            <label for="name">Book:</label> 
            <select class="form-control" id="bookSelected" name="book_id" required focus>
            <option value="" disabled selected>Book</option>        
              @foreach($books as $book)
              <option value="{{$book->id}}">{{ $book->name }}</option>
              @endforeach
            </select>
            </div>
           
            <input type="submit" value="Save">
            </form>
        </div>
    </div>
</div>@endsection