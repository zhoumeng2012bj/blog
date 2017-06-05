<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $name = Input::get('name');
        $pageSize = Input::get('pageSize');
        return $roles =  Role::when($name, function ($query) use ($name) {
            return $query->where('name','like',"$name%");
        })->paginate($pageSize);
    }

    /**
     * 把角色的列表返回
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $lists = Role::all();
       $data = [];
       foreach ($lists as $list){
           $data[$list->id]=$list->name;
       }
       return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->params;
        $role = new Role();
        $role->name = $input['name'];
        $role->description = $input['description'];
        if($role->save()){
            return [
                'msg'=>'保存成功！',
                'code'=>200
            ];
        }
    }

    /**
     * Display the specified resource.
     *获取当前用户的所有角色
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lists = User::find($id)->roles;
        $data = [];
        foreach ($lists as $list){
            $data[$list->id]=$list->name;
        }
        return $data;
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
        $role = Role::find($id);
        $role->name = $request->params['name'];
        $role->description = $request->params['description'];
        if($role->save()){
            return [
                'msg'=>'保存成功！',
                'code'=>200
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //删除该角色的所有原权限
        DB::table('permission_role')->where('role_id', '=', $id)->delete();
        return Role::destroy($id);
    }
}
