<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\ChartOfAccount;
use App\Models\Invoice;
use App\Models\Group;
use App\Models\Specification;
use App\Models\SpecificationCodeMaterial;
use App\Models\ProductService;
use App\Models\ProductServiceCategory;
use Illuminate\Http\Request;

class ProductServiceCategoryController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage constant category')) {
            $categories = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('productServiceCategory.index', compact('categories'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create constant category')) {
            $types = ProductServiceCategory::$catTypes;
            $type = ['' => 'Select Category Type'];

            $types = array_merge($type, $types);

            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('created_by', \Auth::user()->creatorId())->get()
                ->pluck('code_name', 'id');
            $chart_accounts->prepend('Select Account', '');

            return view('productServiceCategory.create', compact('types', 'chart_accounts'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        if (\Auth::user()->can('create constant category')) {

            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required|max:200',
                    'type' => 'required',
                    'color' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $category = new ProductServiceCategory();
            $category->name = $request->name;
            $category->color = $request->color;
            $category->type = $request->type;
            $category->chart_account_id = !empty($request->chart_account) ? $request->chart_account : 0;
            $category->created_by = \Auth::user()->creatorId();
            $category->save();

            return redirect()->route('product-category.index')->with('success', __('Category successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {

        if (\Auth::user()->can('edit constant category')) {
            $types = ProductServiceCategory::$catTypes;
            $category = ProductServiceCategory::find($id);

            return view('productServiceCategory.edit', compact('category', 'types'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {

        if (\Auth::user()->can('edit constant category')) {
            $category = ProductServiceCategory::find($id);
            if ($category->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(), [
                        'name' => 'required|max:200',
                        'type' => 'required',
                        'color' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $category->name = $request->name;
                $category->color = $request->color;
                $category->type = $request->type;
                $category->chart_account_id = !empty($request->chart_account) ? $request->chart_account : 0;
                $category->save();

                return redirect()->route('product-category.index')->with('success', __('Category successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete constant category')) {
            $category = ProductServiceCategory::find($id);
            if ($category->created_by == \Auth::user()->creatorId()) {

                if ($category->type == 0) {
                    $categories = ProductService::where('category_id', $category->id)->first();
                } elseif ($category->type == 1) {
                    $categories = Invoice::where('category_id', $category->id)->first();
                } else {
                    $categories = Bill::where('category_id', $category->id)->first();
                }

                if (!empty($categories)) {
                    return redirect()->back()->with('error', __('this category is already assign so please move or remove this category related data.'));
                }

                $category->delete();

                return redirect()->route('product-category.index')->with('success', __('Category successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getProductCategories()
    {
        $cat = ProductServiceCategory::getallCategories();
        $all_products = ProductService::getallproducts()->count();

        $html = '<div class="mb-3 mr-2 zoom-in ">
                  <div class="card rounded-10 card-stats mb-0 cat-active overflow-hidden" data-id="0">
                     <div class="category-select" data-cat-id="0">
                        <button type="button" class="btn tab-btns btn-primary">' . __("All Categories") . '</button>
                     </div>
                  </div>
               </div>';
        foreach ($cat as $key => $c) {
            $dcls = 'category-select';
            $html .= ' <div class="mb-3 mr-2 zoom-in cat-list-btn">
                          <div class="card rounded-10 card-stats mb-0 overflow-hidden " data-id="' . $c->id . '">
                             <div class="' . $dcls . '" data-cat-id="' . $c->id . '">
                                <button type="button" class="btn tab-btns btn-primary">' . $c->name . '</button>
                             </div>
                          </div>
                       </div>';
        }
        return Response($html);
    }
    
     public function getSpecification(Request $request)
    {
        // dd($request->all());
        if($request->type != 'hsn_code')
        {
        $cat = Specification::with('subspecifications')->where(['priority' => 0, 'group_id' => $request->type])->get();
        
        //  dd(count($cat));
        // $all_products = ProductService::getallproducts()->count();

       $data = [];
       $imghtml = '';
        foreach ($cat as $key => $c) {
                    
                
           if($key == 0)
           {
             $html ='<div class="col-md-4">
            <div class="form-group">
            <label for="specification-name" class="form-label">' . $c->name . '</label>
            <select class="form-control group-material-'.$key.'" data-id="' . $c->name . '"><option>Select' . $c->name . '</option>';   
           }else{
               if($key == count($cat)-1){
                   $imghtml = '<img src="'.url("storage/uploads/pro_image/".$c->image) .'" alt="specification" class="mt-3" style="width:25%;"/><input type="hidden" name="generated_img" value="'.$c->image.'">';
                    $html ='<div class="col-md-4">
            <div class="form-group">
            <label for="specification-name" class="form-label">' . $c->name . '</label>
            <select class="form-control group-material-'.$key.'" data-id="' . $c->name . '" disabled id="print_img"><option>Select' . $c->name . '</option>';
               }else{
                  $html ='<div class="col-md-4">
            <div class="form-group">
            <label for="specification-name" class="form-label">' . $c->name . '</label>
            <select class="form-control group-material-'.$key.'" data-id="' . $c->name . '" disabled><option>Select' . $c->name . '</option>'; 
               }
                
           }
            
            
           
             foreach ($c->subspecifications as $key => $cs) {
            $html .='<option value="' . $cs->id . '">' . $cs->prefix .': '. $cs->name. '</option>';
             }
             $html .='</select></div></div>';
               array_push($data, $html);
            $data['img'] = $imghtml;
        }
    //   dd($data);
        }else
        {
           $product = ProductService::where('hsn_code', $request->code)->first();  
        //   dd($product);
          
           if(isset($product))
           {
            $productService =  ProductService::find($product->id);
            $all =  ProductService::all();
        
         $cat = Specification::with('subspecifications')->where(['priority' => 0, 'group_id' => $productService->group_id])->get();
         $material = SpecificationCodeMaterial::where(['specification_code_order_id' =>$productService->specification_code_order_id])->orderBy('id', 'desc')->pluck('name')->toArray();
        //   dd($material);
        //  $productService = ProductService::find($id);
     
        // $all_products = ProductService::getallproducts()->count();

       $data = [];
       $datas = [];
       $htmls = '';
        foreach ($cat as $key => $c) {
           if($key == 0)
           {
             $html ='<div class="col-md-4" style="float: inline-start; padding:5px;">
            <div class="form-group">
            <label for="specification-name" class="form-label">' . $c->name . '</label>
            <select class="form-control group-material-'.$key.'" data-id="' . $c->name . '"><option>Select' . $c->name . '</option>';   
           }else{
            //   $st = $key%2 == 0?'float: inline-start':'';
                $html ='<div class="col-md-4" style="float: inline-start;padding:5px;">
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
            //   $data['main'] = $html;
            //   array_push($data, $htmls);
        }
          $htmls = '<select class="form-control select2 item" data-url="https://trumen.truelymatch.com/quotation/product" required="required" name="items[0][item]">';
             foreach ($all as $key => $p) {
                 if ($p->id == $productService->id) {
                   $htmls .='<option value="' . $p->id . '" selected>' . $p->name .'</option>';
                } else {
                   $htmls .='<option value="' . $p->id . '">' . $p->name. '</option>';  
                }
              
             }
             $htmls .='</select></div></div>';
             $data['product'] = $htmls;
           }else{
               $response['status'] = 403;
               $response['message'] = 'Recorde not found';
               return Response($response);
           }
            $data['item'] = $productService;
        }
       
        // dd($data);
        return Response($data);
    }
    
     public function getSpecificationMaterials(Request $request)
    {
       
        if($request->tab == 'Enclosure')
        {
             $cat = Specification::where(['id' => $request->id, 'group_id' => $request->type])->first();
            //  dd($cat);
            $html ='<div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material" class="form-label">Material</label><span class="text-danger">*</span>
                <input class="form-control prefix-input" required="required" readonly="" name="material[]" type="text" value="' . $cat->prefix . '" id="material">
               
            </div>
        </div>
        <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="unit_rate" class="form-label">Unit Rate</label><span class="text-danger">*</span>
                <input class="form-control" required="required" step="0.01" name="unit_rate[]" type="number" value="' . $cat->price . '" id="unit_rate-'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_quantity" class="form-label">Quantity</label><span class="text-danger">*</span>
                <input class="form-control material_quantity" required="required" step="1" name="material_quantity[]" type="number" value="1" data-id="'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_total_price" class="form-label">Total</label><span class="text-danger">*</span>
                <input class="form-control number-input" required="required" readonly="" name="material_total_price[]" type="text" value="' . $cat->price . '" id="material_total_price-'.$cat->id.'">
            </div>
        </div>';
        }else if($request->tab == 'Process+Connection+Material')
        {
             $cat = Specification::where(['id' => $request->id, 'group_id' => $request->type])->first();
          $html ='<div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material" class="form-label">Material</label><span class="text-danger">*</span>
                <input class="form-control prefix-input" required="required" readonly="" name="material[]" type="text" value="' . $cat->prefix . '" id="material">
                
              
            </div>
        </div>
        <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="unit_rate" class="form-label">Unit Rate</label><span class="text-danger">*</span>
                <input class="form-control" required="required" step="0.01" name="unit_rate[]" type="number" value="' . $cat->price . '" id="unit_rate-'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_quantity" class="form-label">Quantity</label><span class="text-danger">*</span>
                <input class="form-control material_quantity" required="required" step="1" name="material_quantity[]" type="number" value="1" data-id="'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_total_price" class="form-label">Total</label><span class="text-danger">*</span>
                <input class="form-control number-input" required="required" readonly="" name="material_total_price[]" type="text" value="' . $cat->price . '" id="material_total_price-'.$cat->id.'">
            </div>
        </div>';   
        }else if($request->tab == 'Process Connection Type')
        {
             $cat = Specification::where(['id' => $request->id, 'group_id' => $request->type])->first();
            $html ='<div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material" class="form-label">Material</label><span class="text-danger">*</span>
                <input class="form-control prefix-input" required="required" readonly="" name="material[]" type="text" value="' . $cat->prefix . '" id="material">
                
            </div>
        </div>
        <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="unit_rate" class="form-label">Unit Rate</label><span class="text-danger">*</span>
                <input class="form-control number-input" required="required" step="0.01" name="unit_rate[]" type="number" value="' . $cat->price . '" id="unit_rate-'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_quantity" class="form-label">Quantity</label><span class="text-danger">*</span>
                <input class="form-control material_quantity" required="required" step="1" name="material_quantity[]" type="number" value="1" data-id="'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_total_price" class="form-label">Total</label><span class="text-danger">*</span>
                <input class="form-control" required="required" readonly="" name="material_total_price[]" type="text" value="' . $cat->price . '" id="material_total_price-'.$cat->id.'">
            </div>
        </div>'; 
        }else{
             $cat = Specification::where(['id' => $request->id, 'group_id' => $request->type])->first();
             $html ='<div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material" class="form-label">Material</label><span class="text-danger">*</span>
                <input class="form-control prefix-input" required="required" readonly="" name="material[]" type="text" value="' . $cat->prefix . '" id="material">
              
            </div>
        </div>
        <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="unit_rate" class="form-label">Unit Rate</label><span class="text-danger">*</span>
                <input class="form-control" required="required" step="0.01" name="unit_rate[]" type="number" value="' . $cat->price . '" id="unit_rate-'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_quantity" class="form-label">Quantity</label><span class="text-danger">*</span>
                <input class="form-control material_quantity" required="required" step="1" name="material_quantity[]" type="number" value="1" data-id="'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_total_price" class="form-label">Total</label><span class="text-danger">*</span>
                <input class="form-control number-input" required="required" readonly="" name="material_total_price[]" type="text" value="' . $cat->price . '" id="material_total_price-'.$cat->id.'">
            </div>
        </div>';
            
        }
            
        
        return Response($html);
    }
    
     public function getSpecificationMaterialss(Request $request)
    {
       
        if($request->tab == 'Enclosure')
        {
             $cat = Specification::where(['id' => $request->id, 'group_id' => $request->type])->first();
            $html ='<div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material" class="form-label">Material</label><span class="text-danger">*</span>
                <input class="form-control prefix-input" required="required" readonly="" name="material[]" type="text" value="' . $cat->prefix . '" id="material">
              
            </div>
        </div>
        <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="unit_rate" class="form-label">Unit Rate</label><span class="text-danger">*</span>
                <input class="form-control" required="required" step="0.01" name="unit_rate[]" type="number" value="' . $cat->price . '" id="unit_rate-'.$cat->id.'"  readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_quantity" class="form-label">Quantity</label><span class="text-danger">*</span>
                <input class="form-control material_quantity" required="required" step="1" name="material_quantity[]" type="number" value="1" data-id="'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_total_price" class="form-label">Total</label><span class="text-danger">*</span>
                <input class="form-control number-input" required="required" readonly="" name="material_total_price[]" type="text" value="' . $cat->price . '" id="material_total_price-'.$cat->id.'">
            </div>
        </div>';
        }else if($request->tab == 'Process+Connection+Material')
        {
             $cat = Specification::where(['id' => $request->id, 'group_id' => $request->type])->first();
          $html ='<div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material" class="form-label">Material</label><span class="text-danger">*</span>
                <input class="form-control prefix-input" required="required" readonly="" name="material[]" type="text" value="' . $cat->prefix . '" id="material">
                
            </div>
        </div>
        <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="unit_rate" class="form-label">Unit Rate</label><span class="text-danger">*</span>
                <input class="form-control" required="required" step="0.01" name="unit_rate[]" type="number" value="' . $cat->price . '" id="unit_rate-'.$cat->id.'"  readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_quantity" class="form-label">Quantity</label><span class="text-danger">*</span>
                <input class="form-control material_quantity" required="required" step="1" name="material_quantity[]" type="number" value="1" data-id="'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_total_price" class="form-label">Total</label><span class="text-danger">*</span>
                <input class="form-control number-input" required="required" readonly="" name="material_total_price[]" type="text" value="' . $cat->price . '" id="material_total_price-'.$cat->id.'">
            </div>
        </div>';   
        }else if($request->tab == 'Process Connection Type')
        {
             $cat = Specification::where(['id' => $request->id, 'group_id' => $request->type])->first();
            $html ='<div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material" class="form-label">Material</label><span class="text-danger">*</span>
                <input class="form-control prefix-input" required="required" readonly="" name="material[]" type="text" value="' . $cat->prefix . '" id="material">
                 
            </div>
        </div>
        <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="unit_rate" class="form-label">Unit Rate</label><span class="text-danger">*</span>
                <input class="form-control" required="required" step="0.01" name="unit_rate[]" type="number" value="' . $cat->price . '" id="unit_rate-'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_quantity" class="form-label">Quantity</label><span class="text-danger">*</span>
                <input class="form-control material_quantity" required="required" step="1" name="material_quantity[]" type="number" value="1" data-id="'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_total_price" class="form-label">Total</label><span class="text-danger">*</span>
                <input class="form-control number-input" required="required" readonly="" name="material_total_price[]" type="text" value="' . $cat->price . '" id="material_total_price-'.$cat->id.'">
            </div>
        </div>'; 
        }else{
             $cat = Specification::where(['id' => $request->id, 'group_id' => $request->type])->first();
             $html ='<div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material" class="form-label">Material</label><span class="text-danger">*</span>
                <input class="form-control prefix-input" required="required" readonly="" name="material[]" type="text" value="' . $cat->prefix . '" id="material">
                 
            </div>
        </div>
        <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="unit_rate" class="form-label">Unit Rate</label><span class="text-danger">*</span>
                <input class="form-control" required="required" step="0.01" name="unit_rate[]" type="number" value="' . $cat->price . '" id="unit_rate-'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_quantity" class="form-label">Quantity</label><span class="text-danger">*</span>
                <input class="form-control material_quantity" required="required" step="1" name="material_quantity[]" type="number" value="1" data-id="'.$cat->id.'" readonly="">
            </div>
        </div>
         <div class="col-md-3 comm-div-first">
            <div class="form-group">
                <label for="material_total_price" class="form-label">Total</label><span class="text-danger">*</span>
                <input class="form-control number-input" required="required" readonly="" name="material_total_price[]" type="text" value="' . $cat->price . '" id="material_total_price-'.$cat->id.'">
            </div>
        </div>';
            
        }
            
        
        return Response($html);
    }

    public function getAccount(Request $request)
    {

        $chart_accounts = [];
        if ($request->type == 'income') {
            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->where('chart_of_account_types.name' ,'Income')
            ->where('parent', '=', 0)
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
        } elseif ($request->type == 'expense') {
            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->where('chart_of_account_types.name' ,'Expenses')
            ->where('parent', '=', 0)
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
        } elseif ($request->type == 'asset') {
            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->where('chart_of_account_types.name' ,'Assets')
            ->where('parent', '=', 0)
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
        } elseif ($request->type == 'liability') {
            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->where('chart_of_account_types.name' ,'Liabilities')
            ->where('parent', '=', 0)
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
        } elseif ($request->type == 'equity') {
            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->where('chart_of_account_types.name' ,'Equity')
            ->where('parent', '=', 0)
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
        } elseif ($request->type == 'costs of good sold') {
            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->where('chart_of_account_types.name' ,'Costs of Goods Sold')
            ->where('parent', '=', 0)
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
        } else {
            $chart_accounts = 0;
        }

        $subAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name' , 'chart_of_account_parents.account');
        $subAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
        $subAccounts->where('chart_of_accounts.parent', '!=', 0);
        $subAccounts->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
        $subAccounts = $subAccounts->get()->toArray();

    $response = [
        'chart_accounts' => $chart_accounts,
        'sub_accounts' => $subAccounts,
    ];

        return response()->json($response);

    }

}
