<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\DoctorDataTable;
use App\Http\Controllers\Api\site\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;
use Yajra\DataTables\DataTables;

class DoctorController extends BackEndController
{
    public function __construct(Doctor $model)
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
        $module_name_plural = $this->getClassNameFromModel();
        $module_name_singular = $this->getSingularModelName();
        return view('dashboard.' . $module_name_plural . '.index', compact('module_name_singular', 'module_name_plural'));
    } 
    public function data()
    {
        $doctors = Doctor::select();
        $module_name_plural = $this->getClassNameFromModel();
        return DataTables::of($doctors)
            ->addColumn('record_select', 'dashboard.data_table.record_select')
            ->editColumn('created_at', function (Doctor $user) {
                return $user->created_at->format('d/m/Y');
            })
            ->addColumn('forceDelete',function($data){
                $id = $data->id;
                $token = $data->token;
                $module_name_plural = $this->getClassNameFromModel();

                return view('dashboard.data_table.force_delete',compact('id','module_name_plural','token'));
            })
            ->addColumn('actions', function($data){
            $module_name_plural = $this->getClassNameFromModel();
            $id = $data->id;
              return view('dashboard.data_table.actions',compact('module_name_plural','data','id'));
            })
           
            ->rawColumns(['record_select', 'actions','forceDelete','id'])
            ->toJson();

    }
    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $doctor = $this->model->find($recordId);
            $doctor->delete();

        }//end of for each

        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');

    }// end of bulkDelete
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'         => 'nullable|digits:11|regex:/(01)[0-2]{1}[0-9]{8}/|unique:doctors,phone',
            'email' =>  'nullable|string|email|max:255|unique:doctors' ,
            'password'          => 'required|string|min:6',
            'confirmPassword'   => 'required|string|same:password',
            // 'dob' => 'required|date_format:d/m/Y',
            'dob' => 'required|string',
            'gender' => ['required',Rule::in('Male','Female')],
            'token_firebase'    => 'nullable|string',
        ]);
        $condition = Patient::where('email',$request->email)->orWhere('phone',$request->phone)->first();
        if($condition){
            session()->flash('error', __('site.email_exist'));

        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.create');

        }
        $request_data = $request->except(['_token', 'password', 'confirmPassword', 'role_id','image','dob']);
        $request_data['dob'] = date('d/m/Y',strtotime($request->dob));
        $request_data['password'] = bcrypt($request->password);
        $request_data['serial_key'] = rand(0,10) . time();
        $request_data['type'] = 'doctor';
        $newuser = $this->model->create($request_data);


        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }

   
    public function update(Request $request, $id)
    {
        $user = $this->model->find($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'         => 'nullable|digits:11|regex:/(01)[0-2]{1}[0-9]{8}/|unique:doctors,phone,'.$id,
            'email'             => ['required','string','email','max:255',Rule::unique('doctors','email')->ignore($id)],
            'password'          => 'required|string|min:6',
            'confirmPassword'   => 'required|string|same:password',
            // 'dob' => 'required|date_format:d/m/Y',
            'dob' => 'required|string',
            'gender' => ['required',Rule::in('Male','Female')],
            'token_firebase'    => 'nullable|string',
        ]);
        $condition = Patient::where('email',$request->email)->orWhere('phone',$request->phone)->first();
        if($condition){
            session()->flash('error', __('site.email_exist'));

        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.edit',$id);

        }
        $request_data = $request->except(['_token', 'password', 'confirmPassword','dob']);
        $request_data['dob'] = date('d/m/Y',strtotime($request->dob));
        $request_data['password'] = bcrypt($request->password);
        $request_data['type'] = 'doctor';
        $user->update($request_data);
        // $user->syncRoles($request->role_id);

        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }
    public function destroy($id, Request $request)
    {
        $category = $this->model->findOrFail($id);
        $category->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function forclogout($id)
    {
        $user = $this->model->find($id);
        if($user->token != null)
        {
        JWTAuth::setToken($user->token);
        JWTAuth::invalidate();
        $user->update([
            'login'=>false,
            'token'=>null,
        ]);
        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
        }else
        {
        session()->flash('error', __('site.not_login'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');


        }
    }
}
