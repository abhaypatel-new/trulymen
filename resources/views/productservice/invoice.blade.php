@extends('layouts.admin')

@section('page-title')
    {{__('Production Invoice')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Products')}}</li>
@endsection



@section('content')

   {{ Form::open(array('url' => 'productservice','enctype' => "multipart/form-data")) }}
  
   
        <div class="text-end">
             <button type="submit" class="btn-sm btn btn-primary custom-file-uploads"><i class="ti ti-send"></i>{{__('Save & Send')}}</button>
             <button type="submit" class="btn-sm btn btn-primary custom-file-uploadss"><i class="ti ti-send"></i></i>{{__('Save')}}</button>
               
            </a>
            
        </div>
        
   

<div class="modal-body">
    <div class="card ">
        
        <div class="card-body table-border-style full-card">
            <div class="table-responsive">
            
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                        <th colspan="5" class="text-center">{{__('Production Invoice') }}</th>
                        </tr>
                 
                    </thead>
                    <tbody>
             
                            <tr>
                                <td>Invoice Number: {{$input['invoice']}}</td>
                                <td colspan="3"></td>
                                <td>Incharge: {{$input['createdby']}}</td>
                            <tr>
                            <tr>
                                <td>DateTime:  {{\Carbon\Carbon::parse($input['datetime'])->format('d/m/Y, D g:i A') }}</td>
                                <td colspan="3"></td>
                                <td></td>
                            <tr>
                            <tr>
                                <th width="30">SL</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Production Rate</th>
                                <th>Total Cost</th>
                            <tr>
                            <tr>
                                <td>01</td>
                                <td>{{$input['name']}}</td>
                                <td>01</td>
                                <td>{{$input['sale_price']}}</td>
                                <td>{{$input['sale_price']}}</td>
                            <tr>  
                            <tr>
                                <th>SL</th>
                                <th>Material</th>
                                <th>Quantity</th>
                                <th>Production Rate</th>
                                <th>Total Cost</th>
                            <tr>
                            @foreach($input['material'] as $key => $v)
                            <tr>
                                <td>0{{$key+1}}</td>
                                <td>{{$v}}</td>
                                <td>1</td>
                                <td>{{$input['material_total_price'][$key]}}</td>
                                <td>{{$input['material_total_price'][$key]}}</td>
                                 <input type="hidden" value="{{$input['material_total_price'][$key]}}" name="material_total_price[]">
                                  <input type="hidden" value="{{$v}}" name="material[]">
                                 <input type="hidden" value="1" name="main_quantity">
                            <tr>
                            @endforeach
                            
                   

                    </tbody>
                    
                </table>
               
            </div>
            <div class="row p-10">
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <p>Material Cost:</p>
                                        <p>Labor Cost:</p>
                                        <p>Other Cost:</p>
                                        <hr>
                                        <h5>Total Cost:</h5>
                                       
                                    </div>
                            </div>
                             <div class="col-md-6">
                                    <div class="form-group" style="float: inline-end;text-align: end;">
                                        <p>{{$input['total_price']}}.00 INR</p>
                                        <p>{{$input['labor_charge']}}.00 INR</p>
                                        <p>{{$input['other_cost']}}.00 INR</p>
                                        <hr style="width: 500px;">
                                        <h5>{{$input['sale_price']}}.00 INR</h5>
                                        
                                    </div>
                            </div>
                </div>
                <div class="row p-10">
                            <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <input type="hidden" value="{{$input['total_price']}}" name="total_price">
                                        <input type="hidden" value="{{$input['labor_charge']}}" name="labor_charge">
                                        <input type="hidden" value="{{$input['other_cost']}}"  name="other_cost">
                                        <input type="hidden" value="{{$input['sale_price']}}"  id="numberInput" name="grand_total">
                                        <input type="hidden" value="{{$input['series']}}" name="series">
                                        <input type="hidden" value="{{$input['name']}}" name="name">
                                        <input type="hidden" value="{{$input['quantity']}}" name="quantity">
                                        <input type="hidden" value="{{$input['model']}}" name="model">
                                        <input type="hidden" value="{{$input['group_id']}}" name="group_id">
                                        <input type="hidden" value="{{$input['description']}}" name="description">
                                        <input type="hidden" value="{{$input['invoice']}}" name="invoice">
                                        <input type="hidden" value="{{$input['sendforapp']}}" name="sendforvirification">
                                        <input type="hidden" value="{{$input['sendforapp']}}" name="sendforapp">
                                        <input type="hidden" value="{{$input['datetime']}}" name="datetime">
                                        <input type="hidden" value="{{$input['pro_image']}}" name="pro_image">
                                        <img src="{{ asset(Storage::url('uploads/pro_image/'.$input['pro_image'])) }}" alt="specification" class="img-thumbnail" width="20%"/> 
                                        
                                    </div>
                            </div>
                </div>
                 <div class="row p-10">
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <h5>Product Note: {{$input['description']}}</h5>
                                        <h5>In Word: <span id="result"></span></h5>
                                        <h5>Material Note: {{$input['description']}}</h5>



                                    </div>
                            </div>
                </div>
                 
        </div>
    </div>
</div>

{{Form::close()}}


@endsection



 <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
      
        $(document).ready(function(){
            var gt = $("#numberInput").val();
            toWords(gt)
        });


// Convert numbers to words
// copyright 25th July 2006, by Stephen Chapman http://javascript.about.com
// permission to use this Javascript on your web page is granted
// provided that all of the code (including this copyright notice) is
// used exactly as shown (you can change the numbering system if you wish)

// American Numbering System
var th = ['', 'thousand', 'million', 'billion', 'trillion'];
// uncomment this line for English Number System
// var th = ['','thousand','million', 'milliard','billion'];

var dg = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
var tn = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
var tw = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

function toWords(s) {

    s = s.toString();
    s = s.replace(/[\, ]/g, '');
    if (s != parseFloat(s)) return 'not a number';
    var x = s.indexOf('.');
	var fulllength=s.length;
	
    if (x == -1) x = s.length;
    if (x > 15) return 'too big';
	var startpos=fulllength-(fulllength-x-1);
    var n = s.split('');
	
    var str = '';
    var str1 = ''; //i added another word called cent
    var sk = 0;
    for (var i = 0; i < x; i++) {
        if ((x - i) % 3 == 2) {
            if (n[i] == '1') {
                str += tn[Number(n[i + 1])] + ' ';
                i++;
                sk = 1;
            } else if (n[i] != 0) {
                str += tw[n[i] - 2] + ' ';

                sk = 1;
            }
        } else if (n[i] != 0) {
            str += dg[n[i]] + ' ';
            if ((x - i) % 3 == 0) str += 'hundred ';
            sk = 1;
        }
        if ((x - i) % 3 == 1) {
            if (sk) str += th[(x - i - 1) / 3] + ' ';
            sk = 0;
        }
    }
    if (x != s.length) {
        
        str += 'and '; //i change the word point to and 
        str1 += 'cents '; //i added another word called cent
        //for (var i = x + 1; i < y; i++) str += dg[n[i]] + ' ' ;
		 var j=startpos;
		
		 for (var i = j; i < fulllength; i++) {
		 
        if ((fulllength - i) % 3 == 2) {
            if (n[i] == '1') {
                str += tn[Number(n[i + 1])] + ' ';
                i++;
                sk = 1;
            } else if (n[i] != 0) {
                str += tw[n[i] - 2] + ' ';
				
                sk = 1;
            }
        } else if (n[i] != 0) {
		
            str += dg[n[i]] + ' ';
            if ((fulllength - i) % 3 == 0) str += 'hundred ';
            sk = 1;
        }
        if ((fulllength - i) % 3 == 1) {
		
            if (sk) str += th[(fulllength - i - 1) / 3] + ' ';
            sk = 0;
        }
    }
    }
	var result=str.replace(/\s+/g, ' ') + str1;
    //return str.replace(/\s+/g, ' ');
    var capitalizedText = result.charAt(0).toUpperCase() + result.slice(1);
        // $('#result').text(capitalizedText);
	$('#result').text(result+' Only');
    return result; //i added the word cent to the last part of the return value to get desired output
	
}
    </script>
