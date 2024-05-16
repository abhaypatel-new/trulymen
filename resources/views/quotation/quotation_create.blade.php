
@extends('layouts.admin')
@section('page-title')
    {{__('Quotation Create')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('quotation.index')}}">{{__('Quotation')}}</a></li>
    <li class="breadcrumb-item">{{__('Quotation Create')}}</li>
@endsection
@push('script-page')
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('js/jquery.repeater.min.js')}}"></script>
    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function () {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
                    $('.select2').select2();
                },
                hide: function (deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                        $(this).remove();

                        var inputs = $(".amount");
                        var subTotal = 0;
                        for (var i = 0; i < inputs.length; i++) {
                            subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                        }
                        $('.subTotal').html(subTotal.toFixed(2));
                        $('.totalAmount').html(subTotal.toFixed(2));
                    }
                },
                ready: function (setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');
            console.log(value);
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
                for (var i = 0; i < value.length; i++) {
                    var tr = $('#sortable-table .id[value="' + value[i].id + '"]').parent();
                    tr.find('.item').val(value[i].product_id);
                    changeItem(tr.find('.item'));
                }
            }
        }

        // $(document).on('change', '#vender', function () {
        //     $('#vender_detail').removeClass('d-none');
        //     $('#vender_detail').addClass('d-block');
        //     $('#vender-box').removeClass('d-block');
        //     $('#vender-box').addClass('d-none');
        //     var id = $(this).val();
        //     var url = $(this).data('url');
        //     $.ajax({
        //         url: url,
        //         type: 'POST',
        //         headers: {
        //             'X-CSRF-TOKEN': jQuery('#token').val()
        //         },
        //         data: {
        //             'id': id
        //         },
        //         cache: false,
        //         success: function (data) {
        //             if (data != '') {
        //                 $('#vender_detail').html(data);
        //             } else {
        //                 $('#vender-box').removeClass('d-none');
        //                 $('#vender-box').addClass('d-block');
        //                 $('#vender_detail').removeClass('d-block');
        //                 $('#vender_detail').addClass('d-none');
        //             }
        //         },
        //     });
        // });

        // $(document).on('click', '#remove', function () {
        //     $('#vender-box').removeClass('d-none');
        //     $('#vender-box').addClass('d-block');
        //     $('#vender_detail').removeClass('d-block');
        //     $('#vender_detail').addClass('d-none');
        // })

        $(document).on('change', '.item', function () {
            var iteams_id = $(this).val();
            var url = $(this).data('url');
            var el = $(this);
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'product_id': iteams_id
                },
                cache: false,
                success: function (data) {
                    var item = JSON.parse(data);

                    if(item.productquantity < 1)
                    {
                        show_toastr('Error', "{{__('This product is out of stock!')}}", 'error');
                        return false;
                    }
                    
                    $(el.parent().parent().parent().find('.quantity')).val(1);
                    $(el.parent().parent().parent().find('.model')).val(item.product.model);
                    $(el.parent().parent().parent().find('.hsnCode')).val(item.product.hsn_code);
                    $(el.parent().parent().parent().find('.price')).val(item.product.sale_price);
                    $(el.parent().parent().parent().parent().find('.pro_description')).val(item.product.description);
                    console.log(el);
                    var taxes = '';
                    var tax = [];

                    var totalItemTaxRate = 0;
                    if (item.taxes == 0) {
                        taxes += '-';
                    } else {
                        for (var i = 0; i < item.taxes.length; i++) {

                            taxes += '<span class="badge bg-primary mt-1 mr-2">' + item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' + '</span>';
                            tax.push(item.taxes[i].id);
                            totalItemTaxRate += parseFloat(item.taxes[i].rate);

                        }
                    }
                    var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (item.product.sale_price * 1));

                    $(el.parent().parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                    $(el.parent().parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                    $(el.parent().parent().parent().find('.taxes')).html(taxes);
                    $(el.parent().parent().parent().find('.tax')).val(tax);
                    $(el.parent().parent().parent().find('.unit')).html(item.unit);
                    $(el.parent().parent().parent().find('.discount')).val(0);
                    $(el.parent().parent().parent().find('.amount')).html(item.totalAmount);


                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                    }
                    $('.subTotal').html(subTotal.toFixed(2));


                    var totalItemPrice = 0;
                    var priceInput = $('.price');
                    for (var j = 0; j < priceInput.length; j++) {
                        totalItemPrice += parseFloat(priceInput[j].value);
                    }

                    var totalItemTaxPrice = 0;
                    var itemTaxPriceInput = $('.itemTaxPrice');
                    for (var j = 0; j < itemTaxPriceInput.length; j++) {
                        totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                    }

                    $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                    $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));

                },
            });
        });

        $(document).on('keyup', '.quantity', function () {
            var quntityTotalTaxPrice = 0;

            var el = $(this).parent().parent().parent().parent();
            var quantity = $(this).val();
            if(quantity.length == 1)
            {                
                var quantity = 0 + $(this).val();
            }
            var price = $(el.find('.price')).val();
            var item_id = $(el.find('.item')).val();

            $.ajax({
                url: '{{ route('product.quantity') }}',
                type: 'POST',
                data: {
                    "item_id": item_id,
                    "quantity":quantity,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {

                    if(data < quantity)
                    {
                        show_toastr('Error', "{{__('This product is out of stock!')}}", 'error');
                        return false;
                    }

            var discount = $(el.find('.discount')).val();
            if(discount.length <= 0)
            {
                discount = 0 ;
            }

            var totalItemPrice = (quantity * price) - discount;
            var amount = (totalItemPrice);


            var amount = (totalItemPrice);


            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

            $(el.find('.amount')).html(parseFloat(itemTaxPrice)+parseFloat(amount));

            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var totalItemPrice = 0;
            var inputs_quantity = $(".quantity");

            var priceInput = $('.price');
            for (var j = 0; j < priceInput.length; j++) {
                totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
            }

            var inputs = $(".amount");

            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }

            $('.subTotal').html(amount.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
        }
            });
        })

        $(document).on('keyup change', '.price', function () {
            var el = $(this).parent().parent().parent().parent();
            var price = $(this).val();
            var quantity = $(el.find('.quantity')).val();

            var discount = $(el.find('.discount')).val();
            if(discount.length <= 0)
            {
                discount = 0 ;
            }
            var totalItemPrice = (quantity * price)-discount;

            var amount = (totalItemPrice);


            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

            $(el.find('.amount')).html(parseFloat(itemTaxPrice)+parseFloat(amount));

            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var totalItemPrice = 0;
            var inputs_quantity = $(".quantity");

            var priceInput = $('.price');
            for (var j = 0; j < priceInput.length; j++) {
                totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
            }

            var inputs = $(".amount");

            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }

            $('.subTotal').html(totalItemPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));


        })

        $(document).on('keyup change', '.discount', function () {
            var el = $(this).parent().parent().parent();
            var discount = $(this).val();
            if(discount.length <= 0)
            {
                discount = 0 ;
            }

            var price = $(el.find('.price')).val();
            var quantity = $(el.find('.quantity')).val();
            var totalItemPrice = (quantity * price) - discount;


            var amount = (totalItemPrice);


            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

            $(el.find('.amount')).html(parseFloat(itemTaxPrice)+parseFloat(amount));

            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var totalItemPrice = 0;
            var inputs_quantity = $(".quantity");

            var priceInput = $('.price');
            for (var j = 0; j < priceInput.length; j++) {
                totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
            }

            var inputs = $(".amount");

            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }


            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
            }


            $('.subTotal').html(totalItemPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));




        })

        var vendorId = '{{$customer}}';
        if (vendorId > 0) {
            $('#vender').val(vendorId).change();
        }

    </script>

    <script>
        $(document).on('click', '[data-repeater-delete]', function () {
            $(".price").change();
            $(".discount").change();
        });
    </script>
@endpush
@php

@endphp
@section('content')
    <div class="row">
        {{ Form::open(array('url' => 'quotation','class'=>'w-100')) }}
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            
            <!--Key-->
            <div class="card">
                 <div class="card-header">
                                      
                     <h5>{{__('Key Point')}}</h5>
                  </div>
                <div class="card-body">
                    
                    <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('quotation_number', __('Quote Ref No.'),['class'=>'form-label']) }}
                                        {{ Form::text('quotation_number', $quotation_number, ['class' => 'form-control', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('quotation_date', __('Quote Date'),['class'=>'form-label']) }}
                                       {{Form::date('quotation_date',null,array('class'=>'form-control','required'=>'required'))}}

                                    </div>
                                </div>
                                {{--<div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('warehouse_id', __('Enquiry Ref'),['class'=>'form-label']) }}
                                        {{ Form::select('warehouse_id', $warehouse,null, array('class' => 'form-control select warehouse_id','required'=>'required')) }}
                                    </div>
                                </div>--}}
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                         {{ Form::label('enquiry_ref', __('Enquiry Ref'),['class'=>'form-label']) }}
                                         @if(!empty($lead->products()))
                                                 @foreach($lead->sources() as $source) 
                                                 
                                                   {{ Form::text('enquiry_ref', $source->name, ['class' => 'form-control', 'readonly' => 'readonly']) }}
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
                            <div class="col-md-6">
                                    <div class="form-group">
                                    {{ Form::label('customer_id', __('GST Number'),['class'=>'form-label']) }}
                                    {{ Form::text('gst_number', $leadCustomer->tax_number, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                    </div>
                </div>
                
            <!--Organizations-->
             <div class="card">
                 <div class="card-header">
                                      
                     <h5>{{__('Organization Details')}}</h5>
                  </div>
                <div class="card-body">
                    
                    <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('company_name', __('Companey Name'),['class'=>'form-label']) }}
                                        {{ Form::text('company_name', $lead->industry_name, ['class' => 'form-control', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                                <div class="col-md-4">
                                     <div class="col-sm-1" style="width:0%;position: absolute;">
                                        <div class="dropdown" style="padding: 30.5px 1px;">
                                            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;border-radius: 5px 0px 0px 5px;height: 35px;">
                                            <a href="#"><img src="{{ asset('assets/images/india.png') }}" width="30" alt="india"/> </a>
                                            </button>
                             
                                        </div>
                                     </div>
                                    <div class="form-group">
                                        {{ Form::label('email', __('Email'),['class'=>'form-label']) }}
                                        {{ Form::text('email', $lead->email, ['class' => 'form-control', 'style' => 'padding-left:90px', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('phone', __('Number'),['class'=>'form-label']) }}
                                        {{ Form::text('phone', $lead->phone, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter Phone'))) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('ploat_no', __('Plot No.'),['class'=>'form-label']) }}
                                        {{ Form::text('ploat_no', '34565456', ['class' => 'form-control', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                            
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('street_name', __('Street Name'),['class'=>'form-label']) }}
                                    {{ Form::text('street_name', $leadCustomer->tax_number, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                             <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('area_name', __('Area Name'),['class'=>'form-label']) }}
                                    {{ Form::text('area_name', $lead->name, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" value="{{$leadCustomer->lead_id}}" name="lead_id">
                                    <div class="form-group">
                                    {{ Form::label('pincode', __('Pincode'),['class'=>'form-label']) }}
                                    {{ Form::text('pincode', $leadCustomer->billing_zip, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('city', __('City'),['class'=>'form-label']) }}
                                    {{ Form::text('city', $leadCustomer->billing_city, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('state', __('State'),['class'=>'form-label']) }}
                                    {{ Form::text('state', $leadCustomer->billing_state, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('country', __('Country'),['class'=>'form-label']) }}
                                    {{ Form::text('country', $leadCustomer->billing_country, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('attention', __('Kind Attention'),['class'=>'form-label']) }}
                                    {{ Form::text('attention', auth()->user()->name, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                    </div>
                </div>    
            </div>
            
            <!--Subject-->
            
            <div class="card">
                 <div class="card-header">
                                      
                     <h5>{{__('Subject')}}</h5>
                  </div>
                <div class="card-body">
                    <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                       {{ Form::label('subject', __('Add Subject'),['class'=>'form-label']) }}
                                       
                                       <div class="form-group">{!! Form::textarea('description', $lead->notes , ['class'=>'form-control pro_description','rows'=>'2','placeholder'=>__('Description')]) !!}</div>

                                    </div>
                                </div>
                    </div>
                </div>
            </div>
            
            <!--Quotation Template-->
             <div class="card">
                 <div class="card-header">
                                      
                     <h5>{{__('Quotation Template')}}</h5>
                  </div>
                <div class="card-body">
                    <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                       {{ Form::label('quotation_template', __('Add Content'),['class'=>'form-label']) }}
                                       <div class="form-group">{{ Form::textarea('quotation_template', 'Dear sir

with reference to your enquiry for Requirment of cable flat label switch recharge (LCF-R), we are please to submit here unde our offer 
for the same.

"TRUMEN" is an ISO 9001-2015  manufacturer and experier at level control instruments and our list of instrument', ['class'=>'form-control pro_description','rows'=>'5','placeholder'=>__('Please Add Content Here...') ,'readonly' => 'readonly']) }}</div>

                                    </div>
                                </div>
                    </div>
                </div>
            </div>
            
            <!--Terms & Conditions-->
             <div class="card">
                 <div class="card-header">
                                      
                     <h5>{{__('Terms & Conditions')}}</h5>
                  </div>
                <div class="card-body">
                    <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                       {{ Form::label('terms_conditions', __('Add Content'),['class'=>'form-label']) }}
                                       <div class="form-group">{{ Form::textarea('terms_conditions', 'Add Content

1.PRICES : EX WORKS, INDORE

1.P & F  : EXTRA @ 2.5%

1.TAXES   : GST EXTRA @ 18%

1.FREIGHT   : EXTRA AT ACTUALS THROUGH YOUR APPROVED FREIGHT CARRIER.

1.TRANSIT INSURANCE : EXTRA TO YOUR ACCOUNT.

1.DELIVERY : WITHIN 03-04 WEEKS AFTER CONFIRMED ORDER.

1.PAYMENT : 100% AGAINST PROFORMA INVOICE PRIOR TO DISPATCH.

1.WARRANTY : 60 DAYS

1.VALIDITY OF OFFER :WELVE MONTHS FROM THE DATE OF COMMISSIONING OR FIFTEENN  MONTHS FROM THE DATE OF SUPPLY WHICH EVER IS EARLIER.

1.RELEASE OF PO : FORMAL ORDER MENTIONING YOUR VAT, TIN, CST & DISPATCH  INSTRUCTIONS.

1.CANCELLATION CHARGES : 30% OF PO AMOUNT TO BE PAID IF CANCELLED AFTER  ORDER ACCEPTANCE

Trust our offer is in line with your requirement. Please feel free to contact us for further assistance. We look forward to your valued order
Thanks and Warm Regards.', ['class'=>'form-control pro_description','rows'=>'10','placeholder'=>__('Please Add Terms & Conditions Content Here...'),'readonly' => 'readonly']) }}</div>

                                    </div>
                                </div>
                    </div>
                </div>
            </div>
            
            <!--Sender Address-->
             <div class="card">
                 <div class="card-header">
                                      
                     <h5>{{__('Sendor Address')}}</h5>
                  </div>
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="customer_id" value="{{$leadCustomer->id}}">
                               <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('sender_name', __('Sender Name'),['class'=>'form-label']) }}
                                        {{ Form::text('sender_name', $leadCustomer->name, ['class' => 'form-control', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        {{ Form::label('address', __('Address'),['class'=>'form-label']) }}
                                        {{ Form::text('address', $leadCustomer->billing_address, ['class' => 'form-control', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                                <div class="col-md-4">
                                     <div class="col-sm-1" style="width:0%;position: absolute;">
                                        <div class="dropdown" style="padding: 30.5px 1px;">
                                            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;border-radius: 5px 0px 0px 5px;height: 35px;">
                                            <a href="#"><img src="{{ asset('assets/images/india.png') }}" width="30" alt="india"/> </a>
                                            </button>
                             
                                        </div>
                                     </div>
                                     
                                    <div class="form-group">
                                        {{ Form::label('sender_number', __('Number'),['class'=>'form-label']) }}
                                        {{ Form::text('sender_number', $leadCustomer->contact, ['class' => 'form-control', 'style' => 'padding-left:90px', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('sender_email', __('Email'),['class'=>'form-label']) }}
                                        {{ Form::text('sender_email', $leadCustomer->email, ['class' => 'form-control', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('sender_website', __('Website'),['class'=>'form-label']) }}
                                        {{ Form::text('sender_website', 'www.trumen.com', ['class' => 'form-control', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        @php
        $applications = [
        'sand' => 'Sand', 'liquid' => 'Liquid', 'solid' => 'Solid'
        ];
        @endphp
        <div class="col-12">
            <h5 class=" d-inline-block mb-4">{{__('Product & Services')}}</h5>
            <div class="card repeater" data-value=''>
                <div class="item-section py-2">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="all-button-box me-2">
                                <a href="#" data-repeater-create="" class="btn btn-primary" data-bs-toggle="modal" data-target="#add-bank">
                                    <i class="ti ti-plus"></i> {{__('Add item')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0" data-repeater-list="items" id="   ">
                            <thead>
                            <tr>
                                <th>{{__('Add Product')}}</th>
                                <th>{{__('Application')}}</th>
                                <th>{{__('Model')}}</th>
                                <th>{{__('HSN Code')}}</th>
                                <th>{{__('Qty')}}</th>
                                <th>{{__('Unit Rate')}} </th>
                               {{-- <th>{{__('Discount')}}</th>
                                <th>{{__('Tax')}} (%)</th>--}}
                                <th class="text-end">{{__('Total (INR)')}} <br><small class="text-danger font-weight-bold">{{__('after tax & discount')}}</small></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="ui-sortable" data-repeater-item>
                            <tr>
                                <td width="20%" class="form-group pt-1">
                                    <div class="item_div">
                                        {{-- <select class="form-control item" name="item" placeholder="Select Employee">
                                            <option value="">{{ __('--') }}</option>
                                        </select> --}}
                                        {{ Form::select('item', $product_services,'', array('class' => 'form-control select2 item','data-url'=>route('quotation.product'),'required'=>'required')) }}
                                    </div>
                                </td>
                                 <td>
                                    <div width="10%" class="form-group pt-1">
                                        {{ Form::select('application', $applications,'null', array('class' => 'form-control select2','required'=>'required')) }}
                                       
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        {{ Form::text('model','', array('class' => 'form-control model','required'=>'required','placeholder'=>__('Model'),'required'=>'required')) }}
                                       
                                    </div>
                                </td>
                                 <td>
                                    <div class="form-group">
                                        {{ Form::text('hsn_code','', array('class' => 'form-control hsnCode','required'=>'required','placeholder'=>__('HSN Code'),'required'=>'required')) }}
                                       
                                    </div>
                                </td>
                               <td>
                                    <div class="form-group price-input input-group search-form">
                                    {{ Form::text('quantity','', array('class' => 'form-control quantity','required'=>'required','placeholder'=>__('Qty'),'required'=>'required')) }}
                                        <span class="unit input-group-text bg-transparent"></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form">
                                        {{ Form::text('price','', array('class' => 'form-control price','required'=>'required','placeholder'=>__('Price'),'required'=>'required')) }}
                                        <span class="input-group-text bg-transparent">{{\Auth::user()->currencySymbol()}}</span>
                                    </div>
                                </td>
                               
                                    
                                        {{ Form::hidden('discount','', array('class' => 'form-control discount','required'=>'required','placeholder'=>__('Discount'))) }}
                                       
                                 
                                {{--<td>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="taxes d-none"></div>--}}
                                            {{ Form::hidden('tax','', array('class' => 'form-control tax')) }}
                                            {{ Form::hidden('itemTaxPrice','', array('class' => 'form-control itemTaxPrice')) }}
                                            {{ Form::hidden('itemTaxRate','', array('class' => 'form-control itemTaxRate')) }}
                                       {{-- </div>
                                    </div>
                                </td>--}}

                                <td class="text-end amount">
                                    0.00
                                </td>
                                <td>
                                    <a href="#" class="ti ti-trash text-white text-white repeater-action-btn bg-danger ms-2" data-repeater-delete></a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">{{ Form::textarea('description', null, ['class'=>'form-control pro_description','rows'=>'2','placeholder'=>__('Description')]) }}</div>
                                </td>
                                <td colspan="5"></td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{__('Sub Total')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-end subTotal">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{__('Discount')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-end totalDiscount">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td><strong>{{__('Tax')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-end totalTax">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="blue-text"><strong>{{__('Total Amount')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="blue-text text-end totalAmount">0.00</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" onclick="location.href = '{{route("quotation.index")}}';" class="btn btn-light">
            <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
        </div>
    {{ Form::close() }}
    </div>

@endsection

