<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class ControlPersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $validator = Validator::make($request->all(),[

            'nombre' => 'required',
            'tipo'=> 'required',
        ]);
        $c = ControlPersonal::create([
            
            "nombre" => $request->nombre,
            "tipo"=>$request->tipo,
        ]);
        if($c){
            return response()->json([

            ]);
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
        $c=ControlPersonal::find($id);

        if($c!=null){
            return response()->json([
                "code"=>200,
                "response"=>$c
            ]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //validamos igual

        ControlPersonal::where('id',$id)
        ->update([
            "nombre"=>$request->nombre,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        ControlPersonal::where('id',$id)
        ->delete();
    }
}
