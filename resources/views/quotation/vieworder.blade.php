@extends('layouts.admin')
@section('page-title')
    {{__('Order Detail')}}
@endsection
@push('script-page')
  <script src="{{ asset('public/js/jquery-barcode.js') }}"></script>
    <script>
        $(document).on('click', '#shipping', function () {
            var url = $(this).data('url');
            var is_display = $("#shipping").is(":checked");
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    'is_display': is_display,
                },
                success: function (data) {
                    // console.log(data);
                }
            });
        })
        $(document).ready(function() {
            $(".product_barcode").each(function() {
                var id = $(this).attr("id");
                var sku = $(this).data('skucode');
                sku = encodeURIComponent(sku);
                generateBarcode(sku, id);
            });
        });
        function generateBarcode(val, id) {

            var value = val;
            var btype = '{{ $barcode['barcodeType'] }}';
            var renderer = '{{ $barcode['barcodeFormat'] }}';
            var settings = {
                output: renderer,
                bgColor: '#FFFFFF',
                color: '#000000',
                barWidth: '1',
                barHeight: '50',
                moduleSize: '5',
                posX: '10',
                posY: '20',
                addQuietZone: '1'
            };
            $('#' + id).html("").show().barcode(value, btype, settings);

        }

        setTimeout(myGreeting, 1000);
        function myGreeting() {
            if ($(".datatable-barcode").length > 0) {
                const dataTable =  new simpleDatatables.DataTable(".datatable-barcode");
            }
        } 
    </script>
@endpush

    @php
        $settings = Utility::settings();
        $Qstatus = [
        'Pending',
        'Order',
        'Rejected'
        ];
    @endphp
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('quotation.index')}}">{{__('Order Summary')}}</a></li>
    <li class="breadcrumb-item">Order Detail So No.{{ AUth::user()->orderNumberFormat($quotation->quotation_id) }}</li>
@endsection

@section('action-btn')
    <div class="float-end">
            <div class="form-group" style="float: inline-start;width: 120px;padding-right: 5px;">
            </div>
        @if($quotation->status == 0)<a href="{{ route('quotation.status', ['id' => $quotation->id, 'status' => 'order'])}}" class="btn btn-primary">{{__('Change to Order')}}</a>@elseif($quotation->status == 1 && $quotation->is_jobcard == 0)
        <a href="{{ route('quotation.status', ['id' => $quotation->id, 'status' => 'is_jobcard'])}}" class="btn btn-primary" disabled>{{__('Convert to JobCard')}}</a>
        @elseif($quotation->is_jobcard == 1)
        <a href="#" class="btn btn-secondary" disabled>{{__('Converted in JobCard')}}</a>
        @else
        <a href="{{ route('quotation.status', ['id' => $quotation->id, 'status' => 'is_jobcard'])}}" title="Already Converted"class="btn btn-primary" disabled>{{__('Order Rejected')}}</a>
        @endif
        <a href="{{ route('quotation.pdf', Crypt::encrypt($quotation->id))}}" class="btn btn-primary" target="_blank">{{__('Download')}}</a>
    </div>
@endsection

@section('content')
    <div class="row">
                <div class="col-xl-12">
                    
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="lead-sidenav" style="flex-direction: unset;">
                            @if(Auth::user()->type != 'client')
                                {{--<a href="#general" class="list-group-item list-group-item-action border-0">{{__('General')}}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                                <div data-target="content1" class="list-group-item list-group-item-action border-0 tab active" style="cursor: pointer;">{{__('Quotation')}}
                                    <div class="float-end"></div>
                                </div>--}}
                            @endif

                           
                                {{--<div data-target="content2" data-title="{{ __('Quotataion') }}" class="list-group-item list-group-item-action border-0 tab" style="cursor: pointer;">| {{ __('Revise Quotation')}}
                                    <div class="float-end"></div>
                                </div>--}}
                          
                                <div  data-target="content3" class="list-group-item list-group-item-action border-0 tab active" style="cursor: pointer;">{{__('Orders')}}
                                    <div class="float-end"></div>
                                </div>
                         
                            @if(Auth::user()->type != 'client')
                                <div data-target="content4" class="list-group-item list-group-item-action border-0 tab" style="cursor: pointer;border-bottom-left-radius: unset !important;border-top-left-radius: inherit;border-top-right-radius: inherit;;border-bottom-right-radius: unset !important;">| {{__('Job Card')}}
                                    <div class="float-end"></i></div>
                                </div>
                            @endif
                           

                        </div>
                    </div>
                </div>
              
            </div>
    <div class="content" id="content1" style="display: none;">  
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                            <h4>{{__('quotation')}}</h4>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                            <h4 class="invoice-number">{{ $quotation->is_revised != null?Auth::user()->orderNumberFormat($quotation->quotation_id).'RV':Auth::user()->orderNumberFormat($quotation->quotation_id) }}</h4>
                        </div>
                       
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <small class="font-style">
                                <strong>{{__('Billed To')}} :</strong><br>
                                @if(!empty($customer->billing_name))
                                    {{!empty($customer->billing_name)?$customer->billing_name:''}}<br>
                                    {{!empty($customer->billing_address)?$customer->billing_address:''}}<br>
                                    {{!empty($customer->billing_city)?$customer->billing_city:'' .', '}}<br>
                                    {{!empty($customer->billing_state)?$customer->billing_state:'',', '}},
                                    {{!empty($customer->billing_zip)?$customer->billing_zip:''}}<br>
                                    {{!empty($customer->billing_country)?$customer->billing_country:''}}<br>
                                    {{!empty($customer->billing_phone)?$customer->billing_phone:''}}<br>
                                    @if($settings['vat_gst_number_switch'] == 'on')
                                    <strong>{{__('Tax Number ')}} : </strong>{{!empty($customer->tax_number)?$customer->tax_number:''}}
                                    @endif
                                @else
                                    -
                                @endif
                            </small>
                        </div>
                        <div class="col-4">
                            @if(App\Models\Utility::getValByName('shipping_display')=='on')
                                <small>
                                    <strong>{{__('Shipped To')}} :</strong><br>
                                        @if(!empty($customer->shipping_name))
                                        {{!empty($customer->shipping_name)?$customer->shipping_name:''}}<br>
                                        {{!empty($customer->shipping_address)?$customer->shipping_address:''}}<br>
                                        {{!empty($customer->shipping_city)?$customer->shipping_city:'' . ', '}}<br>
                                        {{!empty($customer->shipping_state)?$customer->shipping_state:'' .', '}},
                                        {{!empty($customer->shipping_zip)?$customer->shipping_zip:''}}<br>
                                        {{!empty($customer->shipping_country)?$customer->shipping_country:''}}<br>
                                        {{!empty($customer->shipping_phone)?$customer->shipping_phone:''}}<br>
                                    @else
                                    -
                                    @endif
                                </small>
                            @endif
                        </div>
                        <div class="col-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="me-4">
                                    <small>
                                        <strong>{{__('Issue Date')}} :</strong>
                                        {{\Auth::user()->dateFormat($quotation->quotation_date)}}<br><br>
                                    </small>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-dark" >#</th>
                                        <th class="text-dark">{{__('Items')}}</th>
                                        <th class="text-dark">{{__('Quantity')}}</th>
                                        <th class="text-dark">{{__('Price')}}</th>
                                        <th class="text-dark">{{__('Tax')}}</th>
                                        <th class="text-dark">{{__('Tax Amount')}}</th>
                                        <th class="text-dark">{{__('Total')}}</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $totalQuantity=0;
                                        $totalRate=0;
                                        $totalTaxPrice=0;
                                        $totalDiscount=0;
                                        $subTotal = 0;
                                        $total = 0;
                                        $taxesData=[];
                                    @endphp
                                    @foreach($iteams as $key =>$iteam)
                                        @if(!empty($iteam->tax))
                                            @php
                                                $taxes=App\Models\Utility::tax($iteam->tax);
                                                $totalQuantity+=$iteam->quantity;
                                                $totalRate+=$iteam->price;
                                                $totalDiscount+=$iteam->discount;

                                                foreach($taxes as $taxe){

                                                    $taxDataPrice=App\Models\Utility::taxRate($taxe->rate,$iteam->price,$iteam->quantity);
                                                    if (array_key_exists($taxe->name,$taxesData))
                                                    {
                                                        $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                                    }
                                                    else
                                                    {
                                                        $taxesData[$taxe->name] = $taxDataPrice;
                                                    }
                                                }
                                            @endphp
                                        @endif
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{!empty($iteam->product())?$iteam->product()->name:''}}</td>
                                            <td>{{$iteam->quantity}}</td>
                                            <td>{{\Auth::user()->priceFormat($iteam->price)}}</td>
                                            <td>
                                                @if(!empty($iteam->tax))
                                                    <table>
                                                        @php
                                                            $totalTaxRate = 0;
                                                            $totalTaxPrice = 0;
                                                        @endphp
                                                        @foreach($taxes as $tax)
                                                            @php
                                                                $taxPrice=App\Models\Utility::taxRate($tax->rate,$iteam->price,$iteam->quantity);
                                                                $totalTaxPrice+=$taxPrice;
                                                            @endphp
                                                            <tr>
                                                                <span class="badge bg-primary">{{$tax->name .' ('.$tax->rate .'%)'}}</span> <br>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{\Auth::user()->priceFormat($totalTaxPrice)}}</td>
                                            <td >{{\Auth::user()->priceFormat(($iteam->price*$iteam->quantity) + $totalTaxPrice)}}</td>
                                        </tr>
                                        @php
                                                $subTotal +=  (($iteam->price*$iteam->quantity) + $totalTaxPrice);
                                                $total = $subTotal - $totalDiscount;
                                    @endphp
                                    @endforeach


                                    <tr>
                                        <td><b>{{__(' Sub Total')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($subTotal)}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{__('Discount')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($totalDiscount)}}</td>
                                    </tr>
                                    <tr class="pos-header">
                                        <td><b>{{__('Total')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($total)}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div></div>
    <div class="content" id="content2" style="display: none;">  
    <div class="row">
        <div class="col-12">
            <div class="card">
               <div class="card-body table-border-style">
                   
                     @if(isset($quotation_r))
                    
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th> {{ __('Sr.') }}</th>
                                    <th> {{ __('Quote Ref No.') }}</th>
                                    <th> {{ __('Product Name') }}</th>
                                    <th> {{ __('Total Cost') }}</th>
                                    <th> {{ __('Quote Date') }}</th>
                                    <th> {{ __('Created by') }}</th>
                                    <th> {{ __('Quote Status') }}</th>
                                    @if (Gate::check('edit quotation') || Gate::check('delete quotation') || Gate::check('convert quotation'))
                                        <th> {{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>


                                @foreach ($quotation_r as $quotation)
                                    <tr>
                                       
                                        <td>
                                            <div class="number-color ms-2" style="font-size:12px;background-color: {{ ($quotation->status == 0)?'#ffa21d':'#6fd943'}}">
                                                   {{ $quotation->id }}</div> 
                                           </td>
                                        <td class="Id">
                                            <a href="{{ route('quotation.show', \Crypt::encrypt($quotation->id)) }}"
                                                class="btn btn-outline-primary">{{ Auth::user()->orderNumberFormat($quotation->quotation_id) }}</a>
                                        </td>
                                        
                                        @if(count($quotation->items)>0)
                                       
                                        @foreach($quotation->items as $item)
                                        @php
                                         $product = \App\Models\ProductService::find(3);
                                         $quoteProduct = \App\Models\QuotationProduct::where('quotation_id', $quotation->quotation_id)->first();
                                         $total =  $item->price * (!empty($quoteProduct)?$quoteProduct->quantity:1);
                                        @endphp
                                        <td>{{$product->name}}</td>
                                        @if(isset($quoteProduct->tax))
                                        @php 
                                        $taxProduct = \App\Models\Tax::find($quoteProduct->tax);
                                        @endphp
                                        <td>{{!empty($taxProduct)?(($total * $taxProduct->rate/100) + $total):$total }}</td>
                                        @else
                                        <td> {{$item->price}}</td>
                                        @endif
                                        @endforeach
                                        @else
                                         @php
                                        
                                         $quoteProduct = \App\Models\QuotationProduct::where('quotation_id', $quotation->quotation_id)->first();
                                          $product = \App\Models\ProductService::find($quoteProduct->product_id);
                                        
                                         $totals =  $quoteProduct->price + $quoteProduct->tax;
                                        @endphp
                                        <td>{{$product->name}}</td>
                                        <td>{{$totals}}</td>
                                        @endif
                                        <td>{{ Auth::user()->dateFormat($quotation->quotation_date) }}</td>
                                        <td> {{ !empty($quotation->customer) ? $quotation->customer->name : '' }} </td>
                                       
                                         <td>{{ $quotation->status ==0?'Waiting for Approval':'Approved' }}</td>
                                        @if (Gate::check('edit quotation') || Gate::check('delete quotation') || Gate::check('convert quotation'))
                                            <td class="Action">
                                                <span>

                                                    @if ($quotation->is_converted == 0)
                                                        @can('convert quotation')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{ route('poses.index', $quotation->id) }}"
                                                                    class="mx-3 btn btn-sm align-items-center"
                                                                    data-bs-toggle="tooltip" title="{{ __('Convert to POS') }}"
                                                                    data-original-title="{{ __('Detail') }}">
                                                                    <i class="ti ti-exchange text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @else
                                                        {{-- @can('show pos') --}}
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{ route('pos.show', \Crypt::encrypt($quotation->converted_pos_id)) }}" class="mx-3 btn btn-sm align-items-center"
                                                                    data-bs-toggle="tooltip"
                                                                    title="{{ __('Already convert to POS') }}"
                                                                    data-original-title="{{ __('Detail') }}">
                                                                    <i class="ti ti-file text-white"></i>
                                                                </a>
                                                            </div>
                                                        {{-- @endcan --}}
                                                    @endif

                                                    @can('edit quotation')
                                                        <div class="action-btn bg-primary ms-2">
                                                            <a href="{{ route('quotation.edit', \Crypt::encrypt($quotation->id)) }}"
                                                                class="mx-3 btn btn-sm align-items-center"
                                                                data-bs-toggle="tooltip" title="Edit"
                                                                data-original-title="{{ __('Convert to JobCard') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('delete quotation')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['quotation.destroy', $quotation->id],
                                                                'class' => 'delete-form-btn',
                                                                'id' => 'delete-form-' . $quotation->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                                data-original-title="{{ __('Delete') }}"
                                                                data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="document.getElementById('delete-form-{{ $quotation->id }}').submit();">
                                                                <i class="ti ti-trash text-white"></i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endcan
                                                </span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
               
                @else
                 <div class="row mt-2">
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                            <h4>{{__('quotation')}}</h4>
                        </div>
                       
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                            <h4 class="invoice-number">Record not found</h4>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
    <div class="content" id="content3">  
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                     @if($quotation_o)
                    <div class="row mt-2">
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                            <h4>{{__('order')}}</h4>
                        </div>
                       
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                            <h4 class="invoice-number">{{ $quotation->is_revised != null?Auth::user()->orderNumberFormat($quotation_o->quotation_id):Auth::user()->orderNumberFormat($quotation_o->quotation_id) }}</h4>
                        </div>
                       
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <small class="font-style">
                                <strong>{{__('Billed To')}} :</strong><br>
                                @if(!empty($customer->billing_name))
                                    {{!empty($customer->billing_name)?$customer->billing_name:''}}<br>
                                    {{!empty($customer->billing_address)?$customer->billing_address:''}}<br>
                                    {{!empty($customer->billing_city)?$customer->billing_city:'' .', '}}<br>
                                    {{!empty($customer->billing_state)?$customer->billing_state:'',', '}},
                                    {{!empty($customer->billing_zip)?$customer->billing_zip:''}}<br>
                                    {{!empty($customer->billing_country)?$customer->billing_country:''}}<br>
                                    {{!empty($customer->billing_phone)?$customer->billing_phone:''}}<br>
                                    @if($settings['vat_gst_number_switch'] == 'on')
                                    <strong>{{__('Tax Number ')}} : </strong>{{!empty($customer->tax_number)?$customer->tax_number:''}}
                                    @endif
                                @else
                                    -
                                @endif
                            </small>
                        </div>
                        <div class="col-4">
                            @if(App\Models\Utility::getValByName('shipping_display')=='on')
                                <small>
                                    <strong>{{__('Shipped To')}} :</strong><br>
                                        @if(!empty($customer->shipping_name))
                                        {{!empty($customer->shipping_name)?$customer->shipping_name:''}}<br>
                                        {{!empty($customer->shipping_address)?$customer->shipping_address:''}}<br>
                                        {{!empty($customer->shipping_city)?$customer->shipping_city:'' . ', '}}<br>
                                        {{!empty($customer->shipping_state)?$customer->shipping_state:'' .', '}},
                                        {{!empty($customer->shipping_zip)?$customer->shipping_zip:''}}<br>
                                        {{!empty($customer->shipping_country)?$customer->shipping_country:''}}<br>
                                        {{!empty($customer->shipping_phone)?$customer->shipping_phone:''}}<br>
                                    @else
                                    -
                                    @endif
                                </small>
                            @endif
                        </div>
                        <div class="col-2">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="me-4">
                                    <small>
                                        <strong>{{__('Issue Date')}} :</strong>
                                        {{\Auth::user()->dateFormat($quotation->quotation_date)}}<br><br>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                           
                            @foreach($iteams as $key =>$iteam)
                             <div id="{{ $iteam->product()->id }}" class="product_barcode product_barcode_hight_de" data-skucode="{{ $iteam->product()->sku }}"></div>
                             @endforeach
                            
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-dark" >#</th>
                                        <th class="text-dark">{{__('Items')}}</th>
                                        <th class="text-dark">{{__('Quantity')}}</th>
                                        <th class="text-dark">{{__('Price')}}</th>
                                        <th class="text-dark">{{__('Tax')}}</th>
                                        <th class="text-dark">{{__('Tax Amount')}}</th>
                                        <th class="text-dark">{{__('Total')}}</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $totalQuantity=0;
                                        $totalRate=0;
                                        $totalTaxPrice=0;
                                        $totalDiscount=0;
                                        $subTotal = 0;
                                        $total = 0;
                                        $taxesData=[];
                                    @endphp
                                    @foreach($iteams as $key =>$iteam)
                                        @if(!empty($iteam->tax))
                                            @php
                                                $taxes=App\Models\Utility::tax($iteam->tax);
                                                $totalQuantity+=$iteam->quantity;
                                                $totalRate+=$iteam->price;
                                                $totalDiscount+=$iteam->discount;

                                                foreach($taxes as $taxe){

                                                    $taxDataPrice=App\Models\Utility::taxRate($taxe->rate,$iteam->price,$iteam->quantity);
                                                    if (array_key_exists($taxe->name,$taxesData))
                                                    {
                                                        $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                                    }
                                                    else
                                                    {
                                                        $taxesData[$taxe->name] = $taxDataPrice;
                                                    }
                                                }
                                            @endphp
                                        @endif
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{!empty($iteam->product())?$iteam->product()->name:''}}</td>
                                            <td>{{$iteam->quantity}}</td>
                                            <td>{{\Auth::user()->priceFormat($iteam->price)}}</td>
                                            <td>
                                                @if(!empty($iteam->tax))
                                                    <table>
                                                        @php
                                                            $totalTaxRate = 0;
                                                            $totalTaxPrice = 0;
                                                        @endphp
                                                        @foreach($taxes as $tax)
                                                            @php
                                                                $taxPrice=App\Models\Utility::taxRate($tax->rate,$iteam->price,$iteam->quantity);
                                                                $totalTaxPrice+=$taxPrice;
                                                            @endphp
                                                            <tr>
                                                                <span class="badge bg-primary">{{$tax->name .' ('.$tax->rate .'%)'}}</span> <br>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{\Auth::user()->priceFormat($totalTaxPrice)}}</td>
                                            <td >{{\Auth::user()->priceFormat(($iteam->price*$iteam->quantity) + $totalTaxPrice)}}</td>
                                        </tr>
                                        @php
                                                $subTotal +=  (($iteam->price*$iteam->quantity) + $totalTaxPrice);
                                                $total = $subTotal - $totalDiscount;
                                    @endphp
                                    @endforeach


                                    <tr>
                                        <td><b>{{__(' Sub Total')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($subTotal)}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{__('Discount')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($totalDiscount)}}</td>
                                    </tr>
                                    <tr class="pos-header">
                                        <td><b>{{__('Total')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($total)}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                     <div class="row mt-2">
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                            <h4>{{__('quotation')}}</h4>
                        </div>
                       
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                            <h4 class="invoice-number">Record not found</h4>
                        </div>
                       
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div></div>
     <div class="content" id="content4" style="display: none;">  
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                     @if($quotation_job)
                    <div class="row mt-2">
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                            <h4>{{__('jobcard')}}</h4>
                        </div>
                       
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                            <h4 class="invoice-number">{{ $quotation->is_revised != null?Auth::user()->jobNumberFormat($quotation_job->quotation_id):Auth::user()->jobNumberFormat($quotation_job->quotation_id) }}</h4>
                        </div>
                       
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <small class="font-style">
                                <strong>{{__('Billed To')}} :</strong><br>
                                @if(!empty($customer->billing_name))
                                    {{!empty($customer->billing_name)?$customer->billing_name:''}}<br>
                                    {{!empty($customer->billing_address)?$customer->billing_address:''}}<br>
                                    {{!empty($customer->billing_city)?$customer->billing_city:'' .', '}}<br>
                                    {{!empty($customer->billing_state)?$customer->billing_state:'',', '}},
                                    {{!empty($customer->billing_zip)?$customer->billing_zip:''}}<br>
                                    {{!empty($customer->billing_country)?$customer->billing_country:''}}<br>
                                    {{!empty($customer->billing_phone)?$customer->billing_phone:''}}<br>
                                    @if($settings['vat_gst_number_switch'] == 'on')
                                    <strong>{{__('Tax Number ')}} : </strong>{{!empty($customer->tax_number)?$customer->tax_number:''}}
                                    @endif
                                @else
                                    -
                                @endif
                            </small>
                        </div>
                        <div class="col-4">
                            @if(App\Models\Utility::getValByName('shipping_display')=='on')
                                <small>
                                    <strong>{{__('Shipped To')}} :</strong><br>
                                        @if(!empty($customer->shipping_name))
                                        {{!empty($customer->shipping_name)?$customer->shipping_name:''}}<br>
                                        {{!empty($customer->shipping_address)?$customer->shipping_address:''}}<br>
                                        {{!empty($customer->shipping_city)?$customer->shipping_city:'' . ', '}}<br>
                                        {{!empty($customer->shipping_state)?$customer->shipping_state:'' .', '}},
                                        {{!empty($customer->shipping_zip)?$customer->shipping_zip:''}}<br>
                                        {{!empty($customer->shipping_country)?$customer->shipping_country:''}}<br>
                                        {{!empty($customer->shipping_phone)?$customer->shipping_phone:''}}<br>
                                    @else
                                    -
                                    @endif
                                </small>
                            @endif
                        </div>
                        <div class="col-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="me-4">
                                    <small>
                                        <strong>{{__('Issue Date')}} :</strong>
                                        {{\Auth::user()->dateFormat($quotation->quotation_date)}}<br><br>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-dark" >#</th>
                                        <th class="text-dark">{{__('Items')}}</th>
                                        <th class="text-dark">{{__('Quantity')}}</th>
                                        <th class="text-dark">{{__('Price')}}</th>
                                        <th class="text-dark">{{__('Tax')}}</th>
                                        <th class="text-dark">{{__('Tax Amount')}}</th>
                                        <th class="text-dark">{{__('Total')}}</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $totalQuantity=0;
                                        $totalRate=0;
                                        $totalTaxPrice=0;
                                        $totalDiscount=0;
                                        $subTotal = 0;
                                        $total = 0;
                                        $taxesData=[];
                                    @endphp
                                    @foreach($iteams as $key =>$iteam)
                                        @if(!empty($iteam->tax))
                                            @php
                                                $taxes=App\Models\Utility::tax($iteam->tax);
                                                $totalQuantity+=$iteam->quantity;
                                                $totalRate+=$iteam->price;
                                                $totalDiscount+=$iteam->discount;

                                                foreach($taxes as $taxe){

                                                    $taxDataPrice=App\Models\Utility::taxRate($taxe->rate,$iteam->price,$iteam->quantity);
                                                    if (array_key_exists($taxe->name,$taxesData))
                                                    {
                                                        $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                                    }
                                                    else
                                                    {
                                                        $taxesData[$taxe->name] = $taxDataPrice;
                                                    }
                                                }
                                            @endphp
                                        @endif
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{!empty($iteam->product())?$iteam->product()->name:''}}</td>
                                            <td>{{$iteam->quantity}}</td>
                                            <td>{{\Auth::user()->priceFormat($iteam->price)}}</td>
                                            <td>
                                                @if(!empty($iteam->tax))
                                                    <table>
                                                        @php
                                                            $totalTaxRate = 0;
                                                            $totalTaxPrice = 0;
                                                        @endphp
                                                        @foreach($taxes as $tax)
                                                            @php
                                                                $taxPrice=App\Models\Utility::taxRate($tax->rate,$iteam->price,$iteam->quantity);
                                                                $totalTaxPrice+=$taxPrice;
                                                            @endphp
                                                            <tr>
                                                                <span class="badge bg-primary">{{$tax->name .' ('.$tax->rate .'%)'}}</span> <br>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{\Auth::user()->priceFormat($totalTaxPrice)}}</td>
                                            <td >{{\Auth::user()->priceFormat(($iteam->price*$iteam->quantity) + $totalTaxPrice)}}</td>
                                        </tr>
                                        @php
                                                $subTotal +=  (($iteam->price*$iteam->quantity) + $totalTaxPrice);
                                                $total = $subTotal - $totalDiscount;
                                    @endphp
                                    @endforeach


                                    <tr>
                                        <td><b>{{__(' Sub Total')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($subTotal)}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{__('Discount')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($totalDiscount)}}</td>
                                    </tr>
                                    <tr class="pos-header">
                                        <td><b>{{__('Total')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{\Auth::user()->priceFormat($total)}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                     <div class="row mt-2">
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                            <h4>{{__('quotation')}}</h4>
                        </div>
                       
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                            <h4 class="invoice-number">Record not found</h4>
                        </div>
                       
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div></div>
   
@endsection
@push('script-page')
   <script>
    // $(document).on('change', '.quotation_status', function () {
          
    //         //   var status = $(this).val();
    //             var url = $(this).data('url');
    //         var id = $('#q_id').val();
    //         alert(id)
    //             $.ajax({
    //                 url: url,
    //                 type: 'GET',
                   
    //                 data: {
    //                     'id': id,
                       
    //                 },
    //                 cache: false,
    //                 success: function (data) {

    //                 },
                    
    //             });

            
    //     });
    $(document).ready(function(){
       
        // Add click event listener to tabs
        $('.tab').click(function(){
            
            // Hide all content divs
            $('.content').hide();
            $('.tab').removeClass('active');
            // Get the target id from data attribute
            $(this).addClass('active');
            var targetId = $(this).data('target');
            
            // Show the corresponding content div
            $('#' + targetId).show();
        });
    });
</script>
@endpush