<?php

namespace App\Http\Controllers;

use App\Khadem;
use Illuminate\Http\Request;
use App\Imports\KhademImport;
use Maatwebsite\Excel\Facades\Excel;

class KhademController extends Controller
{

    public function index()
    {
        $Khadems=Khadem::query('search');
        if ($keyword = request('search')){
            $Khadems->where('codemsr' , 'like' , "%$keyword%")->orWhere('namesr', 'like' , "%$keyword%")->orWhere('familysr' ,'like' , "%$keyword%");
        }

        $all= $Khadems->orderBy('dateshsr','asc')->paginate(12);
      
        return view('khadem/all' , compact('all'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * لیست اماکن
     */
    public function amaken()
    {
        $Khadems=Khadem::query('search');
        if ($keyword = request('search')){
            $Khadems->where('codemsr' , 'like' , "%$keyword%")->orWhere('namesr', 'like' , "%$keyword%")->orWhere('familysr' ,'like' , "%$keyword%");
        }

        $amaken= $Khadems->orderBy('dateshsr','asc')->where('moavenat' , 'اماکن')->where('sherkatDarAzsr' , '0')->paginate(12);
      
        return view('khadem/amaken' , compact('amaken'));
    }
    
/**
 * لیست تبلیغات
 */
    public function tablighat()
    {
        $Khadems=Khadem::query('search');
        if ($keyword = request('search')){
            $Khadems->where('codemsr' , 'like' , "%$keyword%")->orWhere('namesr', 'like' , "%$keyword%")->orWhere('familysr' ,'like' , "%$keyword%");
        }

        $tablighat= $Khadems->orderBy('dateshsr' , 'asc')->where('moavenat' , 'تبلیغات')->where('sherkatDarAzsr' , '0')->paginate(12);

        return view('khadem/tablighat' , compact('tablighat'));
    }

/**
 * لیسیت بسیج
 */
    public function basij()
    {
        $Khadems=Khadem::query('search');
        if ($keyword = request('search')){
            $Khadems->where('codemsr' , 'like' , "%$keyword%")->orWhere('namesr', 'like' , "%$keyword%")->orWhere('familysr' ,'like' , "%$keyword%");
        }

        $basij= $Khadems->orderBy('dateshsr' , 'asc')->where('moavenat' , 'امنیت')->where('sherkatDarAzsr' , '0')->paginate(12);

        return view('khadem/basij' , compact('basij'));
    }

/**
 * لیسیت بسیج
 */
public function hamkar()
{
    $Khadems=Khadem::query('search');
    if ($keyword = request('search')){
        $Khadems->where('codemsr' , 'like' , "%$keyword%")->orWhere('namesr', 'like' , "%$keyword%")->orWhere('familysr' ,'like' , "%$keyword%");
    }

    $hamkar= $Khadems->orderBy('dateshsr' , 'asc')->where('moavenat' , 'همکاران')->where('sherkatDarAzsr' , '0')->paginate(12);

    return view('khadem/hamkar' , compact('hamkar'));
}


    /**
     * 
     * خروجی اکسل
     */


    
/**
 * Import data excel to database
 */
    public function saveImport(Request $request)
    {
        
        Excel::import(new KhademImport,$request->file);
        return redirect('/');
    }


/**
 * view form excel to database
 * 
 */
    // public function importexl()
    // {
    //     return view('admin/input');
    // }



    public function edit(khadem $khadems , $id)
    { 
        $khadem= $khadems->find($id);
        return view('edit' , compact('khadem'));        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sample');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Khadem  $khadems
     * @return \Illuminate\Http\Response
     */
    public function show(Khadem $khadems , $id)
    {
        $khadem = $khadems->find($id);
        return view('show' , compact('khadem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Khadem  $khadems
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request , Khadem $khadems)
    {
        $results = $khadems->find();
        Khadem::where('id' , $khadems->id)->update([
            $results->bkhademyarsr = $request->bkhademyarsr,
            $results->madraksr = $request->madraksr,
        ]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Khadem  $khadems
     * @return \Illuminate\Http\Response
     */
    public function destroy(Khadem $khadems)
    {
        return back();
    }
}
