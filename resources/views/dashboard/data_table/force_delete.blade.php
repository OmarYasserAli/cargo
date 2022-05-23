@if (auth()->user()->hasPermission('update-logout'.$module_name_plural))
@if($token !=null)
<a href="{{route('dashboard.'.$module_name_plural.'.forclogout',$id)}}">@lang('site.force_logout')</a>
@else
<span class="badge badge-success">@lang('site.not_login')</span>
@endif

@else
<span class="badge badge-success">@lang('site.no_permission')</span>
@endif
