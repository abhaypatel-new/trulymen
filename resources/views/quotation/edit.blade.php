@extends('layouts.admin')
@section('page-title')
    {{__('Quotation Edit')}}
@endsection
@section('breadcrumb')
   
@endsection
@push('script-page')

    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('js/jquery.repeater.min.js')}}"></script>
   
    <script>
      $(document).ready(function() {
          
            $('.application').click(function() {
        // Define the select options
        var options = [
            { value: '1', text: 'Sand' },
            { value: '2', text: 'Liquid' },
            { value: '3', text: 'Solid' }
        ];

        // Create the select element
        var select = $('<select></select>').attr('id', 'applicationSelect').attr('name', 'application').addClass('form-control application');

        // Populate the select with options
        $.each(options, function(index, option) {
            select.append($('<option></option>').attr('value', option.value).text(option.text));
        });

        // Replace the text input with the select element
        $(this).replaceWith(select);
    });
    
    // Your CSS styling goes here
    $('.page-header-title').css('display', 'none');
    });
       var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: true,
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
                    if($('.select2').length) {
                        $('.select2').select2();
                    }
                },
                hide: function (deleteElement) {

                    $(this).slideUp(deleteElement);
                    $(this).remove();
                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                    }
                    $('.subTotal').html(subTotal.toFixed(2));
                    $('.totalAmount').html(subTotal.toFixed(2));

                },
                ready: function (setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');
            
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
        // });

        var quotation_id = '{{$quotation->id}}';

        function changeItem(element) {
            var iteams_id = element.val();

            var url = element.data('url');
            var el = element;
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
                      console.log(item);
                      if(item != null)
                      {
                          
                          
                                $(el.parent().parent().parent().find('.quantity')).val(1);
                                $(el.parent().parent().parent().find('.id')).val(item.product.id);
                                $(el.parent().parent().parent().find('.price')).val(item.product.sale_price);
                                $(el.parent().parent().parent().find('.model')).val(item.product.model);
                                $(el.parent().parent().parent().find('.hsnCode')).val(item.product.hsn_code);
                                $(el.parent().parent().parent().find('.amount')).html(item.product.sale_price);
                               
                                $(el.parent().parent().parent().find('.discount')).val(0);
                                $(el.parent().parent().parent().find('.pro_description')).val(item.product.sale_price);
                                $(el.parent().parent().parent().parent().find('.pro_description')).val(item.product.description);
                                 var Ftax = (parseFloat(item.product.labor_charge) + parseFloat(item.product.other_cost));
                            //   $('.totalTax').html(Ftax.toFixed(2));  
                                 var inputs = $(".amount");
                           var ttax = $(".totalTax");
                           var TotalTax = 0;
                            var subTotal = 0;
                            for (var i = 0; i < inputs.length; i++) {
                                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                            }
                             for (var i = 0; i < ttax.length; i++) {
                                TotalTax = parseFloat(TotalTax) + parseFloat($(ttax[i]).html());
                            }
                            var l = parseFloat(item.product.labor_charge);
                            var o = parseFloat(item.product.other_cost);
                           
                            console.log(Ftax)
                            console.log(subTotal)
                             console.log("lsnot"+l)
                              console.log("oyhrt"+o)
                              var gtotal = Ftax+subTotal;
                            $('.subTotal').html(subTotal.toFixed(2));
                            // $('.totalTax').html(TotalTax.toFixed(2));
                            $('.totalAmount').html(gtotal.toFixed(2));
                                
                      }
                    $.ajax({
                        url: '{{route('quotation.items')}}',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': jQuery('#token').val()
                        },
                        data: {
                            'quotation_id': quotation_id,
                            'product_id': iteams_id,
                        },
                        cache: false,
                        success: function (data) {
                            var quotationItems = JSON.parse(data);
                          
                            if(quotationItems.productquantity < 1)
                    {
                        show_toastr('Error', "{{__('This product is out of stock!')}}", 'error');
                        return false;
                    }

                            if (quotationItems != null) {
                              
                                var amount = (quotationItems.price * quotationItems.quantity);
                                $(el.parent().parent().parent().find('.quantity')).val(quotationItems.quantity);
                                $(el.parent().parent().parent().find('.id')).val(quotationItems.product_id);
                                $(el.parent().parent().parent().find('.model')).val(item.product.model);
                                $(el.parent().parent().parent().find('.hsnCode')).val(item.product.hsn_code);
                                $(el.parent().parent().parent().find('.price')).val(quotationItems.price);
                                $(el.parent().parent().parent().find('.discount')).val(quotationItems.discount);
                                $(el.parent().parent().parent().parent().find('.pro_description')).val(quotationItems.description);

                                // $('.pro_description').text(purchaseItems.description);
                            } else {
                                 
                                $(el.parent().parent().parent().find('.quantity')).val(1);
                                $(el.parent().parent().parent().find('.id')).val(quotationItems.product_id);
                                $(el.parent().parent().parent().find('.price')).val(item.product.sale_price);
                                $(el.parent().parent().parent().find('.discount')).val(0);
                                $(el.parent().parent().parent().find('.pro_description')).val(item.product.sale_price);
                                $(el.parent().parent().parent().parent().find('.pro_description')).val(item.product.description);

                            }

                            var taxes = '';
                            var tax = [];

                            var totalItemTaxRate = 0;
                            for (var i = 0; i < item.taxes.length; i++) {

                                taxes += '<span class="badge bg-primary p-2 px-3 rounded mt-1 mr-1">' + item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' + '</span>';
                                tax.push(item.taxes[i].id);
                                totalItemTaxRate += parseFloat(item.taxes[i].rate);

                            }

                            var discount=$(el.parent().parent().parent().find('.discount')).val();

                            if (quotationItems != null) {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100)) * parseFloat((quotationItems.price * quotationItems.quantity)- discount);
                            } else {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100)) * parseFloat((item.product.sale_price * 1)- discount);
                            }


                            $(el.parent().parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                            $(el.parent().parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                            $(el.parent().parent().parent().find('.taxes')).html(taxes);
                            $(el.parent().parent().parent().find('.tax')).val(tax);
                            $(el.parent().parent().parent().find('.unit')).html(item.unit);


                            var inputs = $(".amount");
                            var subTotal = 0;
                            for (var i = 0; i < inputs.length; i++) {
                                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                            }

                            var totalItemPrice = 0;
                            var inputs_quantity = $(".quantity");
                            var priceInput = $('.price');
                            for (var j = 0; j < priceInput.length; j++) {
                                totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
                            }



                            var totalItemTaxPrice = 0;
                            var itemTaxPriceInput = $('.itemTaxPrice');
                            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                                if (quotationItems != null) {
                                    $(el.parent().parent().parent().find('.amount')).html(parseFloat(amount)+parseFloat(itemTaxPrice)-parseFloat(discount));
                                } else {
                                    $(el.parent().parent().parent().find('.amount')).html(parseFloat(item.totalAmount)+parseFloat(itemTaxPrice));
                                }

                            }


                            var totalItemDiscountPrice = 0;
                            var itemDiscountPriceInput = $('.discount');

                            for (var k = 0; k < itemDiscountPriceInput.length; k++) {
                                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                            }


                            $('.subTotal').html(totalItemPrice.toFixed(2));
                            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                            $('.totalAmount').html((parseFloat(totalItemPrice) - parseFloat(totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));
                            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));

                        }
                    });

                },
            });
        }
        $(document).on('change', '.item', function () {
           
            changeItem($(this));
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
            var discount = $(el.find('.discount')).val();
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

            if(discount.length <= 0)
            {
                discount = 0 ;
            }

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

            $('.subTotal').html(totalItemPrice.toFixed(2));
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

        $(document).on('click', '[data-repeater-create]', function () {
           
            $('.item :selected').each(function () {
                var id = $(this).val();
              
                $(".item option[value=" + id + "]").prop("disabled", true);
            });
        })

        $(document).on('click', '[data-repeater-delete]', function () {
            // $('.delete_item').click(function () {
            if (confirm('Are you sure you want to delete this element?')) {
                var el = $(this).parent().parent();
                var id = $(el.find('.id')).val();

                $.ajax({
                    url: '{{route('quotation.product.destroy')}}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('#token').val()
                    },
                    data: {
                        'id': id
                    },
                    cache: false,
                    success: function (data) {

                    },
                });

            }
        });
        
    </script>
    <script>
        $(document).on('click', '[data-repeater-delete]', function () {
            $(".price").change();
            $(".discount").change();
        });
    </script>
@endpush

@section('content')

    <div class="row">

        {{ Form::model($quotation, array('route' => array('quotation.update', $quotation->id), 'method' => 'PUT','class'=>'w-100')) }}
       <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8">
                <h4 class="m-b-10 breadcrumb-item" style="padding-bottom:40px;padding-left:10px;"><a href="{{route('quotation.index')}}" class="text-dark" style="font-weight: bolder;"> <i class="bx bx-undo"></i>{{__('Edit Quotation')}}</a>
                 </h4>
                </div>
                
                <div class="col-lg-4 col-md-4 col-sm-4" style="padding-right: 20px;">
                 <span style="float: inline-end;"><i class="ti ti-send" style="position: absolute;margin-left: 5px;margin-top: 14px;z-index: 10;color: white;"></i><input type="submit" value="{{__('Save')}}" title="{{__('Edit Quotation')}}" class="btn-sm custom-file-uploadss" style="border: none;"></span>
                </div>
            </div>  
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
                                       <input type="hidden" value="yes" name="revised">

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
                                          @if(!empty($lead))
                                         @if(!empty($lead->products()))
                                                 @foreach($lead->sources() as $source) 
                                                 
                                                   {{ Form::text('enquiry_ref', $source->name, ['class' => 'form-control']) }}
                                                   @if (!$loop->last)
                                                        ,
                                                    @endif
                                                   @endforeach
                                                   @else
                                                   -
                                                   @endif
                                                   @else
                                                    {{ Form::text('enquiry_ref',null, ['class' => 'form-control', 'placeholder' => __('Enter Enquiry Ref No.')]) }}
                                       @endif
                                       

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                    <div class="form-group">
                                    {{ Form::label('customer_id', __('GST Number'),['class'=>'form-label']) }}
                                    {{ Form::text('gst_number', $customer->tax_number, array('class' => 'form-control select','required'=>'required')) }}
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
                   
                    @if(!empty($lead))
                    <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('company_name', __('Companey Name'),['class'=>'form-label']) }}
                                        {{ Form::text('company_name', $lead->industry_name, ['class' => 'form-control', 'readonly' => 'readonly']) }}
                                         {{ Form::hidden('organization_id',null, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('email', __('Email'),['class'=>'form-label']) }}
                                        {{ Form::text('email', $lead->email, ['class' => 'form-control', 'readonly' => 'readonly']) }}

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
                                    {{ Form::text('street_name', $customer->tax_number, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                             <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('area_name', __('Area Name'),['class'=>'form-label']) }}
                                    {{ Form::text('area_name', $lead->name, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" value="{{$customer->lead_id}}" name="lead_id">
                                    <div class="form-group">
                                    {{ Form::label('pincode', __('Pincode'),['class'=>'form-label']) }}
                                    {{ Form::text('pincode', $customer->billing_zip, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('city', __('City'),['class'=>'form-label']) }}
                                    {{ Form::text('city', $customer->billing_city, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('state', __('State'),['class'=>'form-label']) }}
                                    {{ Form::text('state', $customer->billing_state, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('country', __('Country'),['class'=>'form-label']) }}
                                    {{ Form::text('country', $customer->billing_country, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('attention', __('Kind Attention'),['class'=>'form-label']) }}
                                    {{ Form::text('attention', auth()->user()->name, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                    </div>
                    @else
                    <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('company_name', __('Companey Name'),['class'=>'form-label']) }}
                                        {{ Form::text('company_name', $quotation->organization->name, ['class' => 'form-control', 'readonly' => 'readonly']) }}
                                        {{ Form::hidden('organization_id',$quotation->organization->id, ['class' => 'form-control']) }}

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('email', __('Email'),['class'=>'form-label']) }}
                                        {{ Form::text('email', $quotation->organization->email, ['class' => 'form-control', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('phone', __('Number'),['class'=>'form-label']) }}
                                        {{ Form::text('phone', $quotation->organization->phone, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter Phone'))) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('ploat_no', __('Plot No.'),['class'=>'form-label']) }}
                                        {{ Form::text('ploat_no',$quotation->organization->plot, ['class' => 'form-control', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                            
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('street_name', __('Street Name'),['class'=>'form-label']) }}
                                    {{ Form::text('street_name', $quotation->organization->street, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                             <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('area_name', __('Area Name'),['class'=>'form-label']) }}
                                    {{ Form::text('area_name', $quotation->organization->area, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" value="{{$customer->lead_id}}" name="lead_id">
                                    <div class="form-group">
                                    {{ Form::label('pincode', __('Pincode'),['class'=>'form-label']) }}
                                    {{ Form::text('pincode', $quotation->organization->pin, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('city', __('City'),['class'=>'form-label']) }}
                                    {{ Form::text('city', $quotation->organization->city, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('state', __('State'),['class'=>'form-label']) }}
                                    {{ Form::text('state',$quotation->organization->state, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('country', __('Country'),['class'=>'form-label']) }}
                                    {{ Form::text('country',$quotation->organization->country, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                    {{ Form::label('attention', __('Kind Attention'),['class'=>'form-label']) }}
                                    {{ Form::text('attention', $quotation->organization->attention, array('class' => 'form-control select','required'=>'required','readonly' => 'readonly')) }}
                                    </div>
                            </div>
                    </div>
                    @endif
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
                                        @if(!empty($lead))
                                       <div class="form-group">{!! Form::textarea('description', $lead->notes , ['class'=>'form-control pro_description','rows'=>'2','placeholder'=>__('Description')]) !!}</div>
                                       @else
                                       <div class="form-group">{!! Form::textarea('subject', $quotation->subject , ['class'=>'form-control pro_description','rows'=>'2','placeholder'=>__('Subject')]) !!}</div>
                                       @endif

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
                        <input type="hidden" name="customer_id" value="{{$customer->id}}">
                               <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('sender_name', __('Sender Name'),['class'=>'form-label']) }}
                                        {{ Form::text('sender_name', $customer->name, ['class' => 'form-control', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        {{ Form::label('address', __('Address'),['class'=>'form-label']) }}
                                        {{ Form::text('address', $customer->billing_address, ['class' => 'form-control', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('sender_number', __('Number'),['class'=>'form-label']) }}
                                        {{ Form::text('sender_number', $customer->contact, ['class' => 'form-control', 'readonly' => 'readonly']) }}

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('sender_email', __('Email'),['class'=>'form-label']) }}
                                        {{ Form::text('sender_email', $customer->email, ['class' => 'form-control', 'readonly' => 'readonly']) }}

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
        '1' => 'Sand', '2' => 'Liquid', '3' => 'Solid'
        ];
        @endphp
     
     
    <div class="col-12">
            <h5 class="d-inline-block mb-4">{{__('Product & Services')}}</h5>
            <div class="card repeater" data-value='{!! json_encode($quotation->items) !!}'>
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
                <div class="card-body table-border-style ">
                    <div class="table-responsive">
                        <table class="table  mb-0" data-repeater-list="items" id="sortable-table">
                            <thead>
                            <tr>
                                <th>{{__('Items')}}</th>
                                <th>{{__('Application')}}</th>
                                <th>{{__('Model')}}</th>
                                <th>{{__('HSN Code')}}</th>
                                <th>{{__('Qty')}}</th>
                                <th>{{__('Price')}} </th>
                                  

                                <th class="text-end">{{__('Amount')}}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="ui-sortable" data-repeater-item>
                            <tr>
                                {{ Form::hidden('id',null, array('class' => 'form-control id')) }}
                                <td width="25%">
                                    <div class="form-group">
                                        {{ Form::select('item', $product_services,null, array('class' => 'form-control select item','data-url'=>route('quotation.product'))) }}
                                    </div>
                                </td>
                                 <td>
                                    <div width="10%" class="form-group">
                                        
                                         {{ Form::text('application',null, array('class' => 'form-control application', 'id' => 'applicationInput')) }}
                                       
                                       
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        {{ Form::text('model',null, array('class' => 'form-control model','required'=>'required','placeholder'=>__('Model'),'required'=>'required')) }}
                                       
                                    </div>
                                </td>
                                 <td>
                                    <div class="form-group">
                                        {{ Form::text('hsn_code',null, array('class' => 'form-control hsnCode','required'=>'required','placeholder'=>__('HSN Code'),'required'=>'required')) }}
                                       
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form" style="width: 50px;">
                                        {{ Form::text('quantity',null, array('class' => 'form-control quantity','required'=>'required','placeholder'=>__('Qty'),'required'=>'required')) }}
                                       
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group price-input input-group search-form" style="width: 132px;">
                                        {{ Form::text('price',null, array('class' => 'form-control price','required'=>'required','placeholder'=>__('Price'),'required'=>'required')) }}
                                        <span class="input-group-text bg-transparent">{{\Auth::user()->currencySymbol()}}</span>
                                    </div>
                                </td>
                                 {{ Form::hidden('discount',null, array('class' => 'form-control discount','required'=>'required','placeholder'=>__('Discount'))) }}
                               {{-- <td>
                                    <div class="form-group price-input input-group search-form">
                                        {{ Form::text('discount',null, array('class' => 'form-control discount','required'=>'required','placeholder'=>__('Discount'))) }}
                                        <span class="input-group-text bg-transparent">{{\Auth::user()->currencySymbol()}}</span>
                                    </div>
                                </td>--}}
                                
                                   
                                            <div class="taxes"></div>
                                            {{ Form::hidden('tax','', array('class' => 'form-control tax')) }}
                                            {{ Form::hidden('itemTaxPrice','', array('class' => 'form-control itemTaxPrice')) }}
                                            {{ Form::hidden('itemTaxRate','', array('class' => 'form-control itemTaxRate')) }}
                                        
                                

                                <td class="text-end amount">
                                    0.00
                                </td>

                                <td>
                                    @can('delete proposal product')
                                        <a href="#" class="ti ti-trash text-white repeater-action-btn bg-danger ms-2 bs-pass-para" data-repeater-delete></a>
                                    @endcan
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        {{ Form::textarea('description', null, ['class'=>'form-control pro_description','rows'=>'2','placeholder'=>__('Description')]) }}
                                    </div>
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
                                <td class="blue-text"><strong>{{__('Total Amount')}} ({{\Auth::user()->currencySymbol()}})</strong><br><small class="text-danger font-weight-bold">{{__('Labor and Other charges included')}}</small></td>
                                <td class="blue-text text-end totalAmount">0.00</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

       {{-- <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" onclick="location.href = '{{route("quotation.index")}}';" class="btn btn-light">
            <input type="submit" value="{{__('Update')}}" class="btn btn-primary">
        </div>--}}
        {{ Form::close() }}
    </div>
@endsection

