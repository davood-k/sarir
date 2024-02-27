<?php

namespace App\Http\Controllers;

use App\Azmoon;
use App\Khadem;
use App\Comision;
use App\placeKh;
use Illuminate\Http\Request;

class ComisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $khadem = Khadem::query('search');
        if ($keyword = request('search')) {
            $khadem->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }
        $list = $khadem->where('ShDarComision', '1')->where('bayeganisr', '0')->get();
        return view('/comision/comision', compact('list'));
    }

    public function issuanceOrders()
    {
        $khadem = Khadem::query('search');
        if ($keyword = request('search')) {
            $khadem->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }
        $list = $khadem->where('ShDarComision', '1')->where('bayeganisr', '0')->get();
        return view('/comision/issuanceOrders', compact('list'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $khadems = Khadem::find($id);
        $khadems->where('id', $id)->update([
            'ShDarComision' => $request->ShDarComision
        ]);

        $khadems->comisions()->firstOrCreate(['khadem_id' => $id]);
        $khadems->azmoons()->update(['dalil' => $request->dalil]);
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Comision $comision)
    {
        $khadem = Khadem::find($request->id);
        $result = $khadem->comisions()->value('TnMahalKhsr');
        $result2 = $khadem->comisions()->value('id');
        // dd(Comision::orderBy('documentId', 'desc')->value('documentId'));
        if ($result === $request->TnMahalKhsr) {
            $khadem->comisions()->update([
                'TnMahalKhsr' => $request->TnMahalKhsr,
                'ShHerasatsr' => $request->ShHerasatsr,
                'dateInterview' => $request->dateInterview,
                'TdHerasatsr' => $request->TdHerasatsr == null ? 0 : 1,
                'ShToliatsr' => $request->ShToliatsr,
                'TdToliatsr' => $request->TdToliatsr == null ? 0 : 1,
                'SiMKhodamsr' => $request->SiMKhodamsr == null ? 0 : 1,
                'SiMSarmayehsr' => $request->SiMSarmayehsr == null ? 0 : 1,
                'SiMAalesr' => $request->SiMAalesr == null ? 0 : 1,
                'SiToliatsr' => $request->SiToliatsr == null ? 0 : 1,
                'ShHokmsr' => $request->ShHokmsr,
            ]);
        } elseif ('کفشدار' === $request->TnMahalKhsr) {
            $lastDocumentIdShoes = Comision::where('TnMahalKhsr', 'کفشدار')->orderBy('documentId', 'desc')->value('documentId');
            $lastDocumentIdShoeses = $lastDocumentIdShoes + '1';
            $khadem->comisions()->update([
                'TnMahalKhsr' => $request->TnMahalKhsr,
                'documentId' => $lastDocumentIdShoeses,
                'ShHerasatsr' => $request->ShHerasatsr,
                'dateInterview' => $request->dateInterview,
                'TdHerasatsr' => $request->TdHerasatsr == null ? 0 : 1,
                'ShToliatsr' => $request->ShToliatsr,
                'TdToliatsr' => $request->TdToliatsr == null ? 0 : 1,
                'SiMKhodamsr' => $request->SiMKhodamsr == null ? 0 : 1,
                'SiMSarmayehsr' => $request->SiMSarmayehsr == null ? 0 : 1,
                'SiMAalesr' => $request->SiMAalesr == null ? 0 : 1,
                'SiToliatsr' => $request->SiToliatsr == null ? 0 : 1,
                'ShHokmsr' => $request->ShHokmsr,
            ]);
        } elseif ('خادم علمی' === $request->TnMahalKhsr) {
            $lastDocumentIdShoes = Comision::where('TnMahalKhsr', 'خادم علمی')->orderBy('documentId', 'desc')->value('documentId');
            $lastDocumentIdShoeses = $lastDocumentIdShoes + '1';
            $khadem->comisions()->update([
                'TnMahalKhsr' => $request->TnMahalKhsr,
                'documentId' => $lastDocumentIdShoeses,
                'ShHerasatsr' => $request->ShHerasatsr,
                'dateInterview' => $request->dateInterview,
                'TdHerasatsr' => $request->TdHerasatsr == null ? 0 : 1,
                'ShToliatsr' => $request->ShToliatsr,
                'TdToliatsr' => $request->TdToliatsr == null ? 0 : 1,
                'SiMKhodamsr' => $request->SiMKhodamsr == null ? 0 : 1,
                'SiMSarmayehsr' => $request->SiMSarmayehsr == null ? 0 : 1,
                'SiMAalesr' => $request->SiMAalesr == null ? 0 : 1,
                'SiToliatsr' => $request->SiToliatsr == null ? 0 : 1,
                'ShHokmsr' => $request->ShHokmsr,
            ]);
        } else {
            $lastDocumentId = Comision::where('documentId', '!=', $request->TnMahalKhsr)->where('TnMahalKhsr', '!=', 'کفشدار')->where('TnMahalKhsr', '!=', 'خادم علمی')->orderBy('documentId', 'desc')->value('documentId');
            $lastDocumentIds = $lastDocumentId + '1';
            $khadem->comisions()->update([
                'TnMahalKhsr' => $request->TnMahalKhsr,
                'documentId' => $lastDocumentIds,
                'ShHerasatsr' => $request->ShHerasatsr,
                'dateInterview' => $request->dateInterview,
                'TdHerasatsr' => $request->TdHerasatsr == null ? 0 : 1,
                'ShToliatsr' => $request->ShToliatsr,
                'TdToliatsr' => $request->TdToliatsr == null ? 0 : 1,
                'SiMKhodamsr' => $request->SiMKhodamsr == null ? 0 : 1,
                'SiMSarmayehsr' => $request->SiMSarmayehsr == null ? 0 : 1,
                'SiMAalesr' => $request->SiMAalesr == null ? 0 : 1,
                'SiToliatsr' => $request->SiToliatsr == null ? 0 : 1,
                'ShHokmsr' => $request->ShHokmsr,
            ]);
        }

        return redirect()->back();
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function show(Comision $comision)
    {
        $khadem = Khadem::query('search');
        if ($keyword = request('search')) {
            $khadem->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }

        $list = $khadem->where('ShDarComision', '1')->get();
        return view('/comision/allcomision', compact('list'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function bayegani(Comision $comision)
    {
        $khadem = Khadem::query('search');
        if ($keyword = request('search')) {
            $khadem->where('bayeganisr', '2')->where('codemsr', 'like', "%$keyword%")->orWhere('namesr', 'like', "%$keyword%")->orWhere('familysr', 'like', "%$keyword%");
        }
        // bayeganisr
        $list = $khadem->where('bayeganisr', '2')->get();
        return view('/comision/bayegan', compact('list'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $khadem = Khadem::find($id);
        $khadem->where('id', $id)->update([
            'bayeganisr' => $request->bayegan,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comision $comision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comision $comision, $id)
    {

        Khadem::where('id', $id)->update([
            'bayeganisr' => 0,
            'sherkatDarAzsr' => 1,
            'ShDarComision' => 0,
        ]);
        $khademAz = Khadem::where('id', $id)->first();
        $khademAz->azmoons()->update([
                'nomrehAzmoonsr' => 0,
            ]);

        return back();
    }
}