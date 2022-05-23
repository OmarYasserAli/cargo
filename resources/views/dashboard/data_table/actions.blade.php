@if (auth()->user()->hasPermission('update-'.$module_name_plural))
<a href="{{route('dashboard.'.$module_name_plural.'.edit', $data->id)}}" style="color: #fff;
background-color: #17a2b8;
border-color: #17a2b8;" rel="tooltip" title="" class="btn btn-info btn-sm "
   >
    <i class="fa fa-edit">@lang('site.edit')</i>
</a>
@else
<input class="btn btn-info btn-sm" type="submit" value="@lang('site.edit')" disabled>
@endif

@if (auth()->user()->hasPermission('delete-'.$module_name_plural))
<form action="{{route('dashboard.'.$module_name_plural.'.destroy', $data->id)}}" method="POST" style="display: inline-block">
    {{csrf_field()}}
    {{method_field('DELETE')}}
    <button type="submit" style="color:#fff!important;" rel="tooltip" title="" class="btn btn-danger  btn-sm delete-one " data-original-title="@lang('site.delete')">
        <i class="fa fa-1x fa-trash"> @lang('site.delete')</i>
    </button> 
    
</form>
@else
<input class="btn btn-danger btn-sm" type="submit" value="@lang('site.delete')" disabled>
@endif
<script>
     $('button.delete-one').click(function(e) {

var that = $(this)

e.preventDefault();

var n = new Noty({
    text: "@lang('site.confirm_delete')",
    type: "warning",
    killer: true,
    buttons: [
        Noty.button("@lang('site.yes')", 'btn btn-success mr-2',
    function() {
            that.closest('form').submit();
        }),

        Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function() {
            n.close();
        })
    ]
});

n.show();

}); //end of delete
</script>