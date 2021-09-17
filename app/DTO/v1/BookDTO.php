<?php

namespace App\DTO\v1;



class BookDTO
{
    public $id;
    public $name;
    public $author;

    function __construct($book)
    {
        $this->name = $book->name;
        $this->author = $book->author;
        $this->id=$book->id;
    }

    public static function get_array_of_book_dtos($books)
    {
        $book_dtos = [];
        foreach ($books as $book) {
            array_push($book_dtos, new BookDTO($book));
        }
        return $book_dtos;
    }
}