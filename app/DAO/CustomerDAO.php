<?php

namespace App\DAO;

use app\Customer;
use Illuminate\Support\Facades\DB;



class CustomerDAO {
    


    public function reserveCustomerForModification($customer_id){
        DB::statement("update customers set inedit_since = current_timestamp, ".
             "current_editor = '".$_SERVER['PHP_AUTH_USER']."' where id=?". 
             " and (current_editor is null or datediff(inedit_since, current_timestamp)>=1)", [$customer_id]);
    }

    public function releaseCustomerFromModification($customer_id){
        DB::statement("update customers set inedit_since = null, ".
             "current_editor = null where id=?". 
             " and (current_editor = ? or datediff(inedit_since, current_timestamp)>=1)", 
             [$customer_id, $_SERVER['PHP_AUTH_USER']]);
    }


}


?>