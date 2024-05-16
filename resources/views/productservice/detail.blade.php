@extends('layouts.admin')
@section('page-title')
   {{__('Product Details')}}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/dropzone.min.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('css/summernote/summernote-bs4.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
    // Your CSS styling goes here
    
      
        $(document).on('change', '.select-status', function () {
            var id = $(this).val();
            var pid = $('#pid').val();
    
            var url = '{{ route('product.status') }}'
           $.ajax({
                type: 'GET',
                url: url,
                data: {
                   
                    'id': id,
                    'pid':pid,
                    'session_key': session_key
                },
                success: function (data) {
                    // console.log(data)
                 
                    if(data.code==200){
                        show_toastr('success', data.msg, 'success');
                    }else{
                        show_toastr('error', response.msg, 'error'); 
                    }
                   
                
                    
            
                }
            });
        });
    });
     
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('productservice.index')}}">{{__('Products')}}</a></li>
    <li class="breadcrumb-item"> {{__('Product Details')}} </li>
@endsection
@section('action-btn')
    <div class="float-end row">
        <div class="col-md-4 form-group">
                    <select class="form-control select-status  text-warning" name="priority_id">
                           <option value="">Ticket Priority</option>
                           <option value="Low" {{$productService->ticket_priority == 'Low'?'selected':''}}>Low</option>
                           <option value="Medium" {{$productService->ticket_priority == 'Medium'?'selected':''}}>Medium</option>
                           <option value="High" {{$productService->ticket_priority == 'High'?'selected':''}}>High</option>
                    </select>
        </div>
        <div class="col-md-4 form-group">
                    <select class="form-control select-status  text-info" name="priority_id">
                           <option value="">Ticket Status</option>
                           <option value="Open" {{$productService->ticket_status == 'Open'?'selected':''}}>Open</option>
                           <option value="Hold" {{$productService->ticket_status == 'Hold'?'selected':''}}>Hold</option>
                           <option value="On-Going" {{$productService->ticket_status == 'On-Going'?'selected':''}}>On-Going</option>
                           <option value="Closed" {{$productService->ticket_status == 'Closed'?'selected':''}}>Closed</option>
                    </select>
        </div>
        <div class="col-md-4 form-group">
                    <select class="form-control select-status text-success" name="priority_id">
                           <option value="">Order Status</option>
                           <option value="1" {{$productService->status == 1?'selected':''}}>Received</option>
                           <option value="2" {{$productService->status == 2?'selected':''}}>Testing</option>
                           <option value="3" {{$productService->status == 3?'selected':''}}>Repairing</option>
                           <option value="4" {{$productService->status == 4?'selected':''}}>Resolved</option>
                           <option value="5" {{$productService->status == 5?'selected':''}}>Dispatch</option>
                           
                    </select>
        </div>
      <input type="hidden" name="pid" id="pid" value="{{$productService->id}}">
    </div>
@endsection
@section('content')
<div class="card" style="margin-top:20px;">
<div class="card-body">
     <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('name',$productService->name, array('class' => 'form-control','required'=>'required','readonly')) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('model', __('Model'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('model', $productService->model, array('class' => 'form-control','required'=>'required','readonly')) }}
            </div>
        </div>
       
       <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('hsn_code', __('Odering code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('hsn_code', $productService->hsn_code, array('class' => 'form-control','required'=>'required','readonly')) }}
            </div>
        </div>
       
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sale_price', __('Sale Price'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::number('sale_price', $productService->sale_price, array('class' => 'form-control','required'=>'required','step'=>'0.01','readonly')) }}
            </div>
        </div>
       
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('purchase_price', __('Purchase Price'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::number('purchase_price', $productService->purchase_price, array('class' => 'form-control','required'=>'required','step'=>'0.01','readonly')) }}
            </div>
        </div>
       

      

       

        <div class="col-md-6 form-group">
            {{Form::label('pro_image',__('Product Image'),['class'=>'form-label'])}}
            <div class="choose-file ">
                <label for="pro_image" class="form-label">
                    
                    <img id="image"  class="mt-3" width="100" src="@if($productService->pro_image){{asset(Storage::url('uploads/pro_image/'.$productService->pro_image))}}@else{{asset(Storage::url('uploads/pro_image/user-2_1654779769.jpg'))}}@endif" />
                </label>
            </div>
        </div>



      {{--  <div class="col-md-6">
            <div class="form-group">
                <label class="d-block form-label">{{__('Type')}}</label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input type" id="customRadio5" name="type" value="product" @if($productService->type=='product') checked @endif >
                            <label class="custom-control-label form-label" for="customRadio5">{{__('Product')}}</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input type" id="customRadio6" name="type" value="service" @if($productService->type=='service') checked @endif >
                            <label class="custom-control-label form-label" for="customRadio6">{{__('Service')}}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}

        <div class="form-group col-md-6">
            {{ Form::label('quantity', __('Quantity'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('quantity',$productService->quantity, array('class' => 'form-control','required'=>'required','readonly')) }}
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {!! Form::textarea('description', $productService->description, ['class'=>'form-control','rows'=>'2', 'readonly']) !!}
        </div>
         
    </div>
    </div>
    </div>
@endsection

