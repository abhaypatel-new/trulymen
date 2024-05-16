@extends('layouts.admin')
@section('page-title')
 {{__('Edit Leads')}}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/dropzone.min.css')}}">
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
    $('.page-header-title').css('display', 'none');
    });

    </script>
@endpush
@section('breadcrumb')
<h4 class="m-b-10"><a href="{{route('leads.list')}}" class="text-dark" style="font-weight: bolder;"> <i class="bx bx-undo"></i>{{__('Edit Leads')}}</a>
</h4>

   {{-- <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('leads.index')}}">{{__('Lead')}}</a></li>
    <li class="breadcrumb-item">{{__('Edit Leads')}}</li>--}}
@endsection
@section('content')
{{ Form::model($lead, array('route' => array('leads.update', $lead->id), 'method' => 'PUT')) }}
<div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8" style="padding:15px;">
                <h6 class="sub-title"></h6>
                </div>
                
                <div class="col-lg-4 col-md-4 col-sm-4" style="margin-top: -30px;">
                 <span style="float: inline-end;"><i class="ti ti-send" style="position: absolute;margin-left: 5px;margin-top: 14px;z-index: 10;color: white;"></i><input type="submit" value="{{__('Save')}}" title="{{__('Edit Lead')}}" class="btn-sm custom-file-uploadss" style="border: none;"></span>
                </div>
            </div>  
{{--<div class="modal-body">
   
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
        $stages = \App\Models\LeadStage::where('created_by', '=', \Auth::user()->ownerId())->get()->pluck('name', 'id');
      
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['lead']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif
   
    <div class="row">
        <div class="col-6 form-group">
            {{ Form::label('subject', __('Subject'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('user_id', __('User'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('user_id', $users,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('email', __('Email'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::email('email', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('phone', __('Phone'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('phone', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('pipeline_id', __('Pipeline'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('pipeline_id', $pipelines,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('stage_id', __('Stage'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('stage_id', [''=>__('Select Stage')],null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <div class="col-12 form-group">
            {{ Form::label('sources', __('Sources'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('sources[]', $sources,null, array('class' => 'form-control select2','id'=>'choices-multiple2','multiple'=>'')) }}
        </div>
        <div class="col-12 form-group">
            {{ Form::label('products', __('Products'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('products[]', $products,null, array('class' => 'form-control select2','id'=>'choices-multiple1','multiple'=>'')) }}
        </div>
        <div class="col-12 form-group">
            {{ Form::label('notes', __('Notes'),['class'=>'form-label']) }}
            {{ Form::textarea('notes',null, array('class' => 'summernote-simple', 'style' => 'padding-top:50px;')) }}
        </div>
    </div>
</div>--}}

<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
          $stages = \App\Models\LeadStage::where('created_by', '=', \Auth::user()->ownerId())->get()->pluck('name', 'id');
          $customers             = \App\Models\Customer::where('lead_id', $lead->id)->get();
         $types = [
         'Product Inquary',
         'Request for Information'
         ];
        
    @endphp
   {{-- @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['lead']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif --}}
    {{-- end for ai module--}}
    
<div class="row">
    <div class="card">
        <div class="card-body">
           <h6 class="sub-title">{{__('Organization')}}</h6>
            <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                {{Form::label('name',__('Sector'),array('class'=>'form-label')) }}<span class="text-danger">*</span>
                {{Form::text('name',null,array('class'=>'form-control','required'=>'required' ,'placeholder'=>_('Enter Name')))}}
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                {{Form::label('industry',__('Industry'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                {{Form::text('industry_name',null,array('class'=>'form-control','required'=>'required' ,'placeholder'=>_('Enter Name')))}}
            </div>
        </div>
       
         <div class="col-lg-4 col-md-4 col-sm-8">
            <div class="form-group">
                {{ Form::label('products', __('Product'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('products[]', $products,null, array('class' => 'form-control select2','id'=>'choices-multiple1','multiple'=>'')) }}

            </div>
        </div>
       <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('quantity',__('Quantity'),['class'=>'form-label'])}}
                {{Form::number('quantity',null,array('class'=>'form-control' , 'placeholder'=>__('Enter quantity')))}}

            </div>
        </div>
   
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('gst_number',__('Gst Number'),['class'=>'form-label'])}}
                {{Form::text('tax_number',$customer->tax_number,array('class'=>'form-control' , 'placeholder' => __('Enter Gst Number')))}}
            </div>
        </div>
       
    </div>
        </div>
     </div>  
     
        <div class="row">
            
                <div class="col-md-8">
                    <div class="card">
                    <div class="card-body">
                    <h6 class="sub-title">{{__('Contact Person Information')}}</h6>
                    <div class="col-sm-6" style="float: inline-end;">
                        <div class="form-group">
                            {{Form::label('billing_name',__('Name'),array('class'=>'','class'=>'form-label')) }}
                            {{Form::text('billing_name', $customer->billing_name,array('class'=>'form-control' , 'placeholder'=>__('Enter Name')))}}
                        </div>
                    </div>
                    <div class="col-5 form-group">
                        {{ Form::label('email', __('Email'),['class'=>'form-label']) }}
                        {{ Form::text('email', $customer->email, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter email'))) }}
                    </div>
                    <div class="col-sm-6" style="float: inline-end;">
                        <div class="form-group">
                            {{Form::label('billing_phone',__('Phone'),array('class'=>'form-label')) }}
                            {{Form::text('phone',null,array('class'=>'form-control' , 'style' => 'padding-left: 90px;','placeholder'=>__('Enter Phone')))}}
                        </div>
                    </div>
                <div class="col-sm-1" style="float: inline-end; width:0%;">
                        <div class="dropdown" style="padding: 30.5px 1px;">
                  <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;border-radius: 5px 0px 0px 5px;height: 35px;">
                  <a href="#"><img src="{{ asset('assets/images/india.png') }}" width="30" alt="india"/> </a>
                  </button>
                 {{-- <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                   
                    <li><a class="dropdown-item" href="#"><img src="{{ asset('assets/images/uk.png') }}" width="30" alt="uk"/></a></li>
                   
                      </ul>--}}
            </div>
            </div>
           
            <div class="col-sm-6">
                <div class="form-group">
                   {!! Form::label('gender', __('Gender'), ['class' => 'form-label' , 'required' => 'required' ]) !!}<span class="text-danger">*</span>
                    <div class="d-flex radio-check">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="g_male" value="Male" name="billing_gender"
                                                        class="form-check-input" value="Male" {{$customer->gender == 'Male'?'checked':''}}>
                                                    <label class="form-check-label " for="g_male">{{ __('Male') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ms-4 custom-control-inline">
                                                    <input type="radio" id="g_female" value="Female" name="billing_gender"
                                                        class="form-check-input" {{$customer->gender == 'Female'?'checked':''}}>
                                                    <label class="form-check-label "
                                                        for="g_female">{{ __('Female') }}</label>
                                                </div>
                                            </div>
    
                </div>
            </div>
            
             <div class="col-sm-5">
                <div class="form-group">
                   {{ Form::label(null, __('Department'), ['class' => 'form-label', 'style' => 'display: table-column']) }}
                 
                </div>
            </div>
             <div class="col-6 form-group"  style="float: inline-end;">
                {{ Form::label('designation', __('Designation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('billing_designation', $customer->billing_designation, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter Designation'))) }}
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    {{Form::label('department',__('Department'),array('class'=>'','class'=>'form-label')) }}<span class="text-danger">*</span>
                    {{Form::text('billing_department',$customer->billing_department,array('class'=>'form-control' , 'placeholder'=>__('Enter Department')))}}
                </div>
            </div>
           <input name="user_id" value="{{$customer->id}}" type="hidden">
           <input name="pipeline_id" value="{{$lead->pipeline_id}}" type="hidden">
           
            </div>
            </div>
            </div>
             <div class="col-md-4">
                 <div class="card">
                 <div class="card-body">
                  <h6 class="sub-title">{{__('Categories')}}</h6><br>
                 <div class="col-sm-12">
                 <div class="form-group">
                    
                     {{ Form::label('stage_id', __('Stage'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                 {{ Form::select('stage_id', $stages,null, array('class' => 'form-control select','required'=>'required')) }}
                </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    {{ Form::label('sources', __('Sources'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                   {{ Form::select('sources[]', $sources,null, array('class' => 'form-control select','required'=>'required')) }}
    
                </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    {{ Form::label('type', __('Request Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                   {{ Form::select('request_id', $types,null, array('class' => 'form-control select','required'=>'required')) }}
    
                </div>
                </div>
               </div>
               </div>
            </div>
        </div>
  @if(count($customers)>0)
  @foreach($customers->slice(1) as $key => $v)
  <div class="card">
        <div class="card-body">
  <div class="add_contact"><input name="shipping_user_id[]" value="{{$v->id}}" type="hidden"><h6 class="sub-title">Contact Person Information</h6><div class="row"><div class="col-lg-6 col-md-6 col-sm-6"><div class="form-group"><label for="shipping_name" class="form-label">Name</label><input class="form-control" placeholder="Enter Name" name="shipping_name[]" value="{{$v->shipping_name}}" type="text" id="shipping_name"></div></div><div class="col-lg-6 col-md-6 col-sm-6"><label for="email" class="form-label">Email</label><input class="form-control" placeholder="Enter email" name="shipping_email[]"  value="{{$v->email}}" type="text"></div><div class="col-lg-6 col-md-6 col-sm-6"><div class="form-group"><label for="shipping_phone" class="form-label">Phone</label><span><button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;border-radius: 5px 0px 0px 5px;height: 35px; margin: 31px 0px 0px -34px;position: absolute;"><a href="#"><img src="https://trumen.truelymatch.com/assets/images/india.png" width="30" alt="india"> </a></button><input class="form-control" style="padding-left: 100px;" placeholder="Enter Phone" name="shipping_phone[]" value="{{$v->shipping_phone}}" type="text" id="shipping_phone"> </span></div></div><div class="col-lg-5 col-md-5 col-sm-5"><div class="form-group"><label for="gender" class="form-label">Gender</label><span class="text-danger">*</span><div class="d-flex radio-check"><div class="custom-control custom-radio custom-control-inline"><input type="radio" id="g_male" value="Male" name="shipping_gender[{{$key}}]" value="{{$v->shipping_gender}}" class="form-check-input" {{$v->shipping_gender != 'Male'?'checked':''}}><label class="form-check-label " for="g_male">Male</label></div><div class="custom-control custom-radio ms-4 custom-control-inline"><input type="radio" id="g_female" value="Female" name="shipping_gender[]" class="form-check-input"><label class="form-check-label " for="g_female" {{$v->shipping_gender != 'Female'?'checked':''}}>Female</label></div></div></div></div><div class="col-lg-6 col-md-6 col-sm-6"><div class="form-group"><label for="department_id" class="form-label">Department</label><input class="form-control" placeholder="Enter Department" name="shipping_department[]" value="{{$v->shipping_department}}" type="text"></div></div><div class="col-lg-5 col-md-5 col-sm-5"><div class="form-group"><label for="designation_id" class="form-label">Designation</label><input class="form-control" placeholder="Enter Designation" name="shipping_designation[]" value="{{$v->shipping_designation}}" type="text"></div></div><div class="col-lg-1 col-md-1 col-sm-1" style="padding-top: 26px;"><a class="mx-3 btn btn-primary sm  align-items-center removeButton" title="remove"><label class="form-check-label " for="g_male"><i class="ti ti-trash text-white"></i></label></a></div></div></div></div></div>
  @endforeach
  @endif
   <div id="divContainer"></div>
            <div class="form-group text-center">
                <a class="btn btn-outline-light sm text-dark" onclick="appendDiv()" style="background-color: revert-layer;"><i class="ti ti-plus" style="background-color: darkgray;
                    border-radius: 50%;"></i> Add Contact Person</a>
             </div>
    @if(App\Models\Utility::getValByName('shipping_display')=='on')
       
       
        <div class="row">
        <div class="col-md-8">
        <div class="card">
        <div class="card-body">
             <h6 class="sub-title">{{__('Address & Contact')}}</h6>
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('shipping_address',__('Address'),array('class'=>'form-label')) }}
                    <label class="form-label" for="example2cols1Input"></label>
                    {{Form::textarea('shipping_address',$customer->shipping_address,array('class'=>'form-control','rows'=>3 , 'placeholder'=>__('Enter Address')))}}

                </div>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-6" style="float: inline-end;">
                <div class="form-group">
                    {{Form::label('shipping_city',__('City'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_city',$customer->shipping_city,array('class'=>'form-control' , 'placeholder'=>__('Enter City')))}}

                </div>
            </div>
            <div class=" col-lg-5 col-md-5 col-sm-5">
                <div class="form-group">
                    {{Form::label('shipping_state',__('State'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_state',$customer->shipping_state,array('class'=>'form-control' , 'placeholder'=>__('Enter State')))}}

                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6" style="float: inline-end;">
                <div class="form-group">
                    {{Form::label('shipping_country',__('Country'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_country',$customer->shipping_country,array('class'=>'form-control' , 'placeholder'=>__('Enter Country')))}}

                </div>
            </div>


            <div class="col-lg-5 col-md-5 col-sm-5">
                <div class="form-group">
                    {{Form::label('shipping_zip',__('Zip Code'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_zip',$customer->shipping_zip,array('class'=>'form-control' , 'placeholder' => __('Enter Zip Code')))}}

                </div>
            </div>
           </div>
           </div>
           </div>
             <div class="col-md-4">
               <div class="card">
               <div class="card-body">  
              <h6 class="sub-title">{{__('Additional')}}</h6><br>
             <div class="col-sm-12">
             <div class="form-group">
                  {{Form::label('assigned_by',__('Lead Assigned by'),array('class'=>'form-label')) }}
                    {{Form::text('assigned_by',\Auth::user()->name,array('class'=>'form-control' , 'placeholder' => __('Lead Assigned Name'), 'readonly'))}}
                
            </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                {{ Form::label('owner_name', __('Lead Owner Name'),['class'=>'form-label']) }}
                {{ Form::select('owner_id', $users,\Auth::user()->id, array('class' => 'form-control select','required'=>'required')) }}

            </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                 {{ Form::label('assigned_to', __('Lead Assigned to'),['class'=>'form-label']) }}
                 {{ Form::select('assigned_to', $users,null, array('class' => 'form-control select','required'=>'required')) }}

            </div>
            </div>
           </div>
           </div>
        </div>
        
        </div>
        </div>
    @endif
        <input type="hidden" name="subject" value="demo">
        <!--<input type="hidden" name="user_id" value="{{\Auth::user()->ownerId()}}">-->
        {{--<div class="col-6 form-group">
            {{ Form::label('subject', __('Subject'),['class'=>'form-label']) }}
            {{ Form::text('subject', hidden, array('class' => 'form-control','required'=>'required' , 'placeholder'=>__('Enter Subject'))) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('user_id', __('User'),['class'=>'form-label']) }}
            {{ Form::select('user_id', $users,null, array('class' => 'form-control select','required'=>'required')) }}
            @if(count($users) == 1)
                <div class="text-muted text-xs">
                    {{__('Please create new users')}} <a href="{{route('users.index')}}">{{__('here')}}</a>.
                </div>
            @endif
        </div>
        <div class="col-6 form-group">
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter Name'))) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('email', __('Email'),['class'=>'form-label']) }}
            {{ Form::text('email', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter email'))) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('phone', __('Phone'),['class'=>'form-label']) }}
            {{ Form::text('phone', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter Phone'))) }}
        </div>--}}
        <div class="card">
        <div class="card-body">
         <div class="col-12 form-group">
            {{ Form::label('notes', __('Notes'),['class'=>'form-label']) }}
            {{ Form::textarea('notes',null, array('class' => 'summernote-simple', 'style' => 'width:100%;')) }}
        </div>
        </div>
         <div class="modal-footer" style="padding: 13px 18px 12px 15px;">
                <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
           </div>  
        </div>
    </div>
</div>
           
{{--<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>--}}

{{Form::close()}}
@endsection

@push('script-page')
<script>

//      $(document).ready(function() {
//       $('input[name="template_name"][id="contact_info"]').prop('checked', false);
//     //   $('.contact_info').addClass('d-none');
//       $('.add_contact').addClass('d-none');
//       $('input[name="template_name"]').change(function() {
//           var radioValue = $('input[name="template_name"]:checked').val();
//           var page_content = $('.contact_info');
//           if (radioValue === "contact_info") {
//               $('.contact_info').removeClass('d-none');
//               $('.add_contact').removeClass('d-none');
//           } else {
//               $('.contact_info').addClass('d-none');
//               $('.add_contact').addClass('d-none');
//           }
//       });
//   });
    var stage_id = '{{$lead->stage_id}}';

    $(document).ready(function () {
        var pipeline_id = $('[name=pipeline_id]').val();
        getStages(pipeline_id);
    });
    
    $(document).on("change", "#commonModal select[name=pipeline_id]", function () {
        var currVal = $(this).val();
        console.log('current val ', currVal);
        getStages(currVal);
        
    });
    function appendDiv() {
    // Create a new div element
    //  $('#divContainer').append("<div id='mySecondDiv'></div>");
    $('#divContainer').append(' <div class="card"><div class="card-body"><div class="add_contact"><input name="shipping_user_id[]" type="hidden"><h6 class="sub-title">Contact Person Information</h6><div class="row"><div class="col-lg-6 col-md-6 col-sm-6"><div class="form-group"><label for="shipping_name" class="form-label">Name</label><input class="form-control" placeholder="Enter Name" name="shipping_name[]" type="text" id="shipping_name"></div></div><div class="col-lg-6 col-md-6 col-sm-6"><label for="email" class="form-label">Email</label><input class="form-control" placeholder="Enter email" name="shipping_email[]" type="text"></div><div class="col-lg-6 col-md-6 col-sm-6"><div class="form-group"><label for="shipping_phone" class="form-label">Phone</label><span><button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;border-radius: 5px 0px 0px 5px;height: 35px; margin: 31px 0px 0px -34px;position: absolute;"><a href="#"><img src="https://trumen.truelymatch.com/assets/images/india.png" width="30" alt="india"> </a></button><input class="form-control" style="padding-left: 100px;" placeholder="Enter Phone" name="shipping_phone[]" type="text" id="shipping_phone"> </span></div></div><div class="col-lg-5 col-md-5 col-sm-5"><div class="form-group"><label for="gender" class="form-label">Gender</label><span class="text-danger">*</span><div class="d-flex radio-check"><div class="custom-control custom-radio custom-control-inline"><input type="radio" id="g_male" value="Male" name="shipping_genders[]" class="form-check-input" checked><label class="form-check-label " for="g_male">Male</label></div><div class="custom-control custom-radio ms-4 custom-control-inline"><input type="radio" id="g_female" value="Female" name="shipping_genders[]" class="form-check-input"><label class="form-check-label " for="g_female">Female</label></div></div></div></div><div class="col-lg-6 col-md-6 col-sm-6"><div class="form-group"><label for="department_id" class="form-label">Department</label><input class="form-control" placeholder="Enter Department" name="shipping_department[]" type="text"></div></div><div class="col-lg-5 col-md-5 col-sm-5"><div class="form-group"><label for="designation_id" class="form-label">Designation</label><input class="form-control" placeholder="Enter Designation" name="shipping_designation[]" type="text"></div></div><div class="col-lg-1 col-md-1 col-sm-1" style="padding-top: 26px;"><a class="mx-3 btn btn-primary sm  align-items-center removeButton" title="remove"><label class="form-check-label " for="g_male"><i class="ti ti-trash text-white"></i></label></a></div></div></div></div></div>');

    // Append the new div to a container
    // var container = document.getElementById('divContainer');
    // container.appendChild(newDiv);
  }
    $(document).on('click', '.removeButton', function(e) {
   e.preventDefault();
   $(this).closest('.add_contact').remove();
   return false;
});
    function getStages(id) {
        $.ajax({
            url: '{{route('leads.json')}}',
            data: {pipeline_id: id, _token: $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            success: function (data) {
                var stage_cnt = Object.keys(data).length;
                $("#stage_id").empty();
                if (stage_cnt > 0) {
                    $.each(data, function (key, data1) {
                        var select = '';
                        if (key == '{{ $lead->stage_id }}') {
                            select = 'selected';
                        }
                        $("#stage_id").append('<option value="' + key + '" ' + select + '>' + data1 + '</option>');
                    });
                }
                $("#stage_id").val(stage_id);
                $('#stage_id').select2({
                    placeholder: "{{__('Select Stage')}}"
                });
            }
        })
    }
</script>
@endpush
