<?php

namespace App\Http\Controllers;

use App\InformationOffice;
use Illuminate\Http\Request;

class InformationOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = InformationOffice::query('search');
        if ($keyword = request('search')) {
            $tasks->where('offices', 'like', "%$keyword%")->orWhere('numbers', 'like', "%$keyword%")->orWhere('timeServices', 'like', "%$keyword%")->orWhere('personsRelation', 'like', "%$keyword%")->orWhere('address', 'like', "%$keyword%")->orWhere('post', 'like', "%$keyword%");
        }

        $list = $tasks->get();
        return view('offices/all', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('offices/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        InformationOffice::create([
            'offices' => $request->offices,
            'personsRelation' => $request->personsRelation,
            'numbers' => $request->numbers,
            'mobiles' => $request->mobiles,
            'address' => $request->address,
            'post' => $request->post,
            'timeServices' => $request->timeServices,
            'description' => $request->description,
        ]);

        return redirect(route('informationOffice.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InformationOffice  $informationOffice
     * @return \Illuminate\Http\Response
     */
    public function show(InformationOffice $informationOffice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\informationOffice  $informationOffice
     * @return \Illuminate\Http\Response
     */
    public function edit(InformationOffice $informationOffice)
    {
        return view('offices.edit', compact('informationOffice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InformationOffice  $informationOffice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InformationOffice $informationOffice)
    {
        InformationOffice::where('id' ,$informationOffice->id )->update([
            'offices' => $request->offices,
            'personsRelation' => $request->personsRelation,
            'numbers' => $request->numbers,
            'mobiles' => $request->mobiles,
            'address' => $request->address,
            'post' => $request->post,
            'timeServices' => $request->timeServices,
            'description' => $request->description,
        ]);

        return redirect(route('informationOffice.index'));
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\informationOffice  $informationOffice
     * @return \Illuminate\Http\Response
     */
    public function destroy(InformationOffice $informationOffice)
    {
        //
    }
}
