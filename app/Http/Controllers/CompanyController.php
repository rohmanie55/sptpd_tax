<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Companie;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company',[
            'companies' => Companie::get(),
        ]);
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
            'nama' => 'required|max:191',
        ]);

        Companie::create($request->except('_token'));

        return redirect()->route('company.index')->with('message', 'Success creating company!');
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
        $request->validate([
            'nama' => 'required|max:191',
        ]);

        Companie::find($id)->update($request->except('_token'));

        return redirect()->route('company.index')->with('message', 'Success creating company!');
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
            Companie::findOrFail($id)->delete();

            return redirect()->route('company.index')->with('success', 'Successfull deleting company!');
       } catch (\Throwable $th) {
            return redirect()->route('company.index')->with('fail', 'Failed deleting company!');
       }
    }

}
