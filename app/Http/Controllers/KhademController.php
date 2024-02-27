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
        $Khadems = Khadem::query('search');
        if ($keyword = request('search')) {
            $Khadems->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }

        $all = $Khadems->orderBy('dateshsr', 'asc')->paginate(12);

        return view('khadem/all', compact('all'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * لیست اماکن
     */
    public function amaken()
    {
        $Khadems = Khadem::query('search');
        if ($keyword = request('search')) {
            $Khadems->where('sherkatDarAzsr', '0')->where('moavenat', 'اماکن')->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }

        $amaken = $Khadems->orderBy('dateshsr', 'asc')->where('moavenat', 'اماکن')->where('sherkatDarAzsr', '0')->paginate(12);

        return view('khadem/amaken', compact('amaken'));
    }

    /**
     * لیست تبلیغات
     */
    public function tablighat()
    {
        $Khadems = Khadem::query('search');
        if ($keyword = request('search')) {
            $Khadems->where('sherkatDarAzsr', '0')->where('moavenat', 'تبلیغات')->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }

        $tablighat = $Khadems->orderBy('dateshsr', 'asc')->where('moavenat', 'تبلیغات')->where('sherkatDarAzsr', '0')->paginate(12);

        return view('khadem/tablighat', compact('tablighat'));
    }

    /**
     * لیسیت بسیج
     */
    public function basij()
    {
        $Khadems = Khadem::query('search');
        if ($keyword = request('search')) {
            $Khadems->where('sherkatDarAzsr', '0')->where('moavenat', 'امنیت')->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }

        $basij = $Khadems->orderBy('dateshsr', 'asc')->where('moavenat', 'امنیت')->where('sherkatDarAzsr', '0')->paginate(12);

        return view('khadem/basij', compact('basij'));
    }

    /**
     * لیسیت بسیج
     */
    public function hamkar()
    {
        $Khadems = Khadem::query('search');
        if ($keyword = request('search')) {
            $Khadems->where('sherkatDarAzsr', '0')->where('moavenat', 'همکاران')->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }

        $hamkar = $Khadems->orderBy('dateshsr', 'asc')->where('moavenat', 'همکاران')->where('sherkatDarAzsr', '0')->paginate(12);

        return view('khadem/hamkar', compact('hamkar'));
    }

    public function elmi()
    {
        $Khadems = Khadem::query('search');
        if ($keyword = request('search')) {
            $Khadems->where('moavenat', 'امیریه تولیت')->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }

        $elmi = $Khadems->orderBy('dateshsr', 'asc')->where('moavenat', 'امیریه تولیت')->orWhere('moavenat', 'سازمان فرهنگی')->orWhere('moavenat', 'نیابت')->paginate(12);

        return view('khadem/others', compact('elmi'));
    }


    public function importexl()
    {
        return view('admin/input');
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
        Excel::import(new KhademImport, $request->file);
        return redirect('/all');
    }


    /**
     * view form excel to database
     * 
     */
    // public function importexl()
    // {
    //     return view('admin/input');
    // }

    
    public function sendtoissuance(Request $request, $id)
    {
        dd(Khadem::find($id));
        
        // $khadem = $khadems->find($id);
        // return view('khadem/edit', compact('khadem'));
    }
    public function edit(khadem $khadems, $id)
    {
        $khadem = $khadems->find($id);
        return view('khadem/edit', compact('khadem'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'codemelli' => 'required',
            'moavenat' => 'required',
            'bkhademyarsr' => 'required',
            'fname' => 'required',
            'lname' => 'required'
        ]);
        if ($request->level == 0) {
            Khadem::firstOrCreate([
                'namesr' => $request->fname,
                'familysr' => $request->lname,
                'codemsr' => $request->codemelli,
                'tdatesr' => $request->tdatesr,
                'sanvatsr' => $request->sanvatsr,
                'moavenat' => $request->moavenat,
                'enzebatsr' => $request->enzebatsr,
                'keifisr' => $request->keifisr,
                'isarsr' => $request->isarsr,
                'tahsilsr' => $request->tahsilsr,
                'nokhbehsr' => $request->nokhbehsr,
                'tajmi' => $request->tajmi,
                'bkhademyarsr' => $request->bkhademyarsr,
                'mobilesr' => $request->mobilesr,
                'dateshsr' => $request->dateshsr,
                'madraksr' => $request->madraksr,
                'descriptionsr' => $request->descriptionsr,
            ]);

        } elseif ($request->level == 1) {
            $khadems = Khadem::firstOrCreate([
                'namesr' => $request->fname,
                'familysr' => $request->lname,
                'codemsr' => $request->codemelli,
                'tdatesr' => $request->tdatesr,
                'sanvatsr' => $request->sanvatsr,
                'moavenat' => $request->moavenat,
                'enzebatsr' => $request->enzebatsr,
                'keifisr' => $request->keifisr,
                'isarsr' => $request->isarsr,
                'tahsilsr' => $request->tahsilsr,
                'nokhbehsr' => $request->nokhbehsr,
                'tajmi' => $request->tajmi,
                'bkhademyarsr' => $request->bkhademyarsr,
                'mobilesr' => $request->mobilesr,
                'dateshsr' => $request->dateshsr,
                'madraksr' => $request->madraksr,
                'sherkatDarAzsr' => $request->level,
                'descriptionsr' => $request->descriptionsr,
            ]);
            $khadems->azmoons()->Create(['khadem_id' => $khadems->id]);

        } elseif ($request->level == 2) {
            $khadems = Khadem::firstOrCreate([
                'namesr' => $request->fname,
                'familysr' => $request->lname,
                'codemsr' => $request->codemelli,
                'tdatesr' => $request->tdatesr,
                'sanvatsr' => $request->sanvatsr,
                'moavenat' => $request->moavenat,
                'enzebatsr' => $request->enzebatsr,
                'keifisr' => $request->keifisr,
                'isarsr' => $request->isarsr,
                'tahsilsr' => $request->tahsilsr,
                'nokhbehsr' => $request->nokhbehsr,
                'tajmi' => $request->tajmi,
                'bkhademyarsr' => $request->bkhademyarsr,
                'mobilesr' => $request->mobilesr,
                'dateshsr' => $request->dateshsr,
                'madraksr' => $request->madraksr,

                'marhalesr' => 0,
                'descriptionsr' => $request->descriptionsr,
            ]);
            $khadems->azmoons()->Create([
                'khadem_id' => $khadems->id,
            ]);
            $khadems->comisions()->Create([
                'khadem_id' => $khadems->id
            ]);
            $khadems->where('id', $khadems->id)->Update([
                'sherkatDarAzsr' => "2",
                'ShDarComision' => "1"
            ]);
        }
        return back();

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
    public function show(Khadem $khadems, $id)
    {
        $khadem = $khadems->find($id);
        return view('khadem/show', compact('khadem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Khadem  $khadems
     * @return \Illuminate\Http\Response
     */
    public function update($id, $field, $value)
    {
        Khadem::where('id', $id)->update([
            $field => $value,
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