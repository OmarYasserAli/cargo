<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Doctor;
use App\Models\EmergencChild;
use App\Models\FirstAid;
use App\Models\FirstAidChild;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class EmergencChildController extends BackEndController
{
    public function __construct(EmergencChild $model)
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
        $first_aid = EmergencChild::select('id','emergenc_id')->with('emergenc:id')->withTranslation()->get();
        $module_name_plural = $this->getClassNameFromModel();
        return DataTables::of($first_aid)
            ->addColumn('record_select', 'dashboard.data_table.record_select')
            ->addColumn('emergenc', function (EmergencChild $first) {
                return $first->emergenc->name;
            })
            ->addColumn('actions', function($data){
            $module_name_plural = $this->getClassNameFromModel();
            $id = $data->id;
              return view('dashboard.data_table.actions',compact('module_name_plural','data','id'));
            })
           
            ->rawColumns(['record_select', 'actions'])
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
            'en.title' => 'required|string',
            'ar.title' => 'required|string',
            'en.description' => 'required|string',
            'ar.description' => 'required|string',
            'emergenc_id' => 'required|exists:emergencs,id',
        
        ]);

        $request_data = $request->except(['_token']);
        $newuser = $this->model->create($request_data);


        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }

   
    public function update(Request $request, $id)
    {
        $user = $this->model->find($id);

        $request->validate([
            'en.title' => 'required|string',
            'ar.title' => 'required|string',
            'en.description' => 'required|string',
            'ar.description' => 'required|string',
            'emergenc_id' => 'required|exists:emergencs,id',
           
        ]);

        $request_data = $request->except(['_token']);
        $user->update($request_data);
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
}
