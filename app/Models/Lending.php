<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lending extends Model
{
    protected $fillable = ['customer_id', 'book_id'];

    protected $with = ['customer','book'];

    public function customer(){

        return $this->belongsTo(Customer::class);
    }

    public function book(){

        return $this->belongsTo(Book::class);
    }
}