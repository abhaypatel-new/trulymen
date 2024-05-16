<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\ChartOfAccountType;
use App\Models\CustomField;
use App\Exports\ProductServiceExport;
use App\Imports\ProductServiceImport;
use App\Models\Product;
use App\Models\Specification;
use App\Models\InvoiceProduct;
use App\Models\SpecificationCodeMaterial;
use App\Models\SpecificationCodeOrder;
use Carbon\Carbon;
use App\Models\ProductService;
use App\Models\ProductServiceCategory;
use App\Models\ProductServiceUnit;
use App\Models\Tax;
use App\Models\Group;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Utility;
use App\Models\Vender;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;




class ProductServiceController extends Controller
{
    public function index(Request $request)
    {

        if(\Auth::user()->can('manage product & service'))
        {
            $category = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'product & service')->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');
            $users = User::where('type', '=', 'client')->get()->pluck('name', 'id');
            $users->prepend(__('Assigned to'), ''); 
             $orderstatuss = [
                    
                     'Received',
                     'Testing',
                     'Repairing',
                     'Dispatch',
                     'Resolved'
                     ];
           
            $translatedTexts = __('Order Status');
            $orderstatus = collect([$translatedTexts])->merge($orderstatuss);
            $ticketstatuss = [
                'Open',
                'Hold',
                'On-Going',
                'Closed'
            ];
            
            $translatedText = __('Ticket Status');
            $ticketstatus = collect([$translatedText])->merge($ticketstatuss);
            $ticketpriority = [
                 'Low',
                 'Medium',
                 'High'
                 
                 ];
            collect($ticketpriority)->prepend(__('Ticket Priority'), '');
            $date = '';
            if(!empty($request->category))
            {

                $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->where('category_id', $request->category)->with(['category','unit','code','group','material'])->get();
            }
            else
            {
                $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->with(['category','unit','code','group','material'])->get();
            }
            //  dd($productServices);
            return view('productservice.index', compact('productServices', 'category', 'users', 'date', 'ticketpriority', 'ticketstatus', 'orderstatus'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create product & service'))
        {
            $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'product')->get();
            $category     = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'product & service')->get()->pluck('name', 'id');
            $group = Group::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $group->prepend('Select Group', '');
            $unit         = ProductServiceUnit::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $tax          = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $incomeChartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
                ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
                ->where('chart_of_account_types.name' ,'income')
                ->where('parent', '=', 0)
                ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
                ->pluck('code_name', 'id');
            $incomeChartAccounts->prepend('Select Account', 0);

            $incomeSubAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name,chart_of_accounts.id, chart_of_accounts.code, chart_of_account_parents.account'));
            $incomeSubAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
            $incomeSubAccounts->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type');
            $incomeSubAccounts->where('chart_of_account_types.name' ,'income');
            $incomeSubAccounts->where('chart_of_accounts.parent', '!=', 0);
            $incomeSubAccounts->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
            $incomeSubAccounts = $incomeSubAccounts->get()->toArray();


            $expenseChartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->whereIn('chart_of_account_types.name' ,['Expenses','Costs of Goods Sold'])
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
            $expenseChartAccounts->prepend('Select Account', '');

            $expenseSubAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name,chart_of_accounts.id, chart_of_accounts.code, chart_of_account_parents.account'));
            $expenseSubAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
            $expenseSubAccounts->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type');
            $expenseSubAccounts->whereIn('chart_of_account_types.name' ,['Expenses','Costs of Goods Sold']);
            $expenseSubAccounts->where('chart_of_accounts.parent', '!=', 0);
            $expenseSubAccounts->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
            $expenseSubAccounts = $expenseSubAccounts->get()->toArray();
            $product_services       = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('productservice.create', compact('category', 'group', 'unit', 'tax', 'customFields','incomeChartAccounts','incomeSubAccounts','expenseChartAccounts' , 'expenseSubAccounts', 'product_services'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
     
        $input = $request->all();
        // dd($request->all());
     if($request->sendforapp == '')
     {
        if(\Auth::user()->can('create product & service'))
        {

            $rules = [
                'name' => 'required',
                'hsn_code' => ['required', Rule::unique('product_services')->where(function ($query) {
                   return $query->where('created_by', \Auth::user()->id);
                 })
                ],
                'sale_price' => 'required|numeric',
                'purchase_price' => 'required|numeric',
               
            ];

            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('productservice.index')->with('error', $messages->first());
            }
// dd($request->all());
            $array = explode(":", $request->hsn_code, 2);
            
            $productCode                     = new SpecificationCodeOrder();
            $productCode->code                =$array[1];
            $productCode->save();      
            
            foreach($request->material_total_price as $key => $v)
            {
            $productCodeMaterial                     = new SpecificationCodeMaterial();
            $productCodeMaterial->name                = $request->material[$key];
            $productCodeMaterial->specification_code_order_id   = $productCode->id;
            $productCodeMaterial->unit_rate                = $request->material_total_price[$key];
            $productCodeMaterial->image                = $request->pro_image;
            $productCodeMaterial->qty                = 1;
            $productCodeMaterial->save();  
            }
            if($productCode)
            {
             
            $productService                      = new ProductService();
            $productService->name                = $request->name;
            $productService->model          = $request->model;
            $productService->hsn_code          = $array[1];
            $productService->description         = $request->description;
            $productService->sku                 = 'PRD'.'-'.rand(9999, 10000);
            $productService->generated_img         = $request->generated_img;
            $productService->sale_price          = $request->sale_price;
            $productService->purchase_price      = $request->purchase_price;
            $productService->tax_id              = '';
            $productService->unit_id             = 1;
            $productService->group_id             =  $request->group_id;
            $productService->labor_charge             = $request->other_cost;
            $productService->other_cost             = $request->other_cost;
            if(!empty($request->pro_image))
            {
                //storage limit
                $image_size = $request->file('pro_image')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if($result==1)
                {
                    if($productService->pro_image)
                    {
                        $path = storage_path('uploads/pro_image' . $productService->pro_image);
                    }
                    $fileName = $request->pro_image->getClientOriginalName();
                    $productService->pro_image = $fileName;
                    $dir        = 'uploads/pro_image';
                    $path = Utility::upload_file($request,'pro_image',$fileName,$dir,[]);
                }
            }
            if(!empty($request->quantity))
            {
                $productService->quantity        = $request->material_quantity;
                $productService->sp_qty        = $request->quantity;
            }
            else{
                $productService->quantity   = 0;
                $productService->sp_qty        =0;
            }
            $productService->type                       = 'product';
            $productService->specification_code_order_id       = $productCode->id;
            $productService->expense_chartaccount_id    = $request->expense_chartaccount_id;
            $productService->category_id                = $request->category_id;
           
            $productService->created_by       = \Auth::user()->creatorId();
            $productService->save();
                $invoice = new Invoice();
                $invoice->invoice_id = $this->invoiceNumber();
                $invoice->customer_id = 1;
                $invoice->status = 0;
                $invoice->issue_date = now()->format('Y-m-d');
                $invoice->due_date = now()->format('Y-m-d');
                $invoice->category_id = 3;
                $invoice->ref_number = rand(9999, 10000);
    //            $invoice->discount_apply = isset($request->discount_apply) ? 1 : 0;
                $invoice->created_by = \Auth::user()->creatorId();
                $invoice->save();
            
                $invoiceProduct = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
                $invoiceProduct->product_id = $productService->id;
                $invoiceProduct->quantity  = $request->material_quantity;
                $invoiceProduct->tax = $request->other_cost +  $request->labor_charge;
//                $invoiceProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                $invoiceProduct->discount = 0;
                $invoiceProduct->price = $request->total_price;
                $invoiceProduct->description =$request->description;
                $invoiceProduct->save();
            }
            return redirect()->route('productservice.index')->with('success', __('Product successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
     }else if($request->sendforvirification != ''){
            
            $array = explode(":", $request->hsn_code, 2);
            
            $productCode                     = new SpecificationCodeOrder();
            $productCode->code                =$array[1];
            $productCode->save();      
            
            foreach($request->material_total_price as $key => $v)
            {
            $productCodeMaterial                     = new SpecificationCodeMaterial();
            $productCodeMaterial->name                = $request->material[$key];
            $productCodeMaterial->specification_code_order_id   = $productCode->id;
            $productCodeMaterial->unit_rate                = $request->material_total_price[$key];
            $productCodeMaterial->image                = $request->pro_image;
            $productCodeMaterial->qty                = 1;
            $productCodeMaterial->save();  
            }
            if($productCode)
            {
            $productService                      = new ProductService();
            $productService->name                = $request->name;
            $productService->model          = $request->model;
            $productService->hsn_code          = $array[1];
            $productService->description         = $request->description;
            $productService->sku                 = 'PRD'.'-'.rand(9999, 10000);
            $productService->sale_price          = $request->grand_total;
            $productService->purchase_price      = $request->total_price;
            $productService->tax_id              = '';
            $productService->unit_id             = 1;
            $productService->group_id             =  $request->group_id;
            $productService->labor_charge             = $request->other_cost;
            $productService->other_cost             = $request->other_cost;
            if(!empty($request->quantity))
            {
                $productService->quantity        = $request->main_quantity;
                $productService->sp_qty        = $request->quantity;
            }
            else{
                $productService->quantity   = 0;
                $productService->sp_qty        =0;
            }
            $productService->type                       = 'product';
            $productService->specification_code_order_id       = $productCode->id;
            $productService->expense_chartaccount_id    = $request->expense_chartaccount_id;
            $productService->category_id                = $request->category_id;
            $productService->pro_image = $request->pro_image;
            $productService->created_by       = \Auth::user()->creatorId();
            $productService->save();
                $invoice = new Invoice();
                $invoice->invoice_id = $this->invoiceNumber();
                $invoice->customer_id = 1;
                $invoice->status = 0;
                $invoice->issue_date = now()->format('Y-m-d');
                $invoice->due_date = now()->format('Y-m-d');
                $invoice->category_id = 3;
                $invoice->ref_number = rand(9999, 10000);
    //            $invoice->discount_apply = isset($request->discount_apply) ? 1 : 0;
                $invoice->created_by = \Auth::user()->creatorId();
                $invoice->save();
            
                $invoiceProduct = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
                $invoiceProduct->product_id = $productService->id;
                $invoiceProduct->quantity  = $request->main_quantity;
                $invoiceProduct->tax = $request->other_cost +  $request->labor_charge;
//                $invoiceProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                $invoiceProduct->discount = 0;
                $invoiceProduct->price = $request->total_price;
                $invoiceProduct->description =$request->description;
                $invoiceProduct->save();
            } 
            return redirect()->route('productservice.index')->with('success', __('Product successfully created.'));
            
     }
     else{
           if(!empty($request->pro_image))
            {
                //storage limit
                $image_size = $request->file('pro_image')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if($result==1)
                {
                    // if($productService->pro_image)
                    // {
                    //     $path = storage_path('uploads/pro_image' . $productService->pro_image);
                    // }
                    $fileName = $request->pro_image->getClientOriginalName();
                    $input['pro_image'] = $fileName;
                    $dir        = 'uploads/pro_image';
                    $path = Utility::upload_file($request,'pro_image',$fileName,$dir,[]);
                }
            }
          $invoice_number = \Auth::user()->invoiceNumberFormat($this->invoiceNumber());
          $input['invoice'] = $invoice_number;
          $input['datetime'] =now();
          
          return view('productservice.invoice', compact('input'));
     }
     
    }
   public function invoiceNumber()
    {
        $latest = Invoice::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->invoice_id + 1;
    }
    public function show()
    {
        return redirect()->route('productservice.index');
    }

    public function edit($id)
    {
        $productService = ProductService::find($id);
        
         $cat = Specification::with('subspecifications')->where(['priority' => 0, 'group_id' => $productService->group_id])->get();
         $material = SpecificationCodeMaterial::where(['specification_code_order_id' =>$productService->specification_code_order_id])->orderBy('id', 'desc')->pluck('name')->toArray();
        //  $productService = ProductService::find($id);
     
        // $all_products = ProductService::getallproducts()->count();

       $data = [];
       $datas = [];
       $htmls = '';
        foreach ($cat as $key => $c) {
           if($key == 0)
           {
             $html ='<div class="col-md-4">
            <div class="form-group">
            <label for="specification-name" class="form-label">' . $c->name . '</label>
            <select class="form-control group-material-'.$key.'" data-id="' . $c->name . '"><option>Select' . $c->name . '</option>';   
           }else{
                $html ='<div class="col-md-4">
            <div class="form-group">
            <label for="specification-name" class="form-label">' . $c->name . '</label>
            <select class="form-control group-material-'.$key.'" data-id="' . $c->name . '" disabled><option>Select' . $c->name . '</option>';
           }
            
            
           
             foreach ($c->subspecifications as $key => $cs) {
                 if (array_search($cs->prefix, $material) !== false) {
                   $html .='<option value="' . $cs->id . '" selected>' . $cs->prefix .': '. $cs->name. '</option>';
                } else {
                   $html .='<option value="' . $cs->id . '">' . $cs->prefix .': '. $cs->name. '</option>';  
                }
              
             }
             $html .='</select></div></div>';
               array_push($data, $html);
        }
        
           $cats = SpecificationCodeMaterial::where(['specification_code_order_id' =>$productService->specification_code_order_id])->orderBy('id', 'desc')->get();
        //   dd($cats);
            foreach ($cats as $key => $cat) {
            $htmls ='<div class="col-md-3 comm-div-first edit-input">
            <div class="form-group">
                <label for="material" class="form-label">Material</label><span class="text-danger">*</span>
                <input class="form-control prefix-input" required="required" readonly="" name="material[]" type="text" value="' . $cat->name . '" id="material">
               
            </div>
        </div>
        <div class="col-md-3 comm-div-first edit-input">
            <div class="form-group">
                <label for="unit_rate" class="form-label">Unit Rate</label><span class="text-danger">*</span>
                <input class="form-control " required="required" step="0.01" name="unit_rate[]" type="number" value="' . $cat->unit_rate . '" id="unit_rate-'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first edit-input">
            <div class="form-group">
                <label for="material_quantity" class="form-label">Quantity</label><span class="text-danger">*</span>
                <input class="form-control material_quantity" required="required" step="1" name="material_quantity[]" type="number" value="1" data-id="'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first edit-input">
            <div class="form-group">
                <label for="material_total_price" class="form-label">Total</label><span class="text-danger">*</span>
                <input class="form-control number-input" required="required" readonly="" name="material_total_price[]" type="text" value="' . $cat->unit_rate . '" id="material_total_price-'.$cat->id.'">
            </div>
        </div>';
         array_push($datas, $htmls);
            }
       
         $group = Group::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $group->prepend('Select Group', '');   
        if(\Auth::user()->can('edit product & service'))
        {
            if($productService->created_by == \Auth::user()->creatorId())
            {
                $category = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'product & service')->get()->pluck('name', 'id');
                $unit     = ProductServiceUnit::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $tax      = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

                $productService->customField = CustomField::getData($productService, 'product');
                $customFields                = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'product')->get();
                $productService->tax_id      = explode(',', $productService->tax_id);
                $incomeChartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
                ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
                ->where('chart_of_account_types.name' ,'income')
                ->where('parent', '=', 0)
                ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
                ->pluck('code_name', 'id');
            $incomeChartAccounts->prepend('Select Account', 0);

            $incomeSubAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name' , 'chart_of_account_parents.account');
            $incomeSubAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
            $incomeSubAccounts->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type');
            $incomeSubAccounts->where('chart_of_account_types.name' ,'income');
            $incomeSubAccounts->where('chart_of_accounts.parent', '!=', 0);
            $incomeSubAccounts->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
            $incomeSubAccounts = $incomeSubAccounts->get()->toArray();


            $expenseChartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->whereIn('chart_of_account_types.name' ,['Expenses','Costs of Goods Sold'])
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
            $expenseChartAccounts->prepend('Select Account', '');

            $expenseSubAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name' , 'chart_of_account_parents.account');
            $expenseSubAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
            $expenseSubAccounts->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type');
            $expenseSubAccounts->whereIn('chart_of_account_types.name' ,['Expenses','Costs of Goods Sold']);
            $expenseSubAccounts->where('chart_of_accounts.parent', '!=', 0);
            $expenseSubAccounts->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
            $expenseSubAccounts = $expenseSubAccounts->get()->toArray();

                return view('productservice.edit', compact('category', 'unit', 'group', 'tax', 'data', 'datas', 'cats', 'productService', 'customFields','incomeChartAccounts','expenseChartAccounts' , 'incomeSubAccounts' , 'expenseSubAccounts'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        
        if(\Auth::user()->can('edit product & service'))
        {
            $productService = ProductService::find($id);
            if($productService->created_by == \Auth::user()->creatorId())
            {
                $rules = [
                    'name' => 'required',
                    'model' => 'required',
                    'sale_price' => 'required|numeric',
                    'purchase_price' => 'required|numeric',
                    

                ];

                $validator = \Validator::make($request->all(), $rules);

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('productservice.index')->with('error', $messages->first());
                }
                // dd($request->all());
               
                
                // $array = explode(":", $request->series, 2);
            
            $productCode                     = SpecificationCodeOrder::find($productService->specification_code_order_id);
            $productCode->code                = $request->hsn_code;
            $productCode->save();      
            $count = 0;
            // dd($productCode);
            foreach($request->material_id as $key => $v)
            {
            
            // $productCodeMaterial                     = SpecificationCodeMaterial::find($request->material_id[$key]);
            SpecificationCodeMaterial::where("id", $request->material_id[$key])->update([
                'name' => $request->material[$key],
                'specification_code_order_id' =>$productService->specification_code_order_id,
                'unit_rate' => $request->material_total_price[$key],
                'qty' =>1
                
                ]);
            // $productCodeMaterial->name                = $request->material[$key];
            // $productCodeMaterial->specification_code_order_id   = $productService->specification_code_order_id;
            // $productCodeMaterial->unit_rate                = $request->material_total_price[$key];
            // $productCodeMaterial->image                = $request->pro_image;
            // $productCodeMaterial->qty                = 1;
            // $productCodeMaterial->save();  
            // $count = $count+1;
            }
            if($productCode)
            {
           
            $productService->name                = $request->name;
            $productService->model          = $request->model;
            $productService->hsn_code          = $request->hsn_code;
            $productService->description         = $request->description;
            $productService->sku                 = 'PRD'.'-'.rand(9999, 10000);
            $productService->sale_price          = $request->sale_price;
            $productService->purchase_price      = $request->purchase_price;
            $productService->tax_id              = '';
            $productService->unit_id             = 1;
            $productService->group_id             =  $request->group_id;
            $productService->labor_charge             = $request->other_cost;
            $productService->other_cost             = $request->other_cost;
            if(!empty($request->quantity))
            {
                $productService->quantity        =$request->material_quantity[0];;
                $productService->sp_qty        = $request->quantity;
            }
            else{
                $productService->quantity   = 0;
                $productService->sp_qty        =0;
            }
            $productService->type                       = 'product';
            $productService->specification_code_order_id       = $productCode->id;
            $productService->expense_chartaccount_id    = $request->expense_chartaccount_id;
            $productService->category_id                = $request->category_id;
             if(!empty($request->pro_image))
                {
                    //storage limit
                    // $file_path = '/uploads/pro_image/'.$productService->pro_image;
                    // $image_size = $request->file('pro_image')->getSize();
                    // $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    // if($result==1)
                    // {
//                         if($productService->pro_image)
//                         {
//                             Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);
//                             $path = storage_path('uploads/pro_image' . $productService->pro_image);
// //                            if(file_exists($path))
// //                            {
// //                                \File::delete($path);
// //                            }
//                         }
                        $fileName = $request->pro_image->getClientOriginalName();
                        $productService->pro_image = $fileName;
                        $dir        = 'uploads/pro_image';
                        $path = Utility::upload_file($request,'pro_image',$fileName,$dir,[]);
                    // }

                }
            $productService->created_by       = \Auth::user()->creatorId();
            $productService->save();
                $invoice = new Invoice();
                $invoice->invoice_id = $this->invoiceNumber();
                $invoice->customer_id = 1;
                $invoice->status = 0;
                $invoice->issue_date = now()->format('Y-m-d');
                $invoice->due_date = now()->format('Y-m-d');
                $invoice->category_id = 3;
                $invoice->ref_number = rand(9999, 10000);
    //            $invoice->discount_apply = isset($request->discount_apply) ? 1 : 0;
                $invoice->created_by = \Auth::user()->creatorId();
                $invoice->save();
            
                $invoiceProduct = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
                $invoiceProduct->product_id = $productService->id;
                $invoiceProduct->quantity  = 1;
                $invoiceProduct->tax = $request->other_cost +  $request->labor_charge;
//                $invoiceProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                $invoiceProduct->discount = 0;
                $invoiceProduct->price = $request->purchase_price;
                $invoiceProduct->description =$request->description;
                $invoiceProduct->save();
            }
                // CustomField::saveData($productService, $request->customField);

                return redirect()->route('productservice.index')->with('success', __('Product successfully updated.'));
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

    public function destroy($id)
    {
        if(\Auth::user()->can('delete product & service'))
        {
            $productService = ProductService::find($id);
            if($productService->created_by == \Auth::user()->creatorId())
            {
                if(!empty($productService->pro_image))
                {
                    //storage limit
                    $file_path = '/uploads/pro_image/'.$productService->pro_image;
                    $result = Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);

                }

                $productService->delete();

                return redirect()->route('productservice.index')->with('success', __('Product successfully deleted.'));
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

    public function export()
    {
        $name = 'product_service_' . date('Y-m-d i:h:s');
        $data = Excel::download(new ProductServiceExport(), $name . '.xlsx');

        return $data;
    }

    public function importFile()
    {
        return view('productservice.import');
    }

    public function import(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $products     = (new ProductServiceImport)->toArray(request()->file('file'))[0];
        $totalProduct = count($products) - 1;
        $errorArray   = [];
        for ($i = 1; $i <= count($products) - 1; $i++) {
            $items  = $products[$i];

            $taxes     = explode(';', $items[5]);

            $taxesData = [];
            foreach ($taxes as $tax)
            {
                $taxes       = Tax::where('id', $tax)->first();
                //                $taxesData[] = $taxes->id;
                $taxesData[] = !empty($taxes->id) ? $taxes->id : 0;


            }

            $taxData = implode(',', $taxesData);
            //            dd($taxData);

            if (!empty($productBySku)) {
                $productService = $productBySku;
            } else {
                $productService = new ProductService();
            }

            $productService->name           = $items[0];
            $productService->sku            = $items[1];
            $productService->sale_price     = $items[2];
            $productService->purchase_price = $items[3];
            $productService->quantity       = $items[4];
            $productService->tax_id         = $items[5];
            $productService->category_id    = $items[6];
            $productService->unit_id        = $items[7];
            $productService->type           = $items[8];
            $productService->description    = $items[9];
            $productService->created_by     = \Auth::user()->creatorId();

            if (empty($productService)) {
                $errorArray[] = $productService;
            } else {
                $productService->save();
            }
        }

        $errorRecord = [];
        if (empty($errorArray)) {

            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        } else {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalProduct . ' ' . 'record');


            foreach ($errorArray as $errorData) {

                $errorRecord[] = implode(',', $errorData);
            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }

    public function warehouseDetail($id)
    {
        $productService = ProductService::find($id);
        return view('productservice.detail', compact('productService'));
    }
  
     public function productInvoice(Request $request)
    {
       
       return view('productservice.invoice'); 
    }
    public function searchProducts(Request $request)
    {

        $lastsegment = $request->session_key;

        if (Auth::user()->can('manage pos') && $request->ajax() && isset($lastsegment) && !empty($lastsegment)) {

            $output = "";
            if($request->war_id == '0'){
                $ids = WarehouseProduct::where('warehouse_id',1)->get()->pluck('product_id')->toArray();

                if ($request->cat_id !== '' && $request->search == '') {
                    if($request->cat_id == '0'){
                        $products = ProductService::getallproducts()->whereIn('product_services.id',$ids)->get();

                    }else{
                        $products = ProductService::getallproducts()->where('category_id', $request->cat_id)->whereIn('product_services.id',$ids)->get();
                    }
                } else {
                    if($request->cat_id == '0'){
                        $products = ProductService::getallproducts()->where('product_services.name', 'LIKE', "%{$request->search}%")->get();
                    }else{
                        $products = ProductService::getallproducts()->where('product_services.name', 'LIKE', "%{$request->search}%")->orWhere('category_id', $request->cat_id)->get();
                    }
                }
            }else{
                $ids = WarehouseProduct::where('warehouse_id',$request->war_id)->get()->pluck('product_id')->toArray();
                
                if($request->cat_id == '0'){
                    $products = ProductService::getallproducts()->whereIn('product_services.id',$ids)->with(['unit'])->get();
                    
                }else{
                    $products = ProductService::getallproducts()->whereIn('product_services.id',$ids)->where('category_id', $request->cat_id)->with(['unit'])->get();
                    
                }
            }

            if (count($products)>0)
            {
                foreach ($products as $key => $product)
                {
                    $quantity= $product->warehouseProduct($product->id,$request->war_id!=0?$request->war_id:1);

                    $unit=(!empty($product) && !empty($product->unit))?$product->unit->name:'';

                    if(!empty($product->pro_image)){
                        $image_url =('uploads/pro_image').'/'.$product->pro_image;
                    }else{
                        $image_url =('uploads/pro_image').'/default.png';
                    }
                    if ($request->session_key == 'purchases')
                    {
                        $productprice = $product->purchase_price != 0 ? $product->purchase_price : 0;
                    }
                    else if ($request->session_key == 'pos')
                    {
                        $productprice = $product->sale_price != 0 ? $product->sale_price : 0;

                    }
                    else
                    {
                        $productprice = $product->sale_price != 0 ? $product->sale_price : $product->purchase_price;
                    }

                    $output .= '

                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-12">
                                <div class="tab-pane fade show active toacart w-100" data-url="' . url('add-to-cart/' . $product->id . '/' . $lastsegment) .'">
                                    <div class="position-relative card">
                                        <img alt="Image placeholder" src="' . asset(Storage::url($image_url)) . '" class="card-image avatar shadow hover-shadow-lg" style=" height: 6rem; width: 100%;">
                                        <div class="p-0 custom-card-body card-body d-flex ">
                                            <div class="card-body my-2 p-2 text-left card-bottom-content">
                                                <h6 class="mb-2 text-dark product-title-name">' . $product->name . '</h6>
                                                <small class="badge badge-primary mb-0">' . Auth::user()->priceFormat($productprice) . '</small>

                                                <small class="top-badge badge badge-danger mb-0">'. $quantity.' '.$unit .'</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    ';

                }

                    return Response($output);

            } else {
                $output='<div class="card card-body col-12 text-center">
                    <h5>'.__("No Product Available").'</h5>
                    </div>';
                return Response($output);
            }
        }
    }
   public function search_Products(Request $request)
    {
            // $orderStatus = $request->status_id;
            // $date = Carbon::parse($request->date)->format('Y/m/d');
            $category = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'product & service')->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');
            $users = User::where('type', '=', 'client')->get()->pluck('name', 'id');
            $users->prepend(__('Assigned to'), ''); 
             $orderstatuss = [
                     
                     'Received',
                     'Testing',
                     'Repairing',
                     'Dispatch',
                     'Resolved'
                     ];
           
            $translatedTexts = __('Order Status');
            $orderstatus = collect([$translatedTexts])->merge($orderstatuss);
            $ticketstatuss = [
                'Open',
                'Hold',
                'On-Going',
                'Closed'
            ];
            
            $translatedText = __('Ticket Status');
            $ticketstatus = collect([$translatedText])->merge($ticketstatuss);
            $ticketpriority = [
                 'Low',
                 'Medium',
                 'High'
                 
                 ];
            collect($ticketpriority)->prepend(__('Ticket Priority'), '');
            $date = '';
            if($request->status_id != 0 && $request->date != '')
            {
              
                $parsedDate = Carbon::parse($request->date)->format('Y-m-d');
                $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->where('status', $request->status_id)->whereDate('created_at', $parsedDate)
                ->with(['category','unit','code','group','material'])->get();
            //   dd($productServices);
                 return view('productservice.index', compact('productServices', 'category', 'users', 'date', 'ticketpriority', 'ticketstatus', 'orderstatus'));
            }
            if($request->date != '')
            {
                
                $parsedDate = Carbon::parse($request->date)->format('Y-m-d');
                $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->whereDate('created_at', $parsedDate)
                ->with(['category','unit','code','group','material'])->get();
               
            }
            else
            {
           
                $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->with(['category','unit','code','group','material'])->get();
               
            }
            //  dd($productServices);
            return view('productservice.index', compact('productServices', 'category', 'users', 'date', 'ticketpriority', 'ticketstatus', 'orderstatus'));
    }
    public function addToCart(Request $request, $id,$session_key)
    {

        if (Auth::user()->can('manage product & service') && $request->ajax()) {
            $product = ProductService::find($id);
            $productquantity = 0;

            if ($product) {
                $productquantity = $product->getTotalProductQuantity();
            }

            if (!$product || ($session_key == 'pos' && $productquantity == 0)) {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            $productname = $product->name;

            if ($session_key == 'purchases') {

                $productprice = $product->purchase_price != 0 ? $product->purchase_price : 0;
            } else if ($session_key == 'pos') {

                $productprice = $product->sale_price != 0 ? $product->sale_price : 0;
            } else {

                $productprice = $product->sale_price != 0 ? $product->sale_price : $product->purchase_price;
            }

            $originalquantity = (int)$productquantity;

            $taxes=Utility::tax($product->tax_id);

            $totalTaxRate=Utility::totalTaxRate($product->tax_id);

            $product_tax='';
            $product_tax_id=[];
            foreach($taxes as $tax){
                $product_tax.= !empty($tax)?"<span class='badge badge-primary'>". $tax->name.' ('.$tax->rate.'%)'."</span><br>":'';
                $product_tax_id[]=!empty($tax) ?$tax->id :0;
            }

            if(empty($product_tax)){
                $product_tax="-";
            }
            $producttax = $totalTaxRate;


            $tax = ($productprice * $producttax) / 100;

            $subtotal        = $productprice + $tax;
            $cart            = session()->get($session_key);
            $image_url = (!empty($product->pro_image) && Storage::exists($product->pro_image)) ? $product->pro_image : 'uploads/pro_image/'. $product->pro_image;

            $model_delete_id = 'delete-form-' . $id;

            $carthtml = '';

            $carthtml .= '<tr data-product-id="' . $id . '" id="product-id-' . $id . '">
                            <td class="cart-images">
                                <img alt="Image placeholder" src="' . asset(Storage::url($image_url)) . '" class="card-image avatar shadow hover-shadow-lg">
                            </td>

                            <td class="name">' . $productname . '</td>

                            <td class="">
                                   <span class="quantity buttons_added">
                                         <input type="button" value="-" class="minus">
                                         <input type="number" step="1" min="1" max="" name="quantity" title="' . __('Quantity') . '" class="input-number" size="4" data-url="' . url('update-cart/') . '" data-id="' . $id . '">
                                         <input type="button" value="+" class="plus">
                                   </span>
                            </td>


                            <td class="tax">' . $product_tax . '</td>

                            <td class="price">' . Auth::user()->priceFormat($productprice) . '</td>

                            <td class="subtotal">' . Auth::user()->priceFormat($subtotal) . '</td>

                            <td class="">
                                 <a href="#" class="action-btn bg-danger bs-pass-para-pos" data-confirm="' . __("Are You Sure?") . '" data-text="' . __("This action can not be undone. Do you want to continue?") . '" data-confirm-yes=' . $model_delete_id . ' title="' . __('Delete') . '}" data-id="' . $id . '" title="' . __('Delete') . '"   >
                                   <span class=""><i class="ti ti-trash btn btn-sm text-white"></i></span>
                                 </a>
                                 <form method="post" action="' . url('remove-from-cart') . '"  accept-charset="UTF-8" id="' . $model_delete_id . '">
                                      <input name="_method" type="hidden" value="DELETE">
                                      <input name="_token" type="hidden" value="' . csrf_token() . '">
                                      <input type="hidden" name="session_key" value="' . $session_key . '">
                                      <input type="hidden" name="id" value="' . $id . '">
                                 </form>

                            </td>
                        </td>';

            // if cart is empty then this the first product
            if (!$cart) {
                $cart = [
                    $id => [
                        "name" => $productname,
                        "quantity" => 1,
                        "price" => $productprice,
                        "id" => $id,
                        "tax" => $producttax,
                        "subtotal" => $subtotal,
                        "originalquantity" => $originalquantity,
                        "product_tax"=>$product_tax,
                        "product_tax_id"=>!empty($product_tax_id)?implode(',',$product_tax_id):0,
                    ],
                ];


                if ($originalquantity < $cart[$id]['quantity'] && $session_key == 'pos') {
                    return response()->json(
                        [
                            'code' => 404,
                            'status' => 'Error',
                            'error' => __('This product is out of stock!'),
                        ],
                        404
                    );
                }

                session()->put($session_key, $cart);

                return response()->json(
                    [
                        'code' => 200,
                        'status' => 'Success',
                        'success' => $productname . __(' added to cart successfully!'),
                        'product' => $cart[$id],
                        'carthtml' => $carthtml,
                    ]
                );
            }

            // if cart not empty then check if this product exist then increment quantity
            if (isset($cart[$id])) {

                $cart[$id]['quantity']++;
                $cart[$id]['id'] = $id;

                $subtotal = $cart[$id]["price"] * $cart[$id]["quantity"];
                $tax      = ($subtotal * $cart[$id]["tax"]) / 100;

                $cart[$id]["subtotal"]         = $subtotal + $tax;
                $cart[$id]["originalquantity"] = $originalquantity;

                if ($originalquantity < $cart[$id]['quantity'] && $session_key == 'pos') {
                    return response()->json(
                        [
                            'code' => 404,
                            'status' => 'Error',
                            'error' => __('This product is out of stock!'),
                        ],
                        404
                    );
                }

                session()->put($session_key, $cart);

                return response()->json(
                    [
                        'code' => 200,
                        'status' => 'Success',
                        'success' => $productname . __(' added to cart successfully!'),
                        'product' => $cart[$id],
                        'carttotal' => $cart,
                    ]
                );
            }

            // if item not exist in cart then add to cart with quantity = 1
            $cart[$id] = [
                "name" => $productname,
                "quantity" => 1,
                "price" => $productprice,
                "tax" => $producttax,
                "subtotal" => $subtotal,
                "id" => $id,
                "originalquantity" => $originalquantity,
                "product_tax"=>$product_tax,
            ];

            if ($originalquantity < $cart[$id]['quantity'] && $session_key == 'pos') {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            session()->put($session_key, $cart);

            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Success',
                    'success' => $productname . __(' added to cart successfully!'),
                    'product' => $cart[$id],
                    'carthtml' => $carthtml,
                    'carttotal' => $cart,
                ]
            );
        } else {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('This Product is not found!'),
                ],
                404
            );
        }
    }

    public function updateCart(Request $request)
    {

        $id          = $request->id;
        $quantity    = $request->quantity;
        $discount    = $request->discount;
        $session_key = $request->session_key;

        if (Auth::user()->can('manage product & service') && $request->ajax() && isset($id) && !empty($id) && isset($session_key) && !empty($session_key)) {
            $cart = session()->get($session_key);


            if (isset($cart[$id]) && $quantity == 0) {
                unset($cart[$id]);
            }

            if ($quantity) {

                $cart[$id]["quantity"] = $quantity;

                $producttax            = isset($cart[$id]) ? $cart[$id]["tax"]:0;
                $productprice          = $cart[$id]["price"];

                $subtotal = $productprice * $quantity;
                $tax      = ($subtotal * $producttax) / 100;

                $cart[$id]["subtotal"] = $subtotal + $tax;

            }
            
            if (isset($cart[$id]) && ($cart[$id]["originalquantity"]) < $cart[$id]['quantity'] && $session_key == 'pos') {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            $subtotal = array_sum(array_column($cart, 'subtotal'));
            $discount = $request->discount;
            $total = $subtotal - $discount;
            $totalDiscount = User::priceFormats($total);
            $discount = $totalDiscount;


            session()->put($session_key, $cart);

            return response()->json(
                [
                    'code' => 200,
                    'success' => __('Cart updated successfully!'),
                    'product' => $cart,
                    'discount' => $discount,
                ]
            );
        } else {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('This Product is not found!'),
                ],
                404
            );
        }
    }

    public function emptyCart(Request $request)
    {
        $session_key = $request->session_key;

        if (Auth::user()->can('manage product & service') && isset($session_key) && !empty($session_key))
        {
            $cart = session()->get($session_key);
            if (isset($cart) && count($cart) > 0)
            {
                session()->forget($session_key);
            }

            return redirect()->back()->with('error', __('Cart is empty!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Cart cannot be empty!.'));

        }
    }

    public function warehouseemptyCart(Request $request)
    {
        $session_key = $request->session_key;

            $cart = session()->get($session_key);
            if (isset($cart) && count($cart) > 0)
            {
                session()->forget($session_key);
            }

        return response()->json();

    }

    
    public function product_status(Request $request)
    {
       
         $product = ProductService::find($request->pid);
        
           $input = $request->input('id');

    if (is_numeric($input)) {
       $product->status = $input;
       $product->save();
    } else {
        if($input == 'High' || $input == 'Medium' ||$input == 'Low'){
            $product->ticket_priority = $input;
            $product->save();
        }else{
            $product->ticket_status = $input;
            $product->save();
        }
    }
    if($product)
    {
       return response()->json(
                [
                    'code' => 200,
                    'msg' => __('Status updated successfully!'),
                   
                ]
            );  
    }else{
         return response()->json(
                [
                    'code' => 403,
                    'msg' => __('Status updation failed!'),
                   
                ]
            );
    }
    

        

    }public function removeFromCart(Request $request)
    {
        $id          = $request->id;
        $session_key = $request->session_key;
        if (Auth::user()->can('manage product & service') && isset($id) && !empty($id) && isset($session_key) && !empty($session_key)) {
            $cart = session()->get($session_key);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put($session_key, $cart);
            }

            return redirect()->back()->with('error', __('Product removed from cart!'));
        } else {
            return redirect()->back()->with('error', __('This Product is not found!'));
        }
    }

}
