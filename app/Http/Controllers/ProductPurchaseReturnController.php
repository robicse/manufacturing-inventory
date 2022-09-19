<?php

namespace App\Http\Controllers;

use App\InvoiceStock;
use App\Party;
use App\ProductPurchase;
use App\ProductPurchaseDetail;
use App\ProductPurchaseReturn;
use App\ProductPurchaseReturnDetail;
use App\Profit;
use App\Stock;
use App\Store;
use App\Transaction;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductPurchaseReturnController extends Controller
{

//    function __construct()
//    {
//        $this->middleware('permission:product-purchase-return-list|product-purchase-return-create|product-purchase-return-edit|product-purchase-return-delete', ['only' => ['index','show','returnablePurchaseProduct','purchaseProductReturn']]);
//        $this->middleware('permission:product-purchase-return-create', ['only' => ['create','store']]);
//        $this->middleware('permission:product-purchase-return-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:product-purchase-return-delete', ['only' => ['destroy']]);
//    }

    public function index()
    {
        $productPurchaseReturns = ProductPurchaseReturn::latest('id','desc')->get();
        //dd($productPurchaseReturns);
        return view('backend.productPurchaseReturn.index',compact('productPurchaseReturns'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        $productPurchaseReturn = ProductPurchaseReturn::find($id);
        $productPurchaseReturnDetails = ProductPurchaseReturnDetail::where('product_purchase_return_id',$id)->get();

        return view('backend.productPurchaseReturn.show', compact('productPurchaseReturn','productPurchaseReturnDetails'));
    }


    public function edit($id)
    {
        $productPurchaseReturn = ProductPurchaseReturn::find($id);
        $productPurchaseReturnDetails = ProductPurchaseReturnDetail::where('product_purchase_return_id',$id)->get();

        return view('backend.productPurchaseReturn.edit_returnable_purchase_products', compact('productPurchaseReturn','productPurchaseReturnDetails'));

    }


    public function update(Request $request, $id)
    {
        //dd($request->all());

        $count = count($request->qty);
        $total_amount = 0;

        for($i=0; $i<$count; $i++){
            $product_purchase_return_detail_id = $request->product_purchase_return_detail_id[$i];

            $productPurchaseReturnDetail = ProductPurchaseReturnDetail::find($product_purchase_return_detail_id);
            $productPurchaseReturnDetail->qty = $request->qty[$i];
            $productPurchaseReturnDetail->price = $request->price[$i];
            $productPurchaseReturnDetail->reason = $request->reason[$i];
            $productPurchaseReturnDetail->update();

            $total_amount += $request->price[$i]*$request->qty[$i];
            $request_qty = $request->qty[$i];



            $product_id = $productPurchaseReturnDetail->product_id;
            $product_purchase_return = ProductPurchaseReturn::find($request->product_purchase_return_id);
            $invoice_no = $product_purchase_return->invoice_no;
            $purchase_invoice_no = $product_purchase_return->purchase_invoice_no;
            $purchase_invoice_no = DB::table('product_purchase_details')
                ->join('product_purchases','product_purchase_details.product_purchase_id','product_purchases.id')
                ->where('product_purchases.invoice_no',$purchase_invoice_no)
                ->latest('product_purchase_details.invoice_no')
                ->pluck('product_purchase_details.invoice_no')
                ->first();

            // update purchase details table stock status
//            $product_purchase_details_info = ProductPurchaseDetail::where('invoice_no',$purchase_invoice_no)->where('product_id',$product_id)->first();
//            $purchase_qty = $product_purchase_details_info->qty;
//            $purchase_previous_sale_qty = $product_purchase_details_info->sale_qty;
//            $total_sale_qty = $purchase_previous_sale_qty - $request->qty[$i];
//            $product_purchase_details_info->sale_qty = $total_sale_qty;
//            if($total_sale_qty == $purchase_qty){
//                $product_purchase_details_info->qty_stock_status = 'Not Available';
//            }else{
//                $product_purchase_details_info->qty_stock_status = 'Available';
//            }
//            $product_purchase_details_info->save();


            // product stock
            $store_id=$product_purchase_return->store_id;
            $stock_row = current_stock_row($store_id,'Finish Goods','purchase return',$product_id);
            $previous_stock = $stock_row->previous_stock;
            $stock_out = $stock_row->stock_out;
            //$current_stock = $stock_row->current_stock;


            if($stock_out != $request_qty){
                $stock_row->user_id = Auth::id();
                $stock_row->store_id = $store_id;
                $stock_row->product_id = $product_id;
                $stock_row->previous_stock = $previous_stock;
                $stock_row->stock_in = $request_qty;
                $stock_row->stock_out = 0;
                $new_stock_out = $previous_stock + $request_qty;
                $stock_row->current_stock = $new_stock_out;
                $stock_row->update();
            }



            // invoice stock
            $invoice_stock_row = current_invoice_stock_row($store_id,'Finish Goods','purchase return',$product_id,$purchase_invoice_no,$invoice_no);
            $previous_invoice_stock = $invoice_stock_row->previous_stock;
            $invoice_stock_out = $invoice_stock_row->stock_out;

            if($invoice_stock_out != $request_qty){
                $invoice_stock_row->user_id = Auth::id();
                $invoice_stock_row->store_id = $store_id;
                $invoice_stock_row->date = date('Y-m-d');
                $invoice_stock_row->product_id = $product_id;
                $invoice_stock_row->previous_stock = $previous_invoice_stock;
                $invoice_stock_row->stock_in = $request_qty;
                $invoice_stock_row->stock_out = 0;
                $new_stock_out = $previous_invoice_stock + $request_qty;
                $invoice_stock_row->current_stock = $new_stock_out;
                $invoice_stock_row->update();
            }

//            $profit_amount = get_profit_amount($purchase_invoice_no,$product_id);

            // profit table
//            $profit = get_profit_amount_row($store_id,$purchase_invoice_no,$invoice_no,$product_id);
//            $profit->user_id = Auth::id();
//            $profit->store_id = $store_id;
//            $profit->product_id = $product_id;
//            $profit->qty = $request_qty;
//            $profit->price = $request->price[$i];
//            $profit->sub_total = $request_qty*$request->price[$i];
//            $profit->discount_amount = 0;
//            $profit->profit_amount = -($request->price[$i]*$request_qty);
//            $profit->date = date('Y-m-d');
//            $profit->update();
        }

        $productPurchaseReturn = ProductPurchaseReturn::find($request->product_purchase_return_id);
        $productPurchaseReturn->total_amount = $total_amount;
        $productPurchaseReturn->update();

        // transaction
        $transaction = Transaction::where('invoice_no',$productPurchaseReturn->invoice_no)->first();
        $transaction->user_id = Auth::id();
        $transaction->store_id = $store_id;
        //$transaction->payment_type = $request->payment_type;
        //$transaction->cheque_number = $request->cheque_number;
        $transaction->date = date('Y-m-d');
        $transaction->amount = $total_amount;
        $transaction->save();

        Toastr::success('Product Purchase Return Updated Successfully', 'Success');
        return redirect()->route('productPurchaseReturn.index');
    }


    public function destroy($id)
    {
        $productPurchaseReturn = ProductPurchaseReturn::find($id);
        $product_purchase_return_details = DB::table('product_purchase_return_details')->where('product_purchase_return_id',$id)->get();
        if(count($product_purchase_return_details) > 0){
            foreach($product_purchase_return_details as $product_purchase_return_detail){

                $store_id = $productPurchaseReturn->store_id;
                $product_id = $product_purchase_return_detail->product_id;
                $qty = $product_purchase_return_detail->qty;
                $price = $product_purchase_return_detail->price;
                $purchase_invoice_no = ProductPurchaseDetail::where('product_purchase_id',$productPurchaseReturn->product_purchase_id)->where('product_id',$product_id)->pluck('invoice_no')->first();
                //dd($purchase_invoice_no);

                // update purchase details table stock status
                $product_purchase_details_info = ProductPurchaseDetail::where('invoice_no',$purchase_invoice_no)->where('product_id',$product_id)->first();
                $purchase_qty = $product_purchase_details_info->qty;
                $purchase_previous_sale_qty = $product_purchase_details_info->sale_qty;
                $total_sale_qty = $purchase_previous_sale_qty + $qty;
                $product_purchase_details_info->sale_qty = $total_sale_qty;
                if($total_sale_qty == $purchase_qty){
                    $product_purchase_details_info->qty_stock_status = 'Not Available';
                }else{
                    $product_purchase_details_info->qty_stock_status = 'Available';
                }
                $product_purchase_details_info->save();


                $check_previous_stock = Stock::where('product_id', $product_id)->latest()->pluck('current_stock')->first();
                if (!empty($check_previous_stock)) {
                    $previous_stock = $check_previous_stock;
                } else {
                    $previous_stock = 0;
                }

                // product stock
                $stock = new Stock();
                $stock->user_id = Auth::id();
                $stock->ref_id = $id;
                $stock->store_id = $store_id;
                $stock->product_id = $product_id;
                $stock->stock_type = 'purchase return delete';
                $stock->previous_stock = $previous_stock;
                $stock->stock_in = $qty;
                $stock->stock_out = 0;
                $stock->current_stock = $previous_stock - $qty;
                $stock->date = date('Y-m-d');
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
                // product stock
                $invoice_stock = new InvoiceStock();
                $invoice_stock->user_id = Auth::id();
                $invoice_stock->ref_id = $id;
                $invoice_stock->purchase_invoice_no = $purchase_invoice_no;
                $invoice_stock->invoice_no = 'PurchaseRetdel-'.$productPurchaseReturn->invoice_no;
                $invoice_stock->store_id = $store_id;
                $invoice_stock->date = date('Y-m-d');
                $invoice_stock->product_id = $product_id;
                $invoice_stock->stock_type = 'purchase return delete';
                $invoice_stock->previous_stock = $previous_invoice_stock;
                $invoice_stock->stock_in = $qty;
                $invoice_stock->stock_out = 0;
                $invoice_stock->current_stock = $previous_invoice_stock - $qty;
                $invoice_stock->save();


                $profit_amount = get_profit_amount($purchase_invoice_no,$product_id);

                // profit table
//                $profit = new Profit();
//                $profit->ref_id = $id;
//                $profit->purchase_invoice_no = $purchase_invoice_no;
//                $profit->invoice_no ='PurchaseRetdel-'.$productPurchaseReturn->invoice_no;
//                $profit->user_id = Auth::id();
//                $profit->store_id = $store_id;
//                $profit->type = 'purchase return delete';
//                $profit->product_id = $product_id;
//                $profit->qty = $qty;
//                $profit->price = $price;
//                $profit->sub_total = $qty*$price;
//                $profit->discount_amount = 0;
//                $profit->profit_amount = $price*$qty;
//                $profit->date = date('Y-m-d');
//                $profit->save();
            }
        }

        $productPurchaseReturn->delete();
        DB::table('product_purchase_return_details')->where('product_purchase_return_id',$id)->delete();
        //DB::table('stocks')->where('ref_id',$id)->where('stock_type','sale return')->delete();
        DB::table('transactions')->where('ref_id',$id)->where('transaction_type','purchase return')->delete();

        Toastr::success('Product Purchase Return Deleted Successfully', 'Success');
        return redirect()->route('productPurchaseReturn.index');
    }
    public function returnablePurchaseProduct(){

        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        $parties = Party::where('type','customer')->get() ;
        if($auth_user == "Admin"){
            $stores = Store::all();
        }else{
            $stores = Store::where('user_id',$auth_user_id)->get();
        }
        $productPurchases = ProductPurchase::latest()->get();

        return view('backend.productPurchaseReturn.returnable_purchase_products',compact('parties','stores','productPurchases'));
    }

    public function getReturnablePurchaseProduct($purchase_id){

        $productPurchase = ProductPurchase::where('id',$purchase_id)->first();
       // dd($productPurchase);
        $products = DB::table('product_purchase_details')
            ->join('products','product_purchase_details.product_id','=','products.id')
            ->where('product_purchase_details.product_purchase_id',$purchase_id)
            ->select('product_purchase_details.id','product_purchase_details.product_id','product_purchase_details.qty','product_purchase_details.price','products.name')
            ->get();

        $html = "<table class=\"table table-striped tabel-penjualan\">
                        <thead>
                            <tr>
                                <th width=\"30\">No</th>
                                <th>Product Name</th>
                                <th align=\"right\">Received Quantity</th>
                                <th>Already Return Quantity</th>
                                <th>Return Quantity</th>
                                <th>Amount</th>
                                <th>Reason <span style=\"color:red\">*</span></th>
                            </tr>
                        </thead>
                        <tbody>";
        if(count($products) > 0):
            foreach($products as $key => $item):

                $check_purchase_return_qty = check_purchase_return_qty($productPurchase->store_id,$item->product_id,$productPurchase->invoice_no);

                $key += 1;
                $html .= "<tr>";
                $html .= "<th width=\"30\">1</th>";
                $html .= "<th><input type=\"hidden\" class=\"form-control\" name=\"product_id[]\" id=\"product_id_$key\" value=\"$item->product_id\" size=\"28\" /><input type=\"hidden\" class=\"form-control\" name=\"product_purchase_detail_id[]\" id=\"product_purchase_detail_id_$key\" value=\"$item->id\" size=\"28\" />$item->name</th>";
//                $html .= "<th><input type=\"hidden\" class=\"form-control\" name=\"product_sale_id[]\" id=\"product_sale_id_$key\" value=\"$item->product_sale_id\" size=\"28\" /></th>";
                $html .= "<th><input type=\"text\" class=\"form-control\" name=\"qty[]\" id=\"qty_$key\" value=\"$item->qty\" size=\"28\" readonly /></th>";
                $html .= "<th><input type=\"text\" class=\"form-control\" name=\"check_sale_return_qty[]\" id=\"check_sale_return_qty_$key\" value=\"$check_purchase_return_qty\" readonly /></th>";
                $html .= "<th><input type=\"text\" class=\"form-control\" name=\"return_qty[]\" id=\"return_qty_$key\" onkeyup=\"return_qty($key,this);\" size=\"28\" /></th>";
                $html .= "<th><input type=\"text\" class=\"form-control\" name=\"total_amount[]\" id=\"total_amount_$key\"  value=\"$item->price\" size=\"28\" /></th>";
                $html .= "<th><textarea type=\"text\" class=\"form-control\" name=\"reason[]\" id=\"reason_$key\"  size=\"28\"></textarea> </th>";
                $html .= "</tr>";
            endforeach;
            $html .= "<tr>";
            $html .= "<th colspan=\"2\"><select name=\"payment_type\" id=\"payment_type\" class=\"form-control\" onchange=\"productType('')\" >
                    <option value=\"Cash\" selected>Cash</option>
                    <option value=\"Cheque\">Cheque</option>
            </select> </th>";
            $html .= "<th><input type=\"text\" name=\"cheque_number\" id=\"cheque_number\" class=\"form-control\" placeholder=\"Cheque Number\" readonly=\"readonly\"  size=\"28\" ></th>";
            $html .= "</tr>";
        endif;
        $html .= "</tbody>
                    </table>";
        echo json_encode($html);
        //dd($html);
    }

    public function purchaseProductReturn(Request $request){
        //dd($request->all());
        $row_count = count($request->return_qty);
        $productPurchase = ProductPurchase::where('id',$request->product_purchase_id)->first();


        $total_amount = 0;
        for ($i = 0; $i < $row_count; $i++) {
            if ($request->return_qty[$i] != null) {
                $total_amount += $request->total_amount[$i]*$request->return_qty[$i];
            }
        }


        $product_purchase_return = new ProductPurchaseReturn();
        $product_purchase_return->invoice_no = 'PurchaseRet-'.$productPurchase->invoice_no;
        $product_purchase_return->purchase_invoice_no = $productPurchase->invoice_no;

        $product_purchase_return->product_purchase_id = $productPurchase->id;
        $product_purchase_return->user_id = Auth::id();
        $product_purchase_return->store_id = $productPurchase->store_id;
        $product_purchase_return->party_id = $productPurchase->party_id;
        $product_purchase_return->payment_type = $productPurchase->payment_type;
        $product_purchase_return->total_amount = $total_amount ;
        $product_purchase_return->save();


        $insert_id = $product_purchase_return->id;
        if($insert_id) {
            for ($i = 0; $i < $row_count; $i++) {
                if ($request->return_qty[$i] != null) {
                    $product_purchase_detail_id = $request->product_purchase_detail_id[$i];
                    $productPurchaseDetail = ProductPurchaseDetail::where('id',$product_purchase_detail_id)->first();

                    $product_purchase_return_detail = new ProductPurchaseReturnDetail();
                    $product_purchase_return_detail->product_purchase_return_id = $insert_id;
                    $product_purchase_return_detail->product_purchase_detail_id = $productPurchaseDetail->id;
                    $product_purchase_return_detail->product_category_id = $productPurchaseDetail->product_category_id;
                    $product_purchase_return_detail->product_sub_category_id = $productPurchaseDetail->product_sub_category_id;
                    $product_purchase_return_detail->product_brand_id = $productPurchaseDetail->product_brand_id;
                    $product_purchase_return_detail->product_id = $productPurchaseDetail->product_id;
                    $product_purchase_return_detail->qty = $request->return_qty[$i];
                    $product_purchase_return_detail->price = $request->total_amount[$i];
                    //$product_purchase_return_detail->discount = $request->discount[$i];
                    $product_purchase_return_detail->reason = isset($request->reason[$i]) ? $request->reason[$i] : 'Something Wrong';
                    $product_purchase_return_detail->save();

                    $product_id = $productPurchaseDetail->product_id;
                    $purchase_invoice_no = ProductPurchaseDetail::where('product_purchase_id',$productPurchase->id)->where('product_id',$product_id)->pluck('invoice_no')->first();
                    //dd($purchase_invoice_no);

                    // update purchase details table stock status
//                    $product_purchase_details_info = ProductPurchaseDetail::where('invoice_no',$purchase_invoice_no)->where('product_id',$product_id)->first();
//
//                    $purchase_qty = $product_purchase_details_info->qty;
//                    $purchase_previous_sale_qty = $product_purchase_details_info->sale_qty;
//                    $total_sale_qty = $purchase_previous_sale_qty - $request->return_qty[$i];
//                    $product_purchase_details_info->sale_qty = $total_sale_qty;
//                    if($total_sale_qty == $purchase_qty){
//                        $product_purchase_details_info->qty_stock_status = 'Not Available';
//                    }else{
//                        $product_purchase_details_info->qty_stock_status = 'Available';
//                    }
//                    $product_purchase_details_info->save();


                    $check_previous_stock = Stock::where('product_id', $product_id)->latest()->pluck('current_stock')->first();
                    if (!empty($check_previous_stock)) {
                        $previous_stock = $check_previous_stock;
                    } else {
                        $previous_stock = 0;
                    }
                    // product stock
                    $stock = new Stock();
                    $stock->user_id = Auth::id();
                    $stock->ref_id = $insert_id;
                    $stock->store_id = $productPurchase->store_id;
                    $stock->product_id = $product_id;
                    $stock->stock_type = 'purchase return';
                    $stock->previous_stock = $previous_stock;
                    $stock->stock_in = 0;
                    $stock->stock_out = $request->return_qty[$i];
                    $stock->current_stock = $previous_stock - $request->return_qty[$i];
                    $stock->date = date('Y-m-d');
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
                    $invoice_stock->invoice_no = 'PurchaseRet-'.$productPurchase->invoice_no;
                    $invoice_stock->store_id = $productPurchase->store_id;
                    $invoice_stock->date = $request->date;
                    $invoice_stock->product_id = $product_id;
                    $invoice_stock->stock_type = 'purchase return';
                    $invoice_stock->previous_stock = $previous_invoice_stock;
                    $invoice_stock->stock_in = 0;
                    $invoice_stock->stock_out = $request->return_qty[$i];
                    $invoice_stock->current_stock = $previous_invoice_stock - $request->return_qty[$i];
                    $invoice_stock->save();


//                    $profit_amount = get_profit_amount($purchase_invoice_no,$product_id);

                    // profit table
//                    $profit = new Profit();
//                    $profit->ref_id = $insert_id;
//                    $profit->purchase_invoice_no = $purchase_invoice_no;
//                    $profit->invoice_no ='PurchaseRet-'.$productPurchase->invoice_no;
//                    $profit->user_id = Auth::id();
//                    $profit->store_id = $productPurchase->store_id;
//                    $profit->type = 'Purchase ';
//                    $profit->product_id = $product_id;
//                    $profit->qty = $request->return_qty[$i];
//                    $profit->price = $request->total_amount[$i];
//                    $profit->sub_total = $request->return_qty[$i]*$request->total_amount[$i];
//                    $profit->discount_amount = 0;
//                    $profit->profit_amount = +($request->total_amount[$i]*$request->return_qty[$i]);
//                    $profit->date = date('Y-m-d');
//                    $profit->save();

                }
            }

            $transaction_product_type = Transaction::where('invoice_no',$productPurchase->invoice_no)->pluck('transaction_product_type')->first();
           // dd($transaction_product_type);
            // transaction
            $transaction = new Transaction();
            $transaction->invoice_no = 'PurchaseRet-' . $productPurchase->invoice_no;
            $transaction->user_id = Auth::id();
            $transaction->store_id = $productPurchase->store_id;
            $transaction->party_id = $productPurchase->party_id;
            $transaction->ref_id = $insert_id;
            $transaction->transaction_product_type = $transaction_product_type;
            $transaction->transaction_type = 'purchase return';
            $transaction->payment_type = $request->payment_type;
            $transaction->cheque_number = $request->cheque_number;
            $transaction->date = date('Y-m-d');
            $transaction->amount = $total_amount;

            $transaction->save();
        }

        Toastr::success('Product Purchase Return Created Successfully', 'Success');
        return redirect()->route('productPurchaseReturn.index');
    }
}
