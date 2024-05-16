@extends('layouts.admin')
@section('page-title')
   {{__('Create Leads')}}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/dropzone.min.css')}}">
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('leads.index')}}">{{__('Lead')}}</a></li>
    <li class="breadcrumb-item"> {{__('Create Leads')}} </li>
@endsection
@section('content')
{{ Form::open(array('url' => 'leads')) }}

    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
         $products = \App\Models\ProductService::where('created_by', '=', \Auth::user()->ownerId())->get()->pluck('name', 'id');
         $sources = \App\Models\Source::where('created_by', '=', \Auth::user()->ownerId())->get()->pluck('name', 'id');
         $stages = \App\Models\LeadStage::where('created_by', '=', \Auth::user()->ownerId())->get()->pluck('name', 'id');
         $designations = \App\Models\Designation::where('created_by', '=', \Auth::user()->ownerId())->get()->pluck('name', 'id');
         $departments = \App\Models\Department::where('created_by', '=', \Auth::user()->ownerId())->get()->pluck('name', 'id');
         $types = [
         'Product Inquary',
         'Request for Information'
         ]
    @endphp

    {{-- end for ai module--}}
 <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8" style="padding:15px;">
                <h6 class="sub-title"></h6>
                </div>
                
                <div class="col-lg-4 col-md-4 col-sm-4" style="margin-top: -52px;">
                 <span style="float: inline-end;"><i class="ti ti-send" style="position: absolute;margin-left: 5px;margin-top: 14px;z-index: 10;color: white;"></i><input type="submit" value="{{__('Save')}}" title="{{__('Create Lead')}}" class="btn-sm custom-file-uploadss" style="border: none;"></span>
                </div>
            </div>    
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
                        {{Form::text('industry',null,array('class'=>'form-control','required'=>'required' ,'placeholder'=>_('Enter Name')))}}
                    </div>
                </div>
               
                 <div class="col-lg-4 col-md-4 col-sm-8">
                    <div class="form-group">
                        {{ Form::label('products', __('Product'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    {{ Form::select('products[]', $products,false, array('class' => 'form-control select2','id'=>'choices-multiple3','multiple'=>'','required'=>'required', 'placeholder' => __('Select Products'))) }}
        
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
                        {{Form::text('gst_number',null,array('class'=>'form-control' , 'placeholder' => __('Enter Gst Number')))}}
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
                        {{Form::text('billing_name',null,array('class'=>'form-control' , 'placeholder'=>__('Enter Name')))}}
                    </div>
                </div>
                <div class="col-5 form-group">
                    {{ Form::label('email', __('Email'),['class'=>'form-label']) }}
                    {{ Form::text('email', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter email'))) }}
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
             
                </div>
                </div>
               
                <div class="col-sm-6">
                    <div class="form-group">
                       {!! Form::label('gender', __('Gender'), ['class' => 'form-label' , 'required' => 'required' ]) !!}<span class="text-danger">*</span>
                        <div class="d-flex radio-check">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="g_male" value="Male" name="billing_gender"
                                                            class="form-check-input" checked>
                                                        <label class="form-check-label " for="g_male">{{ __('Male') }}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio ms-4 custom-control-inline">
                                                        <input type="radio" id="g_female" value="Female" name="billing_gender"
                                                            class="form-check-input">
                                                        <label class="form-check-label "
                                                            for="g_female">{{ __('Female') }}</label>
                                                    </div>
                                                </div>
        
                    </div>
                </div>
                
                 <div class="col-sm-6">
                    <div class="form-group">
                       {{ Form::label(null, __('Department'), ['class' => 'form-label', 'style' => 'display: table-column']) }}
                     
                    </div>
                </div>
                 <div class="col-6 form-group"  style="float: inline-end;">
                    {{ Form::label('designation', __('Designation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    {{ Form::text('billing_designation', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter Designation'))) }}
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        {{Form::label('department',__('Department'),array('class'=>'','class'=>'form-label')) }}<span class="text-danger">*</span>
                        {{Form::text('billing_department',null,array('class'=>'form-control' , 'placeholder'=>__('Enter Department')))}}
                    </div>
                </div>
                
                </div>
               
                </div>
                </div>
                 <div class="col-md-4">
                 <div class="card">
                 <div class="card-body">
                
                      <h6 class="sub-title">{{__('Categories')}}</h6><br>
                     <div class="col-sm-12">
                     <div class="form-group">
                        {{ Form::label('stage_id', __('Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
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
    <div class="row">
        <div id="divContainer">
                    <input type="hidden" name="shipping-check" value="0" id="shipping_check">
        </div>
            <div class="form-group text-center">
                <a class="btn btn-outline-light sm text-dark" onclick="appendDiv()" style="background-color: revert-layer;"><i class="ti ti-plus" style="background-color: darkgray;
                    border-radius: 50%;"></i> Add Contact Person</a>
            </div>
    </div>
   {{-- <div class="add_contact">                
     <h6 class="sub-title">{{__('Contact Person Information')}}</h6>
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_name',__('Name'),array('class'=>'','class'=>'form-label')) }}
                {{Form::text('billing_name',null,array('class'=>'form-control' , 'placeholder'=>__('Enter Name')))}}
            </div>
        </div>
       <div class="col-lg-6 col-md-6 col-sm-6">
            {{ Form::label('email', __('Email'),['class'=>'form-label']) }}
            {{ Form::text('billing_email', null, array('class' => 'form-control', 'placeholder' => __('Enter email'))) }}
        </div>
       <div class="col-lg-6 col-md-6 col-sm-6">
             
            <div class="form-group">
      
    
      
                {{Form::label('shipping_phone',__('Phone'),array('class'=>'form-label')) }}
                 <span>      
      <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;border-radius: 5px 0px 0px 5px;height: 35px; margin: 31px 0px 0px -34px;position: absolute;">
      <a href="#"><img src="{{ asset('assets/images/india.png') }}" width="30" alt="india"/> </a>
      </button>
                {{Form::text('shipping_phone',null,array('class'=>'form-control' , 'style'=> 'padding-left: 100px;', 'placeholder'=>__('Enter Phone')))}} </span>
            </div>
        </div>
        
       <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
               {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<span class="text-danger">*</span>
                <div class="d-flex radio-check">
                     <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="g_male" value="Male" name="billing_gender"
                                                    class="form-check-input" checked>
                                                <label class="form-check-label " for="g_male">{{ __('Male') }}</label>
                     </div>
                    <div class="custom-control custom-radio ms-4 custom-control-inline">
                                                <input type="radio" id="g_female" value="Female" name="billing_gender"
                                                    class="form-check-input">
                                                <label class="form-check-label "
                                                    for="g_female">{{ __('Female') }}</label>
                </div>
                </div>

            </div>
        </div>
      
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
               {{ Form::label('department_id', __('Department'), ['class' => 'form-label']) }}
               {{ Form::text('billin_department', null, array('class' => 'form-control', 'placeholder' => __('Enter Department'))) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('designation_id', __('Designation'), ['class' => 'form-label']) }}
                {{ Form::text('billing_designation', null, array('class' => 'form-control', 'placeholder' => __('Enter Designation'))) }}
            </div>
        </div>
        </div>
    </div>--}}
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
                            {{Form::textarea('shipping_address',null,array('class'=>'form-control','rows'=>3 , 'placeholder'=>__('Enter Address')))}}
        
                        </div>
                    </div>
        
        
                    <div class="col-lg-6 col-md-6 col-sm-6" style="float: inline-end;">
                        <div class="form-group">
                            {{Form::label('shipping_city',__('City'),array('class'=>'form-label')) }}
                            {{Form::text('shipping_city',null,array('class'=>'form-control' , 'placeholder'=>__('Enter City')))}}
        
                        </div>
                    </div>
                    <div class=" col-lg-5 col-md-5 col-sm-5">
                        <div class="form-group">
                            {{Form::label('shipping_state',__('State'),array('class'=>'form-label')) }}
                            {{Form::text('shipping_state',null,array('class'=>'form-control' , 'placeholder'=>__('Enter State')))}}
        
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6" style="float: inline-end;">
                        <div class="form-group">
                            {{Form::label('shipping_country',__('Country'),array('class'=>'form-label')) }}
                            {{Form::text('shipping_country',null,array('class'=>'form-control' , 'placeholder'=>__('Enter Country')))}}
        
                        </div>
                    </div>
        
        
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <div class="form-group">
                            {{Form::label('shipping_zip',__('Zip Code'),array('class'=>'form-label')) }}
                            {{Form::text('shipping_zip',null,array('class'=>'form-control' , 'placeholder' => __('Enter Zip Code')))}}
        
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
                            {{Form::text('assigned_by',\Auth::user()->name,array('class'=>'form-control' , 'placeholder' => __('Lead Assigned Name')))}}
                        
                    </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        {{ Form::label('owner_name', __('Lead Owner Name'),['class'=>'form-label']) }}
                        {{ Form::text('owner_id', \Auth::user()->name, array('class' => 'form-control','required'=>'required')) }}
        
                    </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                         {{ Form::label('assigned_to', __('Lead Assigned to'),['class'=>'form-label']) }}
                         {{ Form::select('user_id', $users,null, array('class' => 'form-control select','required'=>'required')) }}
        
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
                {{ Form::textarea('notes',null, array('class' => 'summernote-simple', 'style' => 'width: 100%;')) }}
            </div>
            </div>
           <div class="modal-footer" style="padding: 13px 18px 12px 15px;">
            <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
           </div>  
        </div>
       
    </div>




{{Form::close()}}
@endsection
@push('script-page')
    <script src="{{asset('css/summernote/summernote-bs4.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  
<script>
  // tinymce.init({
  //   selector: '#mytextarea',
  //   menubar: '',
  // });
function appendDiv() {
    // Create a new div element
    //  $('#divContainer').append("<div id='mySecondDiv'></div>");
    $("#shipping_check").val(1);
    $('#divContainer').append('<div class="card"><div class="card-body"><div class="add_contact"><h6 class="sub-title">Contact Person Information</h6><div class="row"><div class="col-lg-6 col-md-6 col-sm-6"><div class="form-group"><label for="shipping_name" class="form-label">Name</label><input class="form-control" placeholder="Enter Name" name="shipping_name[]" type="text" id="shipping_name"></div></div><div class="col-lg-6 col-md-6 col-sm-6"><label for="email" class="form-label">Email</label><input class="form-control" placeholder="Enter email" name="shipping_email[]" type="text"></div><div class="col-lg-6 col-md-6 col-sm-6"><div class="form-group"><label for="shipping_phone" class="form-label">Phone</label><span><button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;border-radius: 5px 0px 0px 5px;height: 35px; margin: 31px 0px 0px -34px;position: absolute;"><a href="#"><img src="https://trumen.truelymatch.com/assets/images/india.png" width="30" alt="india"> </a></button><input class="form-control" style="padding-left: 100px;" placeholder="Enter Phone" name="shipping_phone[]" type="text" id="shipping_phone"> </span></div></div><div class="col-lg-5 col-md-5 col-sm-5"><div class="form-group"><label for="gender" class="form-label">Gender</label><span class="text-danger">*</span><div class="d-flex radio-check"><div class="custom-control custom-radio custom-control-inline"><input type="radio" id="g_male" value="Male" name="shipping_gender[]" class="form-check-input" checked=""><label class="form-check-label " for="g_male">Male</label></div><div class="custom-control custom-radio ms-4 custom-control-inline"><input type="radio" id="g_female" value="Female" name="shipping_gender[]" class="form-check-input"><label class="form-check-label " for="g_female">Female</label></div></div></div></div><div class="col-lg-6 col-md-6 col-sm-6"><div class="form-group"><label for="department_id" class="form-label">Department</label><input class="form-control" placeholder="Enter Department" name="shipping_department[]" type="text"></div></div><div class="col-lg-5 col-md-5 col-sm-5"><div class="form-group"><label for="designation_id" class="form-label">Designation</label><input class="form-control" placeholder="Enter Designation" name="shipping_designation[]" type="text"></div></div><div class="col-lg-1 col-md-1 col-sm-1" style="padding-top: 26px;"><a class="mx-3 btn btn-primary sm align-items-center removeButton" title="remove"><label class="form-check-label " for="g_male"><i class="ti ti-trash text-white"></i></label></a></div></div></div></div></div>');

    // Append the new div to a container
    // var container = document.getElementById('divContainer');
    // container.appendChild(newDiv);
  }
  
  // Call the function to append content initially
  $(document).on('click', '.removeButton', function() {
//   e.preventDefault();
   $(this).closest('.card').remove();
   return false;
});
 
  $(document).ready(function() {
      $('input[name="template_name"][id="contact_info"]').prop('checked', false);
    //   $('.contact_info').addClass('d-none');
       $('.add_contact').addClass('d-none');
      $('input[name="template_name"]').change(function() {
          var radioValue = $('input[name="template_name"]:checked').val();
          var page_content = $('.contact_info');
          if (radioValue === "contact_info") {
              $('.contact_info').removeClass('d-none');
              $('.add_contact').removeClass('d-none');
          } else {
              $('.contact_info').addClass('d-none');
              $('.add_contact').addClass('d-none');
          }
      });
  });
 

  
</script>
@endpush