<?php

namespace App\Http\Controllers;

use App\Store;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use DB;
use Hash;

use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','show']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin") {
            $users=User::latest()->get();
        }else{
            $users=User::where('id',Auth::user()->id)->get();
        }

        return view('backend.user.index',compact('users'));
    }

    public function create()
    {
        //$roles = Role::pluck('name','name')->all();
        $stores = Store::all();
        $roles = Role::where('name','!=','Admin')->pluck('name','name')->all();
        return view('backend.user.create',compact('roles','stores'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'store_id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);


        $input = $request->all();
        $input['password'] = Hash::make($input['password']);


        $user = User::create($input);
        $user->store_id = $request->store_id;
        $user->update();
        $user->assignRole($request->input('roles'));


        /*return redirect()->route('users.index')
            ->with('success','User created successfully');*/
        Toastr::success('User Created Successfully');
        return redirect()->route('users.index');
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }

    public function edit($id)
    {
        $stores = Store::all();
        $user = User::find($id);
        //$roles = Role::pluck('name','name')->all();
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin") {
            //$roles = Role::pluck('name','name')->all();
            $roles = Role::all();
        }else{
            //$roles = Role::where('name', '!=', 'Admin')->pluck('name', 'name')->all();
            $roles = Role::where('name', '!=', 'Admin')->all();
        }
        $userRole = $user->roles->pluck('name','name')->first();


        return view('backend.user.edit',compact('user','roles','userRole','stores'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            //'store_id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);


        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));
        }


        $user = User::find($id);
        $user->store_id = $request->store_id;
        $user->status = $request->status;
        $user->update();

        // second
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();


        $user->assignRole($request->input('roles'));


        Toastr::success('User Updated Successfully');
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        //User::find($id)->delete();
        $user = User::find($id);
        $user->status = 0;
        $user->update();
        Toastr::warning('User not deleted possible, Please contact with administrator!');
        return redirect()->route('users.index');
    }

    public function activeDeactive (Request $request){
        $softDelete = User::findOrFail($request->soft_delete);
        if($softDelete->status == 1 ){
            $softDelete->status = 0;
        }else{
            $softDelete->status = 1;
        }
        $softDelete->save();
        //return back() ->withResponse('Successfully');
        Toastr::success('Status Changed Successfully');
        return redirect()->route('users.index');
    }

    public function importExportView()
    {
        return view('import');
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function import()
    {
        Excel::import(new UsersImport,request()->file('file'));

        return back();
    }

    public function changedPassword($user_id)
    {
        return view('backend.user.edit-password', compact('user_id'));
    }
    public function changedPasswordUpdated(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);
        //$hashedPassword = Auth::user()->password;
        $hashedPassword = User::where('id',$request->user_id)->pluck('password')->first();
        //dd($request->user_id);

        if (\Illuminate\Support\Facades\Hash::check($request->old_password, $hashedPassword)) {
            if (!Hash::check($request->password, $hashedPassword)) {
                //$user = \App\User::find(Auth::id());
                $user = User::find($request->user_id);
                $user->password = Hash::make($request->password);
                $user->save();
                Toastr::success('Password Updated Successfully','Success');
                Auth::logout();
                return redirect()->route('login');
            } else {
                Toastr::error('New password cannot be the same as old password.', 'Error');
                return redirect()->back();
            }
        } else {
            Toastr::error('Current password not match.', 'Error');
            return redirect()->back();
        }
    }
}
