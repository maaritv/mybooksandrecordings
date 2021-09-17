<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Book;
use App\Models\Lending;
use Illuminate\Support\Facades\DB;
use App\DAO\BookDAO;
use Exception;
use Illuminate\Support\Facades\Redis;

class BookController extends Controller
{

    private $bookDAO = null;

    public function __construct()
    {
        $this->bookDAO = new BookDAO();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all();
        // echo tulostaa muuttujan arvon näyttöön, mikä 
        //voi olla kehitysvaiheessa hyödyllistä, jos et 
        //ole varma, mikä muuttujan arvo on.
        //echo $books;
        //$request = $_REQUEST;
        //echo array_to_string($request);
        //$last_saved_book_from_this_user = $request->cookie('talletettukirja');
        $last_saved_book_from_this_user="";
        $ip = $_SERVER['REMOTE_ADDR'];
        
        return view('books.index', ['books' => $books, 'last_saved_book_from_user'=> $last_saved_book_from_this_user, 'ip' => $ip]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function create()
    {
        return view('books.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'author' => 'required',
            'price' => 'required|numeric'
        ]);
        try {
            $book = new Book([
                'name' => $request->get('name'),
                'author' => $request->get('author'),
                'price' => $request->get('price'),
                'inedit_since' => null,
                'current_editor' => null
            ]);
            $book->save();
            return redirect('/books')->with('success', 'Kirja tallennettu!')->withCookie(cookie('talletettukirja', $book->name, 5));
        } catch (Exception $e) {
            return redirect('/books')->with('error', 'Kirjan tallennus epäonnistui! ');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        return view('books.edit', ['book' => \App\Book::find($id)]);
    }
    */

    /**
     * Show the form for editing the specified resource.
      * Editoivan käyttäjän tarkistaminen saman aikaisen editoinnin 
      * poissulkemiseksi on kommentoitu pois, koska tällä sovelluksella 
      * ei ole HTTP-basic tunnistautuminen käytössä.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        try {
            //$this->bookDAO->reserveBookForModification($id);
            $book = Book::find($id);
            //if (isset($_SERVER['PHP_AUTH_USER']) && $book->current_editor != $_SERVER['PHP_AUTH_USER']) {
            //    return redirect('/books')->with('error', 'Toinen käyttäjä muokkaa kirjaa. Odota kunnes se vapautuu');
            //}
            return view('books.edit', ['book' => $book, 'username' => "unknown"]);
        } catch (Exception $e) {
            return redirect('/books')->with('error', 'Virhe tapahtui kirjan lukituksessa! '.$e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'author' => 'required',
                'price' => 'required|numeric'
            ]);
            if ($request->submit_button == "cancel") {
                //$this->bookDAO->releaseBookFromModification($id);
                return redirect('/books');
            }
            $book = Book::find($id);
            if ($book == null) {
                return redirect('/books')->with('error', 'Kirjan päivitys epäonnistui, koska kirjaa ei löydy!');
            }
            //Tarkista että kirja on lukittu nimenomaan meille, jos ei palataan takaisin päänäkymään virheviestin kanssa.
            //if ($book->current_editor != $_SERVER['PHP_AUTH_USER']) {
            //    return redirect('/books')->with('error', 'Kirjan päivitys epäonnistui, koska toinen käyttäjä muokkaa kirjaa!');
            //}
            //Kirja oli siis lukittu meille, joten tehdään päivitys.
            $book->name =  $request->get('name');
            $book->author = $request->get('author');
            $book->price = $request->get('price');
            $book->save();
            return redirect('/books')->with('success', 'Kirja päivitetty!');
        } catch (Exception $e) {
            return redirect('/books')->with('error', 'Kirjan päivitys epäonnistui ! '.e->getMessage());
        } finally {
            //Lopuksi vielä, joka tapauksessa vapautetaan kirja lukituksesta muiden käyttäjien editoitavaksi.
            //$this->bookDAO->releaseBookFromModification($id);
        }
    }

    /**
     * Remove the specified book from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $book = Book::find($id);
            if ($book == null) {
                return redirect('/books')->with('error', 'Kirjaa ei löydy!');
            }
            // Haetaan kirjalainat jotka kirjaan liittyvät.
            $lendings = DB::table('lendings')->where('book_id', $book->id)
                ->get();
            echo ($lendings);
            if (sizeof($lendings) > 0) {
                foreach ($lendings as $lending) {
                    //Jos on palauttamattomia lainoja, annetaan virhe, eikä tehdä mitään.
                    if ($lending->return_date == null) {
                        return redirect('/books')->with('error', 'Kirja on lainassa, joten sitä ei voi poistaa !');
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
            return redirect('/books')->with('success', 'Kirja poistettu!');
        } catch (Exception $exception) {
            return redirect('/books')->with('error', 'Kirjaa ei voi poistaa! ');
        }
    }

    /**
     * Jos käyttäjä poistuu sivulta muokkaamatta kirjaa, kirjan lukitus pitää poistaa 
     * myös silloin.
     */

    public function releaseBook($id)
    {
     /*   try {
            $this->bookDAO->releaseBookFromModification($id);
        } catch (Exception $e) {
            return redirect('/books')->with('error', 'Kirjan vapauttaminen epäonnistui!');
        }
        */
    }
}