<?php

namespace App\Http\Controllers;

use App\Due;
use App\InvoiceStock;
use App\Party;
use App\Product;
use App\ProductBrand;
use App\ProductCategory;
use App\ProductPurchase;
use App\ProductPurchaseDetail;
use App\ProductSubCategory;
use App\Stock;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Store;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ProductPurchaseController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:product-purchase-list|product-purchase-create|product-purchase-edit|product-purchase-delete', ['only' => ['index','show']]);
        $this->middleware('permission:product-purchase-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-purchase-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-purchase-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $productPurchases = ProductPurchase::latest()->get();
        return view('backend.productPurchase.index',compact('productPurchases'));
    }


    public function create()
    {
        $parties = Party::where('type','supplier')->get() ;
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin"){
            $stores = Store::all();
        }else{
            $stores = Store::where('user_id',$auth_user_id)->get();
        }
        $productCategories = ProductCategory::all();
        $productSubCategories = ProductSubCategory::all();
        $productBrands = ProductBrand::all();
        $products = Product::where('product_type','Finish Goods')->get();
        return view('backend.productPurchase.create',compact('parties','stores','products','productCategories','productSubCategories','productBrands'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'party_id'=> 'required',
            'store_id'=> 'required',

        ]);

        $row_count = count($request->product_id);
        $total_amount = 0;
        for($i=0; $i<$row_count;$i++)
        {
            $total_amount += $request->sub_total[$i];
        }

        $get_invoice_no = ProductPurchase::latest()->pluck('invoice_no')->first();
        if(!empty($get_invoice_no)){
            $get_invoice = str_replace("Pur-","",$get_invoice_no);
            $invoice_no = $get_invoice+1;
        }else{
            $invoice_no = 1000;
        }

        if($request->discount_type == 'percentage'){
            $discount_amount = $request->discount_percentage;
            $discount_percentage = $request->discount_amount;
        }else{
            $discount_amount = $request->discount_amount;
            $discount_percentage = NULL;
        }

        // product purchase
        $productPurchase = new ProductPurchase();
        $productPurchase->invoice_no = 'Pur-'.$invoice_no;
        $productPurchase->party_id = $request->party_id;
        $productPurchase->store_id = $request->store_id;
        $productPurchase->user_id = Auth::id();
        $productPurchase->date = $request->date;
        $productPurchase->discount_type = $request->discount_type;
        $productPurchase->discount_amount = $discount_amount;
        $productPurchase->discount_percentage = $discount_percentage;
        $productPurchase->total_amount = $total_amount;
        $productPurchase->paid_amount = $request->paid_amount;
        $productPurchase->due_amount = $request->due_amount;
        $productPurchase->purchase_product_type = 'Finish Goods';
        $productPurchase->save();
        $insert_id = $productPurchase->id;
        if($insert_id)
        {
            for($i=0; $i<$row_count;$i++)
            {
                $price = $request->price[$i];

                $final_discount_amount = (float)$discount_amount * (float)$price;
                $final_total_amount = (float)$discount_amount + (float)$total_amount;
                $discount_type = $request->discount_type;
                $discount = (float)$final_discount_amount/(float)$final_total_amount;
                if($discount_type != NULL){
                    if($discount_type == 'flat'){
                        $discount = round($discount);
                    }
                }

                $product_id = $request->product_id[$i];
                $barcode = Product::where('id',$product_id)->pluck('barcode')->first();

                // product purchase detail
                $purchase_purchase_detail = new ProductPurchaseDetail();
                $purchase_purchase_detail->product_purchase_id = $insert_id;
                $purchase_purchase_detail->invoice_no = 'Pur-'.$invoice_no;
                $purchase_purchase_detail->product_category_id = $request->product_category_id[$i];
                $purchase_purchase_detail->product_sub_category_id = NULL;
                $purchase_purchase_detail->product_brand_id = $request->product_brand_id[$i];
                $purchase_purchase_detail->product_id = $request->product_id[$i];
                $purchase_purchase_detail->qty = $request->qty[$i];
                $purchase_purchase_detail->price = $request->price[$i];
                $purchase_purchase_detail->discount = $discount;
                $purchase_purchase_detail->mrp_price = $request->mrp_price[$i];
                $purchase_purchase_detail->profit_amount = $request->mrp_price[$i] - $request->price[$i];
                $purchase_purchase_detail->sub_total = $request->qty[$i]*$request->price[$i];
                $purchase_purchase_detail->barcode = $barcode;
                $purchase_purchase_detail->save();

                // product wise stock
                $check_previous_stock = Stock::where('product_id',$product_id)->latest()->pluck('current_stock')->first();
                if(!empty($check_previous_stock)){
                    $previous_stock = $check_previous_stock;
                }else{
                    $previous_stock = 0;
                }
                // product stock
                $stock = new Stock();
                $stock->user_id = Auth::id();
                $stock->ref_id = $insert_id;
                $stock->store_id = $request->store_id;
                $stock->date = $request->date;
                $stock->product_id = $request->product_id[$i];
                $stock->stock_product_type = 'Finish Goods';
                $stock->stock_type = 'purchase';
                $stock->previous_stock = $previous_stock;
                $stock->stock_in = $request->qty[$i];
                $stock->stock_out = 0;
                $stock->current_stock = $previous_stock + $request->qty[$i];
                $stock->date = date('Y-m-d');
                $stock->save();

                // invoice wise stock

                $invoice_stock = new InvoiceStock();
                $invoice_stock->user_id = Auth::id();
                $invoice_stock->ref_id = $insert_id;
                $invoice_stock->purchase_invoice_no = 'Pur-'.$invoice_no;
                $invoice_stock->invoice_no = NULL;
                $invoice_stock->store_id = $request->store_id;
                $invoice_stock->product_id = $request->product_id[$i];
                $invoice_stock->stock_product_type = 'Finish Goods';
                $invoice_stock->stock_type = 'purchase';
                $invoice_stock->previous_stock = 0;
                $invoice_stock->stock_in = $request->qty[$i];
                $invoice_stock->stock_out = 0;
                $invoice_stock->current_stock = 0 + $request->qty[$i];
                $invoice_stock->date = $request->date;
                $invoice_stock->save();
            }

            // due
            $due = new Due();
            $due->invoice_no = 'Pur-'.$invoice_no;
            $due->ref_id = $insert_id;
            $due->user_id = Auth::id();
            $due->store_id = $request->store_id;
            $due->party_id = $request->party_id;
            $due->total_amount = $total_amount;
            $due->paid_amount = $request->paid_amount;
            $due->due_amount = $request->due_amount;
            $due->save();

            // transaction
            $transaction = new Transaction();
            $transaction->invoice_no = $productPurchase->invoice_no;
            $transaction->user_id = Auth::id();
            $transaction->store_id = $request->store_id;
            $transaction->party_id = $request->party_id;
            $transaction->date = $request->date;
            $transaction->ref_id = $insert_id;
            $transaction->transaction_product_type = 'Finish Goods';
            $transaction->transaction_type = 'purchase';
            $transaction->payment_type = $request->payment_type;
            $transaction->cheque_number = $request->cheque_number ? $request->cheque_number : '';
            $transaction->amount = $request->paid_amount;
            $transaction->save();
        }

        Toastr::success('Product Purchase Created Successfully', 'Success');
        return redirect()->route('productPurchases.index');

    }


    public function show($id)
    {
        $productPurchase = ProductPurchase::find($id);
        $productPurchaseDetails = ProductPurchaseDetail::where('product_purchase_id',$id)->get();
        $transactions = Transaction::where('ref_id',$id)->where('invoice_no',$productPurchase->invoice_no)->get();

        return view('backend.productPurchase.show', compact('productPurchase','productPurchaseDetails','transactions'));
    }


    public function edit($id)
    {
        $parties = Party::where('type','supplier')->orWhere('type','own')->get();
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin"){
            $stores = Store::all();
        }else{
            $stores = Store::where('user_id',$auth_user_id)->get();
        }
        $products = Product::all();
        $productPurchase = ProductPurchase::find($id);
        $productCategories = ProductCategory::all();
        $productSubCategories = ProductSubCategory::all();
        $productBrands = ProductBrand::all();
        $transaction = Transaction::where('ref_id',$id)->where('transaction_type','purchase')->first();
        $productPurchaseDetails = ProductPurchaseDetail::where('product_purchase_id',$id)->get();
        $stock_id = Stock::where('ref_id',$id)->where('stock_type','purchase')->pluck('id')->first();
        return view('backend.productPurchase.edit',compact('parties','stores','products','productPurchase','productPurchaseDetails','productCategories','productSubCategories','productBrands','transaction','stock_id'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'party_id'=> 'required',
            'store_id'=> 'required',

        ]);

        $row_count = count($request->product_id);
        $total_amount = 0;
        for($i=0; $i<$row_count;$i++)
        {
            $total_amount += $request->sub_total[$i];
        }

        // product purchase
        $productPurchase = ProductPurchase::find($id);
        $productPurchase ->party_id = $request->party_id;
        $productPurchase ->store_id = $request->store_id;
        $productPurchase ->user_id = Auth::id();
        $productPurchase ->date = $request->date;
        $productPurchase->discount_type = $request->discount_type;
        $productPurchase->discount_amount = $request->discount_amount;
        $productPurchase->total_amount = $total_amount;
        $productPurchase->paid_amount = $request->paid_amount;
        $productPurchase->due_amount = $request->due_amount;
        $productPurchase->update();

        for($i=0; $i<$row_count;$i++)
        {
            $price = $request->price[$i];
            $discount_amount = $request->discount_amount;

            $final_discount_amount = (float)$discount_amount * (float)$price;
            $final_total_amount = (float)$discount_amount + (float)$total_amount;
            $discount_type = $request->discount_type;
            $discount = (float)$final_discount_amount/(float)$final_total_amount;
            if($discount_type != NULL){
                if($discount_type == 'Flat'){
                    $discount = round($discount);
                }
            }

            $product_id = $request->product_id[$i];
            $barcode = Product::where('id',$product_id)->pluck('barcode')->first();

            // product purchase detail
            $product_purchase_detail_id = $request->product_purchase_detail_id[$i];
            $purchase_purchase_detail = ProductPurchaseDetail::findOrFail($product_purchase_detail_id);;
            $purchase_purchase_detail->product_category_id = $request->product_category_id[$i];
            $purchase_purchase_detail->product_sub_category_id = NULL;
            $purchase_purchase_detail->product_brand_id = $request->product_brand_id[$i];
            $purchase_purchase_detail->product_id = $request->product_id[$i];
            $purchase_purchase_detail->qty = $request->qty[$i];
            $purchase_purchase_detail->price = $request->price[$i];
            $purchase_purchase_detail->mrp_price = $request->mrp_price[$i];
            $purchase_purchase_detail->profit_amount = $request->mrp_price[$i] - $request->price[$i];
            $purchase_purchase_detail->sub_total = $request->qty[$i]*$request->price[$i];
            $purchase_purchase_detail->discount = $discount;
            $purchase_purchase_detail->barcode = $barcode;
            $purchase_purchase_detail->update();

            // product stock
            $store_id=$productPurchase->store_id;
            $invoice_no=$productPurchase->invoice_no;
            $purchase_product_type=$productPurchase->purchase_product_type;
            $stock_row = current_stock_row($store_id,$purchase_product_type,'purchase',$product_id);
            //dd($stock_row);
            if(!empty($stock_row)){
                $previous_stock = $stock_row->previous_stock;
                $stock_out = $stock_row->stock_out;
                $request_qty = $request->qty[$i];

                if($stock_out != $request_qty){
                    $stock_row->user_id = Auth::id();
                    $stock_row->store_id = $request->store_id;
                    $stock_row->product_id = $product_id;
                    $stock_row->previous_stock = $previous_stock;
                    $stock_row->stock_in = $request_qty;
                    $stock_row->stock_out = 0;
                    $new_stock_in = $previous_stock + $request_qty;
                    $stock_row->current_stock = $new_stock_in;
                    $stock_row->update();
                }

                // invoice stock
                $invoice_stock_row = current_invoice_stock_row($store_id,$purchase_product_type,'purchase',$product_id,$invoice_no,null);
                $previous_invoice_stock = $invoice_stock_row->previous_stock;
                $invoice_stock_out = $invoice_stock_row->stock_out;

                if($invoice_stock_out != $request_qty){
                    $invoice_stock_row->user_id = Auth::id();
                    $invoice_stock_row->store_id = $store_id;
                    $invoice_stock_row->date = $request->date;
                    $invoice_stock_row->product_id = $product_id;
                    $invoice_stock_row->previous_stock = $previous_invoice_stock;
                    $invoice_stock_row->stock_in = $request_qty;
                    $invoice_stock_row->stock_out = 0;
                    $new_stock_in = $previous_invoice_stock + $request_qty;
                    $invoice_stock_row->current_stock = $new_stock_in;
                    $invoice_stock_row->update();
                }
            }

        }

        // due
        $due = Due::where('ref_id',$id)->where('invoice_no',$productPurchase->invoice_no)->first();
        $due->user_id = Auth::id();
        $due->store_id = $request->store_id;
        $due->party_id = $request->party_id;
        $due->total_amount = $total_amount;
        $due->paid_amount = $request->paid_amount;
        $due->due_amount = $request->due_amount;
        $due->update();

        // transaction
        $transaction = Transaction::where('ref_id',$id)->where('transaction_type','purchase')->first();
        if(!empty($transaction)){
            //$transaction->invoice_no = Null;
            $transaction->user_id = Auth::id();
            $transaction->store_id = $request->store_id;
            $transaction->party_id = $request->party_id;
            $transaction->date = $request->date;
            $transaction->payment_type = $request->payment_type;
            $transaction->cheque_number = $request->cheque_number ? $request->cheque_number : '';
            $transaction->amount = $total_amount;
            $transaction->update();
        }

        Toastr::success('Product Purchases Updated Successfully', 'Success');
        return redirect()->route('productPurchases.index');
    }


    public function destroy($id)
    {
        Toastr::warning('Product Purchase Permanently Deleted Not Possible, Please Contact With Administrator.', 'Warning');
        return redirect()->route('productPurchases.index');
    }

    public function productRelationData(Request $request){
        $product_id = $request->current_product_id;
        $product_category_id = Product::where('id',$product_id)->pluck('product_category_id')->first();
        $product_sub_category_id = Product::where('id',$product_id)->pluck('product_sub_category_id')->first();
        $product_brand_id = Product::where('id',$product_id)->pluck('product_brand_id')->first();
        $options = [
            'categoryOptions' => '',
            'subCategoryOptions' => '',
            'brandOptions' => '',
        ];

        if($product_category_id){
            $categories = ProductCategory::where('id',$product_category_id)->get();
            if(count($categories) > 0){
                $options['categoryOptions'] = "<select class='form-control' name='product_category_id[]' readonly>";
                foreach($categories as $category){
                    $options['categoryOptions'] .= "<option value='$category->id'>$category->name</option>";
                }
                $options['categoryOptions'] .= "</select>";
            }
        }else{
            $options['categoryOptions'] = "<select class='form-control' name='product_sub_category_id[]' readonly>";
            $options['categoryOptions'] .= "<option value=''>No Data Found!</option>";
            $options['categoryOptions'] .= "</select>";
        }
        if(!empty($product_sub_category_id)){
            $subCategories = ProductSubCategory::where('id',$product_sub_category_id)->get();
            if(count($subCategories) > 0){
                $options['subCategoryOptions'] = "<select class='form-control' name='product_sub_category_id[]' readonly>";
                foreach($subCategories as $subCategory){
                    $options['subCategoryOptions'] .= "<option value='$subCategory->id'>$subCategory->name</option>";
                }
                $options['subCategoryOptions'] .= "</select>";
            }
        }else{
            $options['subCategoryOptions'] = "<select class='form-control' name='product_sub_category_id[]' readonly>";
            $options['subCategoryOptions'] .= "<option value=''>No Data Found!</option>";
            $options['subCategoryOptions'] .= "</select>";
        }
        if($product_brand_id){
            $brands = ProductBrand::where('id',$product_brand_id)->get();
            if(count($brands) > 0){
                $options['brandOptions'] = "<select class='form-control' name='product_brand_id[]'readonly>";
                foreach($brands as $brand){
                    $options['brandOptions'] .= "<option value='$brand->id'>$brand->name</option>";
                }
                $options['brandOptions'] .= "</select>";
            }
        }else{
            $options['brandOptions'] = "<select class='form-control' name='product_sub_category_id[]' readonly>";
            $options['brandOptions'] .= "<option value=''>No Data Found!</option>";
            $options['brandOptions'] .= "</select>";
        }

        return response()->json(['success'=>true,'data'=>$options]);
    }
    public function invoice()
    {
        return view('backend.productPurchase.invoice');
    }
    public function invoicePrint()
    {
        return view('backend.productPurchase.invoice-print');
    }

    public function newParty(Request $request){
        $this->validate($request, [
            'type'=> 'required',
            'name' => 'required',
            'phone'=> 'required',
            'email'=> '',
            'address'=> '',
        ]);
        $parties = new Party();
        $parties->type = $request->type;
        $parties->name = $request->name;
        $parties->slug = Str::slug($request->name);
        $parties->phone = $request->phone;
        $parties->email = $request->email;
        $parties->address = $request->address;
        $parties->status = 1;
        $parties->save();
        $insert_id = $parties->id;

        if ($insert_id){
            $sdata['id'] = $insert_id;
            $sdata['name'] = $parties->name;
            echo json_encode($sdata);

        }
        else {
            $data['exception'] = 'Some thing mistake !';
            echo json_encode($data);

        }
    }

    public function supplierPayDue(Request $request){

        $product_purchase_id = $request->product_purchase_id;
        $product_purchase = ProductPurchase::find($product_purchase_id);
        $transaction_product_type = Transaction::where('invoice_no',$product_purchase->invoice_no)->pluck('transaction_product_type')->first();

        $total_amount=$product_purchase->total_amount;
        $paid_amount=$product_purchase->paid_amount;

        $update_product_purchase = ProductPurchase::find($product_purchase_id);
        $update_product_purchase->paid_amount=$paid_amount+$request->new_paid;
        $update_product_purchase->due_amount=$total_amount-($paid_amount+$request->new_paid+$product_purchase->discount_amount);
        $affectedRow = $update_product_purchase->update();

        $due = new Due();
        $due->invoice_no=$product_purchase->invoice_no;
        $due->ref_id=$product_purchase->id;
        $due->user_id=$product_purchase->user_id;
        $due->store_id=$product_purchase->store_id;
        $due->party_id=$product_purchase->party_id;
        $due->total_amount=$product_purchase->total_amount;
        $due->paid_amount=$request->new_paid;
        $due->due_amount=$total_amount-($paid_amount+$request->new_paid+$product_purchase->discount_amount);
        $due->save();

        // transaction
        $transaction = new Transaction();
        $transaction->invoice_no = $product_purchase->invoice_no;
        $transaction->user_id = Auth::id();
        $transaction->store_id = $product_purchase->store_id;
        $transaction->party_id = $product_purchase->party_id;
        $transaction->ref_id = $product_purchase->id;
        $transaction->transaction_product_type = $transaction_product_type;
        $transaction->transaction_type = 'due';
        $transaction->payment_type = $request->payment_type;
        $transaction->cheque_number = $request->cheque_number ? $request->cheque_number : '';
        $transaction->amount = $request->new_paid;
        $transaction->date = date('Y-m-d');
        $transaction->save();

        if($affectedRow){
            Toastr::success('Due Pay Successfully', 'Success');
        }
        return redirect()->back();

    }

    public function supplierDue()
    {
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin"){
            $productPurchases = ProductPurchase::where('due_amount','>',0)->where('purchase_product_type','Finish Goods')->where('party_id','!=',64)->latest()->get();
        }else{
            $productPurchases = ProductPurchase::where('user_id',$auth_user_id)->where('due_amount','>',0)->where('purchase_product_type','Finish Goods')->where('due_amount','!=',64)->get();
        }
        return view('backend.productPurchase.supplier_due',compact('productPurchases'));
    }
}
