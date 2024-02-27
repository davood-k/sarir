<?php

namespace App\Http\Controllers;

use App\Khademyar;
use App\Khadem;
use App\User;
use App\Defination;
use Illuminate\Http\Request;
use App\Exports\KhademyarExport;
use App\Imports\KhademyarsImport;
use Maatwebsite\Excel\Facades\Excel;

class KhademyarController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/textbox');
    }

    /**
     * Import data excel to database
     */
    public function Importkhademyar(Request $request, Khadem $khadems)
    {
        Excel::import(new KhademyarsImport, $request->file);
        return back();
    }

    public function export(Request $request)
    {

        $text = $request->input('expexcelsr');

        $lines = explode("\r\n", $text);

        $data = [];
        foreach ($lines as $line) {
            $data[] = [$line];
        }

        return Excel::download(new KhademyarExport($data), 'matchCode.xlsx');
    }

    /**
     * 
     * Show the form for information khodam.
     * 
     */
    public function info()
    {
        return view('efficient/information');
    }
    /**
     *show PageString
     */
    public function pagestr(Khademyar $khademyar)
    {
        return view('efficient/PageString');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('moarefi/insert');
    }

    public function edit(Request $request, $id)
    {
        $defi = Defination::find($id);
        $khadems = Khademyar::find($defi->khademyar_id);

        return view('moarefi/edit', compact(['defi', 'khadems']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'codemelli' => 'required',
            'moarefi' => 'required',
            'shletter' => 'required'
        ]);

        if (Khademyar::where('codemelli', $request->codemelli)->exists()) {
            $extkhademyar = Khademyar::where('codemelli', $request->codemelli)->first();
            $extkhademyar->definations()->create([
                'user_id' => auth()->user()->id,
                'khademyar_id ' => $extkhademyar->id,
                'sh_letter' => $request->shletter,
                'date_letter' => $request->dateletter,
                'moarefi' => $request->moarefi,
                'moavenat' => $request->moavenat,
                'molahezat' => $request->molahezat,
                'tozih' => $request->tozih,
            ]);
        } else {
            $Khademyar = Khademyar::create([
                'codemelli' => $request->codemelli,
                'fname' => $request->fname,
                'lname' => $request->lname,
                'gender' => $request->gender,
            ]);
            $Khademyar->definations()->create([
                'user_id' => auth()->user()->id,
                'khademyar_id ' => $Khademyar->id,
                'sh_letter' => $request->shletter,
                'date_letter' => $request->dateletter,
                'moarefi' => $request->moarefi,
                'moavenat' => $request->moavenat,
                'molahezat' => $request->molahezat,
                'tozih' => $request->tozih,
            ]);
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Khademyar  $khademyar
     * @return \Illuminate\Http\Response
     */
    public function show(Khademyar $khademyar)
    {

        $Khadems = $khademyar::query('search');
        if ($keyword = request('search')) {
            $Khadems->where('codemelli', 'like', "%$keyword%")->orWhere('fname', 'like', "%$keyword%")->orWhere('lname', 'like', "%$keyword%")->orWhere('lname', 'like', "%$keyword%");
        }
        $all = $Khadems->whereHas('definations', function ($q) {
            $q->where('deleted', '0');
        })->orderBy('id', 'desc')->paginate(15);
        $users =User::all();

        return view('moarefi/all', compact(['all', 'users']));
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Khademyar  $khademyar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $project = Defination::where('id', $id)->first();
        $project->update([
            'user_id' => auth()->user()->id,
            'tozih' => $request->tozih,
            'sh_letter' => $request->shletter,
            'date_letter' => $request->dateletter,
            'moarefi' => $request->moarefi,
        ]);
        $result = Khademyar::where('id', $project->khademyar_id)->first();
        $result->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'codemelli' => $request->codemelli,
            'gender' => $request->gender,
        ]);

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Khademyar  $khademyar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Khademyar $khademyar, $id)
    {
        Defination::where('id', $id)->update([
            'deleted' => '1',
        ]);
        return back();
    }
}