@extends('layout.app')

@section('content')

<div class="content">
    <!-- BEGIN: Top Bar -->
    @include('layout.partial.topbar')
    <!-- END: Top Bar -->
    
    <div class="intro-y  grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Item List -->
        <div class="intro-y col-span-12 lg:col-span-12">
            
            <div class="grid grid-cols-12 gap-5 mt-5 text-center " >
                <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in " >
                    <div class="font-medium text-base">Soup</div>
                    <div class="text-slate-500">5 Items</div>
                </div>
                
                <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in">
                    <div class="font-medium text-base">Pasta</div>
                    <div class="text-slate-500">4 Items</div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in">
                    <div class="font-medium text-base">Waffle</div>
                    <div class="text-slate-500">3 Items</div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in">
                    <div class="font-medium text-base">Snacks</div>
                    <div class="text-slate-500">8 Items</div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in" style="background: transparent">
                    <div class="font-medium text-base"></div>
                    <div class="text-slate-500"></div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in">
                    <div class="font-medium text-base">Beverage</div>
                    <div class="text-slate-500">9 Items</div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in">
                    <div class="font-medium text-base">Snacks</div>
                    <div class="text-slate-500">8 Items</div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in">
                    <div class="font-medium text-base">Deserts</div>
                    <div class="text-slate-500">8 Items</div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in">
                    <div class="font-medium text-base">Beverage</div>
                    <div class="text-slate-500">9 Items</div>
                </div>
            </div>
           
        </div>
        <!-- END: Item List -->
        <!-- BEGIN: Ticket -->
       
        <!-- END: Ticket -->
    </div>
    <!-- BEGIN: New Order Modal -->

    <!-- END: Add Item Modal -->
</div>

        <script type="text/javascript">
            let  shipments=[];
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
                        url: "{{route('shipment.t7wel_qr_save')}}" ,
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
                    $('#manteka-table tr').not(function(){ return !!$(this).has('th').length; }).remove();
                    cnt=1;
                    shipments=[];
                });
                
                $( "#QR" ).keyup(function(e){
                    if(e.keyCode == 13)
                    {
                        var qr = ( $('#QR').val());
                        //  console.log(qr);
                        if(shipments.includes(qr)) return;
                        if(qr=='') return;
                        $.ajax("{{route('getShipmentsByCode')}}"+"?code="+qr+"&status="+current_status+"&case=t7wel_7ala_qr",   // request url
                            {
                            
                                success: function (data, status, xhr) {
                                    
                                    
                                    if(shipments.includes(qr)) return;
                                    shipments.push(qr);
                                    //sconsole.log(shipments.includes(qr) ,shipments);
                                    var res = (data.data)[0];
                                    $('#rakam_tawsel').val(res.code_);
                                    $('#3amel_name').val(res.client_name_);
                                    $('#commercial_name').val(res.commercial_name_);
                                    $('#mostalem_phone').val(res.reciver_phone_);
                                    $('#mo7afza').val(res.mo7afza_);
                                    $('#manteka').val(res.mantqa_);
                                    $('#3nwan').val(res.el3nwan);
                                    $('#cost').val(res.shipment_coast_);
                                    $('#tawsil_cost').val(res.tawsil_coast_);
                                    $('#safi').val(res.total_);
                                
                                
                                    // success callback function
                                    //$('#manteka-table tr').not(function(){ return !!$(this).has('th').length; }).remove();
                                    
                                    console.log((data.data)[0]);
                                        $('#manteka-table   tr:last').after(`<tr class='' >
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
          
            </script>
@endsection
