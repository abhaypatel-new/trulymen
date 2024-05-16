@extends('layouts.admin')
@php
   // $profile=asset(Storage::url('uploads/avatar/'));
$profile=\App\Models\Utility::get_file('uploads/avatar/');
@endphp
@push('css-page')
    <link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
    // Your CSS styling goes here
    
        $('#datepicker').datepicker();
        $('.choices__input').css('color', 'red');
        $('.page-header-title').css('display', 'none');
        $('.choices__list--dropdown').css('color', 'dark');
        $('.form-group').css({'padding': '0px'});
         $('.dataTable-dropdown').css('display', 'none');
         $('.dataTable-input').css({'height': '45px', 'width': '250px'});
        $('.choices__placeholder').attr('placeholder', 'Enter your text here').css('color', 'red');
         $('#choices-multiple4').attr('placeholder', 'Select a city').css('color', 'red');
        // $('.choices').css('margin-right', '25px');
    
});
        $(document).on('click', '#billing_data', function () {
            $("[name='shipping_name']").val($("[name='billing_name']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_phone']").val($("[name='billing_phone']").val());
            $("[name='shipping_zip']").val($("[name='billing_zip']").val());
            $("[name='shipping_address']").val($("[name='billing_address']").val());
        })
        $(document).on('change', '.state-select', function () {
            var state_id = $(this).val();
        //   alert(state_id);
            var url = '{{ route('city') }}'
           var $citySelect = $(this).siblings('.city-select');
       
            getCities(url,state_id, $citySelect);
        });
      function getCities(url, state_id, $citySelect) {
        //   alert(state_id);
        //  $('#choices-multiple4').attr('placeholder', 'Select a city').css('color', 'black');
        $('#choices-multiple4').css('color', 'black');
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                   
                    'state_id': state_id,
                    'session_key': session_key
                },
                success: function (data) {
                    // console.log(data)
                  
                    $('#choices-multiple4').empty();
                $.each(data.data, function(index, city) {
                    console.log(city.name)
               $('#choices-multiple4').append('<option value="' + city.id + '">' + city.name + '</option>');
            });
             $('#choices-multiple4').removeAttr('readonly');
            

            // Initialize Select2 after populating the options
            
                }
            });
        }
    </script>
@endpush
@section('page-title')
    {{__('Manage Customers')}}
@endsection
@section('breadcrumb')
   {{-- <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Customer')}}</li> --}}
@endsection

{{--@section('action-btn')
    <div class="float-end">
        <a href="#" data-size="md"  data-bs-toggle="tooltip" title="{{__('Import')}}" data-url="{{ route('customer.file.import') }}" data-ajax-popup="true" data-title="{{__('Import customer CSV file')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-import"></i>
        </a>
        <a href="{{route('customer.export')}}" data-bs-toggle="tooltip" title="{{__('Export')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-export"></i>
        </a>

        <a href="#" data-size="lg" data-url="{{ route('customer.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Customer')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection --}}

@section('content')
   <div class="card">
    <div class="row">
         {{ Form::open(array('url' => 'customer/search')) }}
               <div class="row">
                  
                    <div class="col-sm-1 form-group" style="width: 62px;">
                        <span style="margin-left: 20px;"><i class="ti ti-filter" style="position: absolute;margin-left: 14px;margin-top: 12px;z-index: 10;color: white;"></i><input type="submit" title="{{__('Search')}}" data-bs-toggle="tooltip" class="btn btn-primary text-primary form-control" style="border: none;width: 40px;" onmouseover="this.style.backgroundColor='#ff3a6e';"></span>
                      
                   </div>
                   <div class="col-sm-2 form-group" style="width: 162px;">
                       
                       <input type="text" class="form-control text-primary" name="date" value="Date" placeholder="Date" title="{{__('Date')}}" data-bs-toggle="tooltip" id="datepicker"style="height: 45px;"><i class="bx bx-calendar text-primary" style="position: absolute;margin-left: 110px;margin-top: -28px;"></i>
                       {{--<img src="{{ asset('assets/images/date-icon.png') }}" width="30" alt="india" style="position: absolute;margin-top: -37px;margin-left: 110px;" id="dateIcon"/>--}}
                   </div>
                    <div class="col-sm-2 form-group" style="width: 162px;">
                       <!--<input type="text" class="form-control btn btn-warning"name="search" value="Assigned By">-->
                        {{ Form::select('user_id', $users,null, array('class' => 'form-control select2','id'=>'choices-multiple3')) }}
                   </div>
                    <div class="col-sm-2 form-group" style="width: 162px;">
                       {{ Form::select('products', $products,'null', array('class' => 'form-control select2', 'id'=>'choices-multiple2')) }}
                   </div> 
                   <div class="col-sm-2 form-group" style="width: 162px;">
                       {{ Form::select('industry_id', $industry,null, array('class' => 'form-control select2','id'=>'choices-multiple1')) }}
                   </div>
                  <div class="col-sm-1 form-group" style="width: 162px;">
                       {{ Form::select('state_id', $states,null, array('class' => 'form-control select2 state-select','id'=>'choices-multiple5')) }}
                   </div>
                   <div class="col-sm-2 form-group" style="width: 162px;">
                       {{ Form::select('city_id',[],null, array('class' => 'form-control select','id'=>'choices-multiple4', 'readonly', 'placeholder'=> 'Select a city', 'style'=>'height: 45px;')) }}
                   </div>
                   
                   
                  
            </div>
             {{Form::close()}}
        <div class="col-md-12">
            <h4 class=""><a href="#" class="text-dark" style="font-weight: bolder;margin-left: 20px;margin-top: 40px;position:absolute;margin-bottom: -20px;">{{__('Customer List')}}</a>
            </h4>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th> {{__('Company Name')}}</th>
                                <th> {{__('Primary Email')}}</th>
                                <th> {{__('Contact Number')}}</th>
                                <th> {{__('Created At')}}</th>
                                <th> {{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($customers as $k=>$customer)
                                <tr class="cust_tr" id="cust_detail" data-url="{{route('customer.show',$customer['id'])}}" data-id="{{$customer['id']}}">
                                    <td class="Id">
                                        <div class="action-btn ms-2" style="font-size:12px;">
                                        @can('show customer')
                                         
                                            <a href="{{ route('customer.show',\Crypt::encrypt($customer['id'])) }}" class="btn btn-outline-primary text-light" style="border-top-right-radius: 0;border-bottom-right-radius: 0;border-top-left-radius: 10px;border-bottom-left-radius: 10px;border: none;font-size:12px;background-color: {{($customer->status == 'Pending')?'#9199a0':(($customer->status == 'Confirm')?'#0AA350':'#ff5000');}}">
                                                @php 
                                                $num = 5; // Your number
                                                $width = 2; // Desired width
                                                $paddingChar = '0'; // Character used for padding
                                                $formattedNum = str_pad($customer['customer_id'], $width, $paddingChar, STR_PAD_LEFT);
                                                @endphp
                                                {{ $formattedNum }}
                                            </a>
                                        @else
                                            <a href="#" class="btn btn-outline-primary">
                                                {{ AUth::user()->customerNumberFormat($customer['customer_id']) }}
                                            </a>
                                        @endcan
                                        </div>
                                    </td>
                                    <td class="font-style">{{ !empty($customer->leads)?$customer->leads->industry_name:'-'}}</td>
                                    
                                    <td>{{$customer['email']}}</td>
                                    <td>{{$customer['contact']}}</td>
                                     <td>{{  \Carbon\Carbon::parse($customer['created_at'])->format('d M Y') }}</td>
                                    <td>{{$customer['status']}}</td>
                                    <td class="Action">
                                        <span>
                                        @if($customer['is_active']==0)
                                                <i class="ti ti-lock" title="Inactive"></i>
                                            @else
                                                @can('show customer')
                                                <div class="action-btn bg-light ms-2">
                                                    <a href="{{ route('customer.show',\Crypt::encrypt($customer['id'])) }}" class="mx-3 btn btn-sm align-items-center"
                                                       data-bs-toggle="tooltip" title="{{__('View')}}">
                                                        <i class="ti ti-eye text-dark"></i>
                                                    </a>
                                                </div>
                                                @endcan
                                               {{-- @can('edit customer')
                                                        <div class="action-btn bg-primary ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('customer.edit',$customer['id']) }}" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Edit Customer')}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan --}}
                                                {{--@can('delete customer')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['customer.destroy', $customer['id']],'id'=>'delete-form-'.$customer['id']]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti ti-trash text-white text-white"></i></a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan--}}
                                            @endif
                                        </span>
                                    </td>
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
