<?php

namespace App\Http\Controllers;

use App\InvoiceStock;
use App\Party;
use App\Product;
use App\ProductBrand;
use App\ProductCategory;
use App\ProductPurchaseDetail;
use App\ProductSale;
use App\ProductSaleDetail;
use App\ProductSaleReplacement;
use App\ProductSaleReplacementDetail;
use App\ProductSaleReturn;
use App\ProductSubCategory;
use App\ProductUnit;
use App\Profit;
use App\Stock;
use App\Store;
use App\Transaction;
use Brian2694\Toastr\Facades\Toastr;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductSaleReplacementController extends Controller
{

    public function index()
    {
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin"){
            $productSaleReplacements = ProductSaleReplacement::latest()->get();
        }else{
            $productSaleReplacements = ProductSaleReplacement::where('user_id',$auth_user_id)->latest()->get();
        }
        return view('backend.productSaleReplacement.index',compact('productSaleReplacements'));
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
        $productSales = ProductSale::latest()->get();

        return view('backend.productSaleReplacement.create',compact('parties','stores','productSales'));
    }



    public function getSaleProduct($sale_id){
        $productSale = ProductSale::where('id',$sale_id)->first();
       // dd($productSale);
        $products = DB::table('product_sale_details')
            ->join('products','product_sale_details.product_id','=','products.id')
            ->where('product_sale_details.product_sale_id',$sale_id)
            ->select('product_sale_details.product_id','product_sale_details.qty','product_sale_details.price','products.name','product_sale_details.purchase_invoice_no')
            ->get();

        $html = "<table class=\"table table-striped tabel-penjualan\">
                        <thead>
                            <tr>
                                <th width=\"10\">No</th>
                                <th width=\"30\">Product Name</th>
                                <th width=\"10\" align=\"right\"> Quantity</th>
                                <th width=\"10\">Already Return Quantity</th>
                                <th width=\"10\">Already Replace Quantity</th>
                                <th width=\"20\">Purchase Invoice</th>
                                <th width=\"10\">Replace Quantity</th>
                                <th width=\"10\" style=\"display: none\">Price</th>
                                <th width=\"20\">Reason</th>
                            </tr>
                        </thead>
                        <tbody>";
                        if(count($products) > 0):
                            foreach($products as $key => $item):
                                $check_sale_return_qty = check_sale_return_qty($productSale->store_id,$item->product_id,$productSale->invoice_no);
                                $check_sale_replace_qty = check_sale_replace_qty($productSale->store_id,$item->product_id,$productSale->invoice_no);


                                //dd($check_purchase_invoice);
                                $key += 1;

                                $current_purchase_invoice_no = $item->purchase_invoice_no;
                                $purchase_invoice_lists = purchase_invoice_lists($item->product_id);

                                $current_stock = 0;
                                $options = "<select class=\"form-control select2\" name=\"purchase_invoice_list[]\" id=\"purchase_invoice_list_$key\">";
                                if(count($purchase_invoice_lists) == 0){
                                    $options = '<option value="">No Data Found!</option>';
                                }elseif(count($purchase_invoice_lists) > 0){
                                    foreach ($purchase_invoice_lists as $key2 => $purchase_invoice_list){

                                        $current_stock = InvoiceStock::where('store_id',$productSale->store_id)
                                            ->where('product_id',$item->product_id)
                                            ->where('purchase_invoice_no',$purchase_invoice_list->invoice_no)
                                            //->where('current_stock','>',0)
                                            ->latest()
                                            ->pluck('current_stock')
                                            ->first();


                                        //$options .= '<option value="'.$purchase_invoice_list->invoice_no.'" '.$current_purchase_invoice_no.' >'.$purchase_invoice_list->invoice_no.'</option>';
                                        $options .= "<option value='$purchase_invoice_list->invoice_no => $current_stock'";
                                        if($current_purchase_invoice_no == $purchase_invoice_list->invoice_no){$options .= 'selected';}
                                        $options .= ">".$purchase_invoice_list->invoice_no."=>".$current_stock."</option>";

                                    }
                                }else{
                                    $options = '<option value="">No Data Found!</option>';
                                }
                                $options .= "</select>";
                                //$options .= "<input type=\"text\" class=\"form-control\" name=\"current_stock[]\" id=\"current_stock_$key\" value=\"$current_stock\" size=\"28\" />";

                                $html .= "<tr>";
                                $html .= "<th width=\"30\">1</th>";
                                $html .= "<th><input type=\"hidden\" class=\"form-control\" name=\"product_id[]\" id=\"product_id_$key\" value=\"$item->product_id\" size=\"28\" />$item->name</th>";
                                $html .= "<th><input type=\"text\" class=\"form-control\" name=\"qty[]\" id=\"qty_$key\" value=\"$item->qty\" size=\"28\" readonly /></th>";
                                $html .= "<th><input type=\"text\" class=\"form-control\" name=\"check_sale_return_qty[]\" id=\"check_sale_return_qty_$key\" value=\"$check_sale_return_qty\" readonly /></th>";
                                $html .= "<th><input type=\"text\" class=\"form-control\" name=\"check_sale_replace_qty[]\" id=\"check_sale_replace_qty_$key\" value=\"$check_sale_replace_qty\" readonly /></th>";
                                //$html .= "<th><select class=\"form-control select2\" name=\"purchase_invoice_list\" id=\"purchase_invoice_list_$key\">$options</th>";
                                $html .= "<th>$options</th>";
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

        $productSale = ProductSale::where('id',$request->product_sale_id)->first();
        //$purchase_invoice_no = ProductSaleDetail::where('product_sale_id',$productSale->id)->pluck('purchase_invoice_no')->first();
        // product replacement
        $purchase_sale_replacement = new ProductSaleReplacement();
        $purchase_sale_replacement->invoice_no = 'Salrep-'.$productSale->invoice_no;
        $purchase_sale_replacement->sale_invoice_no = $productSale->invoice_no;
        $purchase_sale_replacement->product_sale_id = $request->product_sale_id;
        $purchase_sale_replacement->user_id = Auth::user()->id;
        $purchase_sale_replacement->store_id = $productSale->store_id;
        $purchase_sale_replacement->party_id = $productSale->party_id;
        $purchase_sale_replacement->date = date('Y-m-d');
        $purchase_sale_replacement->save();
        $insert_id = $purchase_sale_replacement->id;

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
                    $product_id = $request->product_id[$i];

                    $Product_sale_detail = ProductSaleDetail::where('product_sale_id',$productSale->id)->where('product_id',$product_id)->first();
                    $purchase_invoice_no = ProductSaleDetail::where('product_sale_id',$productSale->id)->where('product_id',$product_id)->pluck('purchase_invoice_no')->first();

                    $purchase_invoice_list_string = $request->purchase_invoice_list[$i];
                    $purchase_invoice_list = explode('=>', $purchase_invoice_list_string);
                    $exist_purchase_invoice_no = trim($purchase_invoice_list[0]);
                    //dd($purchase_invoice_list[0]);
//                    echo gettype($Product_sale_detail->purchase_invoice_no)."\n";
//                    echo gettype($exist_purchase_invoice_no)."\n";
//                    echo $Product_sale_detail->purchase_invoice_no."\n";
//                    echo $exist_purchase_invoice_no."\n";
//                    die();


                    // product replacement detail
                    $purchase_sale_replacement_detail = new ProductSaleReplacementDetail();
                    $purchase_sale_replacement_detail->product_sale_detail_id = $Product_sale_detail->id;
                    $purchase_sale_replacement_detail->purchase_invoice_no = $exist_purchase_invoice_no;
                    $purchase_sale_replacement_detail->p_s_replacement_id = $insert_id;
                    $purchase_sale_replacement_detail->product_id = $request->product_id[$i];
                    $purchase_sale_replacement_detail->replace_qty = $request->replace_qty[$i];
                    $purchase_sale_replacement_detail->price = $request->price[$i];
                    $purchase_sale_replacement_detail->reason = $request->reason[$i];
                    $purchase_sale_replacement_detail->save();



                    // update purchase details table stock status
//                    $product_purchase_details_info = ProductPurchaseDetail::where('invoice_no',$exist_purchase_invoice_no)->where('product_id',$product_id)->first();
//                    $purchase_qty = $product_purchase_details_info->qty;
//                    $purchase_previous_sale_qty = $product_purchase_details_info->sale_qty;
//                    $total_sale_qty = $purchase_previous_sale_qty + $request->replace_qty[$i];
//                    $product_purchase_details_info->sale_qty = $total_sale_qty;
//                    if($total_sale_qty == $purchase_qty){
//                        $product_purchase_details_info->qty_stock_status = 'Not Available';
//                    }else{
//                        $product_purchase_details_info->qty_stock_status = 'Available';
//                    }
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
                    $stock->store_id = $productSale->store_id;
                    $stock->date = date('Y-m-d');
                    $stock->product_id = $product_id;
                    $stock->stock_type = 'replace';
                    $stock->previous_stock = $previous_stock;
                    //$stock->stock_in = 0;
                    $stock->stock_in = $request->replace_qty[$i];
                    $stock->stock_out = $request->replace_qty[$i];
                    //$stock->current_stock = $previous_stock - $request->replace_qty[$i];
                    $stock->current_stock = $previous_stock;
                    //dd($stock);
                    $stock->save();

                    // invoice wise product stock
                    $check_previous_invoice_stock = InvoiceStock::where('store_id',$productSale->store_id)
                        ->where('purchase_invoice_no',$exist_purchase_invoice_no)
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
                    $invoice_stock->purchase_invoice_no = $exist_purchase_invoice_no;
                    $invoice_stock->invoice_no = 'Salrep-'.$productSale->invoice_no;
                    $invoice_stock->store_id = $productSale->store_id;
                    $invoice_stock->date = date('Y-m-d');
                    $invoice_stock->product_id = $product_id;
                    $invoice_stock->stock_type = 'replace';
                    $invoice_stock->previous_stock = $previous_invoice_stock;
                    //$invoice_stock->stock_in = 0;
                    $invoice_stock->stock_in = $request->replace_qty[$i];
                    $invoice_stock->stock_out = $request->replace_qty[$i];
                    //$invoice_stock->current_stock = $previous_invoice_stock - $request->replace_qty[$i];
                    $invoice_stock->current_stock = $previous_invoice_stock;
                    //dd($invoice_stock);
                    $invoice_stock->save();

                    if(strcmp(trim($Product_sale_detail->purchase_invoice_no), trim($exist_purchase_invoice_no)) !== 0){
                        $replace_qty = $request->replace_qty[$i];
                        $profit_amount = get_replace_loss_profit_amount($purchase_invoice_no,$product_id,$exist_purchase_invoice_no,$replace_qty);
                       // dd($profit_amount);

                        //profit table
                        $profit = new Profit();

                        $profit->ref_id = $insert_id;
                        $profit->purchase_invoice_no = $exist_purchase_invoice_no;
                        $profit->invoice_no ='Salrep-'.$productSale->invoice_no;
                        $profit->user_id = Auth::id();
                        $profit->store_id = $productSale->store_id;
                        $profit->type = 'Sale Replace';
                        $profit->product_id = $product_id;
                        $profit->qty = $request->replace_qty[$i];
                        $profit->price = $request->price[$i];
                        $profit->sub_total = $request->replace_qty[$i]*$request->price[$i];
                        $profit->discount_amount = 0;
                        //$profit->profit_amount = -($profit_amount*$request->replace_qty[$i]);
                        $profit->profit_amount = $profit_amount;
                        $profit->date = date('Y-m-d');
                        $profit->save();
                    }


//                    $profit_amount = get_profit_amount($purchase_invoice_no,$product_id);

                    // profit table
//                    $profit = new Profit();
//                    $profit->ref_id = $insert_id;
//                    $profit->purchase_invoice_no = $purchase_invoice_no;
//                    $profit->invoice_no ='Salrep-'.$productSale->invoice_no;
//                    $profit->user_id = Auth::id();
//                    $profit->store_id = $productSale->store_id;
//                    $profit->type = 'Sale';
//                    $profit->product_id = $product_id;
//                    $profit->qty = $request->replace_qty[$i];
//                    $profit->price = $request->price[$i];
//                    $profit->sub_total = $request->replace_qty[$i]*$request->price[$i];
//                    $profit->discount_amount = 0;
//                    $profit->profit_amount = -($profit_amount*$request->replace_qty[$i]);
//                    $profit->date = date('Y-m-d');
//                    $profit->save();
                }
            }
        }

        Toastr::success('Product Sale Created Successfully', 'Success');
        return redirect()->route('productSaleReplacement.index');
    }


    public function show($id)
    {
        $productSaleReplacement = ProductSaleReplacement::find($id);
        $productSaleReplacementDetails = ProductSaleReplacementDetail::where('p_s_replacement_id',$id)->get();

        return view('backend.productSaleReplacement.show', compact('productSaleReplacement','productSaleReplacementDetails'));
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
        $productSaleReplacement = ProductSaleReplacement::find($id);
        $productSaleReplacementDetails = ProductSaleReplacementDetail::where('p_s_replacement_id',$id)->get();

        return view('backend.productSaleReplacement.edit',compact('parties','stores','products','productSaleReplacement','productSaleReplacementDetails','productBrands'));
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());

        $row_count = count($request->replace_qty);

        for($i=0; $i<$row_count;$i++) {
            if ($request->replace_qty[$i] != null) {
                // product replacement detail
                $product_Sale_replacement_detail_id = $request->product_Sale_replacement_detail_id[$i];
                $purchase_sale_replacement_detail = ProductSaleReplacementDetail::find($product_Sale_replacement_detail_id);
                $purchase_sale_replacement_detail->replace_qty = $request->replace_qty[$i];
                $purchase_sale_replacement_detail->reason = $request->reason[$i];
                $purchase_sale_replacement_detail->save();

                $product_id = $request->product_id[$i];
                $Product_sale_detail = ProductSaleDetail::where('product_id',$product_id)->first();

                $purchase_invoice_list_string = $request->purchase_invoice_list[$i];
                $purchase_invoice_list = explode('=>', $purchase_invoice_list_string);
                $exist_purchase_invoice_no = trim($purchase_invoice_list[0]);

                // product stock
                $stock_row = Stock::where('ref_id',$id)->where('stock_type','replace')->where('product_id',$product_id)->first();

                if($stock_row->stock_out != $request->replace_qty[$i]) {

                    if ($request->replace_qty[$i] > $stock_row->stock_out) {
                        $add_or_minus_stock_out = $request->replace_qty[$i] - $stock_row->stock_out;
                        $update_stock_out = $stock_row->stock_out + $add_or_minus_stock_out;
                        $update_current_stock = $stock_row->current_stock - $add_or_minus_stock_out;
                    } else {
                        $add_or_minus_stock_out = $stock_row->stock_out - $request->replace_qty[$i];
                        $update_stock_out = $stock_row->stock_out - $add_or_minus_stock_out;
                        $update_current_stock = $stock_row->current_stock + $add_or_minus_stock_out;
                    }

                    $stock_row->user_id = Auth::user()->id;
                    $stock_row->stock_in = $update_stock_out;
                    $stock_row->stock_out = $update_stock_out;
                    //$stock_row->current_stock = $update_current_stock;
                    $stock_row->current_stock = $stock_row->previous_stock;
                    $stock_row->update();

                    //$product_id = $purchase_sale_replacement_detail->product_id;
                    $product_sale_replacement = ProductSaleReplacement::find($request->purchase_Sale_replacement_id);
                   // dd($product_sale_replacement);
                    $invoice_no = $product_sale_replacement->invoice_no;
                    $sale_invoice_no = $product_sale_replacement->sale_invoice_no;
                    $store_id = $product_sale_replacement->store_id;
                    $request_qty = $request->replace_qty[$i];

                    // update purchase details table stock status
//                    $product_purchase_details_info = ProductPurchaseDetail::where('invoice_no',$exist_purchase_invoice_no)->where('product_id',$product_id)->first();
//                    $purchase_qty = $product_purchase_details_info->qty;
//                    $purchase_previous_sale_qty = $product_purchase_details_info->sale_qty;
//
//                    if ($request_qty > $purchase_previous_sale_qty) {
//                        $add_or_minus_stock_out = $request_qty - $purchase_previous_sale_qty;
//                        $total_sale_qty = $purchase_previous_sale_qty + $add_or_minus_stock_out;
//                    } else {
//                        $add_or_minus_stock_out = $purchase_previous_sale_qty - $request_qty;
//                        $total_sale_qty = $purchase_previous_sale_qty - $add_or_minus_stock_out;
//                    }
//
//                    $product_purchase_details_info->sale_qty = $total_sale_qty;
//                    if($total_sale_qty == $purchase_qty){
//                        $product_purchase_details_info->qty_stock_status = 'Not Available';
//                    }else{
//                        $product_purchase_details_info->qty_stock_status = 'Available';
//                    }
//                    $product_purchase_details_info->save();

                    // invoice stock
                    $invoice_stock_row = current_invoice_stock_row($store_id,'Finish Goods','replace',$product_id,$exist_purchase_invoice_no,$invoice_no);
                    $previous_invoice_stock = $invoice_stock_row->previous_stock;
                    $invoice_stock_out = $invoice_stock_row->stock_out;

                    if($invoice_stock_out != $request_qty){
                        $invoice_stock_row->user_id = Auth::id();
                        $invoice_stock_row->store_id = $store_id;
                        $invoice_stock_row->date = date('Y-m-d');
                        $invoice_stock_row->product_id = $product_id;
                        $invoice_stock_row->previous_stock = $previous_invoice_stock;
                       // $invoice_stock_row->stock_in = 0;
                        $invoice_stock_row->stock_in = $request_qty;
                        $invoice_stock_row->stock_out = $request_qty;
                        $new_stock_out = $previous_invoice_stock - $request_qty;
                        //$invoice_stock_row->current_stock = $new_stock_out;
                        $invoice_stock_row->current_stock = $previous_invoice_stock;
                        $invoice_stock_row->update();
                    }


                    if(strcmp(trim($Product_sale_detail->purchase_invoice_no), trim($exist_purchase_invoice_no)) !== 0){

                        $profit_amount = get_replace_loss_profit_amount($Product_sale_detail->purchase_invoice_no,$product_id,$exist_purchase_invoice_no,$request->replace_qty[$i]);

                        $profit = Profit::where('purchase_invoice_no',$exist_purchase_invoice_no)->where('invoice_no',$invoice_no)->first();
                        $profit->purchase_invoice_no = $exist_purchase_invoice_no;
                        $profit->user_id = Auth::id();
                        $profit->type = 'Sale Replace';
                        $profit->qty = $request->replace_qty[$i];
                        $profit->sub_total = $request->replace_qty[$i]*$profit->price[$i];
                        $profit->discount_amount = 0;
                        $profit->profit_amount = $profit_amount;
                        $profit->date = date('Y-m-d');
                        $profit->save();
                    }
                }
            }
        }

        Toastr::success('Product Sale Updated Successfully', 'Success');
        return redirect()->route('productSaleReplacement.index');
    }


    public function destroy($id)
    {
        $productSaleReplacement = ProductSaleReplacement::find($id);


        //DB::table('stocks')->where('ref_id',$id)->where('stock_type','replace')->delete();
        //DB::table('transactions')->where('ref_id',$id)->delete();

        $productSale = ProductSale::where('id',$productSaleReplacement->product_sale_id)->first();
        $purchase_invoice_no = ProductSaleDetail::where('product_sale_id',$productSale->id)->pluck('purchase_invoice_no')->first();

        $product_sale_replacement_details = DB::table('product_sale_replacement_details')->where('p_s_replacement_id',$id)->get();
        if(count($product_sale_replacement_details) > 0){
            foreach($product_sale_replacement_details as $product_sale_replacement_detail){

                $store_id = $productSaleReplacement->store_id;
                $product_id = $product_sale_replacement_detail->product_id;
                $replace_qty = $product_sale_replacement_detail->replace_qty;

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
                $stock->current_stock = $previous_stock + $replace_qty;
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
                $invoice_stock->invoice_no = 'Salrep-'.$productSale->invoice_no;
                $invoice_stock->store_id = $store_id;
                $invoice_stock->date = date('Y-m-d');
                $invoice_stock->product_id = $product_id;
                $invoice_stock->stock_type = 'replace delete';
                $invoice_stock->previous_stock = $previous_invoice_stock;
                $invoice_stock->stock_in = $replace_qty;
                $invoice_stock->stock_out = 0;
                $invoice_stock->current_stock = $previous_invoice_stock + $replace_qty;
                $invoice_stock->save();

                // update purchase details table stock status
                $product_purchase_details_info = ProductPurchaseDetail::where('invoice_no',$purchase_invoice_no)->where('product_id',$product_id)->first();
                $purchase_qty = $product_purchase_details_info->qty;
                $purchase_previous_sale_qty = $product_purchase_details_info->sale_qty;
                $total_sale_qty = $purchase_previous_sale_qty - $replace_qty;
                $product_purchase_details_info->sale_qty = $total_sale_qty;
                if($total_sale_qty == $purchase_qty){
                    $product_purchase_details_info->qty_stock_status = 'Not Available';
                }else{
                    $product_purchase_details_info->qty_stock_status = 'Available';
                }
                $product_purchase_details_info->save();
            }
        }

        DB::table('product_sale_replacement_details')->where('p_s_replacement_id',$id)->delete();

        $productSaleReplacement->delete();

        Toastr::success('Product Sale Replacement Deleted Successfully', 'Success');
        return redirect()->route('productSaleReplacement.index');
    }
}
