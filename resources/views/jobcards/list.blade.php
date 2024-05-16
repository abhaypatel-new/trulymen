@extends('layouts.admin')
@section('page-title')
    {{__('Manage JobCard')}} 
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
     <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
    /* Hide default arrow */
input[type="text"]::-webkit-input-placeholder {
    color: red;
}
    </style>
@endpush
@push('script-page')
    <script src="{{asset('css/summernote/summernote-bs4.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).on("change", ".change-pipeline select[name=default_pipeline_id]", function () {
            $('#change-pipeline').submit();
        });
        $(document).ready(function() {
           
    // Your CSS styling goes here
     $('#datepicker').datepicker();
     $('.choices__input').css('color', 'red');
        $('.page-header-title').css('display', 'none');
        $('.choices__list--dropdown').css('color', 'dark');
        $('.dataTable-dropdown').css('display', 'none');
        $('.dataTable-input').css('height', '45px');
        $('.dataTable-search').css({'float': 'left','position': 'absolute',
    'margin-top': '-116px',
    'margin-left': '55px',
    'width': '250px',
    'height': '45px'});
        $('.choices__placeholder').attr('placeholder', 'Enter your text here').css('color', 'red');
        $('.dataTable-input').addClass('placeholder-color');
        $('.choices').css('margin-right', '25px');
   
});
    </script>
@endpush
@section('breadcrumb')
   {{-- <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('JobCard')}}</li>--}}
@endsection
{{--@section('action-btn')
    <div class="float-end">
        <a href="{{ route('deals.index') }}" data-bs-toggle="tooltip" title="{{__('Kanban View')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-layout-grid"></i>
        </a>
        <a href="#" data-size="md"  data-bs-toggle="tooltip" title="{{__('Import')}}" data-url="{{ route('deals.file.import') }}" data-ajax-popup="true" data-title="{{__('Import Deal CSV file')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-import"></i>
        </a>
        <a href="{{route('deals.export')}}" data-bs-toggle="tooltip" title="{{__('Export')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-export"></i>
        </a>
        <a href="#" data-size="lg" data-url="{{ route('jobcards.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New JobCard')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection --}}
@section('content')
    
       {{-- <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                <tr>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Price')}}</th>
                                    <th>{{__('Stage')}}</th>
                                    <th>{{__('Users')}}</th>
                                    <th width="300px">{{__('Action')}}</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if(count($deals) > 0)
                               
                                    @foreach ($deals as $deal)
                                    
                                        <tr>
                                            <td>{{ $deal->name }}</td>
                                            <td>{{\Auth::user()->priceFormat($deal->price)}}</td>
                                            <td>{{ $deal->stage_name !=null? $deal->stage_name:'-' }}</td>
                                           
                                            <td>
                                              
                                                    <a href="#" class="btn btn-sm mr-1 p-0 rounded-circle">
                                                        <img alt="image" data-toggle="tooltip" data-original-title="{{$deal->name}}" @if($deal->avatar) src="{{asset('/storage/uploads/avatar/'.$deal->avatar)}}" @else src="{{asset('/storage/uploads/avatar/avatar.png')}}" @endif class="rounded-circle " width="25" height="25">
                                                    </a>
                                               
                                            </td>
                                            @if(\Auth::user()->type != 'Client')
                                                <td class="Action">
                                                    <span>
                                                        @can('view deal')
                                                            @if($deal->is_active)
                                                                <div class="action-btn bg-warning ms-2">
                                                                <a href="{{route('deals.show',$deal->id)}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-size="xl" data-bs-toggle="tooltip" title="{{__('View')}}" data-title="{{__('Lead Detail')}}">
                                                                    <i class="ti ti-eye text-white"></i>
                                                                </a>
                                                            </div>
                                                            @endif
                                                        @endcan
                                                        @can('edit deal')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ URL::to('deals/'.$deal->id.'/edit') }}" data-ajax-popup="true" data-size="xl" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Lead Edit')}}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('delete deal')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['deals.destroy', $deal->id],'id'=>'delete-form-'.$deal->id]) !!}
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>

                                                                {!! Form::close() !!}
                                                             </div>
                                                        @endif
                                                    </span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="font-style">
                                        <td colspan="6" class="text-center">{{ __('No data available in table') }}</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
         <div class="card" style="padding: 40px 10px 0px 0px;">
         <div class="row">
                 {{ Form::open(array('url' => 'jobcard/search')) }}
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
                       {{ Form::select('user_id', $users,null, array('class' => 'form-control select2','id'=>'choices-multiple6')) }}
                   </div>
                    <div class="col-sm-3 form-group">
                       {{ Form::select('products', $products,'null', array('class' => 'form-control select2', 'id'=>'choices-multiple7')) }}
                   </div>
                  
            </div>
             {{Form::close()}}
        <div class="col-md-12">
             <h4 style="margin-bottom: -20px;"><a href="#" class="text-dark" style="font-weight: bolder;margin-left: 20px;">{{__('JobCard Request')}}</a>
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
                                    <th> {{ __('Card Request') }}</th>
                                    @if (Gate::check('edit quotation') || Gate::check('delete quotation') || Gate::check('convert quotation'))
                                        <th> {{ __('Order Acknowledge') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                                        @php
                                         $gtotal =  0;
                                         $total = 0;
                                        @endphp
                                @foreach ($quotations as $quotation)
                                    <tr>
                                       
                                        <td>
                                            <div class="number-color ms-2" style="font-size:12px;background-color: {{ ($quotation->status == 0)?'#ffa21d':'#6fd943'}}">
                                                   {{ $quotation->id }}</div> 
                                           </td>
                                        <td class="Id">
                                            <a href="{{ route('quotation.show', \Crypt::encrypt($quotation->id)) }}"
                                                class="btn btn-outline-primary">{{ Auth::user()->quotationNumberFormat($quotation->quotation_id) }}</a>
                                        </td>
                                        
                                        @if(count($quotation->items)>0)
                                       
                                       
                                        
                                           
                                         <td>
                                              @foreach($quotation->items as $item)
                                        @php
                                         
                                         $quoteProduct = \App\Models\QuotationProduct::where('quotation_id', $quotation->quotation_id)->first();
                                         $products = \App\Models\ProductService::find($quoteProduct->product_id);
                                         $gtotal += $item->price * (!empty($quoteProduct)?$quoteProduct->quantity:1);
                                        @endphp
                                                  {{$products->name}} 
                                                   @if (!$loop->last)
                                                        ,
                                                    @endif
                                                     @endforeach
                                                  </td>
                                       
                                        
                                        @if(isset($quoteProduct->tax))
                                        @php 
                                        $taxProduct = \App\Models\Tax::find($quoteProduct->tax);
                                        @endphp
                                        <td>{{!empty($taxProduct)?(($gtotal * $taxProduct->rate/100) + $total):$gtotal }}</td>
                                        @else
                                        <td> {{$gtotal}}</td>
                                        @endif
                                       
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
                                        @if (Gate::check('edit quotation') || Gate::check('delete quotation') || Gate::check('convert quotation'))
                                            <td class="Action">
                                                <span>

                                                    @if ($quotation->is_converted == 0)
                                                        {{--@can('convert quotation')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{ route('poses.index', $quotation->id) }}"
                                                                    class="mx-3 btn btn-sm align-items-center"
                                                                    data-bs-toggle="tooltip" title="{{ __('Convert to POS') }}"
                                                                    data-original-title="{{ __('Detail') }}">
                                                                    <i class="ti ti-exchange text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endcan --}}
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
                                                     <div class="ms-2">
                                                            <a href="#"
                                                                class="btn btn-lg align-items-center text-light"
                                                                data-bs-toggle="tooltip" title="Send to email"
                                                                data-original-title="{{ __('Send to Email') }}" style="background-color:#009900;border-radius: 20px;">
                                                               {{ __('Send to Email') }}
                                                            </a>
                                                        </div>                
                                                    {{--@can('edit quotation')
                                                        <div class="action-btn bg-primary ms-2">
                                                            <a href="{{ route('quotation.edit', \Crypt::encrypt($quotation->id)) }}"
                                                                class="mx-3 btn btn-sm align-items-center"
                                                                data-bs-toggle="tooltip" title="Edit"
                                                                data-original-title="{{ __('Convert to JobCard') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan --}}
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
