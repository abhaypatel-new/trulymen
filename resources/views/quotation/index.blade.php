@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Quotation') }}
@endsection

@section('breadcrumb')
 
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
    $(document).ready(function() {
    // Your CSS styling goes here
        $('#datepicker').datepicker();
        $('.choices__input').css('color', 'red');
        $('.page-header-title').css('display', 'none');
        $('.choices__list--dropdown').css('color', 'dark');
        // $('.dataTable-top').css('display', 'none');
         $('.dataTable-dropdown').css('display', 'none');
         $('.dataTable-input').css({'height': '45px', 'width': '250px'});
        $('.choices__placeholder').attr('placeholder', 'Enter your text here').css('color', 'red');
        $('.choices').css('margin-right', '25px');
        $('#choices-multiple1').css('color', 'red');
        $('#choices-multiple1').click(function() {
           
     $('#choices-multiple1').css('color', '#000000');
    });
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





@section('content')
<div class="card">
    <div class="row" style="padding:21px;">   
    <div class="col-md-8">
          <a href="{{ route('quotation.create') }}" data-bs-toggle="tooltip" title="{{__('Create New Lead')}}" class="btn btn-outline-light text-primary" style="margin-left: 15px;box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.19);
  text-align: center;">
            <i class="ti ti-plus" style="border: 1px solid;border-radius:5px;"></i>
             {{__('Add New Quotation')}}
        </a>
        {{--<a href="{{ route('leads.index') }}" data-bs-toggle="tooltip" title="{{__('Kanban View')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-layout-grid"></i>
        </a>--}}
        </div>
        <div class="col-md-4">
       
    </div>
    </div>
     @php
     $status = [
     '0' =>'Quote Status',
     'Draft',
     'Waiting for approval',
     'Approved',
     'Sent'
     ];
     @endphp
    <div class="row">
        
         {{ Form::open(array('url' => 'quotation/search')) }}
               <div class="row">
                  
                    <div class="col-sm-1 form-group">
                        <span style="float: inline-end;"><i class="ti ti-filter" style="position: absolute;margin-left: 14px;margin-top: 12px;z-index: 10;color: white;"></i><input type="submit" title="{{__('Search')}}" data-bs-toggle="tooltip" class="btn btn-primary text-primary form-control" style="border: none;width: 40px;" onmouseover="this.style.backgroundColor='#ff3a6e';"></span>
                      
                   </div>
                   <div class="col-sm-2 form-group">
                       
                       <input type="text" class="form-control text-primary" name="date" value="{{$chkdate != ''?$chkdate:'Date'}}" placeholder="Date" title="{{__('Date')}}" data-bs-toggle="tooltip" id="datepicker"><i class="bx bx-calendar text-primary" style="position: absolute;margin-left: 110px;margin-top: -28px;"></i>
                       {{--<img src="{{ asset('assets/images/date-icon.png') }}" width="30" alt="india" style="position: absolute;margin-top: -37px;margin-left: 110px;" id="dateIcon"/>--}}
                   </div>
                    <div class="col-sm-3 form-group">
                       <!--<input type="text" class="form-control btn btn-warning"name="search" value="Assigned By">-->
                        {{ Form::select('user_id', $users,null, array('class' => 'form-control select2','id'=>'choices-multiple3')) }}
                   </div>
                    <div class="col-sm-3 form-group">
                       {{ Form::select('products', $products,'null', array('class' => 'form-control select2', 'id'=>'choices-multiple2')) }}
                   </div> <div class="col-sm-3 form-group">
                      <select class="form-control select" id="choices-multiple1" name="status_id" style="color: rgb(0, 0, 0);">
                      <option value="">Quote Status</option>
                      <option value="0"{{$chkstatus == 0?'selected':''}}>Draft</option>
                      <option value="1"{{$chkstatus == 1?'selected':''}}>Waiting for approval</option>
                      <option value="2"{{$chkstatus == 2?'selected':''}}>Approved</option>
                      <option value="3"{{$chkstatus == 3?'selected':''}}>Sent</option>
                      </select>
                   </div>
                  
            </div>
             {{Form::close()}}
        <div class="col-md-12">
            <h4 class=""><a href="#" class="text-dark" style="font-weight: bolder;margin-left: 20px;margin-top: 40px;position:absolute;margin-bottom: -20px;">{{__('Recent Search')}}</a>
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
                                    <th> {{ __('Quote Status') }}</th>
                                    @if (Gate::check('edit quotation') || Gate::check('delete quotation') || Gate::check('convert quotation'))
                                        <th> {{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                            
                                        @php
                                         $gtotal =  0;
                                         
                                        @endphp
                                @foreach ($quotations as $quotation)
                                    <tr>
                                       
                                        <td>
                                            <div class="number-color ms-2" style="font-size:12px;background-color: {{ ($quotation->status == 1)?'#ffa21d':'#6fd943'}}">
                                                   {{ $quotation->id }}</div> 
                                           </td>
                                        <td class="Id">
                                            <a href="{{ route('quotation.show', \Crypt::encrypt($quotation->id)) }}"
                                                class="btn btn-outline-primary">{{ Auth::user()->quotationNumberFormat($quotation->id) }}</a>
                                        </td>
                                        
                                        @if(count($quotation->items)>0)
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
                                        
                                        
                                        @if(isset($quoteProduct->tax))
                                        @php 
                                        $taxProduct = \App\Models\Tax::find($quoteProduct->tax);
                                        @endphp
                                        <td>{{!empty($taxProduct)?(($gtotal * $taxProduct->rate/100) + $gtotal):$gtotal }}</td>
                                        @else
                                        <td> {{$gtotal}}</td>
                                        @endif
                                        
                                        @else
                                        
                                        <td>-</td>
                                        <td>-</td>
                                        @endif
                                        @php
                                        $owner = \App\Models\User::find($quotation->created_by);
                                        @endphp
                                        <td>{{ Auth::user()->dateFormat($quotation->quotation_date) }}</td>
                                        <td> {{ !empty($quotation->created_by)?$owner->name:'-' }} </td>
                                       
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
                                                    
                                                            
                                                                <div class="action-btn bg-light ms-2">
                                                                <a href="{{route('quotation.show', \Crypt::encrypt($quotation->id))}}" class="mx-3 d-inline-flex align-items-center"  data-size="xl" data-bs-toggle="tooltip" title="{{__('View')}}" data-title="{{__('Quotation Detail')}}">
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
                                                   {{-- @can('delete quotation')
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
