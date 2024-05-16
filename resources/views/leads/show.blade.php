@extends('layouts.admin')
@section('page-title')
    {{$lead->name}}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/dropzone.min.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('css/summernote/summernote-bs4.js')}}"></script>
    <script src="{{asset('assets/js/plugins/dropzone-amd-module.min.js')}}"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
     $(document).ready(function(){
     
      $(document).on('click', '#add-notes', function () {
            var notes = $("#disc-note").val();
            var id = $('#lead_id').val();
            var stage_id = $('#choices-multiple1').val();
            var url = '{{ route('leads.discussion.store', $lead->id) }}'
           $.ajax({
                type: 'POST',
                url: url,
                data: {
                   
                    'id': id,
                    'comment':notes,
                    'stage_id': stage_id,
                    'session_key': session_key,
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                   
                    // console.log(data.msg)
                    //  console.log(data.data)
                       if (data.error) {
                            show_toastr('error', response.msg, 'error'); 
                        $('#note-lists').html('<p>' + data.error + '</p>');
                    } else {
                         show_toastr('success', data.msg, 'success');
                         $("#disc-note").val('');
                        var postHtml = '';
                       
                        $.each(data.data, function(index, values) {
                             $.each(values.discussions, function(index, value) {
                        //   console.log(value)
                        var formattedDate = moment(value.created_at).format('MM/DD/YYYY ddd hh:mm a');
                            postHtml += '<li class="list-group-item px-0"><div class="d-block d-sm-flex align-items-start"><img src="https://trumen.truelymatch.com/storage/uploads/avatar/avatar.png" class="img-fluid wid-40 me-3 mb-2 mb-sm-0" alt="image"><div class="w-100"><div class="d-flex align-items-center justify-content-between"><div class="mb-3 mb-sm-0"><h6 class="mb-0">'+value.comment+'</h6><span class="text-muted text-sm">{{Auth::user()->name}}</span></div><div class="form-check form-switch form-switch-right mb-2">'+formattedDate+'</div></div></div></div></li>';
                             });
                        });
                        console.log(postHtml)
                        $('#note-lists').html(postHtml);
                    }
                },
                error: function(xhr, status, error) {
                    $('#note-lists').html('<p>An error occurred: ' + error + '</p>');
                }
                   
                   
            });
        });
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
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#lead-sidenav',
            offset: 300
        })
        Dropzone.autoDiscover = false;
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {
            maxFiles: 20,
            // maxFilesize: 2000,
            parallelUploads: 1,
            filename: false,
            // acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{route('leads.file.upload',$lead->id)}}",
            success: function (file, response) {
                if (response.is_success) {
                    if(response.status==1){
                        show_toastr('success', response.success_msg, 'success');
                    }
                    dropzoneBtn(file, response);
                } else {
                    myDropzone.removeFile(file);
                    show_toastr('error', response.error, 'error');
                }
            },
            error: function (file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    show_toastr('error', response.error, 'error');
                } else {
                    show_toastr('error', response, 'error');
                }
            }
        });
        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("lead_id", {{$lead->id}});
        });

        function dropzoneBtn(file, response) {
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "badge bg-info mx-1");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "{{__('Download')}}");
            download.innerHTML = "<i class='ti ti-download'></i>";

            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "badge bg-danger mx-1");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "{{__('Delete')}}");
            del.innerHTML = "<i class='ti ti-trash'></i>";

            del.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        data: {_token: $('meta[name="csrf-token"]').attr('content')},
                        type: 'DELETE',
                        success: function (response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                show_toastr('error', response.error, 'error');
                            }
                        },
                        error: function (response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                show_toastr('error', response.error, 'error');
                            } else {
                                show_toastr('error', response, 'error');
                            }
                        }
                    })
                }
            });

            var html = document.createElement('div');
            html.appendChild(download);
            @if(Auth::user()->type != 'client')
            @can('edit lead')
            html.appendChild(del);
            @endcan
            @endif

            file.previewTemplate.appendChild(html);
        }

        @foreach($lead->files as $file)
        @if (file_exists(storage_path('lead_files/'.$file->file_path)))
        // Create the mock file:
        var mockFile = {name: "{{$file->file_name}}", size: {{\File::size(storage_path('lead_files/'.$file->file_path))}}};
        // Call the default addedfile event handler
        myDropzone.emit("addedfile", mockFile);
        // And optionally show the thumbnail of the file:
        myDropzone.emit("thumbnail", mockFile, "{{asset(Storage::url('lead_files/'.$file->file_path))}}");
        myDropzone.emit("complete", mockFile);

        dropzoneBtn(mockFile, {download: "{{route('leads.file.download',[$lead->id,$file->id])}}", delete: "{{route('leads.file.delete',[$lead->id,$file->id])}}"});
        @endif
        @endforeach

        @can('edit lead')
        $('.summernote-simple').on('summernote.blur', function () {

            $.ajax({
                url: "{{route('leads.note.store',$lead->id)}}",
                data: {_token: $('meta[name="csrf-token"]').attr('content'), notes: $(this).val()},
                type: 'POST',
                success: function (response) {
                    if (response.is_success) {
                        // show_toastr('Success', response.success,'success');
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('error', response.error, 'error');
                    } else {
                        show_toastr('error', response, 'error');
                    }
                }
            })
        });
        @else
        $('.summernote-simple').summernote('disable');
        @endcan
    
    </script>

@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('leads.index')}}">{{__('Lead')}}</a></li>
    <li class="breadcrumb-item"> {{$lead->name}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('convert lead to deal')
            @if(!empty($deal))
                <a href="@can('View Deal') @if($deal->is_active) {{route('deals.show',$deal->id)}} @else # @endif @else # @endcan" data-size="lg" data-bs-toggle="tooltip" class="btn btn-sm btn-primary">
                   {{__('Already Converted To Customer')}}
                </a>
            @else
           
                                                    {{ Form::model($lead, array('route' => array('leads.convert.to.deal', $lead->id), 'method' => 'POST')) }}
                                                    {{ Form::hidden('client_name', $lead->name, array('class' => 'form-control','required'=>'required')) }}
                                                    {{ Form::hidden('client_email', $lead->email, array('class' => 'form-control','required'=>'required')) }}
                                                    {{ Form::hidden('name', $lead->subject, array('class' => 'form-control','required'=>'required')) }}
                                                    <a href="#" class="btn btn-primary  align-items-center bs-pass-para" data-bs-toggle="tooltip"
                                                       data-original-title="{{__('Convert')}}" data-confirm="{{__('Are You Sure You Want to Convert To Customer?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$lead->id}}').submit();">
                                                        {{__('Convert To Customer')}}
                                                    {!! Form::close() !!}
                                               
               {{-- <a href="#" data-size="lg" data-url="{{ URL::to('leads/'.$lead->id.'/show_convert') }}" data-ajax-popup="true" data-bs-toggle="tooltip" class="btn btn-sm btn-primary">
                   {{__('Convert To Customer')}}
                </a> --}}
            @endif
        @endcan

       <a href="#" data-url="{{ URL::to('leads/'.$lead->id.'/labels') }}" data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="{{__('Label')}}" class="btn btn-sm btn-primary" style="display: none;">
            <i class="ti ti-bookmark"></i>
        </a>
       {{-- <a href="#" data-size="xl" data-url="{{ route('leads.edit',$lead->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Edit')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-pencil"></i>
        </a>--}}
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="lead-sidenav" style="flex-direction: unset;">
                            @if(Auth::user()->type != 'client')
                                {{--<a href="#general" class="list-group-item list-group-item-action border-0">{{__('General')}}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>--}}
                                <div data-target="content1" class="list-group-item list-group-item-action border-0 tab active" tyle="cursor: pointer;">{{__('Profile')}}
                                    <div class="float-end"></div>
                                </div>
                                
                            @endif

                            @if(Auth::user()->type != 'client')
                                {{--<a href="#users_products" class="list-group-item list-group-item-action border-0">{{__('Users').' | '.__('Products')}}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>--}}
                                <a href="{{ route('quotations.create', ['lead_id' => $lead->id, 0]) }}" data-title="{{ __('Quotataion Create') }}" class="list-group-item list-group-item-action border-0">| {{ __('Quotation')}}
                                    <div class="float-end"></div>
                                </a>
                            @endif

                           {{-- @if(Auth::user()->type != 'client')
                                <a href="#sources_emails" class="list-group-item list-group-item-action border-0">{{__('Sources').' | '.__('Emails')}}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif
                            @if(Auth::user()->type != 'client')
                                <a href="#discussion_note" class="list-group-item list-group-item-action border-0">{{__('Discussion').' | '.__('Notes')}}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif
                            @if(Auth::user()->type != 'client')
                                <a href="#files" class="list-group-item list-group-item-action border-0">{{__('Files')}}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif --}}
                            @if(Auth::user()->type != 'client')
                           
                                <div data-target="content2" class="list-group-item list-group-item-action border-0 tab" style="cursor: pointer;">| {{__('Call Notes')}}
                                    <div class="float-end"></i></div>
                                </div>
                            @endif
                            @if(Auth::user()->type != 'client')
                                <div  data-target="content3" class="list-group-item list-group-item-action border-0 tab" style="cursor: pointer;border-bottom-left-radius: unset !important;border-top-left-radius: inherit;border-top-right-radius: inherit;;border-bottom-right-radius: unset !important;
">| {{__('Activity')}}
                                    <div class="float-end"></i></div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
              
            </div>
            <div class="row">
                 <div class="content" id="content1">  
                <div class="col-xl-12">
                    <?php
                    $products = $lead->products();
                    $sources = $lead->sources();
                    $calls = $lead->calls;
                    $emails = $lead->emails;
                    ?>
                   {{-- <div id="general" class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-mail"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0">{{__('Email')}}</p>
                                            <h5 class="mb-0 text-primary">{{!empty($lead->email)?$lead->email:''}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-warning">
                                            <i class="ti ti-phone"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0">{{__('Phone')}}</p>
                                            <h5 class="mb-0 text-warning">{{!empty($lead->phone)?$lead->phone:''}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-info">
                                            <i class="ti ti-test-pipe"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0">{{__('Pipeline')}}</p>
                                            <h5 class="mb-0 text-info">{{$lead->pipeline->name}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 mt-4">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-server"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0">{{__('Stage')}}</p>
                                            <h5 class="mb-0 text-primary">{{$lead->stage->name}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 mt-4">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-warning">
                                            <i class="ti ti-calendar"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0">{{__('Created')}}</p>
                                            <h5 class="mb-0 text-warning">{{\Auth::user()->dateFormat($lead->created_at)}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 mt-4">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-info">
                                            <i class="ti ti-chart-bar"></i>
                                        </div>
                                        <div class="ms-2">
                                            <h3 class="mb-0 text-info">{{$precentage}}%</h3>
                                            <div class="progress mb-0">
                                                <div class="progress-bar bg-info" style="width: {{$precentage}}%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <small class="text-muted">{{__('Product')}}</small>
                                            <h3 class="m-0">{{count($products)}}</h3>
                                        </div>
                                        <div class="col-auto">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti ti-shopping-cart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <small class="text-muted">{{__('Source')}}</small>
                                            <h3 class="m-0">{{count($sources)}}</h3>
                                        </div>
                                        <div class="col-auto">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-social"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <small class="text-muted">{{__('Files')}}</small>
                                            <h3 class="m-0">{{count($lead->files)}}</h3>
                                        </div>
                                        <div class="col-auto">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti ti-file"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div id="users_products">
                        <div class="row">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5>{{__('Users')}}</h5>
                                            <div class="float-end">
                                                <a  data-size="md" data-url="{{ route('leads.users.edit',$lead->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Add User')}}" class="btn btn-sm btn-primary ">
                                                    <i class="ti ti-plus text-white"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                <tr>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($lead->users as $user)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div>
                                                                    <img @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('/storage/uploads/avatar/avatar.png')}}" @endif class="wid-30 rounded-circle me-3" alt="avatar image">
                                                                </div>
                                                                <p class="mb-0">{{$user->name}}</p>
                                                            </div>
                                                        </td>
                                                        @can('edit lead')
                                                            <td>
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['leads.users.destroy', $lead->id,$user->id],'id'=>'delete-form-'.$lead->id]) !!}
                                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>

                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </td>
                                                        @endcan
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5>{{__('Products')}}</h5>
                                            <div class="float-end">
                                                <a data-size="md" data-url="{{ route('leads.products.edit',$lead->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Add Product')}}" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-plus text-white"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                <tr>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Price')}}</th>
                                                    <th>{{__('Action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($lead->products() as $product)
                                                    <tr>
                                                        <td>
                                                            {{$product->name}}
                                                        </td>
                                                        <td>
                                                            {{\Auth::user()->priceFormat($product->sale_price)}}
                                                        </td>
                                                        @can('edit lead')
                                                            <td>
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['leads.products.destroy', $lead->id,$product->id]]) !!}
                                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>

                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </td>
                                                        @endcan
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="sources_emails">
                        <div class="row">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5>{{__('Sources')}}</h5>
                                            <div class="float-end">
                                            <a data-size="md" data-url="{{ route('leads.sources.edit',$lead->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Add Source')}}" class="btn btn-sm btn-primary">
                                                <i class="ti ti-plus text-white"></i>
                                            </a>
                                        </div>
                                        </div>

                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                <tr>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($sources as $source)
                                                    <tr>
                                                        <td>{{$source->name}} </td>
                                                        @can('edit lead')
                                                            <td>
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['leads.sources.destroy', $lead->id,$source->id],'id'=>'delete-form-'.$lead->id]) !!}
                                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>

                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </td>
                                                        @endcan
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5>{{__('Emails')}}</h5>
                                            @can('create lead email')
                                                <div class="float-end">
                                                    <a data-size="md" data-url="{{ route('leads.emails.create',$lead->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Email')}}" class="btn btn-sm btn-primary">
                                                        <i class="ti ti-plus text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                        </div>

                                    </div>
                                    <div class="card-body">
                                        <div class="list-group list-group-flush mt-2">
                                            @if(!$emails->isEmpty())
                                                @foreach($emails as $email)
                                                    <li class="list-group-item px-0">
                                                        <div class="d-block d-sm-flex align-items-start">
                                                            <img src="{{asset('/storage/uploads/avatar/avatar.png')}}"
                                                                 class="img-fluid wid-40 me-3 mb-2 mb-sm-0" alt="image">
                                                            <div class="w-100">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div class="mb-3 mb-sm-0">
                                                                        <h6 class="mb-0">{{$email->subject}}</h6>
                                                                        <span class="text-muted text-sm">{{$email->to}}</span>
                                                                    </div>
                                                                    <div class="form-check form-switch form-switch-right mb-2">
                                                                        {{$email->created_at->diffForHumans()}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="text-center">
                                                    {{__(' No Emails Available.!')}}
                                                </li>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>--}}
               {{-- <div id="discussion_note">
                        <div class="row">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5>{{__('Discussion')}}</h5>
                                            <div class="float-end">
                                                <a data-size="lg" data-url="{{ route('leads.discussions.create',$lead->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Add Message')}}" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-plus text-white"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush mt-2">
                                            @if(!$lead->discussions->isEmpty())
                                                @foreach($lead->discussions as $discussion)
                                                    <li class="list-group-item px-0">
                                                        <div class="d-block d-sm-flex align-items-start">
                                                            <img src="@if($discussion->user->avatar) {{asset('/storage/uploads/avatar/'.$discussion->user->avatar)}} @else {{asset('/storage/uploads/avatar/avatar.png')}} @endif"
                                                                 class="img-fluid wid-40 me-3 mb-2 mb-sm-0" alt="image">
                                                            <div class="w-100">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div class="mb-3 mb-sm-0">
                                                                        <h6 class="mb-0"> {{$discussion->comment}}</h6>
                                                                        <span class="text-muted text-sm">{{$discussion->user->name}}</span>
                                                                    </div>
                                                                    <div class="form-check form-switch form-switch-right mb-2">
                                                                        {{$discussion->created_at->diffForHumans()}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="text-center">
                                                    {{__(' No Data Available.!')}}
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5>{{__('Notes')}}</h5>
                                            @php
                                                $user = \App\Models\User::find(\Auth::user()->creatorId());
                                                $plan= \App\Models\Plan::getPlan($user->plan);
                                            @endphp
                                            @if($plan->chatgpt == 1)
                                                <div class="float-end">
                                                    <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm" data-ajax-popup-over="true" id="grammarCheck" data-url="{{ route('grammar',['grammar']) }}"
                                                       data-bs-placement="top" data-title="{{ __('Grammar check with AI') }}">
                                                        <i class="ti ti-rotate"></i> <span>{{__('Grammar check with AI')}}</span>
                                                    </a>
                                                    <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['lead']) }}"
                                                       data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
                                                        <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <textarea class="summernote-simple " name="note">{!! $lead->notes !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                   {{-- <div id="files" class="card">
                        <div class="card-header ">
                            <h5>{{__('Files')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12 dropzone top-5-scroll browse-file" id="dropzonewidget"></div>
                        </div>
                    </div>--}}
              
                    <div id="profile">
                       {{-- <div class="card-header ">
                            <h5>{{__('Profile')}}</h5>
                        </div>--}}
                       
                       
                          
                             <div class="row" style="line-height: 2;">
                               
                                <div class="col-md-8 col-sm-8">
                                <div class="card"> 
                                 <div class="card-body">
                                     <h5>{{__('Contact Person Info')}}</h5>
                                <div class="row" >
                                <div class="col-md-6 col-sm-6">
                                    <div class="d-flex align-items-start">
                                         <div class="ms-2">
                                            {{__('Name')}}: <span class="mb-0">{{!empty($lead->customer->name)?$lead->customer->name:''}}</span>
                                        </div>
                                   
                                </div>
                               </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="d-flex align-items-start">
                                        
                                        <div class="ms-2">
                                                 {{__('Email')}}: <span class="mb-0">{{!empty($lead->customer->email)?$lead->customer->email:''}}</span>
                                        </div>
                                   
                                </div>
                               </div>
                                 <div class="col-md-6 col-sm-6">
                                    <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                          
                                           {{__('Number')}}: <span class="mb-0 ">{{!empty($lead->customer->billing_state)?$lead->customer->billing_state:''}}</span>
                                        </div>
                                    </div>
                               </div>
                                  <div class="col-md-6 col-sm-6">
                                    <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                            
                                           {{__('Department')}}: <span class="mb-0 ">{{!empty($lead->customer->billing_city)?$lead->customer->billing_city:''}}</span>
                                        </div>
                                    </div>
                               </div>
                                 <div class="col-md-6 col-sm-6">
                                    <div class="d-flex align-items-start">
                                        <div class="ms-2">
                                           {{__('Designation')}}: <span class="mb-0">{{!empty($lead->customer->billing_country)?$lead->customer->billing_country:''}}</span>
                                        </div>
                                    </div>
                                    </div>
                                     <div class="col-md-6 col-sm-6">
                                    <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                            
                                           {{__('Gender')}}: <span class="mb-0">{{!empty($lead->customer->gender)?$lead->customer->gender:''}}</span>
                                        </div>
                                    </div>
                                    </div>
                                     <div class="col-md-6 col-sm-6">
                                    <div class="d-flex align-items-start">
                                        <div class="ms-2">
                                           {{__('Alternate')}}: <span class="mb-0">{{!empty($lead->customer->billing_phone)?$lead->customer->billing_phone:''}}</span>
                                        </div>
                                    </div>
                                    </div>
                                    </div>
                               
                                </div>
                                </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                     <div class="card">
                                      <div class="card-body">     
                                     <h5 style="padding-left: 5px;">{{__('Categories')}}</h5>
                                      <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                          
                                            {{__('Status')}}: <span class="mb-0">{{!empty($user->name)?$user->name:''}}</span><br>
                                           @if(!empty($lead->products()))
                                                 @foreach($lead->sources() as $source) 
                                                 {{__('Source')}}: <span class="mb-0">{{!empty($source->name)?$source->name:''}}</span><br>
                                                  
                                                   @if (!$loop->last)
                                                        ,
                                                    @endif
                                                   @endforeach
                                                   @else
                                                   -
                                                   @endif
                                           
                                             {{__('Request Type')}}: <span class="mb-0">{{!empty($lead->request_id)?$lead->request_id:''}}</span>
                                           
                                            
                                        </div>
                                    </div>
                                </div>
                           
                            </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        <div class="card">
                        <div class="card-body">
                                @php
                                $customers = \App\Models\Customer::where('lead_id', $lead->id)->get();
                                @endphp
                                @if(count($customers)>0)
                                @foreach($customers->slice(1) as $key => $v)
                             <div class="row" style="line-height: 2;">
                                   <h5>{{__('Contact Person Info')}} ({{$key}})</h5>
                               
                                <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                         <div class="ms-2">
                                            {{__('Name')}}: <span class="mb-0">{{!empty($v->name)?$v->name:''}}</span>
                                        </div>
                                   
                                </div>
                               </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                        
                                        <div class="ms-2">
                                                 {{__('Email')}}: <span class="mb-0">{{!empty($v->email)?$v->email:''}}</span>
                                        </div>
                                   
                                </div>
                               </div>
                                 <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                          
                                           {{__('Number')}}: <span class="mb-0 ">{{!empty($v->billing_state)?$v->billing_state:''}}</span>
                                        </div>
                                    </div>
                               </div>
                                  <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                            
                                           {{__('Department')}}: <span class="mb-0 ">{{!empty($v->billing_city)?$v->billing_city:''}}</span>
                                        </div>
                                    </div>
                               </div>
                                 <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                        <div class="ms-2">
                                           {{__('Designation')}}: <span class="mb-0">{{!empty($v->billing_country)?$v->billing_country:''}}</span>
                                        </div>
                                    </div>
                                    </div>
                                     <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                            
                                           {{__('Gender')}}: <span class="mb-0">{{!empty($v->gender)?$v->gender:''}}</span>
                                        </div>
                                    </div>
                                    </div>
                                     <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                        <div class="ms-2">
                                           {{__('Alternate')}}: <span class="mb-0">{{!empty($v->billing_phone)?$v->billing_phone:''}}</span>
                                        </div>
                                    </div>
                                    </div>
                                    
                             
                           
                            </div>
                             @if (!$loop->last)
                                   <br>
                                @endif
                                @endforeach
                                @endif
                        </div>
                        </div>
                        <div class="card">
                        <div class="card-body">
                            <h5>{{__('Organization')}}</h5>
                             <div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                            
                                            {{__('Sector')}}: <span class="mb-0">{{!empty($lead->name)?$lead->name:''}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                        <div class="ms-2">
                                            {{__('Industry')}}: <span class="mb-0">{{!empty($lead->industry_name)?$lead->industry_name:''}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="d-flex align-items-start">
                                        <div class="ms-2">
                                           
                                              @if(!empty($lead->products()))
                                                 @foreach($lead->products() as $product) 
                                                  {{__('Product')}}: <span class="mb-0">{{$product->name}}</span> 
                                                   @if (!$loop->last)
                                                        ,
                                                    @endif
                                                   @endforeach
                                                   @else
                                                   -
                                                   @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 mt-4">
                                    <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                            {{__('Quantity')}}: <span class="mb-0">{{$lead->quantity}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 mt-4">
                                    <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                            {{__('GST Number')}}: <span class="mb-0">{{ $lead->customer != null?$lead->customer->tax_number:'-'}}</span>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                        </div>
                       
                            
                            <div class="row" style="line-height: 2;">
                                <div class="col-md-8 col-sm-8">
                                    <div class="card">
                                     <div class="card-body">
                                     <h5  style="padding-left: 5px;">{{__('Address & Contact')}}</h5>
                                      <div class="d-flex align-items-start">
                                        <div class="ms-2">
                                            {{__('Address')}}: <span class="mb-0">{{!empty($lead->customer->billing_address)?$lead->customer->billing_address:''}}</span>
                                        </div>
                                    </div>
                                
                                
                                <div class="row" >
                                <div class="col-md-6 col-sm-6">
                                    <div class="d-flex align-items-start">
                                        
                                        <div class="ms-2">
                                            @if(!empty($lead->products()))
                                                 @foreach($lead->sources() as $source) 
                                                 {{__('Website')}}: <span class="mb-0">{{!empty($source->name)?$source->name:''}}</span>
                                                  
                                                   @if (!$loop->last)
                                                        ,
                                                    @endif
                                                   @endforeach
                                                   @else
                                                   -
                                                   @endif
                                            
                                        </div>
                                   
                                </div>
                               </div>
                                 <div class="col-md-6 col-sm-6">
                                    <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                          
                                           {{__('State')}}: <span class="mb-0 ">{{!empty($lead->customer->billing_state)?$lead->customer->billing_state:''}}</span>
                                        </div>
                                    </div>
                               </div>
                                  <div class="col-md-6 col-sm-6">
                                    <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                            
                                           {{__('City')}}: <span class="mb-0 ">{{!empty($lead->customer->billing_city)?$lead->customer->billing_city:''}}</span>
                                        </div>
                                    </div>
                               </div>
                                 <div class="col-md-6 col-sm-6">
                                    <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                            
                                           {{__('Country')}}: <span class="mb-0">{{!empty($lead->customer->billing_country)?$lead->customer->billing_country:''}}</span>
                                        </div>
                                    </div>
                                    </div>
                                    </div>
                                 </div>
                                 </div>
                                </div>
                               
                                <div class="col-md-4 col-sm-4">
                                    <div class="card">
                                    <div class="card-body">
                                     <h5 style="padding-left: 5px;">{{__('Additional')}}</h5>
                                      <div class="d-flex align-items-start">
                                       
                                        <div class="ms-2">
                                            @foreach($lead->users as $key => $user)
                                               @if ($loop->iteration == 2)
                                                @continue
                                                @endif
                                            {{__('Lead Assigned by')}}: <span class="mb-0">{{!empty($user->name)?$user->name:''}}</span><br>
                                            @endforeach
                                            {{__('Lead Owner ')}}: <span class="mb-0">{{!empty($lead->owner->name)?$lead->owner->name:''}}</span><br>
                                             @foreach($lead->users as $user)
                                            
                                             @if ($loop->first)
                                                @continue
                                               
                                            @endif
                                             {{__('Lead Assigned to')}}: <span class="mb-0">{{!empty($user->name)?$user->name:''}}</span>
                                            @endforeach
                                            
                                        </div>
                                    </div>
                                </div>
                           
                            </div>
                        </div>
                        </div>
                        <div class="card">
                        <div class="card-body">
                            <h5>{{__('Key Notes')}}</h5>
                            <div class="col-md-12 dropzone top-5-scroll browse-file" id="getnotedetails" style="text-align: justify;">
                                {!! $lead->notes !!}
                            </div>
                        </div>
                        </div>
                    </div>
                    <div  id="content2" class="card content" style="display:none;">  
                   
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5>{{__('Call Notes')}}</h5>
                                            {{--<div class="float-end">
                                                <a data-size="lg" data-url="{{ route('leads.discussions.create',$lead->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Add Message')}}" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-plus text-white"></i>
                                                </a>
                                            </div>--}}
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush mt-2" id="note-lists">
                                            @if(!$lead->discussions->isEmpty())
                                                @foreach($lead->discussions as $discussion)
                                                    <li class="list-group-item px-0">
                                                        <div class="d-block d-sm-flex align-items-start">
                                                            <img src="@if($discussion->user->avatar) {{asset('/storage/uploads/avatar/'.$discussion->user->avatar)}} @else {{asset('/storage/uploads/avatar/avatar.png')}} @endif"
                                                                 class="img-fluid wid-40 me-3 mb-2 mb-sm-0" alt="image">
                                                            <div class="w-100">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div class="mb-3 mb-sm-0">
                                                                        <h6 class="mb-0"> {{$discussion->comment}}</h6>
                                                                        <span class="text-muted text-sm">{{$discussion->user->name}}</span>
                                                                    </div>
                                                                    <div class="form-check form-switch form-switch-right mb-2">
                                                                        {{$discussion->created_at->diffForHumans()}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="text-center">
                                                    {{__(' No Data Available.!')}}
                                                </li>
                                            @endif
                                        </ul>
                                      

                                        <div class="row">
                                            <div class="col-12 form-group">
                                                {{ Form::label('comment', __('Message'),['class'=>'form-label']) }}
                                                {{ Form::textarea('comment', null, array('class' => 'form-control', 'id'=>'disc-note')) }}
                                                 {{ Form::hidden('id', $lead->id, array('class' => 'form-control', 'id'=>'lead_id')) }}
                                            </div>
                                           
                                        </div> 
                                           
                                        
                                    </div>
                                    
                                    <div class="row" style="padding: 10px;">
                                           <div class="col-3 form-group">
                                               {{ Form::select('stage_id', $stages,null, array('class' => 'form-control select2','id'=>'choices-multiple1')) }}
                                           </div>
                                            <div class="col-9 form-group">
                                                <div class="modal-footer">
                                                    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                                                    <input type="submit" value="{{__('Add')}}" class="btn  btn-primary" id="add-notes">
                                                </div>
                                            </div>    
                                        </div>        
                                   

                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                       {{-- <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5>{{__('Calls')}}</h5>

                                <div class="float-end">
                                <a data-size="lg" data-url="{{ route('leads.calls.create',$lead->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Add Call')}}" class="btn btn-sm btn-primary">
                                    <i class="ti ti-plus text-white"></i>
                                </a>
                            </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th width="">{{__('Subject')}}</th>
                                        <th>{{__('Call Type')}}</th>
                                        <th>{{__('Duration')}}</th>
                                        <th>{{__('User')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($calls as $call)
                                        <tr>
                                            <td>{{ $call->subject }}</td>
                                            <td>{{ ucfirst($call->call_type) }}</td>
                                            <td>{{ $call->duration }}</td>
                                            <td>{{ isset($call->getLeadCallUser) ? $call->getLeadCallUser->name : '-' }}</td>
                                            <td>
                                                @can('edit lead call')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ URL::to('leads/'.$lead->id.'/call/'.$call->id.'/edit') }}" data-ajax-popup="true" data-size="xl" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Call')}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('delete lead call')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['leads.calls.destroy', $lead->id,$call->id],'id'=>'delete-form-'.$lead->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>

                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>--}}
                   
                
                     <div id="content3" class="content" style="display:none;">
                    <div id="activity" class="card">
                        <div class="card-header">
                            <h5>{{__('Activity')}}</h5>
                        </div>
                        <div class="card-body ">

                            <div class="row leads-scroll" >
                                <ul class="event-cards list-group list-group-flush mt-3 w-100">
                                    @if(!$lead->activities->isEmpty())
                                        @foreach($lead->activities as $activity)
                                            <li class="list-group-item card mb-3">
                                                <div class="row align-items-center justify-content-between">
                                                    <div class="col-auto mb-3 mb-sm-0">
                                                        <div class="d-flex align-items-center">
                                                            <div class="theme-avtar bg-primary">
                                                                <i class="ti {{ $activity->logIcon() }}"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="text-dark text-sm">{{ __($activity->log_type) }}</span>
                                                                <h6 class="m-0">{!! $activity->getLeadRemark() !!}</h6>
                                                                <small class="text-muted">{{$activity->created_at->diffForHumans()}}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">

                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @else
                                        No activity found yet.
                                    @endif
                                </ul>
                            </div>

                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
