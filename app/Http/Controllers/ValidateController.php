<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class ValidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     // dd(111);

      $tokenName=Input::get('token');
      $key1=Input::get('server-key');
      if(empty($tokenName)){
          return   Redirect::to('/#/login?returnurl='.Input::get('returnurl'));
      }
      //dd($tokenName);
      $user= User::where('remember_token',$tokenName)->get();
      if(empty($user)){
          return   Redirect::to('/#/login?returnurl='.Input::get('returnurl'));
      }
      else{
         return json_decode($user)  ;
      }
    }

    public function sso()
    {
       // dd(111);

        $returnurl=Input::get('returnurl');
        if(empty(Auth::user())){
            return   Redirect::to('/#/login?returnurl='.$returnurl);
        }

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        //
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
    }
}
