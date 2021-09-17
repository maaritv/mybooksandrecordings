<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Lending;
use App\DTO\v1\BookDTO;
use App\DTO\v1\MessageError;
use Illuminate\Support\Facades\DB;
use Exception;

class BookRestController extends Controller
{


    /**
     * Palauttaa kaikki kirjat JSON-muodossa. 
     */

    public function index()
    {
        try {
            $books = Book::all();
            if ($books == null) {
                $error = new MessageError();
                $error->set_error_code(100);
                $error->set_error_message("No books are found");
                return response()->json($error);
            }
            $book_dtos = BookDTO::get_array_of_book_dtos($books);
            //var_dump($books);
            return response()->json($book_dtos);
        } catch (Exception $e) {
            //joku sisäinen virhe. emme voi taata oikeaa vastausta. eikä tilanne välttämättä korjaannu.
            abort(500);
        }
    }

    public function search_book($name, $author)
    {
        try {
            $book = Book::like('name', $name)->get();
            if ($book == null) {
                $error = new MessageError();
                $error->set_error_code(100);
                $error->set_error_message("Book " . $name . " does not exist.");
                return response()->json($error);
            }
            return response()->json(new BookDTO($book));
        } catch (Exception $e) {
            //echo $e->getMessage();
            abort(500);
        }
    }

    public function show($id)
    {
        try {
            //echo $id;
            $book = Book::find($id);
            if ($book == null) {
                $error = new MessageError();
                $error->set_error_code(100);
                $error->set_error_message("Book " . $id . " does not exist.");
                return response()->json($error);
            }
            return response()->json(new BookDTO($book));
        } catch (Exception $e) {
            //echo $e->getMessage();
            abort(500);
        }
    }

    public function destroy($id)
    {
        try {
            $book = Book::find($id);
            if ($book == null) {
                $error = new MessageError();
                $error->set_error_message("Kirjaa ei löydy");
                $error->set_error_code(100);
                return response()->json($error);
            }
            // Haetaan kirjalainat jotka kirjaan liittyvät.
            $lendings = DB::table('lendings')->where('book_id', $book->id)
                ->get();
            echo ($lendings);
            if (sizeof($lendings) > 0) {
                foreach ($lendings as $lending) {
                    //Jos on palauttamattomia lainoja, annetaan virhe, eikä tehdä mitään.
                    if ($lending->return_date == null) {
                        $error = new MessageError();
                        $error->set_error_message("Kirja on lainassa, joten sitä ei voida poistaa");
                        //meidän api:ssa on määritelty, että 101 tarkoittaa että kirja on lainassa.
                        $error->set_error_code(101);
                        return response()->json($error);
                    }
                }
                //Jos palauttamattomia lainoja ei ollut, poistetaan palautetut lainat tietokannasta...
                foreach ($lendings as $lending) {
                    $lending_to_be_deleted = Lending::find($lending->id);
                    $lending_to_be_deleted->delete();
                }
            }
            //...jotta voidaan lopuksi poistaa kirja.
            $book->delete();
            $error = new MessageError();
            //Koska tämä on web-palvelu, emme voi palauttaa web-sivua. Vastauksena lähetämme MessageError-tietorakenteen, jossa 
            //virhekoodi 0 on varattu onnistuneelle tapahtumalle. Halutessamme voimme nimetä MessageError-luokan jollain yleisemmällä nimellä 
            //kuten RESTMessage. API-funktioissa paluukoodi 0 yleisesti tarkoittaa onnistunutta toimenpidettä. 
            $error->set_error_code(0);
            return response()->json($error);
        } catch (Exception $exception) {
            abort(500);
        }
    }
}