<?php

namespace App\Http\Controllers;

use App\InvoiceStock;
use App\Party;
use App\Product;
use App\ProductBrand;
use App\ProductPurchase;
use App\ProductPurchaseDetail;
use App\ProductPurchaseReplacement;
use App\ProductPurchaseReplacementDetail;
use App\ProductSale;
use App\ProductSaleDetail;
use App\Stock;
use App\Store;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductPurchaseReplacementController extends Controller
{

    public function index()
    {
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin"){
            $productPurchaseReplacements = ProductPurchaseReplacement::latest()->get();
        }else{
            $productPurchaseReplacements = ProductPurchaseReplacement::where('user_id',$auth_user_id)->latest()->get();
        }
        return view('backend.productPurchaseReplacement.index',compact('productPurchaseReplacements'));
    }


    public function create()
    {
        // dd('kk');
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        $parties = Party::where('type','customer')->get() ;
        if($auth_user == "Admin"){
            $stores = Store::all();
        }else{
            $stores = Store::where('user_id',$auth_user_id)->get();
        }
        $productPurchases = ProductPurchase::latest()->get();

        return view('backend.productPurchaseReplacement.create',compact('parties','stores','productPurchases'));
    }
    public function getPurchaseProduct($purchase_id){
        $productPurchase = ProductPurchase::where('id',$purchase_id)->first();
        $products = DB::table('product_purchase_details')
            ->join('products','product_purchase_details.product_id','=','products.id')
            ->where('product_purchase_details.product_purchase_id',$purchase_id)
            ->select('product_purchase_details.product_id','product_purchase_details.qty','product_purchase_details.price','products.name')
            ->get();


        $html = "<table class=\"table table-striped tabel-penjualan\">
                        <thead>
                            <tr>
                                <th width=\"30\">No</th>
                                <th>Product Name</th>
                                <th align=\"right\"> Quantity</th>
                                <th>Already Return Quantity</th>
                                <th>Already Replace Quantity</th>
                                <th>Replace Quantity</th>
                                <th style=\"display: none\">Price</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>";
        if(count($products) > 0):
            foreach($products as $key => $item):
                $check_purchase_return_qty = check_purchase_return_qty($productPurchase->store_id,$item->product_id,$productPurchase->invoice_no);
                $check_purchase_replace_qty = check_purchase_replace_qty($productPurchase->store_id,$item->product_id,$productPurchase->invoice_no);
                $key += 1;
                $html .= "<tr>";
                $html .= "<th width=\"30\">1</th>";
                $html .= "<th><input type=\"hidden\" class=\"form-control\" name=\"product_id[]\" id=\"product_id_$key\" value=\"$item->product_id\" size=\"28\" />$item->name</th>";
                $html .= "<th><input type=\"text\" class=\"form-control\" name=\"qty[]\" id=\"qty_$key\" value=\"$item->qty\" size=\"28\" readonly /></th>";
                $html .= "<th><input type=\"text\" class=\"form-control\" name=\"check_purchase_return_qty[]\" id=\"check_purchase_return_qty_$key\" value=\"$check_purchase_return_qty\" readonly /></th>";
                $html .= "<th><input type=\"text\" class=\"form-control\" name=\"check_purchase_replace_qty[]\" id=\"check_purchase_replace_qty_$key\" value=\"$check_purchase_replace_qty\" readonly /></th>";
                $html .= "<th><input type=\"text\" class=\"form-control\" name=\"replace_qty[]\" id=\"replace_qty_$key\" onkeyup=\"replace_qty($key,this);\" size=\"28\" /></th>";
                $html .= "<th style=\"display: none\"><input type=\"text\" class=\"form-control\" name=\"price[]\" id=\"price_$key\" value=\"$item->price\" size=\"28\" /></th>";
                $html .= "<th><textarea type=\"text\" class=\"form-control\" name=\"reason[]\" id=\"reason_$key\"  size=\"28\" ></textarea> </th>";
                $html .= "</tr>";
            endforeach;
            //$html .= "<tr><th align=\"right\" colspan=\"7\"><input type=\"button\" class=\"btn btn-danger\" name=\"remove\" id=\"remove\" size=\"28\" value=\"Clear Item\" onClick=\"deleteAllCart()\" /></th></tr>";
        endif;
        $html .= "</tbody>
                    </table>";
        echo json_encode($html);
    }


    public function store(Request $request)
    {
        //dd($request->all());
        $row_count = count($request->replace_qty);

        $productPurchase = ProductPurchase::where('id',$request->product_purchase_id)->first();
        // product replacement
        $purchase_replacement = new ProductPurchaseReplacement();
        $purchase_replacement->invoice_no = 'Purchaserep-'.$productPurchase->invoice_no;
        $purchase_replacement->purchase_invoice_no = $productPurchase->invoice_no;
        $purchase_replacement->product_purchase_id = $request->product_purchase_id;
        $purchase_replacement->user_id = Auth::user()->id;
        $purchase_replacement->store_id = $productPurchase->store_id;
        $purchase_replacement->party_id = $productPurchase->party_id;
        $purchase_replacement->date = date('Y-m-d');
        $purchase_replacement->save();
        $insert_id = $purchase_replacement->id;

        $total_amount = 0;
        for ($i = 0; $i < $row_count; $i++) {
            if ($request->replace_qty[$i] != null) {
                $total_amount += $request->replace_qty[$i]*$request->price[$i];
            }
        }

        if($insert_id){
            for($i=0; $i<$row_count;$i++)
            {
                if($request->replace_qty[$i] != null){
                    // product replacement detail
                    $purchase_replacement_detail = new ProductPurchaseReplacementDetail();
                    $purchase_replacement_detail->product_purchase_replacement_id = $insert_id;
                    $purchase_replacement_detail->product_id = $request->product_id[$i];
                    $purchase_replacement_detail->replace_qty = $request->replace_qty[$i];
                    $purchase_replacement_detail->price = $request->price[$i];
                    $purchase_replacement_detail->reason = $request->reason[$i];
                    $purchase_replacement_detail->save();

                    $product_id = $request->product_id[$i];
                    $purchase_invoice_no = ProductPurchaseDetail::where('product_purchase_id',$productPurchase->id)->where('product_id',$product_id)->pluck('invoice_no')->first();

                    // update purchase details table stock status
//                    $product_purchase_details_info = ProductPurchaseDetail::where('invoice_no',$purchase_invoice_no)->where('product_id',$product_id)->first();
//                    $purchase_qty = $product_purchase_details_info->qty;
//                    $purchase_previous_purchase_qty = $product_purchase_details_info->qty;
//                    $total_purchase_qty = $purchase_previous_purchase_qty + $request->replace_qty[$i];
//                    $product_purchase_details_info->sale_qty = $total_purchase_qty;
//                    if($total_purchase_qty == $purchase_qty){
//                        $product_purchase_details_info->qty_stock_status = 'Not Available';
//                    }else{
//                        $product_purchase_details_info->qty_stock_status = 'Available';
//                    }
//                    //dd($product_purchase_details_info);
//                    $product_purchase_details_info->save();


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
                    $stock->store_id = $productPurchase->store_id;
                    $stock->date = date('Y-m-d');
                    $stock->product_id = $product_id;
                    $stock->stock_type = 'purchase replace';
                    $stock->previous_stock = $previous_stock;
                    //$stock->stock_in = 0;
                    $stock->stock_in = $request->replace_qty[$i];
                    $stock->stock_out = $request->replace_qty[$i];
                    //$stock->current_stock = $previous_stock - $request->replace_qty[$i];
                    $stock->current_stock = $previous_stock;
                    $stock->save();

                    // invoice wise product stock
                    $check_previous_invoice_stock = InvoiceStock::where('store_id',$productPurchase->store_id)
                        ->where('purchase_invoice_no',$purchase_invoice_no)
                        ->where('product_id',$product_id)
                        ->latest()
                        ->pluck('current_stock')
                        ->first();

                    if(!empty($check_previous_invoice_stock)){
                        $previous_invoice_stock = $check_previous_invoice_stock;
                    }else{
                        $previous_invoice_stock = 0;
                    }
                    // product stock
                    $invoice_stock = new InvoiceStock();
                    $invoice_stock->user_id = Auth::id();
                    $invoice_stock->ref_id = $insert_id;
                    $invoice_stock->purchase_invoice_no = $purchase_invoice_no;
                    $invoice_stock->invoice_no = 'Purchaserep-'.$productPurchase->invoice_no;
                    $invoice_stock->store_id = $productPurchase->store_id;
                    $invoice_stock->date = date('Y-m-d');
                    $invoice_stock->product_id = $product_id;
                    $invoice_stock->stock_type = 'purchase replace';
                    $invoice_stock->previous_stock = $previous_invoice_stock;
                    //$invoice_stock->stock_in = 0;
                    $invoice_stock->stock_in = $request->replace_qty[$i];
                    $invoice_stock->stock_out = $request->replace_qty[$i];
                    //$invoice_stock->current_stock = $previous_invoice_stock - $request->replace_qty[$i];
                    $invoice_stock->current_stock = $previous_invoice_stock;
                    $invoice_stock->save();

                }
            }
        }

        Toastr::success('Product Purchase Created Successfully', 'Success');
        return redirect()->route('productPurchaseReplacement.index');
    }


    public function show($id)
    {
        $productPurchaseReplacement = ProductPurchaseReplacement::find($id);
        $productPurchaseReplacementDetails = ProductPurchaseReplacementDetail::where('product_purchase_replacement_id',$id)->get();

        return view('backend.productPurchaseReplacement.show', compact('productPurchaseReplacement','productPurchaseReplacementDetails'));
    }


    public function edit($id)
    {
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin"){
            $stores = Store::all();
        }else{
            $stores = Store::where('user_id',$auth_user_id)->get();
        }
        $parties = Party::where('type','customer')->get() ;
        $products = Product::all();
        $productBrands = ProductBrand::all();
        $productPurchaseReplacement = ProductPurchaseReplacement::find($id);
        $productPurchaseReplacementDetails = ProductPurchaseReplacementDetail::where('product_purchase_replacement_id',$id)->get();

        return view('backend.productPurchaseReplacement.edit',compact('parties','stores','products','productPurchaseReplacement','productPurchaseReplacementDetails','productBrands'));

    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        $productPurchaseReplacement = ProductPurchaseReplacement::find($id);
        $productPurchase = ProductPurchase::where('id',$productPurchaseReplacement->product_purchase_id)->first();
        $purchase_invoice_no = ProductPurchaseDetail::where('product_purchase_id',$productPurchase->id)->pluck('invoice_no')->first();

        $product_purchase_replacement_details = DB::table('product_purchase_replacement_details')->where('product_purchase_replacement_id',$id)->get();
        if(count($product_purchase_replacement_details) > 0){
            foreach($product_purchase_replacement_details as $product_purchase_replacement_detail){

                $store_id = $productPurchaseReplacement->store_id;
                $product_id = $product_purchase_replacement_detail->product_id;
                $replace_qty = $product_purchase_replacement_detail->replace_qty;

                $check_previous_stock = Stock::where('product_id',$product_id)->where('store_id',$store_id)->latest()->pluck('current_stock')->first();
                if(!empty($check_previous_stock)){
                    $previous_stock = $check_previous_stock;
                }else{
                    $previous_stock = 0;
                }

                // product stock
                $stock = new Stock();
                $stock->user_id = Auth::id();
                $stock->ref_id = $id;
                $stock->store_id = $store_id;
                $stock->date = date('Y-m-d');
                $stock->product_id = $product_id;
                $stock->stock_type = 'replace delete';
                $stock->previous_stock = $previous_stock;
                $stock->stock_in = $replace_qty;
                $stock->stock_out = 0;
                $stock->current_stock = $previous_stock - $replace_qty;
                $stock->save();

                // invoice wise product stock
                $check_previous_invoice_stock = InvoiceStock::where('store_id',$store_id)
                    ->where('purchase_invoice_no',$purchase_invoice_no)
                    ->where('product_id',$product_id)
                    ->latest()
                    ->pluck('current_stock')
                    ->first();

                if(!empty($check_previous_invoice_stock)){
                    $previous_invoice_stock = $check_previous_invoice_stock;
                }else{
                    $previous_invoice_stock = 0;
                }

                // product invoice stock
                $invoice_stock = new InvoiceStock();
                $invoice_stock->user_id = Auth::id();
                $invoice_stock->ref_id = $id;
                $invoice_stock->purchase_invoice_no = $purchase_invoice_no;
                $invoice_stock->invoice_no = 'Salrep-'.$productPurchase->invoice_no;
                $invoice_stock->store_id = $store_id;
                $invoice_stock->date = date('Y-m-d');
                $invoice_stock->product_id = $product_id;
                $invoice_stock->stock_type = 'replace delete';
                $invoice_stock->previous_stock = $previous_invoice_stock;
                $invoice_stock->stock_in = $replace_qty;
                $invoice_stock->stock_out = 0;
                $invoice_stock->current_stock = $previous_invoice_stock - $replace_qty;
                $invoice_stock->save();

                // update purchase details table stock status
//                $product_purchase_details_info = ProductPurchaseDetail::where('invoice_no',$purchase_invoice_no)->where('product_id',$product_id)->first();
//                $purchase_qty = $product_purchase_details_info->qty;
//                $purchase_previous_sale_qty = $product_purchase_details_info->sale_qty;
//                $total_sale_qty = $purchase_previous_sale_qty - $replace_qty;
//                $product_purchase_details_info->sale_qty = $total_sale_qty;
//                if($total_sale_qty == $purchase_qty){
//                    $product_purchase_details_info->qty_stock_status = 'Not Available';
//                }else{
//                    $product_purchase_details_info->qty_stock_status = 'Available';
//                }
//                $product_purchase_details_info->save();
            }
        }

        DB::table('product_purchase_replacement_details')->where('product_purchase_replacement_id',$id)->delete();

        $productPurchaseReplacement->delete();

        Toastr::success('Product Purchase Replacement Deleted Successfully', 'Success');
        return redirect()->route('productPurchaseReplacement.index');
    }
}
