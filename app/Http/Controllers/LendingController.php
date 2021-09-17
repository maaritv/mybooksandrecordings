<?php

namespace App\Http\Controllers;

use App\Models\Lending;
use App\Models\Customer;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;


class LendingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lendings = Lending::with('customer', 'book')->get();
        return view('lendings.index', ['lendings' => $lendings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::all();
        $books = Book::all();
        //etsitään lainat, joita ei ole palautettu
        $current_lendings = Lending::where('return_date', null)->get();
        if (sizeof($current_lendings) == 0) {
            return view('lendings.create', ['customers' => $customers, 'books' => $books]);
        } else {
            $booksNotLended = [];
            //Käydään läpi kirjat, ja etsitään, löytyykö niihin lainauksia
            foreach ($books as $book) {
                if ($this->is_book_lended($book, $current_lendings) == false) {
                    array_push($booksNotLended, $book);
                }
            }
        }
        return view('lendings.create', ['customers' => $customers, 'books' => $booksNotLended]);
    }

    private function is_book_lended($book, $lendings)
    {
        foreach ($lendings as $lending) {
            if ($lending->book_id == $book->id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Kun uusi lainaus tehdään, valitsemalla kirja ja asiakas 
     * alasvetovalikosta, tätä funktiota kutsutaan. Onnistuneen 
     * tallennuksen jälkeen suoritus ohjataan takaisin näkymään, 
     * joka listaa kaikki lainat (eli näkymä on eri kuin josta lainaus tehtiin.) 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'book_id' => 'required'
        ]);
        try {
            $customer = Customer::find($request->get('customer_id'));
            if ($customer == null) {
                return redirect('/lendings')->with('error', 'Asiakasta ei löydy');
            }
            $book = Book::find($request->get('book_id'));
            if ($book == null) {
                return redirect('/lendings')->with('error', 'Kirjaa ei löydy');
            }
            $lending = new Lending([
                'customer_id' => $request->get('customer_id'),
                'book_id' => $request->get('book_id')
            ]);
            $lending->lending_date = date('Y-m-d');
            $lending->save();
            return redirect('/lendings')->with('success', 'Lainaus tallennettu!');
        } catch (Exception $e) {
            return redirect('/lendings')->with('error', 'Lainaus epäonnistui');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Lainauksen päivitys on sama kuin palautus. Eli lainaus muutetaan 
     * tilaan, jossa palautuspvm saa arvon merkiksi palautuksesta.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function returnBook($id)
    {
        try {
            $lending = Lending::find($id);
            if ($lending == null) {
                return redirect('/lendings')->with('error', 'Lainausta ei löydy');
            }
            $lending->return_date =  date('Y-m-d');
            $lending->save();
            return redirect('/lendings')->with('success', 'Lainaus on palautettu!');
        } catch (Exception $e) {
            return redirect('/lendings')->with('error', 'Virhe palautuksessa!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}