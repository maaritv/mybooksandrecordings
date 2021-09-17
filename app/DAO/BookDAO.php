<?php

namespace App\DAO;

use app\Book;
use Illuminate\Support\Facades\DB;



class BookDAO {
    


    public function reserveBookForModification($book_id){
        DB::statement("update books set inedit_since = current_timestamp, ".
             "current_editor = '".$_SERVER['PHP_AUTH_USER']."' where id=?". 
             " and (current_editor is null or datediff(inedit_since, current_timestamp)>=1)", [$book_id]);
    }

    public function releaseBookFromModification($book_id){
        DB::statement("update books set inedit_since = null, ".
             "current_editor = null where id=?". 
             " and (current_editor = ? or datediff(inedit_since, current_timestamp)>=1)", 
             [$book_id, $_SERVER['PHP_AUTH_USER']]);
    }


}


?>