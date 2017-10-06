<?php

namespace App\Http\Controllers;

use App\models\Role;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
<<<<<<< HEAD
      $tokenName=Input::get('sso');
      $key1=Input::get('server-key');
      $user= User::where('remember_token',$tokenName)->get();
      if(empty($user)){
=======
        set_time_limit(0);
        $arr = [120,400,390,188,118,150,230,350,250,300,130,290,380,260,280,30,200,100,50];
        $new = [];
        foreach ($arr as $key =>$value){
            foreach ($arr as $k1=>$v1){
                $sort = [];
                $sort[] = $value;
                $sort[] = $v1;
                $new[$this->numSort($sort)]['type'] = $this->numSort($sort);
                $new[$this->numSort($sort)]['total'] = $value+$v1;
            }
        }
        foreach ($arr as $key =>$value){
            foreach ($arr as $k1=>$v1){
                foreach ($arr as $k2=>$v2){
                    $sort = [];
                    $sort[] = $value;
                    $sort[] = $v1;
                    $sort[] = $v2;
                    $new[$this->numSort($sort)]['type'] = $this->numSort($sort);
                    $new[$this->numSort($sort)]['total'] = $value+$v1+$v2;
                }
            }
        }

        foreach ($new as $k=>$v){
            foreach ($arr as $k1=>$v1){
                $sort = [];
                $sort[] = $v['type'];
                $sort[] = $v1;
                $new[$this->numSort($sort)]['type'] = $this->numSort($sort);
                $new[$this->numSort($sort)]['total'] = $v['total']+$v1;
            }
        }
        DB::table('suan')->insert($new);
>>>>>>> 03beebd9f2ddb8876522a2880eab338c3709b1d4

      }
      else{
          return json_decode($user);
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

    }
    public function getlist($id){
        $total  = DB::table('suan')->where('total',$id)->take(5)->get();
        return $total;
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
    public function numSort($arr){
        sort($arr);
        return implode('+',$arr);
    }
}
