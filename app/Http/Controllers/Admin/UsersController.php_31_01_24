<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\User;
use App\Models\BranchModel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $branches = BranchModel::where('status',1)->get();
        if($request->all()){
            //print_r($request->all()); die;
            //$users1 = User::select('users.*');
            
            $users1 = User::where('user_type','!=','supplier')->where('user_type','!=','buyer');
            
            if($request->name != ''){
                $users1->where('users.name','like','%'.$request->name.'%');
            }

            if($request->email != ''){
               // print_r($request->email); die;
                $users1->where('users.email',$request->email);
            }

            if($request->veepeeuser_id != ''){

                $users1->where('users.veepeeuser_id',$request->veepeeuser_id);
            }

            

            if($request->branch_id != ''){
               
                $users1->where('users.branch_id',$request->branch_id);
            }

            if($request->status != ''){
                $users1->where('users.status',$request->status);
            }
            
            $users1->orderby('users.veepeeuser_id','desc');
            $users = $users1->paginate(20);
            
            //echo '<pre>';print_r($users); die;;
            return view('admin.users.index', compact('users','branches'));
            
        } else {

            $users = User::where('user_type','!=','supplier')->where('user_type','!=','buyer')->paginate(20);
            
            return view('admin.users.index', compact('users','branches'));
        }
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $branches = BranchModel::all()->pluck('name', 'id');
     // echo '<pre>';  print_r($branches); die;
        return view('admin.users.create', compact('roles','branches'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $user->load('roles');
        $branches = BranchModel::all()->pluck('name', 'id');
        return view('admin.users.edit', compact('roles', 'user', 'branches'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }


    public function blockuser($user_id){
        $block = User::where('id',$user_id)->first();
        if($block->block == 1){
            
            $block->update(['block'=>0]);
             return redirect()->back()->withSuccess('This user has been unblocked successfully.');
        } else {
            
           $block->update(['block'=>1]); 
            return redirect()->back()->withSuccess('This user has been blocked successfully.');
        }
        
       


    }
}
