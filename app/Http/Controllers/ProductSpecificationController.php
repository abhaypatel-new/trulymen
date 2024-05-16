<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\ChartOfAccountType;
use App\Models\CustomField;
use App\Exports\ProductSpecificationExport;
use App\Imports\ProductServiceImport;
use App\Models\Product;
use App\Models\Specification;
use App\Models\ProductService;
use App\Models\ProductServiceCategory;
use App\Models\ProductServiceUnit;
use App\Models\Tax;
use App\Models\Group;
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

class ProductSpecificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if(\Auth::user()->can('manage product & service'))
        {
           

            $productServices = Specification::where('priority', '=', 0)->get();

            return view('productspecification.index', compact('productServices'));
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
            $group = Group::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $group->prepend('Select Group', '');
            return view('productspecification.create', compact('group'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        //   dd($request->all());
        if(\Auth::user()->can('create product & service'))
        {
     
            $rules = [
                'name' => 'required',
                'prefix' => 'required',
                'sub_specification' => 'required',
                
            ];

            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('productspecification.index')->with('error', $messages->first());
            }

            $productService                      = new Specification();
            $productService->name                = $request->name;
            $productService->group_id = $request->group_id;
            $productService->priority       = 0;
            $productService->created_by       = \Auth::user()->creatorId();
           
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
                    $productService->image = $fileName;
                    $dir        = 'uploads/pro_image';
                    $path = Utility::upload_file($request,'pro_image',$fileName,$dir,[]);
                }
            }
            //  dd($productService);
             $productService->save();
            if(count($request->prefix)>0) {
               
                 
                foreach($request->prefix as $key => $v)
                {
                    $productSub                      = new Specification();
                    $productSub->name                = $request->sub_specification[$key];
                    $productSub->priority       =$productService->id;
                    $productSub->price                = $request->price[$key];
                    $productSub->prefix                = $request->prefix[$key];
                    $productSub->group_id = $request->group_id;
                    $productSub->created_by       = \Auth::user()->creatorId();
                    $productSub->save();
                }
                
            }
             
            
            
           
            CustomField::saveData($productService, $request->customField);

            return redirect()->route('productspecification.index')->with('success', __('Materials successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('productspecification.index');
    }

    public function edit($id)
    {
        $productService = Specification::find($id);
        $subproductService = Specification::where('priority', $id)->get();
        $group = Group::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        // $productService = Specification::where($id);

        if(\Auth::user()->can('edit product & service'))
        {
            if($productService->created_by == \Auth::user()->creatorId())
            {
                return view('productspecification.edit', compact('productService', 'subproductService', 'group'));
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
    //  dd($request->all());
        if(\Auth::user()->can('edit product & service'))
        {
            $productService = Specification::find($id);
            if($productService->created_by == \Auth::user()->creatorId())
            {
                $rules = [
                    'name' => 'required',

                ];

                $validator = \Validator::make($request->all(), $rules);

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('productspecification.index')->with('error', $messages->first());
                }

                $productService->name           = $request->name;
              
                if(!empty($request->pro_image))
                {
                    //storage limit
                    $file_path = '/uploads/pro_image/'.$productService->pro_image;
                    $image_size = $request->file('pro_image')->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    if($result==1)
                    {
                        if($productService->pro_image)
                        {
                            Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);
                            $path = storage_path('uploads/pro_image' . $productService->pro_image);
//                            if(file_exists($path))
//                            {
//                                \File::delete($path);
//                            }
                        }
                        $fileName = $request->pro_image->getClientOriginalName();
                        $productService->image = $fileName;
                        $dir        = 'uploads/pro_image';
                        $path = Utility::upload_file($request,'pro_image',$fileName,$dir,[]);
                    }

                }

                $productService->created_by     = \Auth::user()->creatorId();
                $productService->group_id = $request->group_id;
                $productService->save();
                
                if(count($request->s_id)>0) {
                foreach($request->prefix as $key => $v)
                {
                   
                    $productSub                      = Specification::find($request->s_id[$key]);
                    $productSub->name                = $request->sub_specification[$key];
                    $productSub->priority       =$id;
                    $productSub->price                = $request->price[$key];
                    $productSub->prefix                = $request->prefix[$key];
                    $productSub->group_id = $request->group_id;
                    $productSub->created_by       = \Auth::user()->creatorId();
                    $productSub->save();
                    // dd(Specification::find($request->s_id[$key])); 
                }
                
            }
            if($request->check == 1)
            {
            if(count($request->prefixs)>0)
            {
                
                 foreach($request->prefixs as $key => $v)
                {
                   
                    $productSubNew               = new Specification();
                    $productSubNew->name                = $request->sub_specifications[$key];
                    $productSubNew->priority       =$id;
                    $productSubNew->price                = $request->prices[$key];
                    $productSubNew->prefix                = $request->prefixs[$key];
                    $productSubNew->group_id = $request->group_id;
                    $productSubNew->created_by       = \Auth::user()->creatorId();
                    $productSubNew->save();  
                }
            }
               
            }
                return redirect()->route('productspecification.index')->with('success', __('Materials successfully updated.'));
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
            $productService = Specification::find($id);
            if($productService->created_by == \Auth::user()->creatorId())
            {
                if(!empty($productService->pro_image))
                {
                    //storage limit
                    $file_path = '/uploads/pro_image/'.$productService->pro_image;
                    $result = Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);

                }

                $productService->delete();

                return redirect()->route('productspecification.index')->with('success', __('Record successfully deleted.'));
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
       
        $name = 'product_specification_' . date('Y-m-d i:h:s');
        $data = Excel::download(new ProductSpecificationExport(), $name . '.xlsx');

        return $data;
    }

    public function importFile()
    {
        return view('productspecification.import');
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
        $products = WarehouseProduct::with(['warehouse'])->where('product_id', '=', $id)->where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('productspecification.detail', compact('products'));
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

    public function removeFromCart(Request $request)
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
