 @extends('layouts.admin')
@section('page-title')
    {{__('Manage Product & Services')}}
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
    'margin-left': '65px',
    'width': '250px',
    'height': '45px'});
        $('.choices__placeholder').attr('placeholder', 'Enter your text here').css('color', 'red');
         $('.select').attr('placeholder', 'Enter your text here').css('color', 'red');
        $('.dataTable-input').addClass('placeholder-color');
    
     $('.form-group').css('margin-bottom', '0px');
    $('.choices').css('margin-right', '25px');
    $(document).on("click", ".select", function () {
        
         $(this).css('color', 'black');
    });
    
});
      
    </script>
@endpush

@section('breadcrumb')
    {{--<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Product & Services')}}</li>--}}
@endsection
{{--@section('action-btn')
    <div class="float-end">
        <a href="#" data-size="md"  data-bs-toggle="tooltip" title="{{__('Import')}}" data-url="{{ route('productservice.file.import') }}" data-ajax-popup="true" data-title="{{__('Import product CSV file')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-import"></i>
        </a>
        <a href="{{route('productservice.export')}}" data-bs-toggle="tooltip" title="{{__('Export')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-export"></i>
        </a>

        <a href="{{ route('productservice.create') }}" data-bs-toggle="tooltip" title="{{__('Create New Product')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>

    </div>
@endsection --}}

@section('content')
<div class="card">
<div class="row" style="padding:21px;">   
    <div class="col-md-8">
          <a href="{{ route('productservice.create') }}" data-bs-toggle="tooltip" title="{{__('Create New Product')}}" class="btn btn-outline-light text-primary" style="margin-left: 15px;box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.19);
  text-align: center;">
            <i class="ti ti-plus" style="border: 1px solid;border-radius:5px;"></i>
             {{__('Create New Product')}}
        </a>
       
        </div>
        <div class="col-md-4">
       
    </div>
    </div>
    <div class="row">
         {{ Form::open(array('url' => 'product/searching')) }}
               <div class="row">
                  
                    <div class="col-sm-1 form-group">
                        <span style="float: inline-end;"><i class="ti ti-search" style="position: absolute;margin-left: 14px;margin-top: 12px;z-index: 10;color: white;"></i><input type="submit" title="{{__('Search')}}" data-bs-toggle="tooltip" class="btn btn-danger text-danger form-control" style="border: none;width: 40px;" onmouseover="this.style.backgroundColor='#ff3a6e';"></span>
                      
                   </div>
                   <div class="col-sm-2 form-group">
                        <input type="text" class="form-control text-primary" name="date" value="" placeholder="Date" title="{{__('Date')}}" data-bs-toggle="tooltip" id="datepicker" style="height: 45px;"><i class="bx bx-calendar text-primary" style="position: absolute;margin-left: 125px;margin-top: -28px;"></i>
                       {{--<img src="{{ asset('assets/images/date-icon.png') }}" width="30" alt="india" style="position: absolute;margin-top: -37px;margin-left: 110px;" id="dateIcon"/>--}}
                   </div>
                    <div class="col-sm-3 form-group">
                       <!--<input type="text" class="form-control btn btn-warning"name="search" value="Assigned By">-->
                        {{ Form::select('user_id', $users,null, array('class' => 'form-control select2','id'=>'choices-multiple3', 'style' => 'height: 45px')) }}
                   </div>
                  
                   <div class="col-sm-2 form-group" style="margin-left: -22px;">
                       {{ Form::select('status_id', $orderstatus,null, array('class' => 'form-control select')) }}
                   </div>
                   <div class="col-sm-2 form-group">
                       {{ Form::select('ticket_status_id', $ticketstatus,null, array('class' => 'form-control select')) }}
                   </div>
                   <div class="col-sm-2 form-group">
                       <select class="form-control select" name="priority_id">
                           <option value="0">Ticket Priority</option>
                           <option value="1">Low</option>
                           <option value="2">Medium</option>
                           <option value="3">High</option>
                           </select>
                   </div>
                  
            </div>
             {{Form::close()}}
        <div class="col-sm-12">
            <div class=" mt-2 {{isset($_GET['category'])?'show':''}}" id="multiCollapseExample1">
                
                    <div class="card-body">
                        {{ Form::open(['route' => ['productservice.index'], 'method' => 'GET', 'id' => 'product_service']) }}
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6">
                                <div class="btn-box">
                                    {{ Form::label('category', __('Category'),['class'=>'form-label']) }}
                                    {{ Form::select('category', $category, null, ['class' => 'form-control select','id'=>'choices-multiple', 'required' => 'required']) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                   onclick="document.getElementById('product_service').submit(); return false;"
                                   data-bs-toggle="tooltip" title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('productservice.index') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                   title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off "></i></span>
                                </a>
                            </div>

                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('No.')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Model')}}</th>
                                <th>{{__('Specification Order')}}</th>
                                <th>{{__('Sale Price')}}</th>
                                <th>{{__('Purchase Price')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Ticket Priority')}}</th>
                                <th>{{__('Ticket Status')}}</th>
                                <th>{{__('Order Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                                @php 
                                                
                                                $width = 2; // Desired width
                                                $paddingChar = '0'; // Character used for padding
                                              
                                                @endphp
                                
                            @foreach ($productServices as $productService)
                                               
                                <tr class="font-style">
                                     <td> <div class="number-color ms-2" style="font-size:12px;background-color: {{($productService->status == 1)?'#9199a0':(($productService->status == 4)?'#0AA350':(($productService->status == 5)?'#693599':(($productService->status == 3)?'#24A9F9':'#E91C2B')));}}">
                                                   {{ str_pad($productService->id, $width, $paddingChar, STR_PAD_LEFT) }}</div></td>
                                    <td>{{ $productService->name}}</td>
                                    <td>{{ !empty($productService->group)?$productService->group->name:'' }}</td>
                                    <td>{{ $productService->hsn_code == ''?'-':$productService->hsn_code}}</td>
                                    <td>{{ \Auth::user()->priceFormat($productService->sale_price) }}</td>
                                    <td>{{  \Auth::user()->priceFormat($productService->purchase_price )}}</td>
                                    <td>{{$productService->quantity}}</td>
                                    <td>{{$productService->ticket_priority}}</td>
                                    <td>{{$productService->ticket_status}}</td>
                                    <td>{{ ($productService->status == 1)?'Received':(($productService->status == 4)?'Resolved':(($productService->status == 5)?'Dispatch':(($productService->status == 3)?'Reporting':'Testing'))) }}</td>
                                    @if(Gate::check('edit product & service') || Gate::check('delete product & service'))
                                        <td class="Action">

                                            <div class="action-btn bg-light ms-2">
                                                <a href="{{ route('productservice.detail',$productService->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Product Details')}}" data-title="{{__('Product Details')}}">
                                                    <i class="ti ti-eye text-dark"></i>
                                                </a>
                                            </div>

                                            @can('edit product & service')
                                                <div class="action-btn bg-light ms-2">
                                                    <a href="{{ route('productservice.edit',$productService->id) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Edit Product')}}">
                                                        <i class="ti ti-pencil text-dark"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                           {{-- @can('delete product & service')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['productservice.destroy', $productService->id],'id'=>'delete-form-'.$productService->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti ti-trash text-white"></i></a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan --}}
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

@push('script-page')
<script>

 $(document).ready(function() {
    // Your CSS styling goes here
    $('.choices__input ').css('color', 'red');
    // $(document).on("blur", "#dateInput", function () {
    //     alert("dfds")
    //     $(this).type('date');
    //     $("#dateIcon").css('display', 'none');
    // });
    
});

</script>
@endpush

