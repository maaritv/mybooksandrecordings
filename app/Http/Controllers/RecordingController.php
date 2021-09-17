<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Recording;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Redis;

class RecordingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recordings = Recording::all();
        return view('recordings.index', ['recordings' => $recordings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('recordings.create');
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
            'artist' => 'required',
            'published' => 'numeric'
        ]);
        try {
            $recording = new Recording([
                'name' => $request->get('name'),
                'artist' => $request->get('artist'),
                'published' => $request->get('published')
            ]);
            $recording->save();
            return redirect('/recordings')->with('success', 'Äänite on tallennettu!');
        } catch (Exception $e) {
            return redirect('/recordings')->with('error', 'Äänitteen tallennus epäonnistui! ');
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
            $recording = Recording::find($id);
            if ($recording==null){
                return redirect('/recordings')->with('error', 'Äänitettä ei löydy!');
            }
            return view('recordings.edit', ['recording' => $recording]);
        } catch (Exception $e) {
            return redirect('/recordings')->with('error', 'Virhe tapahtui äänitteen päivityksessä! '.$e->getMessage());
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
                'artist' => 'required',
                'published' => 'numeric'
            ]);
            if ($request->submit_button == "cancel") {
                return redirect('/recordings');
            }
            $recording = Recording::find($id);
            if ($recording == null) {
                return redirect('/recordings')->with('error', 'Äänitteen päivitys epäonnistui, koska sitä ei löydy!');
            }
            $recording->name =  $request->get('name');
            $recording->artist = $request->get('artist');
            $recording->published = $request->get('published');
            $recording->save();
            return redirect('/recordings')->with('success', 'Äänite päivitetty!');
        } catch (Exception $e) {
            return redirect('/recordings')->with('error', 'Äänitteen päivitys epäonnistui ! '.e->getMessage());
        } finally {
            //Lopuksi vielä, joka tapauksessa vapautetaan kirja lukituksesta muiden käyttäjien editoitavaksi.
            //$this->bookDAO->releaseBookFromModification($id);
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
        try {
            $recording = Recording::find($id);
            if ($recording == null) {
                return redirect('/recordings')->with('error', 'Äänitettä ei löydy!');
            }
            $recording->delete();
            return redirect('/recordings')->with('success', 'Äänite poistettu!');
        } catch (Exception $exception) {
            return redirect('/recordings')->with('error', 'Äänitettä ei voi poistaa! ');
        }
    }
}