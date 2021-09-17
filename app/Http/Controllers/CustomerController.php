<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Lending;
use App\DAO\CustomerDAO;
use Illuminate\Support\Facades\DB;
use Exception;


class CustomerController extends Controller
{


    private $customerDAO = null;

    public function __construct()
    {
        $this->customerDAO = new CustomerDAO();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', ['customers' => $customers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create');
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
            'first_name' => 'required',
            'last_name' => 'required'
        ]);
        try {
            $customer = new Customer([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name')
            ]);
            $customer->save();
            return redirect('/customers')->with('success', 'Asiakas tallennettu!');
        } catch (Exception $e) {
            return redirect('/customers')->with('error', 'Asiakkaan tallennus epäonnistui!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $this->customerDAO->reserveCustomerForModification($id);
            $customer = Customer::find($id);
            if ($customer->current_editor != $_SERVER['PHP_AUTH_USER']) {
                return redirect('/customers')->with('error', 'Toinen käyttäjä muokkaa asiakas. Odota kunnes se vapautuu');
            }
            return view('customers.edit', ['customer' => $customer, 'username' => $_SERVER['PHP_AUTH_USER']]);
        } catch (Exception $e) {
            return redirect('/customers')->with('error', 'Virhe tapahtui asiakkaan lukituksessa!');
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
                'first_name' => 'required',
                'last_name' => 'required'
            ]);
            if ($request->submit_button == "cancel") {
                $this->customerDAO->releaseCustomerFromModification($id);
                return redirect('/books');
            }
            $customer = Customer::find($id);
            if ($customer == null) {
                return redirect('/customers')->with('error', 'Asiakkaan päivitys epäonnistui, koska asiakas ei löydy!');
            }
            //Tarkista että asiakas on lukittu nimenomaan meille, jos ei palataan takaisin päänäkymään virheviestin kanssa.
            if ($customer->current_editor != $_SERVER['PHP_AUTH_USER']) {
                return redirect('/customers')->with('error', 'Asiakkaan päivitys epäonnistui, koska toinen käyttäjä muokkaa asiakasta!');
            }
            //Asiakas oli siis lukittu meille, joten tehdään päivitys.
            $customer->first_name =  $request->get('first_name');
            $customer->last_name = $request->get('last_name');
            $customer->save();
            return redirect('/customers')->with('success', 'Asiakas päivitetty!');
        } catch (Exception $e) {
            return redirect('/customers')->with('error', 'Asiakkaan päivitys epäonnistui! '.$e->getMessage());
        } finally {
            //Lopuksi vielä, joka tapauksessa vapautetaan asiakas lukituksesta muiden käyttäjien editoitavaksi.
            $this->customerDAO->releaseCustomerFromModification($id);
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
        {
            try {
                $customer = Customer::find($id);
                if ($customer == null) {
                    return redirect('/customers')->with('error', 'Asiakasta ei löydy!');
                }
                // $lendigs = Lending::query();
                $lendings = DB::table('lendings')->where('customer_id', $customer->id)
                    ->get();
                echo ($lendings);
                if (sizeof($lendings) > 0) {
                    foreach ($lendings as $lending) {
                        if ($lending->return_date == null) {
                            return redirect('/customers')->with('error', 'Asiakkaalla on lainoja, joten häntä ei voida poistaa !');
                        }
                    }
                    foreach ($lendings as $lending) {
                        $lending_to_be_deleted=Lending::find($lending->id);
                        $lending_to_be_deleted->delete();
                    }
                }
                $customer->delete();
                return redirect('/customers')->with('success', 'Asiakas poistettu!');
            } catch (Exception $exception) {
                return redirect('/customers')->with('error', 'Asiakasta ei voi poistaa! '.$exception->getMessage());
            }
        }
    }
}