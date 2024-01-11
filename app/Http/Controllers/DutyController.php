<?php

namespace App\Http\Controllers;

use App\Duty;
use Illuminate\Http\Request;

class DutyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Duty::query('search');
        if ($keyword = request('search')) {
            $tasks->where('title', 'like', "%$keyword%")->orWhere('descriptions', 'like', "%$keyword%")->orWhere('span', 'like', "%$keyword%");
        }

        $list = $tasks->paginate(12);
        return view('duty/all', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('duty/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Duty::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'date' => $request->date,
            'numbers' => $request->numbers,
            'span' => $request->span,
            'expires' => $request->expires,
            'importantrange' => $request->importantrange,
            'descriptions' => $request->descriptions,
        ]);

        return redirect(route('duty.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Duty  $duty
     * @return \Illuminate\Http\Response
     */
    public function show(Duty $duty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Duty  $duty
     * @return \Illuminate\Http\Response
     */
    public function edit(Duty $duty)
    {

        return view('duty.edit', compact('duty'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Duty  $duty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Duty $duty)
    {
        
        $duties = Duty::where('id', $duty->id)->first();
        $duties->update([
            'title' => $request->title,
            'date' => $request->date,
            'numbers' => $request->numbers,
            'span' => $request->span,
            'expires' => $request->expires,
            'importantrange' => $request->importantrange,
            'descriptions' => $request->descriptions,
        ]);
        

        return redirect('admin/duty');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Duty  $duty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Duty $duty)
    {
        $duty->delete();
        return back();
    }
}