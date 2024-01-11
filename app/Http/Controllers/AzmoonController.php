<?php

namespace App\Http\Controllers;

use App\Azmoon;
use App\Khadem;
use Illuminate\Http\Request;

class AzmoonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Khadem::query('search');
        if ($keyword = request('search')) {
            $list->where('sherkatDarAzsr', '1')->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }
        $khadem = $list->where('sherkatDarAzsr', '1')->where('ShDarComision', '0')->where('bayeganisr', '0')->get();

        return view('/azmoon/azmoon', compact('khadem'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function taeedsh()
    {
        $list = Khadem::query('search');
        if ($keyword = request('search')) {
            $list->where('ShDarComision', '0')->where('sherkatDarAzsr', '2')->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }
        $khadem = $list->where('sherkatDarAzsr', '2')->where('ShDarComision', '0')->where('bayeganisr', '0')->get();

        return view('/azmoon/TaeedeAzmoon', compact('khadem'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $khadem = Khadem::find($id);
        $khadem->azmoons()->create(['khadem_id' => $id]);
        $khadem->update([$khadem->sherkatDarAzsr = 1]);

        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * ثبت نمره آزمون
     */
    public function store(Khadem $Khadem, Request $request, $id)
    {
        $date = Khadem::where('id', $id)->value('marhalesr');
        $khadem = Khadem::find($id);
        if ($request->nomrehAz >= 70) {
            $khadem->azmoons()->where('khadem_id', $id)->update([
                'nomrehAzmoonsr' => $request->nomrehAz,
                'job' => $request->job
            ]);
            Khadem::where('id', $id)->update([
                'sherkatDarAzsr' => '2',
            ]);
        } else {
            $khadem->azmoons()->where('khadem_id', $id)->update([
                'nomrehAzmoonsr' => $request->nomrehAz,
                'job' => $request->job
            ]);
        }

        Khadem::where('id', $id)->update([
            'marhalesr' => $date + 1
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * 
     * @param  \App\Azmoon  $azmoon
     * @return \Illuminate\Http\Response
     */
    public function bayegan()
    {
        $list = Khadem::query('search');
        if ($keyword = request('search')) {
            $list->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }
        $khadem = $list->where('sherkatDarAzsr', '1')->where('ShDarComision', '0')->get();

        return view('/azmoon/allazmoon', compact('khadem'));
    }


    public function Print()
    {
        $list = Khadem::query('search');
        if ($keyword = request('search')) {
            $list->where('ShDarComision', '0')->where('sherkatDarAzsr', '2')->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }
        $khadem = $list->where('sherkatDarAzsr', '2')->where('ShDarComision', '0')->where('bayeganisr', '0')->get();

        return view('/azmoon/PrintAzmoon', compact('khadem'));
    }

    public function infolderpr()
    {
        $list = Khadem::query('search');
        if ($keyword = request('search')) {
            $list->where('ShDarComision', '0')->where('sherkatDarAzsr', '2')->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }
        $khadem = $list->where('sherkatDarAzsr', '2')->where('ShDarComision', '0')->where('bayeganisr', '0')->get();

        return view('/azmoon/infolderpr', compact('khadem'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     *  * ارجاع به بایگان آزمون
     * 
     * @param  \App\Azmoon  $azmoon
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $khadem = Khadem::find($id);
        $khadem->where('id', $id)->update([
            'bayeganisr' => $request->bayegan,
        ]);
        $khadem->azmoons()->where('khadem_id', $id)->update([
            'dalil' => $request->dalil
        ]);

        return back();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Azmoon  $azmoon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Azmoon $azmoon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Azmoon  $azmoon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Azmoon $azmoon, $id)
    {
        Khadem::where('id', $id)->update([
            'marhalesr' => '0',
            'sherkatDarAzsr' => '0',
        ]);
        $result = $azmoon->where('khadem_id', $id)->first();
        $result->delete();
        return back();
    }
}