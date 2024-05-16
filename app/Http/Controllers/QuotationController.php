<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\PosPayment;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\User;
use App\Models\SpecificationCodeMaterial;
use App\Models\Specification;
use App\Models\Lead;
use App\Models\Purchase;
use App\Models\Utility;
use App\Models\ProductServiceCategory;
use App\Models\ProductService;
use App\Models\WarehouseProduct;
use App\Models\warehouse;
use App\Models\QuotationProduct;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use DB;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->can('manage quotation'))
        {
            $quotations      = Quotation::where('created_by', \Auth::user()->creatorId())->with(['customer','warehouse', 'items'])->orderBy('id', 'desc')->get();
            // dd($quotations);
             $customers     = Customer::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customers->prepend('Select Customer', '');

            $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $warehouse->prepend('Select Warehouse', '');

            // $product_services = ['--'];

            $quotation_number = \Auth::user()->quotationNumberFormat($this->quotationNumber());
            $product_services       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customer       = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->where('type', '!=', 'company')->where('id', '!=', \Auth::user()->id)->get()->pluck('name', 'id');
            // $lead = Lead::find($lead_id);
            // $leadCustomer              = Customer::where('lead_id', $lead_id)->first();
            $users = User::where('type', '=', 'company')->get()->pluck('name', 'id');
            $users->prepend(__('Created by'), '');
            $products       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $products->prepend(__('Product'), '');
            $status = DB::table('status')->get()->pluck('name','id');
            $status->prepend(__('Quote Status'), '');
          
           
            $date = now();
            $chkdate = '';
            $chkstatus = '';
            
           
// dd($quotations);
            return view('quotation.index',compact('quotations','users', 'products', 'status', 'chkstatus', 'chkdate'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
     public function order_search(Request $request)
    {
         $status = $request->status_id == 9?'Complete':(($request->status_id == 8)?'Open':(($request->status_id == 7)?'On-Going':'Pending'));
        // echo $status;die;
         $date = $request->date == 'Date'?'':$request->date;
         $usr = \Auth::user();
        if (Auth::user()->can('manage quotation'))
        {
            if ($date != '' && $request->products != '' && $request->status_id != '') {
                    
                       
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                        ->where('quotations.quotation_date', '=', $parsedDate)
                        ->where('quotations.order_status', '=', $status)
                        ->where('quotation_products.product_id', '=', $request->products)
                        // ->orderBy('quotations.id')
                        ->groupBy('quotations.id')
                        ->get();
                        // dd($quotations);
                    
                }
            elseif ($date != '') {
                    
                       
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                        ->where('quotations.quotation_date', '=', $parsedDate)
                        // ->orderBy('quotations.id')
                        ->groupBy('quotations.id')
                        ->get();
                        // dd($quotations);
                    
                }elseif ($request->products != '') {
                   
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                         ->where('quotation_products.product_id', '=', $request->products)
                        ->orderBy('quotations.id')
                        // ->groupBy('quotation_products.quotation_id')
                        ->get();
                //   dd($request->products);
                }
                elseif ($request->status_id != '') {
                      
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                       
                        ->where('quotations.order_status', '=', $status)
                       
                        ->orderBy('quotations.id')
                       
                        // ->groupBy('quotation_products.quotation_id')
                        ->get();
                //   dd($quotations);
                }
                else {
            
                    $quotations      = Quotation::where('created_by', \Auth::user()->creatorId())->with(['customer','warehouse', 'items'])->orderBy('id', 'desc')->get();
                    // dd($quotations);
                     $customers     = Customer::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                    $customers->prepend('Select Customer', '');
        
                    $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                    $warehouse->prepend('Select Warehouse', '');
        
                    // $product_services = ['--'];
        
                    $quotation_number = \Auth::user()->quotationNumberFormat($this->quotationNumber());
                    $product_services       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                    $customer       = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->where('type', '!=', 'company')->where('id', '!=', \Auth::user()->id)->get()->pluck('name', 'id');
                  
                    $date = now();

                }
                
            // $quotations      = Quotation::where(['created_by'=> \Auth::user()->creatorId(), 'status' => 1])->with(['customer','warehouse'])->get();
             
            $products       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $products->prepend(__('Product'), '');
            $status = DB::table('order_status')->where('parent', 1)->get()->pluck('name','id');
            $status->prepend(__('Order Status'), '');    
            return view('quotation.list',compact('quotations', 'status', 'products'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
     public function orders()
    {
        if (Auth::user()->can('manage quotation'))
        {
            $quotations      = Quotation::where(['created_by'=> \Auth::user()->creatorId(), 'is_order' => 1])->with(['customer','warehouse'])->get();
             
            $products       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $products->prepend(__('Product'), '');
            $status = DB::table('order_status')->where('parent', 1)->get()->pluck('name','id');
            $status->prepend(__('Order Status'), '');    
            return view('quotation.list',compact('quotations', 'status', 'products'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
     public function jobcards()
    {
        if (Auth::user()->can('manage quotation'))
        {
            $quotations      = Quotation::where(['created_by'=> \Auth::user()->creatorId(), 'is_jobcard' => 1])->with(['customer','warehouse'])->get();
            $users = Customer::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $users->prepend(__('Created by'), '');
            $products       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $products->prepend(__('Product'), '');
            return view('jobcards.list',compact('quotations', 'users', 'products'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function jobcard_search(Request $request)
    {
        if (Auth::user()->can('manage quotation'))
        {
             $date = $request->date == 'Date'?'':$request->date;
            if ($date != '' && $request->products != '' && $request->user_id != '') {
                    
                       
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                        ->where('quotations.quotation_date', '=', $parsedDate)
                        ->where('quotations.created_by', '=', $request->user_id)
                        ->where('quotation_products.product_id', '=', $request->products)
                       ->where('quotations.is_jobcard', 1)
                        ->groupBy('quotations.id')
                        ->get();
                        // dd($quotations);
                    
                }
            elseif ($date != '') {
                    
                       
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                        ->where('quotations.quotation_date', '=', $parsedDate)
                        ->where('quotations.is_jobcard', 1)
                        ->groupBy('quotations.id')
                        ->get();
                        // dd($quotations);
                    
                }elseif ($request->products != '') {
                   
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                         ->where('quotation_products.product_id', '=', $request->products)
                        ->orderBy('quotations.id')
                        ->where('quotations.is_jobcard', 1)
                        ->get();
                //   dd($request->products);
                }
                elseif ($request->user_id != '') {
                      
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                       
                        ->where('quotations.created_by', '=', $request->user_id)
                        ->where('quotations.is_jobcard', 1)
                        ->orderBy('quotations.id')
                       
                        // ->groupBy('quotation_products.quotation_id')
                        ->get();
                //   dd($quotations);
                }
                else {
            
                     $quotations      = Quotation::where(['created_by'=> \Auth::user()->creatorId(), 'is_jobcard' => 1])->with(['customer','warehouse'])->get();
                }
           
            $users = Customer::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $users->prepend(__('Created by'), '');
            $products       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $products->prepend(__('Product'), '');
            return view('jobcards.list',compact('quotations', 'users', 'products'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(\Auth::user()->can('create quotation'))
        {
            // $lead_id = request('lead_id');
            // dd($lead_id);
            $customers     = Customer::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customers->prepend('Select Customer', '');

            $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $warehouse->prepend('Select Warehouse', '');

            // $product_services = ['--'];

            $quotation_number = \Auth::user()->quotationNumberFormat($this->quotationNumber());
            $product_services       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customer       = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->where('type', '!=', 'company')->where('id', '!=', \Auth::user()->id)->get()->pluck('name', 'id');
            // $lead = Lead::find($lead_id);
            // $leadCustomer              = Customer::where('lead_id', $lead_id)->first();
            $users = Customer::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $users->prepend(__('Created by'), '');
            $products       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $products->prepend(__('Product'), '');
           
            $date = now();
           
            return view('quotation.create', compact('customer', 'customers','warehouse' , 'quotation_number', 'product_services'));
            // return view('quotation.quotation_create', compact('customers','warehouse' , 'quotation_number', 'product_services', 'lead', 'leadCustomer', 'customer'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function quotationCreate(Request $request)
    {
        $lead_id = request('lead_id');
        $customers     = Customer::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customers->prepend('Select Customer', '');

            $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $warehouse->prepend('Select Warehouse', '');

            // $product_services = ['--'];

            $quotation_number = \Auth::user()->quotationNumberFormat($this->quotationNumber());
            $product_services       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
             $product_services->prepend(' -- ', '');
            $customer       = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->where('type', '!=', 'company')->where('id', '!=', \Auth::user()->id)->get()->pluck('name', 'id');
            $lead = Lead::find($lead_id);
            
            $leadCustomer              = Customer::find($lead->user_id);
        
        // $warehouseProducts = WarehouseProduct::where('created_by', '=', \Auth::user()->creatorId())->where('warehouse_id',$request->warehouse_id)->get()->pluck('product_id')->toArray();
        // $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->whereIn('id',$warehouseProducts)->where('type','!=', 'service')->get()->pluck('name', 'id');
        // $product_services->prepend(' -- ', '');

        return view('quotation.quotation_create', compact('customer','warehouse' , 'quotation_number','product_services', 'leadCustomer', 'lead'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  dd($request->all());
        if(\Auth::user()->can('create quotation'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'customer_id' => 'required',
                    'warehouse_id' => 'nullable',
                    'quotation_date' => 'required',
                    'items' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $customer = Customer::where('id',$request->customer_id)->first();
            $warehouse = warehouse::where('name',$request->warehouse_id)->first();
            
            
            
            $quotations                 = new Quotation();
            $quotations->quotation_id    = $this->quotationNumber();
            $quotations->customer_id      = $customer->id;
            $quotations->lead_id      = $request->lead_id;
            // $quotations->application      = $request->application;
            // $quotations->warehouse_id      = $warehouse->id;
            $quotations->warehouse_id      =2;
            $quotations->quotation_date  = $request->quotation_date;
            $quotations->status         =  0;
            $quotations->category_id    =  0;
            $quotations->created_by     = \Auth::user()->creatorId();
            $quotations->save();

            $products = $request->items;

            for($i = 0; $i < count($products); $i++)
            {
               
                $quotationItems              = new QuotationProduct();
                $quotationItems->quotation_id    = $quotations->id;
                $quotationItems->product_id = $products[$i]['item'];
                $quotationItems->price      = $products[$i]['price'];
                $quotationItems->application      = $products[$i]['application'];
                $quotationItems->quantity   = $products[$i]['quantity'];
                $quotationItems->tax       = $products[$i]['tax'] == null?0.00:$products[$i]['tax'];
                $quotationItems->discount        = $products[$i]['discount'];
                $quotationItems->save();
            }

            return redirect()->route('quotation.index', $quotations->id)->with('success', __('Quotation successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
     /**
     * Store a newly created resource in storage.
     */
    public function quotation_store(Request $request)
    {
    //   dd($request->all());
        if(\Auth::user()->can('create quotation'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'company_name' => 'required',
                    'company_email' => 'required',
                    'company_phone' => 'required',
                    'plot' => 'required',
                    'street' => 'required',
                    'customer_id' => 'required',
                    'area' => 'nullable',
                    'pin' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'country' => 'required',
                    'sender_email' => 'required',
                    'sender_name' => 'required',
                    'address' => 'required',
                    'contact' => 'required',
                    'quotation_date' => 'nullable',
                    'items' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            // $customer = Customer::where('id',$request->customer_id)->first();
            // $warehouse = warehouse::where('name',$request->warehouse_id)->first();
 
            // Customer Store Start
            
                $customer                  = new Customer();
                $customer->customer_id     = $this->customerNumber();
                $customer->name            = $request->sender_name;
                $customer->contact         = $request->contact;
                $customer->email           = $request->sender_email;
                // $customer->tax_number      =$request->tax_number;
                $customer->created_by      = \Auth::user()->creatorId();
                $customer->billing_name    = $request->sender_name;
                 $customer->billing_address = $request->address;
                $customer->shipping_address = $request->address;

                $customer->lang = !empty($default_language) ? $default_language->value : '';

                $customer->save();
            
            // Customer End
            
            // Organization Store Start
            
            $org                 = new Organization();
            $org->name    = $request->company_name;
            $org->email      = $request->company_email;
            $org->phone      = $request->company_phone;
            $org->pin      = $request->pin;
            $org->area      = $request->area;
            $org->street      =$request->street;
            $org->city  = $request->city;
            $org->state         =  $request->state;
            $org->country    =  $request->country;
            $org->plot    =  $request->plot;
            $org->attention    =  $request->attention;
           
            $org->save();
            
            // Organization End
            
            $cust = Customer::where('id',$request->customer_id)->first();
            $quotations                 = new Quotation();
            $quotations->quotation_id    = $this->quotationNumber();
            $quotations->customer_id      = $customer->id;
            $quotations->lead_id      = $cust->lead_id;
            $quotations->assigned_to      = $cust->id;
            $quotations->organization_id      = $org->id;
            // $quotations->warehouse_id      = $warehouse->id;
            $quotations->warehouse_id      =3;
            $quotations->quotation_date  = now()->format('Y-m-d');
            $quotations->status         =  0;
            $quotations->category_id    =  0;
            $quotations->created_by     = \Auth::user()->creatorId();
            $quotations->save();

            $products = $request->items;

            for($i = 0; $i < count($products); $i++)
            {
               
                $quotationItems              = new QuotationProduct();
                $quotationItems->quotation_id    = $quotations->id;
                $quotationItems->product_id = $products[$i]['item'];
                $quotationItems->price      = $products[$i]['price'];
                $quotationItems->application      = $products[$i]['application'];
                $quotationItems->quantity   = $products[$i]['quantity'];
                $quotationItems->tax       = $products[$i]['tax'] == null?0.00:$products[$i]['tax'];
                $quotationItems->discount        = $products[$i]['discount'];
                $quotationItems->save();
            }
          
            return redirect()->route('quotation.index', $quotations->id)->with('success', __('Quotation successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($ids)
    {

        if (\Auth::user()->can('show quotation') || \Auth::user()->type == 'company') {
            try {
                $id = Crypt::decrypt($ids);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Quotation Not Found.'));
            }

            $id = Crypt::decrypt($ids);

            $quotation = Quotation::find($id);
            $quotation_r = Quotation::where('is_revised',$id)->get();
            $quotation_o = Quotation::with('items')->where(['id' => $id, 'is_order' => 1])->get();
            $quotation_job = Quotation::with('items')->where(['id' => $id, 'is_order' => 1, 'is_jobcard' => 1])->get();
            // dd($quotation);
           $barcode = [
                'barcodeType' => Auth::user()->barcodeType(),
                'barcodeFormat' => Auth::user()->barcodeFormat(),
            ];
            
            if ($quotation->created_by == \Auth::user()->creatorId()) {
                $quotationPayment = PosPayment::where('pos_id', $quotation->id)->first();
                $customer = $quotation->customer;
               
                 $iteams = $quotation->items;   
                
                
                
// dd($quotation_r);
                return view('quotation.view', compact('quotation_r','quotation_o','quotation_job', 'quotation', 'customer', 'iteams', 'quotationPayment', 'barcode'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function orderview($ids)
    {

        if (\Auth::user()->can('show quotation') || \Auth::user()->type == 'company') {
            try {
                $id = Crypt::decrypt($ids);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Quotation Not Found.'));
            }

            $id = Crypt::decrypt($ids);

            $quotation = Quotation::find($id);
            $quotation_r = Quotation::where('is_revised',$id)->get();
            $quotation_o = Quotation::where(['id' => $id, 'is_order' => 1])->first();
        //  dd($quotation_o);
            $quotation_job = Quotation::where(['id' => $id, 'is_order' => 1, 'is_jobcard' => 1])->first();
            $barcode = [
                'barcodeType' => Auth::user()->barcodeType(),
                'barcodeFormat' => Auth::user()->barcodeFormat(),
            ];
            if ($quotation->created_by == \Auth::user()->creatorId()) {
                $quotationPayment = PosPayment::where('pos_id', $quotation->id)->first();
                $customer = $quotation->customer;
                $iteams = $quotation->items;

                return view('quotation.vieworder', compact('quotation_r','quotation_o','quotation_job', 'quotation', 'customer', 'iteams', 'quotationPayment', 'barcode'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ids)
    {
        if(\Auth::user()->can('edit quotation'))
        {
//             $id   = Crypt::decrypt($ids);
//             $quotation     = Quotation::find($id);
// // dd($quotation);
//             $leadCustomer = Customer::where('id',$quotation->customer_id)->first();
//             $warehouse = warehouse::where('id',$quotation->warehouse_id)->first();
           
//             // $warehouseProducts = WarehouseProduct::where('created_by', '=', \Auth::user()->creatorId())->where('warehouse_id',$quotation->warehouse_id)->get()->pluck('product_id')->toArray();
//             $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->where('type','!=', 'service')->get()->pluck('name', 'id');
//             $product_services->prepend(' -- ', '');
//     // dd($product_services);
//             $quotation_number = \Auth::user()->quotationNumberFormat($this->quotationNumber());
//             $lead = Lead::find($quotation->lead_id);
//             // $leadCustomer              = Customer::where('customer_id', $quotation->customer_id)->first();

//             return view('quotation.edit', compact('product_services','warehouse' , 'quotation_number' , 'quotation', 'lead', 'leadCustomer'));

 $id   = Crypt::decrypt($ids);
            $quotation     = Quotation::find($id);

            $customer = Customer::where('id',$quotation->customer_id)->first();
            $warehouse = warehouse::where('id',$quotation->warehouse_id)->first();

            $warehouseProducts = WarehouseProduct::where('created_by', '=', \Auth::user()->creatorId())->where('warehouse_id',$quotation->warehouse_id)->get()->pluck('product_id')->toArray();
            $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->where('type','!=', 'service')->get()->pluck('name', 'id');
            $product_services->prepend(' -- ', '');

            $quotation_number = \Auth::user()->quotationNumberFormat($this->quotationNumber());

            return view('quotation.edit', compact('customer', 'product_services','warehouse' , 'quotation_number' , 'quotation'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quotation $quotation)
    {
        //  dd($request->all());
        if(\Auth::user()->can('edit quotation'))
        {
            if($request->revised == 'yes')
            {
        //  dd($this->quotationNumber());
             $validator = \Validator::make(
                    $request->all(), [
                        'customer_id' => 'required',
                        'quotation_date' => 'required',
                        // 'items' => 'required',
                    ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('quotation.index')->with('error', $messages->first());
                }
                // dd($quotation);
            if($request->organization_id == null)   
            {
            $org                 = new Organization();
            $org->name    = $request->company_name;
            $org->email      = $request->email;
            $org->phone      = $request->phone;
            $org->pin      = $request->pincode;
            $org->area      = $request->area_name;
            $org->street      =$request->street_name;
            $org->city  = $request->city;
            $org->state         =  $request->state;
            $org->country    =  $request->country;
            $org->plot    =  $request->ploat_no;
            $org->attention    =  $request->attention;
             $org->save();
            }
           
                $customer = Customer::where('id',$request->customer_id)->first();
                // $warehouse = warehouse::where('name',$request->warehouse_id)->first();
                 $quotationR = new Quotation();
                $quotationR->customer_id      = $customer->id;
                $quotationR->quotation_id    = $this->quotationNumber();
                $quotationR->organization_id    = $request->organization_id == null?$org->id:$request->organization_id;
                $quotationR->is_revised      = $quotation->id;
                // $quotationR->warehouse_id      = $warehouse->id;
                $quotationR->quotation_date  = $request->quotation_date;
                $quotationR->status         =  0;
                $quotationR->category_id    =  0;
                $quotationR->created_by     = \Auth::user()->creatorId();
                //  dd($quotationR);
                $quotationR->save();
                $products = $request->items;
                
                for($i = 0; $i < count($products); $i++)
                {
                    
                    $quotationProduct = null;

                    if($quotationProduct == null)
                    {
                        $quotationProduct             = new QuotationProduct();
                        $quotationProduct->quotation_id    = $quotationR->id;

                    }
                    if(isset($products[$i]['item']))
                    {
                        $quotationProduct->product_id = $products[$i]['item'];
                    }

                    $quotationProduct->quantity    = $products[$i]['quantity'];
                    $quotationProduct->tax         = $products[$i]['tax'];
                    $quotationProduct->discount    = $products[$i]['discount'];
                    $quotationProduct->price       = $products[$i]['price'];
                    $quotationProduct->description = $products[$i]['description'];
                    $quotationProduct->save();
// dd($quotationProduct);
                }

                return redirect()->route('quotation.index')->with('success', __('Quotation successfully updated.'));   
            }
            if($quotation->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                        'customer_id' => 'required',
                        'quotation_date' => 'required',
                        'items' => 'required',
                    ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('quotation.index')->with('error', $messages->first());
                }
                $customer = Customer::where('id',$request->customer_id)->first();
                // $warehouse = warehouse::where('name',$request->warehouse_id)->first();

                $quotation->customer_id      = $customer->id;
                // $quotation->warehouse_id      = $warehouse->id;
                $quotation->quotation_date  = $request->quotation_date;
                $quotation->status         =  0;
                $quotation->category_id    =  0;
                $quotation->created_by     = \Auth::user()->creatorId();
                $quotation->save();
                $products = $request->items;
            //   dd($products);
                for($i = 0; $i < count($products); $i++)
                {
                    
                    $quotationProduct = QuotationProduct::find($products[$i]['id']);

                    if($quotationProduct == null)
                    {
                        $quotationProduct             = new QuotationProduct();
                        $quotationProduct->quotation_id    = $quotation->id;

                    }
                    if(isset($products[$i]['item']))
                    {
                        $quotationProduct->product_id = $products[$i]['item'];
                    }

                    $quotationProduct->quantity    = $products[$i]['quantity'];
                    $quotationProduct->tax         = $products[$i]['tax'];
                    $quotationProduct->discount    = $products[$i]['discount'];
                    $quotationProduct->price       = $products[$i]['price'];
                    $quotationProduct->description = $products[$i]['description'];
                    $quotationProduct->save();
// dd($quotationProduct);
                }

                return redirect()->route('quotation.index')->with('success', __('Quotation successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quotation $quotation)
    {
        if(\Auth::user()->can('delete quotation'))
        {
            if($quotation->created_by == \Auth::user()->creatorId())
            {


                $quotation->delete();
                QuotationProduct::where('quotation_id', '=', $quotation->id)->delete();


                return redirect()->route('quotation.index')->with('success', __('Quotation successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
     /**
     * Remove the specified resource from storage.
     */
    public function changeStatus($id, $status)
    {
      
           
                if($status == 'order')
                {
                   $quotation             = Quotation::find($id);
                                $quotation->is_order = 1;
                                $quotation->save();  
                }else{
                    $quotation             = Quotation::find($id);
                                $quotation->is_jobcard = 1;
                                $quotation->save(); 
                }
               
            $st = $status == 'order'?'Quotation has been change to order successfully':'Order has been change to JobCard successfully' ; 
            if($quotation)
            {    
            return redirect()->back()->with('success', __($st));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }

    function quotationNumber()
    {
        $latest = Quotation::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->quotation_id + 1;
    }

    public function product(Request $request)
    {
        
        
        $data['product']     = $product = ProductService::find($request->product_id);
        
        //specification listing start
       
        
         $cat = Specification::with('subspecifications')->where(['priority' => 0, 'group_id' => $product->group_id])->get();
         $material = SpecificationCodeMaterial::where(['specification_code_order_id' =>$product->specification_code_order_id])->orderBy('id', 'desc')->pluck('name')->toArray();
        //  $productService = ProductService::find($id);
     
        // $all_products = ProductService::getallproducts()->count();

      
       $datas = [];
       $htmls = '';
        foreach ($cat as $key => $c) {
           if($key == 0)
           {
             $html ='<tr>
            <td>
            <label for="specification-name" class="form-label" style="display: inline-block; margin-right: 100px;">' . $c->name . '</label></td>
            <td><select class="form-control group-material-'.$key.'" data-id="' . $c->name . '" style="display: inline-block; margin-right: 25px;"><option>Select' . $c->name . '</option>';   
           }else{
                $html ='<tr class="d-none">
            <td>
            <label for="specification-name" class="form-label" style="display: inline-block; margin-right: 100px;">' . $c->name . '</label></td>
            <td><select class="form-control group-material-'.$key.'" data-id="' . $c->name . '" disabled style="display: inline-block; margin-right: 25px;"><option>Select' . $c->name . '</option>';
           }
            
            
           
             foreach ($c->subspecifications as $key => $cs) {
                 if (array_search($cs->prefix, $material) !== false) {
                   $html .='<option value="' . $cs->id . '" selected>' . $cs->prefix .': '. $cs->name. '</option>';
                } else {
                   $html .='<option value="' . $cs->id . '">' . $cs->prefix .': '. $cs->name. '</option>';  
                }
              
             }
             $html .='</select></td><td style="display: inline-block;">
            <a class="btn btn-primary sm text-light enable-row"><i class="ti ti-plus"></i> Add Specification</a>
         </td></tr>';
               array_push($datas, $html);
        }
         //specification listing end
        $data['unit']        = !empty($product->unit) ? $product->unit->name : '';
        $data['taxRate']     = $taxRate = !empty($product->tax_id) ? $product->taxRate($product->tax_id) : 0;
        $data['taxes']       = !empty($product->tax_id) ? $product->tax($product->tax_id) : 0;
        $salePrice           = $product->sale_price;
        $quantity            = 1;
        $taxPrice            = ($taxRate / 100) * ($salePrice * $quantity);
        $data['totalAmount'] = ($salePrice * $quantity);
        $data['listing'] = $datas; 
        $product = ProductService::find($request->product_id);
        $productquantity = 0;
        
        if ($product) {
            $productquantity = $product->quantity;
        }
        $data['productquantity'] = $productquantity;
// dd($data);
        return json_encode($data);
    }

    public function productDestroy(Request $request)
    {

        if(\Auth::user()->can('delete quotation'))
        {

            QuotationProduct::where('id', '=', $request->id)->delete();

            return redirect()->back()->with('success', __('Quotation product successfully deleted.'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function items(Request $request)
    {
        $items = QuotationProduct::where('quotation_id', $request->quotation_id)->where('product_id', $request->product_id)->first();
 
        return json_encode($items);
    }

    public function productQuantity(Request $request)
    {
        $product = ProductService::find($request->item_id);
        $productquantity = 1;

        // if ($product) {
        //     $productquantity = $product->getQuantity();
        // }

        return json_encode($productquantity);

    }

    public function previewQuotation($template, $color)
    {

        $objUser = \Auth::user();
        $settings = Utility::settings();

        $quotation = new Quotation();
        $quotationPayment = new posPayment();
        $quotationPayment->amount = 360;
        $quotationPayment->discount = 100;

        $customer = new \stdClass();
        $customer->email = '<Email>';
        $customer->shipping_name = '<Customer Name>';
        $customer->shipping_country = '<Country>';
        $customer->shipping_state = '<State>';
        $customer->shipping_city = '<City>';
        $customer->shipping_phone = '<Customer Phone Number>';
        $customer->shipping_zip = '<Zip>';
        $customer->shipping_address = '<Address>';
        $customer->billing_name = '<Customer Name>';
        $customer->billing_country = '<Country>';
        $customer->billing_state = '<State>';
        $customer->billing_city = '<City>';
        $customer->billing_phone = '<Customer Phone Number>';
        $customer->billing_zip = '<Zip>';
        $customer->billing_address = '<Address>';

        $totalTaxPrice = 0;
        $taxesData = [];
        $items = [];
        for ($i = 1; $i <= 3; $i++) {
            $item = new \stdClass();
            $item->name = 'Item ' . $i;
            $item->quantity = 1;
            $item->tax = 5;
            $item->discount = 50;
            $item->price = 100;

            $taxes = [
                'Tax 1',
                'Tax 2',
            ];

            $itemTaxes = [];
            foreach ($taxes as $k => $tax) {
                $taxPrice = 10;
                $totalTaxPrice += $taxPrice;
                $itemTax['name'] = 'Tax ' . $k;
                $itemTax['rate'] = '10 %';
                $itemTax['price'] = '$10';
                $itemTaxes[] = $itemTax;
                if (array_key_exists('Tax ' . $k, $taxesData)) {
                    $taxesData['Tax ' . $k] = $taxesData['Tax 1'] + $taxPrice;
                } else {
                    $taxesData['Tax ' . $k] = $taxPrice;
                }
            }
            $item->itemTax = $itemTaxes;
            $items[] = $item;
        }

        $quotation->quotation_id = 1;

        $quotation->issue_date = date('Y-m-d H:i:s');
        $quotation->itemData = $items;

        $quotation->totalTaxPrice = 60;
        $quotation->totalQuantity = 3;
        $quotation->totalRate = 300;
        $quotation->totalDiscount = 10;
        $quotation->taxesData = $taxesData;
        $quotation->created_by = $objUser->creatorId();

        $preview = 1;
        $color = '#' . $color;
        $font_color = Utility::getFontColor($color);

        $logo = asset(Storage::url('uploads/logo/'));

        $company_logo = Utility::getValByName('company_logo_dark');
        $settings_data = \App\Models\Utility::settingsById($quotation->created_by);
        $quotation_logo = isset($settings_data['quotation_logo']) ? $settings_data['quotation_logo'] : '';

        if (isset($quotation_logo) && !empty($quotation_logo)) {
            $img = Utility::get_file('quotation_logo/') . $quotation_logo;
        } else {
            $img = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }

        return view('quotation.templates.' . $template, compact('quotation', 'preview', 'color', 'img', 'settings', 'customer', 'font_color', 'quotationPayment'));
    }

    public function saveQuotationTemplateSettings(Request $request)
    {

        $post = $request->all();
        unset($post['_token']);

        if (isset($post['quotation_template']) && (!isset($post['quotation_color']) || empty($post['quotation_color']))) {
            $post['quotation_color'] = "ffffff";
        }

        if ($request->quotation_logo) {
            $dir = 'quotation_logo/';
            $quotation_logo = \Auth::user()->id . '_quotation_logo.png';
            $validation = [
                'mimes:' . 'png',
                'max:' . '20480',
            ];
            $path = Utility::upload_file($request, 'quotation_logo', $quotation_logo, $dir, $validation);
            if ($path['flag'] == 0) {
                return redirect()->back()->with('error', __($path['msg']));
            }
            $post['quotation_logo'] = $quotation_logo;
        }

        foreach ($post as $key => $data) {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                    $data,
                    $key,
                    \Auth::user()->creatorId(),
                ]
            );
        }

        return redirect()->back()->with('success', __('Quotation Setting updated successfully'));
    }

    public function printView(Request $request)
    {

        $sess = session()->get('pos');

        $user = Auth::user();
        $settings = Utility::settings();

        $customer = Customer::where('name', '=', $request->vc_name)->where('created_by', $user->creatorId())->first();
        $warehouse = warehouse::where('id', '=', $request->warehouse_name)->where('created_by', $user->creatorId())->first();

        $details = [
            'pos_id' => $user->quotationNumberFormat($this->quotationNumber()),
            'customer' => $customer != null ? $customer->toArray() : [],
            'warehouse' => $warehouse != null ? $warehouse->toArray() : [],
            'user' => $user != null ? $user->toArray() : [],
            'date' => date('Y-m-d'),
            'pay' => 'show',
        ];

        if (!empty($details['customer'])) {
            $warehousedetails = '<h7 class="text-dark">' . ucfirst($details['warehouse']['name']) . '</p></h7>';
            $details['customer']['billing_state'] = $details['customer']['billing_state'] != '' ? ", " . $details['customer']['billing_state'] : '';
            $details['customer']['shipping_state'] = $details['customer']['shipping_state'] != '' ? ", " . $details['customer']['shipping_state'] : '';
            $customerdetails = '<h6 class="text-dark">' . ucfirst($details['customer']['name']) . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['billing_phone'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['billing_address'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['billing_city'] . $details['customer']['billing_state'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['billing_country'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['billing_zip'] . '</p></h6>';
            $shippdetails = '<h6 class="text-dark"><b>' . ucfirst($details['customer']['name']) . '</b>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['shipping_phone'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['shipping_address'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['shipping_city'] . $details['customer']['shipping_state'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['shipping_country'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['shipping_zip'] . '</p></h6>';

        } else {
            $customerdetails = '<h2 class="h6"><b>' . __('Walk-in Customer') . '</b><h2>';
            $warehousedetails = '<h7 class="text-dark">' . ucfirst($details['warehouse']['name']) . '</p></h7>';
            $shippdetails = '-';

        }

        $settings['company_telephone'] = $settings['company_telephone'] != '' ? ", " . $settings['company_telephone'] : '';
        $settings['company_state'] = $settings['company_state'] != '' ? ", " . $settings['company_state'] : '';

        $userdetails = '<h6 class="text-dark"><b>' . ucfirst($details['user']['name']) . ' </b> <h2  class="font-weight-normal">' . '<p class="m-0 font-weight-normal">' . $settings['company_name'] . $settings['company_telephone'] . '</p>' . '<p class="m-0 font-weight-normal">' . $settings['company_address'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $settings['company_city'] . $settings['company_state'] . '</p>' . '<p class="m-0 font-weight-normal">' . $settings['company_country'] . '</p>' . '<p class="m-0 font-weight-normal">' . $settings['company_zipcode'] . '</p></h2>';

        $details['customer']['details'] = $customerdetails;
        $details['warehouse']['details'] = $warehousedetails;
            //
        $details['customer']['shippdetails'] = $shippdetails;

        $details['user']['details'] = $userdetails;

        $mainsubtotal = 0;
        $sales = [];

        foreach ($sess as $key => $value) {

            $subtotal = $value['price'] * $value['quantity'];
            $tax = ($subtotal * $value['tax']) / 100;
            $sales['data'][$key]['name'] = $value['name'];
            $sales['data'][$key]['quantity'] = $value['quantity'];
            $sales['data'][$key]['price'] = Auth::user()->priceFormat($value['price']);
            $sales['data'][$key]['tax'] = $value['tax'] . '%';
            $sales['data'][$key]['product_tax'] = $value['product_tax'];
            $sales['data'][$key]['tax_amount'] = Auth::user()->priceFormat($tax);
            $sales['data'][$key]['subtotal'] = Auth::user()->priceFormat($value['subtotal']);
            $mainsubtotal += $value['subtotal'];
        }

        $discount = !empty($request->discount) ? $request->discount : 0;
        $sales['discount'] = Auth::user()->priceFormat($discount);
        $total = $mainsubtotal - $discount;
        $sales['sub_total'] = Auth::user()->priceFormat($mainsubtotal);
        $sales['total'] = Auth::user()->priceFormat($total);

        //for barcode

        $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get();
        $barcode = [
            'barcodeType' => Auth::user()->barcodeType(),
            'barcodeFormat' => Auth::user()->barcodeFormat(),
        ];

        return view('quotation.printview', compact('details', 'sales', 'customer', 'productServices', 'barcode'));

    }

    public function quotation($quotation_Id)
    {
        $settings = Utility::settings();
        $quotationId = Crypt::decrypt($quotation_Id);
        $quotation = Quotation::where('id', $quotationId)->first();


        $data = \DB::table('settings');
        $data = $data->where('created_by', '=', $quotation->created_by);
        $data1 = $data->get();

        foreach ($data1 as $row) {
            $settings[$row->name] = $row->value;
        }

        $customer = $quotation->customer;

        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate = 0;
        $totalDiscount = 0;
        $taxesData = [];
        $items = [];
       
        foreach ($quotation->items as $product) {

            $item = new \stdClass();
            $item->name = !empty($product->product()) ? $product->product()->name : '';
            $item->quantity = $product->quantity;
            $item->tax = $product->tax;
            $item->discount = $product->discount;
            $item->price = $product->price;
            $item->description = $product->description;
            $item->id = !empty($product->product()) ? $product->product()->id : '';
            $item->sku = !empty($product->product()) ? $product->product()->sku : ''; 
            $totalQuantity += $item->quantity;
            $totalRate += $item->price;
            $totalDiscount += $item->discount;
            $taxes = Utility::tax($product->tax);
            $itemTaxes = [];
            if (!empty($item->tax)) {
                foreach ($taxes as $tax) {
                    $taxPrice = Utility::taxRate($tax->rate, $item->price, $item->quantity);
                    $totalTaxPrice += $taxPrice;

                    $itemTax['name'] = $tax->name;
                    $itemTax['rate'] = $tax->rate . '%';
                    $itemTax['price'] = Utility::priceFormat($settings, $taxPrice);
                    $itemTaxes[] = $itemTax;

                    if (array_key_exists($tax->name, $taxesData)) {
                        $taxesData[$tax->name] = $taxesData[$tax->name] + $taxPrice;
                    } else {
                        $taxesData[$tax->name] = $taxPrice;
                    }

                }

                $item->itemTax = $itemTaxes;
            } else {
                $item->itemTax = [];
            }
            $items[] = $item;
        }

        $quotation->itemData = $items;
        $quotation->totalTaxPrice = $totalTaxPrice;
        $quotation->totalQuantity = $totalQuantity;
        $quotation->totalRate = $totalRate;
        $quotation->totalDiscount = $totalDiscount;
        $quotation->taxesData = $taxesData;

        $logo = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $quotation_logo = Utility::getValByName('quotation_logo');
        if (isset($quotation_logo) && !empty($quotation_logo)) {
            $img = Utility::get_file('quotation_logo/') . $quotation_logo;
        } else {
            $img = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }
         $barcode = [
                'barcodeType' => Auth::user()->barcodeType(),
                'barcodeFormat' => Auth::user()->barcodeFormat(),
            ];
// dd($settings['quotation_template']);
        if ($quotation) {
            $color = '#' . $settings['quotation_color'];
            $font_color = Utility::getFontColor($color);
 
            return view('quotation.templates.' . $settings['quotation_template'], compact('quotation', 'color', 'settings', 'customer', 'img', 'font_color','barcode'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
    
     //searching start
    
     public function search(Request $request)
    {
    //  dd($request->all());
         $date = $request->date == 'Date'?'':$request->date;
         $usr = \Auth::user();
         $leads = '';    
        
            
            
                
                
             if ($request->status_id != '' && $request->products != '' && $date != '' && $request->user_id != '') {
                    
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                       
                        ->where('quotations.status', '=', $request->status_id)
                        ->whereDate('quotations.quotation_date', '=',$parsedDate)
                        ->where('quotations.created_by', '=', $request->user_id)
                        ->orderBy('quotations.id')
                        ->get();
                //   dd($quotations);
                }
                 elseif ($request->statis_id != '' && $date != '' && $request->user_id != '') {
                  
                       
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                       
                        ->where('quotations.status', '=', $request->status_id)
                        ->whereDate('quotations.quotation_date', '=',$parsedDate)
                        ->where('quotations.created_by', '=', $request->user_id)
                        ->orderBy('quotations.id')
                        ->get();
                  
                }
                 elseif ($request->status_id != '' && $date != '') {
                        // echo "sdfds";die;
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                       
                       
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                       
                        ->where('quotations.status', '=', $request->status_id)
                        ->whereDate('quotations.quotation_date', '=',$parsedDate)
                       
                        ->orderBy('quotations.id')
                        ->get();
                  
                }
                elseif ($request->user_id != '') {
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                        ->where('quotations.created_by', '=', $request->user_id)
                        ->orderBy('quotations.id')
                        ->get();
                    
                }
                elseif ($date != '') {
                    
                       
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                        ->where('quotations.quotation_date', '=', $parsedDate)
                        // ->orderBy('quotations.id')
                        ->groupBy('quotations.id')
                        ->get();
                        // dd($quotations);
                    
                }elseif ($request->products != '') {
                   
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                         ->where('quotation_products.product_id', '=', $request->products)
                        ->orderBy('quotations.id')
                        // ->groupBy('quotation_products.quotation_id')
                        ->get();
                //   dd($request->products);
                }
                elseif ($request->status_id != '') {
                      
                        $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        $quotations     = Quotation::select('quotations.*')
                        ->join('quotation_products', 'quotation_products.quotation_id', '=', 'quotations.id')
                       
                        ->where('quotations.status', '=', $request->status_id)
                       
                        ->orderBy('quotations.id')
                       
                        // ->groupBy('quotation_products.quotation_id')
                        ->get();
                //   dd($quotations);
                }
                else {
            
                    $quotations      = Quotation::where('created_by', \Auth::user()->creatorId())->with(['customer','warehouse', 'items'])->orderBy('id', 'desc')->get();
                    // dd($quotations);
                     $customers     = Customer::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                    $customers->prepend('Select Customer', '');
        
                    $warehouse     = warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                    $warehouse->prepend('Select Warehouse', '');
        
                    // $product_services = ['--'];
        
                    $quotation_number = \Auth::user()->quotationNumberFormat($this->quotationNumber());
                    $product_services       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                    $customer       = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->where('type', '!=', 'company')->where('id', '!=', \Auth::user()->id)->get()->pluck('name', 'id');
                  
                    $date = now();

                }
            $chkdate = $request->date != 'Date'?Carbon::parse($date)->format('Y-m-d'):'Date';
            $chkstatus = $request->status_id != ''?$request->status_id:'';
            $products = $request->products;
            $user_id = $request->user_id;
            $users = User::where('type', '=', 'company')->get()->pluck('name', 'id');
            $users->prepend(__('Created by'), '');
            $products       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $products->prepend(__('Product'), '');
            
          
            return view('quotation.index',compact('quotations','users', 'products', 'chkdate', 'chkstatus', 'products', 'user_id'));
        
    }
    
    //searching end
    
    function customerNumber()
    {
        $latest = Customer::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->customer_id + 1;
    }
}
