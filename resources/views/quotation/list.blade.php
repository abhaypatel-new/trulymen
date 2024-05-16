@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Order') }}
@endsection
@section('breadcrumb')
    {{--<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Order') }}</li> --}}
@endsection
@push('css-page')
   
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
    /* Hide default arrow */
input[type="text"]::-webkit-input-placeholder {
    color: red;
}
    </style>
@endpush
@push('script-page')
   
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
    $(document).ready(function() {
    // Your CSS styling goes here
     $('#datepicker').datepicker();
     $('.choices__input').css('color', 'red');
        $('.page-header-title').css('display', 'none');
        $('.choices__list--dropdown').css('color', 'dark');
        $('.dataTable-dropdown').css('display', 'none');
         $('.dataTable-input').css('height', '45px');
        $('.dataTable-search').css({'float': 'left','position': 'absolute',
    'margin-top': '-145px',
    'margin-left': '55px',
    'width': '250px',
    'height': '45px'});
        $('.choices__placeholder').attr('placeholder', 'Enter your text here').css('color', 'red');
        $('.dataTable-input').addClass('placeholder-color');
    
    $('.choices').css('margin-right', '25px');
    // $(document).on("blur", "#dateInput", function () {
    //     alert("dfds")
    //     $(this).type('date');
    //     $("#dateIcon").css('display', 'none');
    // });
    
});
        $('.copy_link').click(function(e) {
            e.preventDefault();
            var copyText = $(this).attr('href');

            document.addEventListener('copy', function(e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('success', 'Url copied to clipboard', 'success');
        });
    </script>
@endpush


@section('action-btn')
    <div class="float-end">


        {{--        <a href="{{ route('bill.export') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Export')}}"> --}}
        {{--            <i class="ti ti-file-export"></i> --}}
        {{--        </a> --}}

        @can('create quotation')
            {{-- <a href="{{ route('quotations.create', 0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
            <a href="{{ route('quotation.create') }}"
                data-bs-toggle="tooltip" data-title="{{ __('Quotataion Create') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>--}}
        @endcan
    </div>
@endsection

 
@section('content')
  <div class="card" style="padding: 40px 10px 0px 0px;">
    <div class="row">
         {{ Form::open(array('url' => 'order/search')) }}
               <div class="row" style="padding-bottom: 20px;">
                  
                    <div class="col-sm-1 form-group">
                        <span style="float: inline-end;"><i class="ti ti-filter" style="position: absolute;margin-left: 14px;margin-top: 12px;z-index: 10;color: white;"></i><input type="submit" title="{{__('Search')}}" data-bs-toggle="tooltip" class="btn btn-primary text-primary form-control" style="border: none;width: 40px;" onmouseover="this.style.backgroundColor='#ff3a6e';"></span>
                        
                      
                   </div>
                   <div class="col-sm-3 form-group">
                      
                       </div>
                   <div class="col-sm-2 form-group">
                       
                       <input type="text" class="form-control text-primary" name="date" value="Date" placeholder="Date" title="{{__('Date')}}" data-bs-toggle="tooltip" id="datepicker" style="height: 45px;"><i class="bx bx-calendar text-primary" style="position: absolute;margin-left: 135px;margin-top: -28px;"></i>
                       {{--<img src="{{ asset('assets/images/date-icon.png') }}" width="30" alt="india" style="position: absolute;margin-top: -37px;margin-left: 140px;" id="dateIcon"/>--}}
                   </div>
                    
                    <div class="col-sm-3 form-group">
                       {{ Form::select('products', $products,'null', array('class' => 'form-control select2', 'id'=>'choices-multiple7')) }}
                   </div> <div class="col-sm-3 form-group">
                       {{ Form::select('status_id', $status,null, array('class' => 'form-control select2','id'=>'choices-multiple6')) }}
                   </div>
                  
            </div>
             {{Form::close()}}
        <div class="col-md-12">
           <h4 class="m-b-10"><a href="#" class="text-dark" style="font-weight: bolder;margin-left: 20px;">{{__('Recent Search')}}</a>
</h4>
                <div class="card-body table-border-style">
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
                                    <th> {{ __('Status') }}</th>
                                     <th> {{ __('Order Status') }}</th>
                                    @if (Gate::check('edit quotation') || Gate::check('delete quotation') || Gate::check('convert quotation'))
                                        <th> {{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                                        @php
                                         
                                         $productNames= [];
                                          $totalPrice = 0;
                                          $gtotal = 0;
                                        @endphp        
                                @foreach ($quotations as $quotation)
                                    <tr>
                                       
                                        <td>
                                            <div class="number-color ms-2" style="font-size:12px;background-color: {{ ($quotation->status == 1)?'#ffa21d':'#6fd943'}}">
                                                   {{ $quotation->id }}</div> 
                                           </td>
                                        <td class="Id">
                                            <a href="{{ route('quotation.order.view', \Crypt::encrypt($quotation->id)) }}"
                                                class="btn btn-outline-primary">{{ Auth::user()->quotationNumberFormat($quotation->quotation_id) }}</a>
                                        </td>
                                        
                                        @if(count($quotation->items)>0)
                                       
                                        @foreach($quotation->items as $item)
                                        @php
                                         
                                         $quoteProduct = \App\Models\QuotationProduct::where('quotation_id', $quotation->quotation_id)->first();
                                         $product = \App\Models\ProductService::find($item->id);
                                         $gtotal += $item->price * (!empty($quoteProduct)?$quoteProduct->quantity:1);
                                        
                                         
                                        @endphp
                                       
                                        @endforeach
                                         <td>
                                           @foreach($quotation->items as $item)
                                        @php
                                         
                                         $quoteProduct = \App\Models\QuotationProduct::where('quotation_id', $quotation->quotation_id)->first();
                                         $product = \App\Models\ProductService::find($item->product_id);
                                        
                                         $gtotal = $item->price * (!empty($quoteProduct)?$quoteProduct->quantity:1);
                                        @endphp
                                        {{$product->name}} 
                                                   @if (!$loop->last)
                                                        ,
                                                    @endif
                                        @endforeach
                                                  </td>
                                        
                                        @php 
                                       
                                        $productNamesConcatenated = implode(', ', $productNames);
                                        @endphp
                                        
                                        
                                        
                                        <td>{{$gtotal }}</td>
                                       
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
                                       
                                         <td>{{ $quotation->status ==1?'Waiting for Approval':'Approved' }}</td>
                                          <td>{{ $quotation->order_status }}</td>
                                        @if (Gate::check('edit quotation') || Gate::check('delete quotation') || Gate::check('convert quotation'))
                                            <td class="Action">
                                                <span>

                                                    {{--@if ($quotation->is_converted == 0)
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
                                                        @can('show pos')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{ route('pos.show', \Crypt::encrypt($quotation->converted_pos_id)) }}" class="mx-3 btn btn-sm align-items-center"
                                                                    data-bs-toggle="tooltip"
                                                                    title="{{ __('Already convert to POS') }}"
                                                                    data-original-title="{{ __('Detail') }}">
                                                                    <i class="ti ti-file text-white"></i>
                                                                </a>
                                                            </div>
                                                     @endcan 
                                                    @endif--}}
                                                     <div class="action-btn bg-light ms-2">
                                                                <a href="{{ route('quotation.order.view', \Crypt::encrypt($quotation->id)) }}"
                                                                    class="mx-3 btn btn-sm align-items-center"
                                                                    data-bs-toggle="tooltip" title="{{ __('View Quotation') }}"
                                                                    data-original-title="{{ __('Detail') }}">
                                                                    <i class="ti ti-eye text-dark"></i>
                                                                </a>
                                                            </div>
                                                    @can('edit quotation')
                                                        <div class="action-btn bg-light ms-2">
                                                            <a href="{{ route('quotation.edit', \Crypt::encrypt($quotation->id)) }}"
                                                                class="mx-3 btn btn-sm align-items-center"
                                                                data-bs-toggle="tooltip" title="Edit"
                                                                data-original-title="{{ __('Convert to JobCard') }}">
                                                                <i class="ti ti-pencil text-dark"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    {{--@can('delete quotation')
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
                                                    @endcan --}}
                                                </span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
