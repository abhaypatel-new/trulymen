@extends('layouts.admin')
@push('script-page')
@endpush
@section('page-title')
    {{__('Manage Customer-Detail')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('customer.index')}}">{{__('Customer')}}</a></li>
    <li class="breadcrumb-item">{{$customer['name']}}</li>
@endsection
@push('script-page')
    <script>
        function copyToClipboard(element) {

            var copyText = element.id;
            navigator.clipboard.writeText(copyText);
            // document.addEventListener('copy', function (e) {
            //     e.clipboardData.setData('text/plain', copyText);
            //     e.preventDefault();
            // }, true);
            //
            // document.execCommand('copy');
            show_toastr('success', 'Url copied to clipboard', 'success');
        }
    </script>
@endpush

@section('action-btn')
    <div class="float-end">
        @can('create invoice')
            <a href="{{ route('invoice.create',$customer->id) }}" class="btn btn-sm btn-primary">
                {{__('Create Invoice')}}
            </a>
        @endcan
        @can('create proposal')
            <a href="{{ route('proposal.create',$customer->id) }}" class="btn btn-sm btn-primary">
                {{__('Create Proposal')}}
            </a>
        @endcan

        @can('edit customer')
            <a href="#" data-size="lg" data-url="{{ route('customer.edit',$customer['id']) }}" data-ajax-popup="true" title="{{__('Edit Customer')}}" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}" class="btn btn-sm btn-primary">
                <i class="ti ti-pencil"></i>
            </a>
        @endcan

        @can('delete customer')
            {!! Form::open(['method' => 'DELETE','class' => 'delete-form-btn', 'route' => ['customer.destroy', $customer['id']]]) !!}
                <a href="#" data-bs-toggle="tooltip" title="{{__('Delete Customer')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{ $customer['id']}}').submit();" class="btn btn-sm btn-danger bs-pass-para">
                    <i class="ti ti-trash text-white"></i>
                </a>
            {!! Form::close() !!}
        @endcan
    </div>
@endsection

@section('content')
  <div class="row">
                <div class="col-xl-12">
                    
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="lead-sidenav" style="flex-direction: unset;">
                            @if(Auth::user()->type != 'client')
                                {{--<a href="#general" class="list-group-item list-group-item-action border-0">{{__('General')}}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>--}}
                                <div data-target="content1" class="list-group-item list-group-item-action border-0 tab active" style="cursor: pointer;">{{__('Profile')}}
                                    <div class="float-end"></div>
                                </div>
                            @endif

                           
                                <div data-target="content2" data-title="{{ __('Quotataion') }}" class="list-group-item list-group-item-action border-0 tab" style="cursor: pointer;">| {{ __('Quotation')}}
                                    <div class="float-end"></div>
                                </div>
                          
                                <div  data-target="content3" class="list-group-item list-group-item-action border-0 tab" style="cursor: pointer;">{{__('Orders')}}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </div>
                         
                            @if(Auth::user()->type != 'client')
                                <div data-target="content4" class="list-group-item list-group-item-action border-0 tab" style="cursor: pointer;">| {{__('Job Card')}}
                                    <div class="float-end"></i></div>
                                </div>
                            @endif
                           

                        </div>
                    </div>
                </div>
              
            </div>
    <div class="content" id="content1">        
    <div class="row">
        <div class="col-md-4 col-lg-4 col-xl-4 mb-4">
            <div class="card customer-detail-box customer_card">
                <div class="card-body">
                    <h5 class="card-title">{{__('Customer Info')}}</h5>
                    <p class="card-text mb-0">{{$customer['name']}}</p>
                    <p class="card-text mb-0">{{$customer['email']}}</p>
                    <p class="card-text mb-0">{{$customer['contact']}}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-4 col-xl-4 mb-4">
            <div class="card customer-detail-box customer_card">
                <div class="card-body">
                    <h5 class="card-title">{{__('Billing Info')}}</h5>
                    <p class="card-text mb-0">{{$customer['billing_name']}}</p>
                    <p class="card-text mb-0">{{$customer['billing_address']}}</p>
                    <p class="card-text mb-0">{{$customer['billing_city'].', '. $customer['billing_state'] .', '.$customer['billing_zip']}}</p>
                    <p class="card-text mb-0">{{$customer['billing_country']}}</p>
                    <p class="card-text mb-0">{{$customer['billing_phone']}}</p>
                </div>
            </div>

        </div>
        <div class="col-md-4 col-lg-4 col-xl-4 mb-4">
            <div class="card customer-detail-box customer_card">
                <div class="card-body">
                    <h5 class="card-title">{{__('Shipping Info')}}</h5>
                    <p class="card-text mb-0">{{$customer['shipping_name']}}</p>
                    <p class="card-text mb-0">{{$customer['shipping_address']}}</p>
                    <p class="card-text mb-0">{{$customer['shipping_city'].', '. $customer['shipping_state'] .', '.$customer['shipping_zip']}}</p>
                    <p class="card-text mb-0">{{$customer['shipping_country']}}</p>
                    <p class="card-text mb-0">{{$customer['shipping_phone']}}</p>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card pb-0">
                <div class="card-body">
                    <h5 class="card-title">{{__('Company Info')}}</h5>

                    <div class="row">
                        @php
                            $totalInvoiceSum=$customer->customerTotalInvoiceSum($customer['id']);
                            $totalInvoice=$customer->customerTotalInvoice($customer['id']);
                            $averageSale=($totalInvoiceSum!=0)?$totalInvoiceSum/$totalInvoice:0;
                        @endphp
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4">
                                <p class="card-text mb-0">{{__('Customer Id')}}</p>
                                <h6 class="report-text mb-3">{{AUth::user()->customerNumberFormat($customer['customer_id'])}}</h6>
                                <p class="card-text mb-0">{{__('Total Sum of Invoices')}}</p>
                                <h6 class="report-text mb-0">{{\Auth::user()->priceFormat($totalInvoiceSum)}}</h6>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4">
                                <p class="card-text mb-0">{{__('Date of Creation')}}</p>
                                <h6 class="report-text mb-3">{{\Auth::user()->dateFormat($customer['created_at'])}}</h6>
                                <p class="card-text mb-0">{{__('Quantity of Invoice')}}</p>
                                <h6 class="report-text mb-0">{{$totalInvoice}}</h6>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4">
                                <p class="card-text mb-0">{{__('Balance')}}</p>
                                <h6 class="report-text mb-3">{{\Auth::user()->priceFormat($customer['balance'])}}</h6>
                                <p class="card-text mb-0">{{__('Average Sales')}}</p>
                                <h6 class="report-text mb-0">{{\Auth::user()->priceFormat($averageSale)}}</h6>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4">
                                <p class="card-text mb-0">{{__('Overdue')}}</p>
                                <h6 class="report-text mb-3">{{\Auth::user()->priceFormat($customer->customerOverdue($customer['id']))}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   </div>
     <div class="row content" style="display: none;" id="content2">
        <div class="col-md-12">
            <div class="card">
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
                                        @foreach($quotation->items as $item)
                                        @php
                                         $product = \App\Models\ProductService::find($item->product_id);
                                         $quoteProduct = \App\Models\QuotationProduct::where('quotation_id', $quotation->quotation_id)->first();
                                         $total =  $item->price * (!empty($quoteProduct)?$quoteProduct->quantity:1);
                                        @endphp
                                        <td>{{$product->name}}</td>
                                        @if(isset($quoteProduct->tax))
                                        @php 
                                        $taxProduct = \App\Models\Tax::find($quoteProduct->tax);
                                        @endphp
                                        <td>{{!empty($taxProduct)?(($total * $taxProduct->rate/100) + $total):$total }}</td>
                                        @else
                                        <td> {{$item->price}}</td>
                                        @endif
                                        @endforeach
                                        @else
                                        <td>-</td>
                                        <td>-</td>
                                        @endif
                                        <td>{{ Auth::user()->dateFormat($quotation->quotation_date) }}</td>
                                        <td> {{ !empty($quotation->customer) ? $quotation->customer->name : '' }} </td>
                                       
                                         <td>{{ $quotation->status ==0?'Waiting for Approval':'Approved' }}</td>
                                        @if (Gate::check('edit quotation') || Gate::check('delete quotation') || Gate::check('convert quotation'))
                                            <td class="Action">
                                                <span>

                                                    @if ($quotation->is_converted == 0)
                                                        @can('convert quotation')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{ route('poses.index', $quotation->id) }}"
                                                                    class="mx-3 btn btn-sm align-items-center"
                                                                    data-bs-toggle="tooltip" title="{{ __('Convert to POS') }}"
                                                                    data-original-title="{{ __('Detail') }}">
                                                                    <i class="ti ti-exchange text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
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

                                                    @can('edit quotation')
                                                        <div class="action-btn bg-primary ms-2">
                                                            <a href="{{ route('quotation.edit', \Crypt::encrypt($quotation->id)) }}"
                                                                class="mx-3 btn btn-sm align-items-center"
                                                                data-bs-toggle="tooltip" title="Edit"
                                                                data-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('delete quotation')
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
                                                    @endcan
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
    
    <div class="row content" style="display: none;" id="content3">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style table-border-style">
                    <h5 class="d-inline-block mb-5">{{__('Proposal')}}</h5>

                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                            <tr>
                                <th>{{__('Proposal')}}</th>
                                <th>{{__('Issue Date')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Status')}}</th>
                                @if(Gate::check('edit proposal') || Gate::check('delete proposal') || Gate::check('show proposal'))
                                    <th width="10%"> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customer->customerProposal($customer->id) as $proposal)
                                <tr>
                                    <td class="Id">
                                        <a href="{{ route('proposal.show',\Crypt::encrypt($proposal->id)) }}" class="btn btn-outline-primary">{{ AUth::user()->proposalNumberFormat($proposal->proposal_id) }}
                                        </a>
                                    </td>
                                    <td>{{ Auth::user()->dateFormat($proposal->issue_date) }}</td>
                                    <td>{{ Auth::user()->priceFormat($proposal->getTotal()) }}</td>
                                    <td>
                                        @if($proposal->status == 0)
                                            <span class="badge bg-primary p-2 px-3 rounded status_badge">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 1)
                                            <span class="badge bg-warning p-2 px-3 rounded status_badge">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 2)
                                            <span class="badge bg-danger p-2 px-3 rounded status_badge">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 3)
                                            <span class="badge bg-info p-2 px-3 rounded status_badge">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 4)
                                            <span class="badge bg-primary p-2 px-3 rounded status_badge">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @endif
                                    </td>
                                    @if(Gate::check('edit proposal') || Gate::check('delete proposal') || Gate::check('show proposal'))
                                        <td class="Action">
                                            <span>
                                              @if($proposal->is_convert==0)
                                                    @can('convert invoice')
                                                        <div class="action-btn bg-warning ms-2">
                                                        {!! Form::open(['method' => 'get', 'route' => ['proposal.convert', $proposal->id],'id'=>'proposal-form-'.$proposal->id]) !!}
                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" data-original-title="{{__('Convert to Invoice')}}" title="{{__('Convert to Invoice')}}" data-confirm="You want to confirm convert to invoice. Press Yes to continue or Cancel to go back" data-confirm-yes="document.getElementById('proposal-form-{{$proposal->id}}').submit();">
                                                                <i class="ti ti-exchange text-white"></i>
                                                            </a>
                                                         {!! Form::close() !!}
                                                    </div>
                                                    @endcan
                                                @else
                                                    @can('convert invoice')
                                                        <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ route('invoice.show',\Crypt::encrypt($proposal->converted_invoice_id)) }}"
                                                           class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Already convert to Invoice')}}" data-original-title="{{__('Already convert to Invoice')}}" >
                                                            <i class="ti ti-file text-white"></i>
                                                        </a>
                                                    </div>
                                                    @endcan
                                                @endif
                                                @can('duplicate proposal')
                                                    <div class="action-btn bg-primary ms-2">
                                                    {!! Form::open(['method' => 'get', 'route' => ['proposal.duplicate', $proposal->id],'id'=>'duplicate-form-'.$proposal->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" data-original-title="{{__('Duplicate')}}"  title="{{__('Duplicate Proposal')}}" data-confirm="You want to confirm duplicate this invoice. Press Yes to continue or Cancel to go back" data-confirm-yes="document.getElementById('duplicate-form-{{$proposal->id}}').submit();">
                                                            <i class="ti ti-copy text-white text-white"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                                @can('show proposal')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('proposal.show',\Crypt::encrypt($proposal->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Show')}}" data-original-title="{{__('Detail')}}">
                                                            <i class="ti ti-eye text-white text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('edit proposal')
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="{{ route('proposal.edit',\Crypt::encrypt($proposal->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('delete proposal')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['proposal.destroy', $proposal->id],'id'=>'delete-form-'.$proposal->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip"  title="Delete" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$proposal->id}}').submit();">
                                                            <i class="ti ti-trash text-white text-white"></i>
                                                         </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
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
    <div class="row content" style="display: none;" id="content4">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style table-border-style">
                    <h5 class="d-inline-block mb-5">{{__('Invoice')}}</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{__('Invoice')}}</th>
                                <th>{{__('Issue Date')}}</th>
                                <th>{{__('Due Date')}}</th>
                                <th>{{__('Due Amount')}}</th>
                                <th>{{__('Status')}}</th>
                                @if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                    <th width="10%"> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($customer->customerInvoice($customer->id) as $invoice)
                                <tr>
                                    <td class="Id">
                                        <a href="{{ route('invoice.show',\Crypt::encrypt($invoice->id)) }}" class="btn btn-outline-primary">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}
                                        </a>
                                    </td>
                                    <td>{{ \Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                    <td>
                                        @if(($invoice->due_date < date('Y-m-d')))
                                            <p class="text-danger"> {{ \Auth::user()->dateFormat($invoice->due_date) }}</p>
                                        @else
                                            {{ \Auth::user()->dateFormat($invoice->due_date) }}
                                        @endif
                                    </td>
                                    <td>{{\Auth::user()->priceFormat($invoice->getDue())  }}</td>
                                    <td>
                                        @if($invoice->status == 0)
                                            <span class="badge bg-primary p-2 px-3 rounded status_badge">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 1)
                                            <span class="badge bg-warning p-2 px-3 rounded status_badge">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 2)
                                            <span class="badge bg-danger p-2 px-3 rounded status_badge">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 3)
                                            <span class="badge bg-info p-2 px-3 rounded status_badge">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 4)
                                            <span class="badge bg-primary p-2 px-3 rounded status_badge">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @endif
                                    </td>
                                    @if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                        <td class="Action">
                                            <span>
                                                @can('copy invoice')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a class="mx-3 btn btn-sm align-items-center"  id="{{ route('invoice.link.copy',$invoice->id) }}"  onclick="copyToClipboard(this)" data-bs-toggle="tooltip" data-original-title="{{__('Copy Invoice')}}"><i class="ti ti-link text-white"></i></a>
                                                    </div>
                                                @endcan
                                                @can('duplicate invoice')
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" data-original-title="{{__('Duplicate')}}" title="{{__('Duplicate Invoice')}}" data-confirm="You want to confirm this action. Press Yes to continue or Cancel to go back" data-confirm-yes="document.getElementById('duplicate-form-{{$invoice->id}}').submit();">
                                                            <i class="ti ti-copy text-white text-white"></i>
                                                            {!! Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id],'id'=>'duplicate-form-'.$invoice->id]) !!}
                                                            {!! Form::close() !!}
                                                        </a>
                                                    </div>
                                                @endcan
                                                    @can('show invoice')
                                                        <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('invoice.show',\Crypt::encrypt($invoice->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Show')}}" data-original-title="{{__('Detail')}}">
                                                            <i class="ti ti-eye text-white text-white"></i>
                                                        </a>
                                                    </div>
                                                    @endcan
                                                    @can('edit invoice')
                                                        <div class="action-btn bg-primary ms-2">
                                                            <a href="{{ route('invoice.edit',\Crypt::encrypt($invoice->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('delete invoice')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id],'id'=>'delete-form-'.$invoice->id]) !!}

                                                            <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$invoice->id}}').submit();">
                                                                <i class="ti ti-trash text-white text-white"></i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endcan
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
@push('script-page')
   <script>
    $(document).ready(function(){
       
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
</script>
@endpush

