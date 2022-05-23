
<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use Illuminate\Http\Request;

class UserController extends BackEndController
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       //get all data of Model
       $rows = $this->model->when($request->search,function($query) use ($request){
           $query->where('name','like','%' .$request->search . '%')
                 ->orWhere('email', 'like','%' . $request->search . '%')
                 ->orWhere('phone', 'like','%' . $request->search . '%')
                 ->orWhere('address', 'like','%' . $request->search . '%');

       });
       $rows = $this->filter($rows,$request);
        $module_name_plural = $this->getClassNameFromModel();
        $module_name_singular = $this->getSingularModelName();
        // return $module_name_plural;
        return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
    } //end of ind
    public function store(Request $request)
    {
        
        $request->validate([
            'name'          => 'required|min:5|string',
            'email'         => 'required|email',
            'phone'         => 'required|digits:11|regex:/(01)[0-2]{1}[0-9]{8}/|unique:users,phone',
            'password'      => 'required|min:5|string|confirmed',
            'password_confirmation'   => 'required|min:5|string|same:password',
            'address'       => 'nullable|min:5|string',
            'role_id[]'=>'required',
            'image'=>'required|mimes:jpg,jpeg,png,svg'
        ]);

        $request_data = $request->except(['_token', 'password', 'password_confirmation', 'role_id','image']);
        $request_data['password'] = bcrypt($request->password);
        if($request->has('image')){
            $path = rand(0,1000000) . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path('public/uploads/user_images') , $path);
            $request_data['image']   = $path;
        }
        $newuser = $this->model->create($request_data);

       
        if($request->role_id){
            $newuser->attachRoles($request->role_id);
        }

        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }

   
    public function update(Request $request, $id)
    {
        $user = $this->model->find($id);

        $request->validate([
            'name'          => 'required|min:5|string',
            'email'         => 'required|email',
            'phone'         => 'required|digits:11|regex:/(01)[0-2]{1}[0-9]{8}/|unique:users,phone,'.$user->id.'',
            'password_confirmation'   => 'same:password',
            'address'       => 'nullable|min:5|string',
            'role_id[]'=>'required',
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
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }
    public function destroy($id, Request $request)
    {
        $category = $this->model->findOrFail($id);
        if(file_exists(base_path('public/uploads/user_images/') . $category->image)){
            unlink(base_path('public/uploads/user_images/') . $category->image);
        }
        $category->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
}
