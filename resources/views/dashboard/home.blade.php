@extends('dashboard.layouts.app')
@section('title')
@lang('site.Home')
@stop

@section('content')

    <div class="content-wrapper" style="min-height: 0">

        <section class="content-header">

            <h1>@lang('site.dashboard')</h1>

            <ol class="breadcrumb">
                <li class="active"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</li>
            </ol>
        </section>

        <section class="content">

        <div class="row">

            {{-- categories --}}
            @if (auth()->user()->hasPermission('read-users'))

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{App\User::count()}}</h3>

                        <p>@lang('site.users')</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div> 
                    @if (auth()->user()->hasPermission('read-users'))
                        <a href="{{ route('dashboard.users.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                    @endif
                </div>
            </div>
            @endif
            @if (auth()->user()->hasPermission('read-roles'))

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{App\Role::count()}}</h3>

                        <p>@lang('site.roles')</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-hourglass-half"></i>
                    </div>
                    @if (auth()->user()->hasPermission('read-roles'))
                        <a href="{{ route('dashboard.roles.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                    @endif
                </div>
            </div>
            @endif
      
          

</div><!-- end of row -->
    

        </section><!-- end of content -->
        {{-- @include('dashboard.layouts._char') --}}

    </div><!-- end of content wrapper -->


@endsection


@push('script')


@endpush
