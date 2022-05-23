<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module_name_plural = 'profiles';
        $row = Auth::user();
        return view('dashboard.profiles.edit', compact('module_name_plural','row'));

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
        $user = Auth::user();
        $request->validate([
            'name'          => 'required|min:5|string',
            'email'         => 'required|email',
            'phone'         => 'required|digits:11|regex:/(01)[0-2]{1}[0-9]{8}/|unique:users,phone,'.$user->id.'',
            'password_confirmation'   => 'same:password',
            'address'       => 'nullable|min:5|string',
            'role_id'=>'required',
            'image'=>'required|mimes:jpg,jpeg,png,svg'
        ]);

        $request_data = $request->except(['_token', 'password', 'password_confirmation', 'role_id']);
        if($request->has('password') && $request->password !=null){
        
            $request_data['password'] = bcrypt($request->password);
        }
        if($user->image == null){
            $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path('public/uploads/user_images') , $path);
            $request_data['image']    = $path;
        } else {
            $oldImage = $user->image;

            //updat image
            $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path('public/uploads/user_images') , $path);
            $request_data['image']    = $path;

            //delet old image
            if(file_exists(base_path('public/uploads/user_images/') . $oldImage)){
                unlink(base_path('public/uploads/user_images/') . $oldImage);
            }   
        }
    

       

        if($request->role_id){
            $user->syncRoles($request->role_id);
        }

        $user->update($request_data);
        // $user->syncRoles($request->role_id);

        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.home');
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
