@extends('layouts.admin')
@section('page-title')
    {{__('Manage Leads')}} @if($pipeline) - {{$pipeline->name}} @endif
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
        // $('.dataTable-top').css('display', 'none');
        $('.choices__placeholder').attr('placeholder', 'Enter your text here').css('color', 'red');
       
   
        //$('.dataTable-top').css('display', 'none');
         $('.dataTable-dropdown').css('display', 'none');
         $('.dataTable-input').css({'height': '45px', 'width': '250px'});
       
        
    $('.choices').css('margin-right', '25px');
    // $(document).on("blur", "#dateInput", function () {
    //     alert("dfds")
    //     $(this).type('date');
    //     $("#dateIcon").css('display', 'none');
    // });
    
});

    </script>
@endpush

@section('breadcrumb')
  
@endsection



@section('content')

    @if($pipeline)
    <div class="card">
    <div class="row" style="padding:21px;">   
    <div class="col-md-8">
         <a href="{{ route('leads.create') }}" data-bs-toggle="tooltip" title="{{__('Create New Lead')}}" class="btn btn-outline-light text-primary" style="margin-left: 15px;box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.19);
  text-align: center;">
            <i class="ti ti-plus" style="border: 1px solid;border-radius:5px;"></i>
             {{__('Create New Lead')}}
        </a>
        {{--<a href="{{ route('leads.index') }}" data-bs-toggle="tooltip" title="{{__('Kanban View')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-layout-grid"></i>
        </a>--}}
        </div>
        <div class="col-md-4">
        <a href="#" data-size="md"  data-bs-toggle="tooltip" title="{{__('Import')}}" data-url="{{ route('leads.file.import') }}" data-ajax-popup="true" data-title="{{__('Import Lead CSV file')}}" class="btn btn-lg btn-primary" style="margin-left: 100px;border-radius: 12px;box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.19);
  text-align: center;">
            <i class="ti ti-file-import"></i>
             {{__('Import')}}
        </a>
            <a href="{{route('leads.export')}}" data-bs-toggle="tooltip" title="{{__('Export')}}" class="btn btn-lg btn-dark" style="float: inline-end;border-radius: 12px;box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.19);
  text-align: center;">
            <i class="ti ti-file-export"></i>
             {{__('Export')}}
        </a>
        {{--<a href="{{ route('leads.create') }}" data-bs-toggle="tooltip" title="{{__('Create New Lead')}}" class="btn btn-lg btn-primary" >
            <i class="ti ti-plus" style="border: 1px solid;"></i>
             {{__('Create New Lead')}}
        </a> --}}
    </div>
    </div>
        <div class="row">
            
                 {{ Form::open(array('url' => 'leads/search')) }}
               <div class="row">
                  
                    <div class="col-sm-1 form-group">
                        <span style="float: inline-end;"><i class="ti ti-filter" style="position: absolute;margin-left: 14px;margin-top: 12px;z-index: 10;color: white;"></i><input type="submit" title="{{__('Search')}}" data-bs-toggle="tooltip" class="btn btn-primary text-primary form-control" style="border: none;width: 40px;" onmouseover="this.style.backgroundColor='#ff3a6e';"></span>
                      
                   </div>
                   <div class="col-sm-2 form-group">
                       
                       <input type="text" class="form-control text-primary" name="date" value="Date" placeholder="Date" title="{{__('Date')}}" data-bs-toggle="tooltip" id="datepicker"><i class="bx bx-calendar text-primary" style="position: absolute;margin-left: 110px;margin-top: -28px;"></i>
                       {{--<img src="{{ asset('assets/images/date-icon.png') }}" width="30" alt="india" style="position: absolute;margin-top: -37px;margin-left: 110px;" id="dateIcon"/>--}}
                   </div>
                    <div class="col-sm-3 form-group">
                       <!--<input type="text" class="form-control btn btn-warning"name="search" value="Assigned By">-->
                        {{ Form::select('user_id', $users,null, array('class' => 'form-control select2','id'=>'choices-multiple3')) }}
                   </div>
                    <div class="col-sm-3 form-group">
                       {{ Form::select('products', $products,'null', array('class' => 'form-control select2', 'id'=>'choices-multiple2')) }}
                   </div> <div class="col-sm-3 form-group">
                       {{ Form::select('stage_id', $stages,null, array('class' => 'form-control select2','id'=>'choices-multiple1')) }}
                   </div>
                  
            </div>
             {{Form::close()}}
            <div class="col-xl-12">
               <h4 class=""><a href="#" class="text-dark" style="font-weight: bolder;margin-left: 20px;margin-top: 40px;position:absolute;margin-bottom: -20px;">{{__('Recent Search')}}</a>
            </h4>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                <tr>
                                    <th>{{__('Sr.')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Company Name')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Product Name')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Assigned to')}}</th>
                                    {{--<th>{{__('Users')}}</th>--}}
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($leads) > 0)
                                    @foreach ($leads as $lead)
                                        <tr>
                                          
                                            <td>
                                                   
                                               <div class="number-color ms-2" style="font-size:12px;background-color: {{!empty($lead->stage)?$lead->stage->color:'#EBF868'}}">
                                                   {{ $lead->id }}</div> 
                                                </td>
                                            <td>{{ \Carbon\Carbon::parse($lead->created_at)->format('d/m/Y') }}</td>
                                            <td>{{ $lead->name }}</td>
                                            <td>{{ $lead->users[0]->name }}</td>
                                             
                                              <td>
                                                 
                                                 @if(!empty($lead->products()))
                                                 @foreach($lead->products() as $product) 
                                                  {{$product->name}} 
                                                   @if (!$loop->last)
                                                        ,
                                                    @endif
                                                   @endforeach
                                                   @else
                                                   -
                                                   @endif
                                                  </td>
                                             
                                           
                                            <td>{{  !empty($lead->stage)?$lead->stage->name:'-' }}</td>
                                             <td>{{ count($lead->users)>1?$lead->users[1]->name:'-' }}</td>
                                            {{--<td>
                                                @foreach($lead->users as $user)
                                                    <a href="{{route('leads.show',$lead->id)}}" class="btn btn-sm mr-1 p-0 rounded-circle">
                                                        <img alt="image" data-toggle="tooltip" data-original-title="{{$user->name}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('/storage/uploads/avatar/avatar.png')}}" @endif class="rounded-circle " width="25" height="25">
                                                    </a>
                                                @endforeach
                                            </td>--}}
                                            @if(Auth::user()->type != 'client')
                                                <td class="Action">
                                                    <span>
                                                    @can('view lead')
                                                            @if($lead->is_active)
                                                                <div class="action-btn bg-light ms-2">
                                                                <a href="{{route('leads.show',$lead->id)}}" class="mx-3 d-inline-flex align-items-center"  data-size="xl" data-bs-toggle="tooltip" title="{{__('View')}}" data-title="{{__('Lead Detail')}}">
                                                                    <i class="ti ti-eye text-dark"></i>
                                                                </a>
                                                            </div>
                                                            @endif
                                                        @endcan
                                                        @can('edit lead')
                                                            <div class="action-btn bg-light ms-2">
                                                                <a href="{{ route('leads.edit',$lead->id) }}" class="mx-3 d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Lead Edit')}}">
                                                                    <i class="ti ti-pencil text-dark"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                       {{-- @can('delete lead')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['leads.destroy', $lead->id],'id'=>'delete-form-'.$lead->id]) !!}
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti ti-trash text-white"></i></a>

                                                                {!! Form::close() !!}
                                                             </div>

                                                        @endif --}}
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
        </div>
    @endif

@endsection
