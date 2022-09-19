<?php

use App\InvoiceStock;
use App\ProductPurchaseDetail;
use App\ProductSaleDetail;
use App\Stock;
use App\Profit;
use Illuminate\Support\Facades\DB;

function store_test($store_id){
    return $store_id + 3;
}

if (!function_exists('sum_finish_goods_purchase_price')) {
    function sum_finish_goods_purchase_price($store_id)
    {
        $sum_finish_goods_purchase_price = 0;

        $productPurchaseDetails = DB::table('product_purchase_details')
            ->join('product_purchases', 'product_purchases.id', '=', 'product_purchase_details.product_purchase_id')
            ->select('product_id', 'product_category_id', 'product_sub_category_id', 'product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
            ->where('product_purchases.store_id', $store_id)
            ->where('product_purchases.ref_id',NULL)
            ->where('product_purchases.purchase_product_type','Finish Goods')
            ->groupBy('product_id')
            ->groupBy('product_category_id')
            ->groupBy('product_sub_category_id')
            ->groupBy('product_brand_id')
            ->get();

        if (!empty($productPurchaseDetails)) {
            foreach ($productPurchaseDetails as $key => $productPurchaseDetail) {
                $sum_finish_goods_purchase_price += $productPurchaseDetail->sub_total;
            }
        }


        $sum_total_amount = DB::table('product_productions')
            ->join('product_purchases','product_productions.id','product_purchases.ref_id')
            ->select(DB::raw('SUM(product_productions.total_amount) as sum_total_amount'))
            ->first();
        if($sum_total_amount){
            $sum_finish_goods_purchase_price += $sum_total_amount->sum_total_amount;
        }

        return $sum_finish_goods_purchase_price;
    }
}

if (!function_exists('sum_raw_materials_price')) {
    function sum_raw_materials_price($store_id)
    {
        $sum_raw_materials_price = 0;
        $productPurchaseDetails = DB::table('product_purchase_details')
            ->join('product_purchases', 'product_purchases.id', '=', 'product_purchase_details.product_purchase_id')
            ->select('product_id', 'product_category_id', 'product_sub_category_id', 'product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
            ->where('product_purchases.store_id', $store_id)
            //->where('product_purchases.ref_id',NULL)
            ->where('product_purchases.purchase_product_type','Raw Materials')
            ->groupBy('product_id')
            ->groupBy('product_category_id')
            ->groupBy('product_sub_category_id')
            ->groupBy('product_brand_id')
            ->get();

        $product_productions = DB::table('product_productions')
            ->select(DB::raw('SUM(total_amount) as sum_production_total_amount'))
            ->where('store_id',$store_id)
            ->first();

        $sum_production_total_amount = $product_productions->sum_production_total_amount;

        if (!empty($productPurchaseDetails)) {
            foreach ($productPurchaseDetails as $key => $productPurchaseDetail) {
                $sum_raw_materials_price += $productPurchaseDetail->sub_total;
            }
        }

        return $sum_raw_materials_price - $sum_production_total_amount;
    }
}

//if (!function_exists('sum_sale_price')) {
//    function sum_sale_price($store_id)
//    {
//        $sum_sale_price = 0;
//        $productPurchaseDetails = DB::table('product_purchase_details')
//            ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
//            ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
//            ->where('product_purchases.store_id',$store_id)
//            //->where('product_purchases.ref_id',NULL)
//            ->where('product_purchases.purchase_product_type','Finish Goods')
//            ->groupBy('product_id')
//            ->groupBy('product_category_id')
//            ->groupBy('product_sub_category_id')
//            ->groupBy('product_brand_id')
//            ->get();
//
//        if(!empty($productPurchaseDetails)) {
//            foreach ($productPurchaseDetails as $key => $productPurchaseDetail) {
//                // sale
//                $productSaleDetails = DB::table('product_sale_details')
//                    ->select('product_id', 'product_category_id', 'product_sub_category_id', 'product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
//                    ->where('product_id', $productPurchaseDetail->product_id)
//                    ->where('product_category_id', $productPurchaseDetail->product_category_id)
//                    ->where('product_sub_category_id', $productPurchaseDetail->product_sub_category_id)
//                    ->where('product_brand_id', $productPurchaseDetail->product_brand_id)
//                    ->groupBy('product_id')
//                    ->groupBy('product_category_id')
//                    ->groupBy('product_sub_category_id')
//                    ->groupBy('product_brand_id')
//                    ->first();
//
//                if (!empty($productSaleDetails)) {
//                    $sum_sale_price += $productSaleDetails->sub_total;
//                }
//            }
//        }
//
//        return $sum_sale_price;
//    }
//}

if (!function_exists('sum_sale_price')) {
    function sum_sale_price($store_id)
    {
        $product_sales = DB::table('product_sales')
            ->select(DB::raw('SUM(total_amount) as sum_product_sale_amount'))
            ->where('store_id',$store_id)
            ->first();

        return $sum_sale_price = $product_sales->sum_product_sale_amount;

    }
}

if (!function_exists('sum_sale_return_price')) {
    function sum_sale_return_price($store_id)
    {
//        $sum_sale_return_price = 0;
//        $productPurchaseDetails = DB::table('product_purchase_details')
//            ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
//            ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
//            ->where('product_purchases.store_id',$store_id)
//            //->where('product_purchases.ref_id',NULL)
//            //->where('product_purchases.purchase_product_type','Finish Goods')
//            ->groupBy('product_id')
//            ->groupBy('product_category_id')
//            ->groupBy('product_sub_category_id')
//            ->groupBy('product_brand_id')
//            ->get();
//
//        if(!empty($productPurchaseDetails)) {
//            foreach ($productPurchaseDetails as $key => $productPurchaseDetail) {
//                // sale return
//                $productSaleReturnDetails = DB::table('product_sale_return_details')
//                    ->select('product_id', 'product_category_id', 'product_sub_category_id', 'product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'))
//                    ->where('product_id', $productPurchaseDetail->product_id)
//                    ->where('product_category_id', $productPurchaseDetail->product_category_id)
//                    ->where('product_sub_category_id', $productPurchaseDetail->product_sub_category_id)
//                    ->where('product_brand_id', $productPurchaseDetail->product_brand_id)
//                    ->groupBy('product_id')
//                    ->groupBy('product_category_id')
//                    ->groupBy('product_sub_category_id')
//                    ->groupBy('product_brand_id')
//                    ->first();
//
//                if (!empty($productSaleReturnDetails)) {
//                    $sum_sale_return_price += $productSaleReturnDetails->price;
//
//                }
//            }
//        }
//
//        return $sum_sale_return_price;

        $product_sale_returns = DB::table('product_sale_returns')
            ->select(DB::raw('SUM(total_amount) as sum_product_sale_return_amount'))
            ->where('store_id',$store_id)
            ->first();

        return $sum_sale_return_price = $product_sale_returns->sum_product_sale_return_amount;
    }
}

if (!function_exists('sum_purchase_return_price')) {
    function sum_purchase_return_price($store_id)
    {
        $product_purchase_returns = DB::table('product_purchase_returns')
            ->select(DB::raw('SUM(total_amount) as sum_product_purchase_return_amount'))
            ->where('store_id',$store_id)
            ->first();

        return $product_purchase_returns->sum_product_purchase_return_amount;
    }
}

if (!function_exists('total_expense')) {
    function total_expense($store_id,$start_date=null,$end_date=null)
    {
        $total_expense = 0;
        if($start_date != NULL && $end_date != NULL){
            $total_expense = \App\Expense::where('date','>=',$start_date)
                ->where('date','<=',$end_date)
                ->where('store_id',$store_id)
                ->sum('amount');
        }else{
            $total_expense = \App\Expense::where('store_id',$store_id)->sum('amount');
        }

        return $total_expense;
    }
}

if (!function_exists('product_sale_return_discount')) {
    function product_sale_return_discount($store_id,$start_date= null, $end_date=null)
    {
        if($start_date != null && $end_date != null){
            $productSaleReturnDiscount = DB::table('product_sale_returns')
                ->where('store_id',$store_id)
                ->where('created_at','>=',$start_date.' 00:00:00')
                ->where('created_at','<=',$end_date.' 23:59:59')
                ->select( DB::raw('SUM(discount_amount) as total_discount'))
                ->first();
        }else{
            $productSaleReturnDiscount = DB::table('product_sale_returns')
                ->select( DB::raw('SUM(discount_amount) as total_discount'))
                ->where('store_id',$store_id)
                ->first();
        }


        $sum_total_return_discount = 0;
        if($productSaleReturnDiscount){
            $sum_total_return_discount = $productSaleReturnDiscount->total_discount;
        }

        return $sum_total_return_discount;
    }
}

if (!function_exists('product_sale_discount')) {
    function product_sale_discount($store_id,$start_date= null, $end_date= null)
    {
        if($start_date != null && $end_date != null){
            $productSaleDiscount = DB::table('product_sales')
                ->select( DB::raw('SUM(discount_amount) as total_discount'))
                ->where('store_id',$store_id)
                ->where('created_at','>=',$start_date.' 00:00:00')
                ->where('created_at','<=',$end_date.' 23:59:59')
                ->first();
        }else{
            $productSaleDiscount = DB::table('product_sales')
                ->select( DB::raw('SUM(discount_amount) as total_discount'))
                ->where('store_id',$store_id)
                ->first();
        }

        $sum_total_discount = 0;
        if($productSaleDiscount){
            $sum_total_discount = $productSaleDiscount->total_discount;
        }

        return $sum_total_discount;
    }
}

//if (!function_exists('loss_profit')) {
//    function loss_profit($store_id,$start_date=null,$end_date=null)
//    {
//        $sum_purchase_price = 0;
//        $sum_sale_price = 0;
//        $sum_sale_return_price = 0;
//        $sum_profit_amount = 0;
//
//        $productPurchaseDetails = DB::table('product_purchase_details')
//            ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
//            ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
//            ->where('product_purchases.store_id',$store_id)
//            ->where('product_purchases.created_at','>=',$start_date.' 00:00:00')
//            ->where('product_purchases.created_at','>=',$end_date.' 23:59:59')
//            //->where('product_purchases.ref_id',NULL)
//            //->where('product_purchases.purchase_product_type','Finish Goods')
//            ->groupBy('product_id')
//            ->groupBy('product_category_id')
//            ->groupBy('product_sub_category_id')
//            ->groupBy('product_brand_id')
//            ->get();
//
//        if(!empty($productPurchaseDetails)){
//            foreach($productPurchaseDetails as $key => $productPurchaseDetail){
//                $purchase_average_price = $productPurchaseDetail->sub_total/$productPurchaseDetail->qty;
//                $sum_purchase_price += $productPurchaseDetail->sub_total;
//
//                // sale
//                $productSaleDetails = DB::table('product_sale_details')
//                    ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
//                    ->where('product_id',$productPurchaseDetail->product_id)
//                    ->where('product_category_id',$productPurchaseDetail->product_category_id)
//                    ->where('product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
//                    ->where('product_brand_id',$productPurchaseDetail->product_brand_id)
//                    ->groupBy('product_id')
//                    ->groupBy('product_category_id')
//                    ->groupBy('product_sub_category_id')
//                    ->groupBy('product_brand_id')
//                    ->first();
//
//                if(!empty($productSaleDetails))
//                {
//                    $sale_total_qty = $productSaleDetails->qty;
//                    $sum_sale_price += $productSaleDetails->sub_total;
//                    $sale_average_price = $productSaleDetails->sub_total/ (int) $productSaleDetails->qty;
//
//                    if($sale_total_qty > 0){
//                        $amount = ($sale_average_price*$sale_total_qty) - ($purchase_average_price*$sale_total_qty);
//                        if($amount > 0){
//                            $sum_profit_amount += $amount;
//                        }else{
//                            $sum_profit_amount -= $amount;
//                        }
//
//                    }
//                }
//
//                // sale return
//
//                $productSaleReturnDetails = DB::table('product_sale_return_details')
//                    ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'))
//                    ->where('product_id',$productPurchaseDetail->product_id)
//                    ->where('product_category_id',$productPurchaseDetail->product_category_id)
//                    ->where('product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
//                    ->where('product_brand_id',$productPurchaseDetail->product_brand_id)
//                    ->groupBy('product_id')
//                    ->groupBy('product_category_id')
//                    ->groupBy('product_sub_category_id')
//                    ->groupBy('product_brand_id')
//                    ->first();
//
//                if(!empty($productSaleReturnDetails))
//                {
//                    $sale_return_total_qty = $productSaleReturnDetails->qty;
//                    $sale_return_total_amount = $productSaleReturnDetails->price;
//                    $sum_sale_return_price += $productSaleReturnDetails->price;
//                    $sale_return_average_price = $sale_return_total_amount/$productSaleReturnDetails->qty;
//
//                    if($sale_return_total_qty > 0){
//                        $amount = $sale_return_average_price - ($purchase_average_price*$sale_return_total_qty);
//                        if($amount > 0){
//                            $sum_profit_amount -= $amount;
//                        }else{
//                            $sum_profit_amount += $amount;
//                        }
//                    }
//                }
//            }
//        }
//
//        $loss_profit = $sum_profit_amount;
//
//        return $loss_profit;
//    }
//}

if (!function_exists('loss_profit')) {
    function loss_profit($store_id,$start_date=null,$end_date=null)
    {
        if(!empty($start_date)  && !empty($end_date)){
             $profit = DB::table('profits')
                ->select(DB::raw('SUM(profit_amount) as sum_profit_amount'))
                ->where('store_id',$store_id)
                 ->where('date','>=',$start_date)
                 ->where('date','<=',$end_date)
                ->first();

        }else{
            $profit = DB::table('profits')
                ->select(DB::raw('SUM(profit_amount) as sum_profit_amount'))
                ->where('store_id',$store_id)
                ->first();

        }
        return $loss_profit = $profit->sum_profit_amount;

        //$sale_discount = product_sale_discount($store_id,$start_date= null, $end_date= null);
        //return $sale_discount;
        //$sale_return_discount = product_sale_return_discount($store_id);
        //$final_sale_discount = $sale_discount - $sale_return_discount;

        //return $loss_profit = $profit->sum_profit_amount - $final_sale_discount;
        //return $loss_profit = $profit->sum_profit_amount - $sale_discount;


    }
}

if (!function_exists('party_discounts')) {
    function party_discounts($store_id=null,$party_id=null,$start_date=null,$end_date=null)
    {
        if($party_id != null && $start_date != null && $end_date != null){
            return $party_discounts = DB::table('product_sales')
                ->join('parties','product_sales.party_id','parties.id')
                ->where('product_sales.discount_amount','>',0)
                ->where('product_sales.store_id',$store_id)
                ->where('parties.id',$party_id)
                ->where('product_sales.date','>=',$start_date)
                ->where('product_sales.date','<=',$end_date)
                ->select('product_sales.invoice_no','product_sales.discount_amount','product_sales.date','parties.name')
                ->get();
        }elseif($party_id != null){
            return $party_discounts = DB::table('product_sales')
                ->join('parties','product_sales.party_id','parties.id')
                ->where('product_sales.discount_amount','>',0)
                ->where('product_sales.store_id',$store_id)
                ->where('parties.id',$party_id)
                ->select('product_sales.invoice_no','product_sales.discount_amount','product_sales.date','parties.name')
                ->get();
        }elseif($start_date != null && $end_date != null){
            return $party_discounts = DB::table('product_sales')
                ->join('parties','product_sales.party_id','parties.id')
                ->where('product_sales.discount_amount','>',0)
                ->where('product_sales.store_id',$store_id)
                ->where('product_sales.date','>=',$start_date)
                ->where('product_sales.date','<=',$end_date)
                ->select('product_sales.invoice_no','product_sales.discount_amount','product_sales.date','parties.name')
                ->get();
        }else{
            return $party_discounts = DB::table('product_sales')
                ->join('parties','product_sales.party_id','parties.id')
                ->where('product_sales.discount_amount','>',0)
                ->where('product_sales.store_id',$store_id)
                ->select('product_sales.invoice_no','product_sales.discount_amount','product_sales.date','parties.name')
                ->get();
        }
    }
}

if (!function_exists('purchase_invoice_nos')) {
    function purchase_invoice_nos($store_id=null,$product_id=null)
    {
        if($store_id != null && $product_id != null){
            return $invoice_nos = DB::table('product_purchases')
                ->leftjoin('product_purchase_details','product_purchase_details.product_purchase_id','product_purchases.id')
                ->where('product_purchase_details.qty_stock_status','Available')
                //->where('product_purchases.purchase_product_type','Finish Goods')
                ->where('product_purchases.store_id',$store_id)
                ->where('product_purchase_details.product_id',$product_id)
                ->select('product_purchases.invoice_no')
                ->get();
        }else{
            return $invoice_nos = DB::table('product_purchases')
                ->leftjoin('product_purchase_details','product_purchase_details.product_purchase_id','product_purchases.id')
                ->where('product_purchase_details.qty_stock_status','Available')
                //->where('product_purchases.purchase_product_type','Finish Goods')
                ->select('product_purchases.invoice_no')
                ->get();
        }
    }
}

if (!function_exists('raw_materials_purchase_invoice_nos')) {
    function raw_materials_purchase_invoice_nos($store_id=null,$product_id=null)
    {
        if($store_id != null && $product_id != null){
            return $invoice_nos = DB::table('product_purchases')
                ->leftjoin('product_purchase_details','product_purchase_details.product_purchase_id','product_purchases.id')
                ->where('product_purchase_details.qty_stock_status','Available')
                ->where('product_purchases.purchase_product_type','Raw Materials')
                ->where('product_purchases.store_id',$store_id)
                ->where('product_purchase_details.product_id',$product_id)
                ->select('product_purchases.invoice_no')
                ->get();
        }else{
            return $invoice_nos = DB::table('product_purchases')
                ->leftjoin('product_purchase_details','product_purchase_details.product_purchase_id','product_purchases.id')
                ->where('product_purchase_details.qty_stock_status','Available')
                ->where('product_purchases.purchase_product_type','Raw Materials')
                ->select('product_purchases.invoice_no')
                ->get();
        }
    }
}

if (!function_exists('current_stock_row')) {
    function current_stock_row($store_id,$stock_product_type,$stock_type,$product_id)
    {
        return $current_stock_row = Stock::where('store_id',$store_id)
            ->where('stock_type',$stock_type)
            ->where('stock_product_type',$stock_product_type)
            ->where('product_id',$product_id)
            ->latest()->first();
    }
}


if (!function_exists('current_invoice_stock_row')) {
    function current_invoice_stock_row($store_id,$stock_product_type,$stock_type,$product_id,$purchase_invoice_no,$invoice_no=null)
    {
        if($invoice_no != null){
            return $current_stock_row = InvoiceStock::where('store_id',$store_id)
                ->where('stock_type',$stock_type)
                ->where('stock_product_type',$stock_product_type)
                ->where('purchase_invoice_no',$purchase_invoice_no)
                ->where('invoice_no',$invoice_no)
                ->where('product_id',$product_id)
                ->first();

        }else{
            return $current_stock_row = InvoiceStock::where('store_id',$store_id)
                ->where('stock_type',$stock_type)
                ->where('stock_product_type',$stock_product_type)
                ->where('purchase_invoice_no',$purchase_invoice_no)
                ->where('product_id',$product_id)
                ->first();
        }
    }
}

//if (!function_exists('get_profit_amount')) {
//    function get_profit_amount($purchase_invoice_no,$product_id)
//    {
//        return $get_profit_amount = ProductPurchaseDetail::where('invoice_no',$purchase_invoice_no)
//            ->where('product_id',$product_id)
//            ->pluck('profit_amount')
//            ->first();
//    }
//}

if (!function_exists('get_profit_amount')) {
    function get_profit_amount($purchase_invoice_no,$product_id, $current_sale_price)
    {
        //dd($current_sale_price);
        $purchase_price = ProductPurchaseDetail::where('invoice_no',$purchase_invoice_no)
            ->where('product_id',$product_id)
            ->pluck('price')
            ->first();

        return $current_sale_price - $purchase_price;
    }
}

if (!function_exists('get_replace_loss_profit_amount')) {
    function get_replace_loss_profit_amount($purchase_invoice_no,$product_id,$purchase_invoice_list,$replace_qty)
    {
        $get_previous_price_amount = ProductPurchaseDetail::where('invoice_no',$purchase_invoice_no)
            ->where('product_id',$product_id)
            ->pluck('price')
            ->first();
        $get_previous_mrp_price_amount = ProductPurchaseDetail::where('invoice_no',$purchase_invoice_no)
            ->where('product_id',$product_id)
            ->pluck('mrp_price')
            ->first();

        $get_previous_total_amount = $get_previous_mrp_price_amount - $get_previous_price_amount ;

        $get_new_price_amount = ProductPurchaseDetail::where('invoice_no',$purchase_invoice_list)
            ->where('product_id',$product_id)
            ->pluck('price')
            ->first();

        $get_new_mrp_price_amount = ProductPurchaseDetail::where('invoice_no',$purchase_invoice_list)
            ->where('product_id',$product_id)
            ->pluck('mrp_price')
            ->first();

        $get_new_total_amount = $get_new_mrp_price_amount - $get_new_price_amount ;

        if($get_previous_total_amount > $get_new_total_amount){
            $amount = $get_previous_total_amount - $get_new_total_amount;
            //dd($amount);
            return ($amount*$replace_qty);
        }else{
            $amount = $get_new_total_amount - $get_previous_total_amount;
            return -($amount*$replace_qty);
        }
    }
}

if (!function_exists('get_profit_amount_row')) {
    function get_profit_amount_row($store_id,$purchase_invoice_no,$invoice_no,$product_id)
    {
        return $get_profit_amount = Profit::where('store_id',$store_id)
            ->where('purchase_invoice_no',$purchase_invoice_no)
            ->where('invoice_no',$invoice_no)
            ->where('product_id',$product_id)
            ->first();
    }
}

if (!function_exists('edited_current_invoice_stock')) {
//    function edited_current_invoice_stock($store_id,$purchase_invoice_no,$product_id,$invoice_no,$product_sale_detail_id)
//    {
//        $purchase_qty = DB::table('product_purchase_details')
//            ->join('product_purchases','product_purchase_details.product_purchase_id','product_purchases.id')
//            ->where('product_purchases.store_id',$store_id)
//            ->where('product_purchases.invoice_no',$purchase_invoice_no)
//            ->where('product_purchase_details.product_id',$product_id)
//            ->pluck('product_purchase_details.qty')
//            ->first();
//
//        $previous_sale_qty = DB::table('product_sale_details')
//            ->join('product_sales','product_sale_details.product_sale_id','product_sales.id')
//            ->where('product_sales.store_id',$store_id)
//            ->where('product_sale_details.purchase_invoice_no',$purchase_invoice_no)
//            ->where('product_sales.invoice_no','!=',$invoice_no)
//            ->where('product_sale_details.product_id',$product_id)
//            ->select(DB::raw('SUM(product_sale_details.qty) as sum_qty'))
//            ->first();
//        $previous_sale_sum_qty = $previous_sale_qty->sum_qty;
//        if($previous_sale_sum_qty != null){
//            return $purchase_qty - $previous_sale_sum_qty;
//        }else{
//            return $purchase_qty;
//        }
//    }
    function edited_current_invoice_stock($store_id,$product_id,$product_sale_qty)
    {
        return $current_stock = DB::table('stocks')
            ->where('store_id',$store_id)
            ->where('product_id',$product_id)
            ->latest()
            ->pluck('current_stock')
            ->first();


    }
}

if (!function_exists('check_sale_return_qty')) {
    function check_sale_return_qty($store_id,$product_id,$sale_invoice_no)
    {
        $sale_return_qty = DB::table('product_sale_return_details')
            ->join('product_sale_returns','product_sale_return_details.product_sale_return_id','product_sale_returns.id')
            ->where('product_sale_returns.store_id',$store_id)
            ->where('product_sale_returns.sale_invoice_no',$sale_invoice_no)
            ->where('product_sale_return_details.product_id',$product_id)
            ->select(DB::raw('sum(product_sale_return_details.qty) as total_sale_return_qty'))
            ->first();

        return $sale_return_qty->total_sale_return_qty;

    }
}

if (!function_exists('check_purchase_return_qty')) {
    function check_purchase_return_qty($store_id,$product_id,$purchase_invoice_no)
    {
        $purchase_return_qty = DB::table('product_purchase_return_details')
            ->join('product_purchase_returns','product_purchase_return_details.product_purchase_return_id','product_purchase_returns.id')
            ->where('product_purchase_returns.store_id',$store_id)
            ->where('product_purchase_returns.purchase_invoice_no',$purchase_invoice_no)
            ->where('product_purchase_return_details.product_id',$product_id)
            ->select(DB::raw('sum(product_purchase_return_details.qty) as total_purchase_return_qty'))
            ->first();

        return $purchase_return_qty->total_purchase_return_qty;

    }
}
if (!function_exists('check_purchase_replace_qty')) {
    function check_purchase_replace_qty($store_id,$product_id,$invoice_no)
    {
        $purchase_replace_qty = DB::table('product_purchase_replacement_details')
            ->join('product_purchase_replacements','product_purchase_replacement_details.product_purchase_replacement_id','product_purchase_replacements.id')
            ->where('product_purchase_replacements.store_id',$store_id)
            ->where('product_purchase_replacements.purchase_invoice_no',$invoice_no)
            ->where('product_purchase_replacement_details.product_id',$product_id)
            ->select(DB::raw('sum(product_purchase_replacement_details.replace_qty) as total_purchase_replace_qty'))
            ->first();

        return $purchase_replace_qty->total_purchase_replace_qty;

    }
}

if (!function_exists('check_sale_replace_qty')) {
    function check_sale_replace_qty($store_id,$product_id,$sale_invoice_no)
    {
        $sale_replace_qty = DB::table('product_sale_replacement_details')
            ->join('product_sale_replacements','product_sale_replacement_details.p_s_replacement_id','product_sale_replacements.id')
            ->where('product_sale_replacements.store_id',$store_id)
            ->where('product_sale_replacements.sale_invoice_no',$sale_invoice_no)
            ->where('product_sale_replacement_details.product_id',$product_id)
            ->select(DB::raw('sum(product_sale_replacement_details.replace_qty) as total_sale_replace_qty'))
            ->first();

        return $sale_replace_qty->total_sale_replace_qty;

    }
}

//if (!function_exists('purchase_invoice_lists')) {
//    function purchase_invoice_lists($product_id)
//    {
//        $purchase_invoice_lists = DB::table('product_purchase_details')
//            ->where('product_id',$product_id)
//            ->select('invoice_no','product_id')
//            ->get();
//        //dd($purchase_invoice_lists);
//        return $purchase_invoice_lists;
//
//    }
//}

if (!function_exists('purchase_invoice_lists')) {
    function purchase_invoice_lists($product_id)
    {
        $purchase_invoice_lists = DB::table('product_purchase_details')
            ->where('product_id',$product_id)
            ->select('invoice_no','product_id')
            ->get();
        //dd($purchase_invoice_lists);
        return $purchase_invoice_lists;

    }
}




if (!function_exists('salesProductModels')) {
    function salesProductModels($product_sale_id)
    {
        $salesProductModels = DB::table('product_sale_details')
            ->leftJoin('products','product_sale_details.product_id','products.id')
            ->where('product_sale_details.product_sale_id',$product_sale_id)
            ->select('products.model')
            ->get();

        $models = '';
        $elements = [];
        if(count($salesProductModels) > 0){
            foreach($salesProductModels as $salesProductModel){
                $elements[] = $salesProductModel->model;
            }
            $models = implode(',', $elements);
        }
        return $models;
    }
}

if (!function_exists('saleReturnsProductModels')) {
    function saleReturnsProductModels($product_sale_return_id)
    {
        $saleReturnsProductModels = DB::table('product_sale_return_details')
            ->leftJoin('products','product_sale_return_details.product_id','products.id')
            ->where('product_sale_return_details.product_sale_return_id',$product_sale_return_id)
            ->select('products.model')
            ->get();

        $models = '';
        $elements = [];
        if(count($saleReturnsProductModels) > 0){
            foreach($saleReturnsProductModels as $saleReturnsProductModel){
                $elements[] = $saleReturnsProductModel->model;
            }
            $models = implode(',', $elements);
        }
        return $models;
    }
}

if (!function_exists('saleReplacementsProductModels')) {
    function saleReplacementsProductModels($product_sale_replacement_id)
    {
        $saleReplacementsProductModels = DB::table('product_sale_replacement_details')
            ->leftJoin('products','product_sale_replacement_details.product_id','products.id')
            ->where('product_sale_replacement_details.p_s_replacement_id',$product_sale_replacement_id)
            ->select('products.model')
            ->get();

        $models = '';
        $elements = [];
        if(count($saleReplacementsProductModels) > 0){
            foreach($saleReplacementsProductModels as $saleReplacementsProductModel){
                $elements[] = $saleReplacementsProductModel->model;
            }
            $models = implode(',', $elements);
        }
        return $models;
    }
}

if (!function_exists('purchaseReturnsProductModels')) {
    function purchaseReturnsProductModels($product_purchase_return_id)
    {
        $purchaseReturnsProductModels = DB::table('product_purchase_return_details')
            ->leftJoin('products','product_purchase_return_details.product_id','products.id')
            ->where('product_purchase_return_details.product_purchase_return_id',$product_purchase_return_id)
            ->select('products.model')
            ->get();

        $models = '';
        $elements = [];
        if(count($purchaseReturnsProductModels) > 0){
            foreach($purchaseReturnsProductModels as $purchaseReturnsProductModel){
                $elements[] = $purchaseReturnsProductModel->model;
            }
            $models = implode(',', $elements);
        }
        return $models;
    }
}

if (!function_exists('purchaseReplacementsProductModels')) {
    function purchaseReplacementsProductModels($product_purchase_replacement_id)
    {
        $purchaseReplacementsProductModels = DB::table('product_purchase_replacement_details')
            ->leftJoin('products','product_purchase_replacement_details.product_id','products.id')
            ->where('product_purchase_replacement_details.product_purchase_replacement_id',$product_purchase_replacement_id)
            ->select('products.model')
            ->get();

        $models = '';
        $elements = [];
        if(count($purchaseReplacementsProductModels) > 0){
            foreach($purchaseReplacementsProductModels as $purchaseReplacementsProductModel){
                $elements[] = $purchaseReplacementsProductModel->model;
            }
            $models = implode(',', $elements);
        }
        return $models;
    }
}

if (!function_exists('stockLow')) {
    function stockLow($store_id)
    {
//        return  \App\Stock::where('store_id',$store_id)
//            ->whereIn('id', function($query) {
//                $query->from('stocks')->where('current_stock','<=', 10)->groupBy('product_id')->selectRaw('MAX(id)');
//            })->latest('id')->get();

        return  \App\Stock::where('store_id',$store_id)
                            ->whereIn('id', function($query) {
                               $query->from('stocks')->groupBy('product_id')->selectRaw('MAX(id)');
                            })->where('current_stock','<=', 10)->latest('id')->get();

    }
}

if (!function_exists('saleReplacementAlreadySale')) {
    function saleReplacementAlreadySale($product_id,$product_sale_replacement_detail_id)
    {

        return  \Illuminate\Support\Facades\DB::table('product_sale_details')
            //->join('product_sales','product_sales.id','product_sale_details.product_sale_id')
            ->join('product_sale_replacement_details','product_sale_details.id','product_sale_replacement_details.product_sale_detail_id')
            ->where('product_sale_details.product_id',$product_id)
            ->where('product_sale_replacement_details.id',$product_sale_replacement_detail_id)
            ->pluck('product_sale_details.qty')
            ->first();

    }
}

if (!function_exists('currentInvoiceStock')) {
    function currentInvoiceStock($store_id,$product_id,$invoice_no)
    {

        return  \App\InvoiceStock::where('store_id',$store_id)
            ->where('product_id',$product_id)
            ->where('purchase_invoice_no',$invoice_no)
            ->latest()
            ->pluck('current_stock')
            ->first();

    }
}

?>
