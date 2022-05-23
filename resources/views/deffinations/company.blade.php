@extends('layout.app')

@section('content')
<div class="content">
                <!-- BEGIN: Top Bar -->
                <div class="top-bar">
                    <!-- BEGIN: Breadcrumb -->
                    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Application</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                    <!-- END: Breadcrumb -->
                    <!-- BEGIN: Search -->
                    <div class="intro-x relative mr-3 sm:mr-6">
                        <div class="search hidden sm:block">
                            <input type="text" class="search__input form-control border-transparent" placeholder="Search...">
                            <i data-lucide="search" class="search__icon dark:text-slate-500"></i> 
                        </div>
                        <a class="notification sm:hidden" href=""> <i data-lucide="search" class="notification__icon dark:text-slate-500"></i> </a>
                        <div class="search-result">
                            <div class="search-result__content">
                                <div class="search-result__content__title">Pages</div>
                                <div class="mb-5">
                                    <a href="" class="flex items-center">
                                        <div class="w-8 h-8 bg-success/20 dark:bg-success/10 text-success flex items-center justify-center rounded-full"> <i class="w-4 h-4" data-lucide="inbox"></i> </div>
                                        <div class="ml-3">Mail Settings</div>
                                    </a>
                                    <a href="" class="flex items-center mt-2">
                                        <div class="w-8 h-8 bg-pending/10 text-pending flex items-center justify-center rounded-full"> <i class="w-4 h-4" data-lucide="users"></i> </div>
                                        <div class="ml-3">Users & Permissions</div>
                                    </a>
                                    <a href="" class="flex items-center mt-2">
                                        <div class="w-8 h-8 bg-primary/10 dark:bg-primary/20 text-primary/80 flex items-center justify-center rounded-full"> <i class="w-4 h-4" data-lucide="credit-card"></i> </div>
                                        <div class="ml-3">Transactions Report</div>
                                    </a>
                                </div>
                                <div class="search-result__content__title">Users</div>
                                <div class="mb-5">
                                    <a href="" class="flex items-center mt-2">
                                        <div class="w-8 h-8 image-fit">
                                            <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-4.jpg">
                                        </div>
                                        <div class="ml-3">Russell Crowe</div>
                                        <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">russellcrowe@left4code.com</div>
                                    </a>
                                    <a href="" class="flex items-center mt-2">
                                        <div class="w-8 h-8 image-fit">
                                            <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-3.jpg">
                                        </div>
                                        <div class="ml-3">John Travolta</div>
                                        <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">johntravolta@left4code.com</div>
                                    </a>
                                    <a href="" class="flex items-center mt-2">
                                        <div class="w-8 h-8 image-fit">
                                            <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-11.jpg">
                                        </div>
                                        <div class="ml-3">Angelina Jolie</div>
                                        <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">angelinajolie@left4code.com</div>
                                    </a>
                                    <a href="" class="flex items-center mt-2">
                                        <div class="w-8 h-8 image-fit">
                                            <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-10.jpg">
                                        </div>
                                        <div class="ml-3">Al Pacino</div>
                                        <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">alpacino@left4code.com</div>
                                    </a>
                                </div>
                                <div class="search-result__content__title">Products</div>
                                <a href="" class="flex items-center mt-2">
                                    <div class="w-8 h-8 image-fit">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/preview-8.jpg">
                                    </div>
                                    <div class="ml-3">Samsung Galaxy S20 Ultra</div>
                                    <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">Smartphone &amp; Tablet</div>
                                </a>
                                <a href="" class="flex items-center mt-2">
                                    <div class="w-8 h-8 image-fit">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/preview-3.jpg">
                                    </div>
                                    <div class="ml-3">Nike Air Max 270</div>
                                    <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">Sport &amp; Outdoor</div>
                                </a>
                                <a href="" class="flex items-center mt-2">
                                    <div class="w-8 h-8 image-fit">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/preview-3.jpg">
                                    </div>
                                    <div class="ml-3">Dell XPS 13</div>
                                    <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">PC &amp; Laptop</div>
                                </a>
                                <a href="" class="flex items-center mt-2">
                                    <div class="w-8 h-8 image-fit">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/preview-11.jpg">
                                    </div>
                                    <div class="ml-3">Nikon Z6</div>
                                    <div class="ml-auto w-48 truncate text-slate-500 text-xs text-right">Photography</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- END: Search -->
                    <!-- BEGIN: Notifications -->
                    <div class="intro-x dropdown mr-auto sm:mr-6">
                        <div class="dropdown-toggle notification notification--bullet cursor-pointer" role="button" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="bell" class="notification__icon dark:text-slate-500"></i> </div>
                        <div class="notification-content pt-2 dropdown-menu">
                            <div class="notification-content__box dropdown-content">
                                <div class="notification-content__title">Notifications</div>
                                <div class="cursor-pointer relative flex items-center ">
                                    <div class="w-12 h-12 flex-none image-fit mr-1">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-4.jpg">
                                        <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                                    </div>
                                    <div class="ml-2 overflow-hidden">
                                        <div class="flex items-center">
                                            <a href="javascript:;" class="font-medium truncate mr-5">Russell Crowe</a> 
                                            <div class="text-xs text-slate-400 ml-auto whitespace-nowrap">05:09 AM</div>
                                        </div>
                                        <div class="w-full truncate text-slate-500 mt-0.5">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 20</div>
                                    </div>
                                </div>
                                <div class="cursor-pointer relative flex items-center mt-5">
                                    <div class="w-12 h-12 flex-none image-fit mr-1">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-3.jpg">
                                        <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                                    </div>
                                    <div class="ml-2 overflow-hidden">
                                        <div class="flex items-center">
                                            <a href="javascript:;" class="font-medium truncate mr-5">John Travolta</a> 
                                            <div class="text-xs text-slate-400 ml-auto whitespace-nowrap">03:20 PM</div>
                                        </div>
                                        <div class="w-full truncate text-slate-500 mt-0.5">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem </div>
                                    </div>
                                </div>
                                <div class="cursor-pointer relative flex items-center mt-5">
                                    <div class="w-12 h-12 flex-none image-fit mr-1">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-11.jpg">
                                        <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                                    </div>
                                    <div class="ml-2 overflow-hidden">
                                        <div class="flex items-center">
                                            <a href="javascript:;" class="font-medium truncate mr-5">Angelina Jolie</a> 
                                            <div class="text-xs text-slate-400 ml-auto whitespace-nowrap">01:10 PM</div>
                                        </div>
                                        <div class="w-full truncate text-slate-500 mt-0.5">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomi</div>
                                    </div>
                                </div>
                                <div class="cursor-pointer relative flex items-center mt-5">
                                    <div class="w-12 h-12 flex-none image-fit mr-1">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-10.jpg">
                                        <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                                    </div>
                                    <div class="ml-2 overflow-hidden">
                                        <div class="flex items-center">
                                            <a href="javascript:;" class="font-medium truncate mr-5">Al Pacino</a> 
                                            <div class="text-xs text-slate-400 ml-auto whitespace-nowrap">03:20 PM</div>
                                        </div>
                                        <div class="w-full truncate text-slate-500 mt-0.5">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomi</div>
                                    </div>
                                </div>
                                <div class="cursor-pointer relative flex items-center mt-5">
                                    <div class="w-12 h-12 flex-none image-fit mr-1">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-7.jpg">
                                        <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                                    </div>
                                    <div class="ml-2 overflow-hidden">
                                        <div class="flex items-center">
                                            <a href="javascript:;" class="font-medium truncate mr-5">Edward Norton</a> 
                                            <div class="text-xs text-slate-400 ml-auto whitespace-nowrap">01:10 PM</div>
                                        </div>
                                        <div class="w-full truncate text-slate-500 mt-0.5">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Notifications -->
                    <!-- BEGIN: Account Menu -->
                    <div class="intro-x dropdown w-8 h-8">
                        <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in" role="button" aria-expanded="false" data-tw-toggle="dropdown">
                            <img alt="Midone - HTML Admin Template" src="dist/images/profile-6.jpg">
                        </div>
                        <div class="dropdown-menu w-56">
                            <ul class="dropdown-content bg-primary text-white">
                                <li class="p-2">
                                    <div class="font-medium">Russell Crowe</div>
                                    <div class="text-xs text-white/70 mt-0.5 dark:text-slate-500">Backend Engineer</div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider border-white/[0.08]">
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="user" class="w-4 h-4 mr-2"></i> Profile </a>
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Add Account </a>
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="lock" class="w-4 h-4 mr-2"></i> Reset Password </a>
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="help-circle" class="w-4 h-4 mr-2"></i> Help </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider border-white/[0.08]">
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Logout </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- END: Account Menu -->
                </div>
                <!-- END: Top Bar -->
                
                <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
                    <!-- BEGIN: Post Content -->
                    <div class="intro-y col-span-12 lg:col-span-8">
                        
                        <div class="post intro-y overflow-hidden box mt-5">
                            
                            <div class="post__content tab-content">
                                <div id="content" class="tab-pane p-5 active" role="tabpanel" aria-labelledby=	"content-tab">
							   <div> 
							      <label for="regular-form-1" class="form-label">
							      أسم الشركة عربى
							      </label> 
							      <input id="regular-form-1" type="text" class="form-control" > 
							   </div>
							   <div class="mt-3"> 
								   	<label for="regular-form-2" class="form-label">اسم الشركه انجلزي</label> 
								   	<input id="regular-form-2" type="text" class="form-control" >
							   	</div>
							   <div class="mt-3"> 
								   	<label for="regular-form-2" class="form-label">عنوان الشركة</label> 
								   	<input id="regular-form-2" type="text" class="form-control" >
							   	</div>
							   <div class="mt-3"> 
								   	<label for="regular-form-2" class="form-label">التليفون</label> 
								   	<input id="regular-form-2" type="text" class="form-control" >
							   	</div>
							   <div class="mt-3"> 
								   	<label for="regular-form-5" class="form-label">
								   ملاحضات</label> 
								   	<textarea id="regular-form-5" type="text" class="form-control"  > 
								   	</textarea>
							   	</div>
							    <button class="btn btn-primary mt-5">Save</button>  
							</div>
							                         
							</div>
                        </div>
                    </div>
                    <!-- END: Post Content -->
                    <!-- BEGIN: Post Info -->
                    
                    <!-- END: Post Info -->
                </div>
</div>
@endsection
