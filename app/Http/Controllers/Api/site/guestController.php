<?php

namespace App\Http\Controllers\Api\site;

use App\CustomClass\response;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Modele;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App;
use App\Models\Country;
use App\Models\City;
class guestController
{

public function countryWithCity()
{
    $countries=Country::with('cities')->get();
    if(count($countries)<=0){
        return response::falid('Country Is Empty', 400);
    }
    return response::suceess('success', 200, 'countries',$countries);
}

public function CategoryWithModeles()
{
    $categories=Category::with('modeles')->get();
    if(count($categories)<=0){
        return response::falid('Category Is Empty', 400);
    }
    return response::suceess('success', 200, 'categories',$categories);
}
public function searchInJobs(Request $request)
{
  
    $Jobs=Job::when($request->search,function($q) use($request){
        $q->where('job_title','like',  '%' .$request->search. '%')
          ->orWhere('details','like','%' .$request->search . '%')
          ->orWhere('salary','like','%' .$request->search. '%')
          ->orWhere('gender','like','%' .$request->search. '%')
          ->orWhere('type','like','%' .$request->search. '%')
          ->orWhere('experience','like','%' .$request->search. '%')
          ->orWhere('education_bachelor','like','%' .$request->search. '%')
          ->orWhere('education_excellent','like','%' .$request->search. '%')
          ->orWhere('education_ma','like','%' .$request->search . '%')
          ->orWhere('education_fellowship','like','%' .$request->search. '%')
          ->orWhere('education_phd','like','%' .$request->search. '%')

          ->orWhereHas('employer', function ($q) use ($request) {
                $q->Where('company_name', 'like', '%' . $request->search . '%');
                })
          ->orWhereHas('category', function ($q) use ($request) {
            $q->Where('name', 'like', '%' . $request->search . '%');
            })
          ->orWhereHas('country', function ($q) use ($request) {
                $q->Where('name', 'like', '%' . $request->search . '%');
                })
          ->orWhereHas('city', function ($q) use ($request) {
            $q->Where('name', 'like', '%' . $request->search . '%');
            })   ;      
                })->where('status',1)->paginate(2);
    if($Jobs->count()>0)
    
    {
        return response::suceess('success',200,'jobs',guestResourceJob::collection($Jobs)->response()->getData(true));
    }else{
        return response::falid('No Jobs',404, 'jobs',null);

    }
}
public function filterJobs(Request $request)
{
  

    $jobs=Job::where('status',1);
    if($request->has('gender') && $request->gender != null)
    {
         $jobs->where('gender',$request->gender);
    }
    if($request->has('job_filed') && $request->job_filed != null)
    {
         $jobs->where('job_filed',$request->job_filed);   
    }
    if($request->has('country_id') && $request->country_id != null)
    {
        $jobs->where('country_id',$request->country_id);   
    }
    if($request->has('city_id') && $request->city_id != null)
    {
        $jobs->where('city_id',$request->city_id);   
    }
    if($request->has('experience') && $request->experience != null)
    {
        $jobs->where('experience','like','%' . $request->experience . '%');   
    }
    if($request->has('experience_from') && $request->has('experience_to') && $request->experience_from != null &&  $request->experience_to != null)
    {
        $jobs->where('experience','>=', $request->experience_from )->where('experience','<=', $request->experience_to );   
    }
    if($request->has('salary') && $request->salary != null)
    {
        $jobs->where('salary',$request->salary);   
    }
    if($request->has('salary_from') && $request->has('salary_to') && $request->salary_from != null &&  $request->salary_to != null)
    {
        $jobs->where('salary','>=', $request->salary_from )->where('salary','<=', $request->salary_to );   
    }
    $jobss=$jobs->latest()->paginate(2);
    if($jobs->count()>0)
    
    {
        return response::suceess('success',200,'jobs',guestResourceJob::collection($jobss)->response()->getData(true));
    }else{
        return response::falid('No Jobs',404, 'jobs',null);

    }
}
}
