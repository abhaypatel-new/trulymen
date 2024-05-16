 @extends('layouts.admin')
@section('page-title')
    {{__('Manage Material & Specifications')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Material & Specifications')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
       {{-- <a href="#" data-size="md"  data-bs-toggle="tooltip" title="{{__('Import')}}" data-url="{{ route('productservice.file.import') }}" data-ajax-popup="true" data-title="{{__('Import product CSV file')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-import"></i>
        </a>--}}
        {{--<a href="{{route('productspecification.export')}}" data-bs-toggle="tooltip" title="{{__('Export')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-export"></i>
        </a>--}}

        <a href="#" data-size="xl" data-url="{{ route('productspecification.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Product')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>

    </div>
@endsection

@section('content')
   
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Sr.')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Prefix')}}</th>
                                <th>{{__('Price')}}</th>
                                <th>{{__('Image')}}</th>
                                <th>{{__('Priority')}}</th>
                                <th>{{__('Created_by')}}</th>
                                <th>{{__('Created Date')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($productServices as $productService)
                                <tr class="font-style">
                                     <td> <div class="number-color action-btn bg-primary ms-2" style="font-size:12px;">
                                                   {{ $productService->id }}</div></td>
                                    <td>{{ $productService->name}}</td>
                                    <td>{{ $productService->prefix }}</td>
                                    <td>
                                        @php
                                         $sum = \App\Models\Specification::where('priority', '=', $productService->id)->sum('price');
                                        @endphp
                                        {{ $sum }}</td>
                                    <td><a href="#" class="b-brand">
                                        @php
                                        $img = $productService->image == null?'default.png':$productService->image;
                                        @endphp
                                        <img src="{{ asset(Storage::url('uploads/pro_image/'.$img)) }}" alt="specification" class="img-thumbnail" /> 
                                        </a>
                                    </td>
                                    <td>{{ $productService->priority == 0?'Main':'Sub Specification'}}</td>
                                    <td>{{ $productService->users->name}}</td>
                                    <td>{{  \Carbon\Carbon::parse($productService->created_at)->format('d M Y') }}</td>
                                    @if(Gate::check('edit product & service') || Gate::check('delete product & service'))
                                        <td class="Action">

                                           {{-- <div class="action-btn bg-warning ms-2">
                                                <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('productspecification.detail',$productService->id) }}"
                                                   data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Warehouse Details')}}" data-title="{{__('Warehouse Details')}}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>--}}

                                            @can('edit product & service')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('productspecification.edit',$productService->id) }}" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Edit Materials')}}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('delete product & service')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['productservice.destroy', $productService->id],'id'=>'delete-form-'.$productService->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti ti-trash text-white"></i></a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan
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

