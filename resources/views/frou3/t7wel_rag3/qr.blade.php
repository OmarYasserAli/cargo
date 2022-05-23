@extends('layout.app')

@section('content')

<div class="content">
                <!-- BEGIN: Top Bar -->
                @include('layout.partial.topbar')
                <!-- END: Top Bar -->
                <!-- BEGIN: Modal Toggle --> 
<!-- END: Modal Toggle --> <!-- BEGIN: Modal Content --> 
<div id="type_modal" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body px-5 py-10">
                <div class="text-center">
                    <div class="mb-5" style="font-size: 25px">اسم الفرع</div>
                    <div class="form-inline">
                        
                        <select class="form-select form-select-lg sm:mt-2 sm:mr-2 mb-5" id='select_type' aria-label=".form-select-lg example">
                            @foreach ($branches as $branch)
                             <option value="{{$branch->serial_}}">{{$branch->name_}}</option>
                             @endforeach
                        </select>
                      </div>
                     <button type="button" data-tw-dismiss="" id='modal_close' class="btn btn-primary w-24">استمرار</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Modal Content -->
                <div class="intro-y flex items-center mt-8">
                    
                </div>
                <div class="grid grid-cols-12 gap-6 ">
                    <div class="intro-y col-span-12 lg:col-span-4">
                        <!-- BEGIN: Right Basic Table -->
                        <div class="post intro-y overflow-hidden box ">
                            <div id='shipment_form'>
                                <div class="post__content tab-content">
                                    <div id="content" class="tab-pane p-5 active" role="tabpanel" aria-labelledby=	"content-tab">
                                        <div class="form-inline">
                                            <label for="type" class="form-label sm:w-20">تحويل الشحنات الى</label>
                                            <input id="type" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-inline mt-1">
                                            <label for="QR" class="form-label sm:w-20">QR</label>
                                            <input id="QR" type="text" class="form-control" >
                                        </div>
                                        <div class="form-inline mt-1">
                                            <label for="rakam_tawsel" class="form-label sm:w-20">رقم الوصل</label>
                                            <input id="rakam_tawsel" type="text" class="form-control" disabled>
                                        </div>
                                        
                                        <div class="form-inline mt-1">
                                            <label for="3amel_name" class="form-label sm:w-20">اسم العميل</label>
                                            <input id="3amel_name" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-inline mt-1">
                                            <label for="commercial_name" class="form-label sm:w-20">الاسم التجارى</label>
                                            <input id="commercial_name" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-inline mt-1">
                                            <label for="mostalem_phone" class="form-label sm:w-20">هاتف المستلم</label>
                                            <input id="mostalem_phone" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-inline mt-1">
                                            <label for="mo7afza" class="form-label sm:w-20">المحافظة</label>
                                            <input id="mo7afza" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-inline mt-1">
                                            <label for="manteka" class="form-label sm:w-20">المنطقة</label>
                                            <input id="manteka" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-inline mt-1">
                                            <label for="3nwan" class="form-label sm:w-20">العنوان</label>
                                            <input id="3nwan" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-inline mt-1">
                                            <label for="branch" class="form-label sm:w-20">الفرع</label>
                                            <input id="branch" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-inline mt-1">
                                            <label for="area" class="form-label sm:w-20">منطقة الشحنه</label>
                                            <input id="area" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-inline mt-1">
                                            <label for="status" class="form-label sm:w-20"> الحالة</label>
                                            <input id="status" type="text" class="form-control" disabled>
                                        </div>
                                        
                                        <div class="form-inline mt-1">
                                            <label for="cost" class="form-label sm:w-20">مبلغ السحنة</label>
                                            <input id="cost" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-inline mt-1">
                                            <label for="tawsil_cost" class="form-label sm:w-20">مبلغ التوصيل</label>
                                            <input id="tawsil_cost" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-inline mt-1">
                                            <label for="safi" class="form-label sm:w-20">الصافى</label>
                                            <input id="safi" type="text" class="form-control" disabled>
                                        </div>
                                        
                                        <div class="sm:ml-20 sm:pl-5 mt-5" style="font-size: 20px">
                                            <button class="btn btn-primary hidden" id='qr_submit'>اضافة</button>
                                            <button class="btn btn-warning" id='qr_new'>جديد</button>
                                        </div>
                                </div>
                            </div>                   
							</div>
                        </div>
                        <!-- END: Basic Table -->
                        
                    </div>

                     <div class="intro-y col-span-12 lg:col-span-8">
                        <!-- BEGIN: right Basic Table -->
                        <div class="intro-y box ">
                            <div class=" p-5 border-b border-slate-200/60">
                                <h2 class="font-medium text-base mr-auto">
                                   الشحنات
                                </h2>
                               
                            </div>
                            <div class="p-5" id="basic-table">
                                <div class="preview">
                                    <div class="overflow-x-auto">
                                        <table class="table" id='manteka-table'>
                                            <thead>
                                                <tr class="mantika-row">
                                                    
                                                    <th class="whitespace-nowrap">#</th>
                                                    <th class="whitespace-nowrap">الكود</th>
                                                    <th class="whitespace-nowrap">اسم العميل</th>
                                                    <th class="whitespace-nowrap">هاتف المستلم</th>
                                                    <th class="whitespace-nowrap">العنوان</th>
                                                    <th class="whitespace-nowrap">مبلغ الشحنه</th>
                                                    <th class="whitespace-nowrap"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                
                                              
                                            </tbody>
                                        </table>
                                        <div class="sm:ml-20 sm:pl-5 mt-5" style="font-size: 20px">
                                            <button class="btn btn-primary " id='tanfez'>تنفيذ</button>
                                        
                                            <button class="btn btn-warning " id='cancel' >حذف</button>
                                          </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <!-- END: Basic Table -->
                        
                    </div>
                </div>
            </div>

        <script type="text/javascript">
            let  shipments=[];
            let  selected=[];
            let cnt=1;
           
            let current_status=0;
            $( document ).ready(function() {
                const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#type_modal"));
                 myModal.show();
            });
            
            $( "#modal_close" ).click(function() {
                
                $('#type').val($( "#select_type option:selected" ).text());
                current_status=$( "#select_type option:selected" ).val();
                const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#type_modal"));
                myModal.hide();
            });
            $( "#qr_new" ).click(function() {
                $('#manteka-table tr').not(function(){ return !!$(this).has('th').length; }).remove();
                    cnt=1;
                    shipments=[];
                $('#shipment_form').find("input[type=text], textarea").val("");
                const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#type_modal"));
                myModal.show();
            });
             

            
                $( "#tanfez" ).click(function() {
                 
                    $.ajax({
                        url: "{{route('frou3_t7wel_sho7nat_qr_save')}}" ,
                        type: 'post',
                        data:{ code:shipments, status:current_status, _token: "{{ csrf_token() }}"},
                        error: function(e){
                            console.log(e);
                        },
                        success: function(res) {
                            alert('تم التحويل بناح');
                        }
                    });
                     
                });
                $( "#cancel" ).click(function() {
                    cnt=0;
                    $('#manteka-table tr').each(function( index ) {
                         if($( this ).hasClass('selected') )
                         {
                            //selected.push($(this).data('code'));
                            $( this ).remove();
                            var code=$(this).data('code')
                            shipments.splice(shipments.indexOf(code)-1, 1)
                            console.log(shipments);
                         }else{
                            $( this ).find("td:first").text(cnt);
                            cnt++;
                         }
                    });
                });
                
                $( "#QR" ).keyup(function(e){
                    if(e.keyCode == 13)
                    {
                        var qr = ( $('#QR').val());
                        //  console.log(qr);
                        if(shipments.includes(qr)) return;
                        if(qr=='') return;
                        $.ajax("{{route('getShipmentsByCode')}}"+"?code="+qr+"&status="+current_status+"&case=frou3_t7wel_rag3_qr",   // request url
                            {
                            
                                success: function (data, status, xhr) {
                                    
                                    
                                    if(shipments.includes(qr)) return;
                                    shipments.push(qr);
                                    console.log(data);
                                    var res = (data.data)[0];
                                    $('#rakam_tawsel').val(res.code_);
                                    $('#3amel_name').val(res.client_name_);
                                    $('#commercial_name').val(res.commercial_name_);
                                    $('#mostalem_phone').val(res.reciver_phone_);
                                    $('#mo7afza').val(res.mo7afza_);
                                    $('#manteka').val(res.mantqa_);
                                    $('#3nwan').val(res.el3nwan);
                                    $('#branch').val(res.branch_);
                                    $('#area').val(res.Ship_area_);
                                    $('#status').val((res.shipment_status).name_);
                                    $('#cost').val(res.shipment_coast_);
                                    $('#tawsil_cost').val(res.tawsil_coast_);
                                    $('#safi').val(res.total_);
                                
                                  
                                   
                                  
                                        $('#manteka-table   tr:last').after(`<tr class='sho7nat-row' data-code=`+res.code_+`>
                                            <td>`+cnt+`</td>
                                            <td>`+res.code_+`</td>
                                            <td >`+(res.client_name_)+`   </td> 
                                            <td >`+(res.reciver_phone_)+`   </td> 
                                            
                                            <td  >`+(res.mo7afza_)+`</td>
                                            <td  >`+(res.shipment_coast_)+`</td>
                                        <td>
                                        
                                            </td>
                                            </tr>`
                                            );
                                            cnt++;
                            },
                            error: function (request, status, error) {
                                alert("خطأ فى ادخال الشحنة");
                            }
                        });
                    }
                });
                
                $('#manteka-table').on('click', 'tr', function(){
                    var code = $(this).data('code')
                 
                        $(this).toggleClass('selected');
 
                });
               
            </script>
@endsection
