<?php

namespace App\Http\Controllers;

use App\InvoiceStock;
use App\Product;
use App\ProductPurchaseDetail;
use App\Stock;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $barcode = $request->barcode;
        $store_id = $request->store_id;
        $data = array();
        if($barcode){
            $product_check_exists = Product::where('barcode',$barcode)->pluck('id')->first();
            if($product_check_exists){
                if(Cart::count() > 0){
                    if($product_check_exists )
                    $previous_invoice_no = NULL;
                    $qty_sum = 0;
                    $product_ids = [];
                    foreach(Cart::content() as $item){
                        $product_ids[] = $item->id;
                        $previous_invoice_no = $item->options['invoice_no'];
                        $qty_sum += $item->qty;
                    }

                    if (in_array($product_check_exists, $product_ids))
                    {
                        $product_current_invoice_stock_check_exists = InvoiceStock::where('product_id',$product_check_exists)
                            ->where('purchase_invoice_no',$previous_invoice_no)
                            ->where('store_id',$store_id)
                            ->latest()
                            ->first();

                        $check_current_stock = $product_current_invoice_stock_check_exists->current_stock;
                        if(($check_current_stock > 0) && ($qty_sum < $check_current_stock)) {
                            $data['product_check_exists'] = 'Product Found!';

                            $product_purchase_detail_info = ProductPurchaseDetail::where('product_id',$product_check_exists)
                                ->where('qty_stock_status','Available')
                                ->first();
                            $price = $product_purchase_detail_info->mrp_price;
                            $invoice_no = $product_purchase_detail_info->invoice_no;

                            $product = DB::table('products')
                                ->where('barcode', $barcode)
                                ->first();

                            if (!empty($product)) {
                                $data['id'] = $product->id;
                                $data['name'] = $product->name;
                                $data['qty'] = 1;
                                $data['price'] = $price;
                                $data['options']['barcode'] = $barcode;
                                $data['options']['invoice_no'] = $invoice_no;

                                Cart::add($data);
                            }
                        }else{
                            $data['product_check_exists'] = 'No Product Stock Found!';
                        }
                    }else{
                        $product_current_stock_check_exists = Stock::where('product_id',$product_check_exists)->latest()->pluck('current_stock')->first();
                        if($product_current_stock_check_exists > 0){
                            $data['product_check_exists'] = 'Product Found!';
                            $product = DB::table('products')
                                ->where('barcode',$barcode)
                                ->first();

                            if(!empty($product)){
                                $product_purchase_detail_info = ProductPurchaseDetail::where('product_id',$product->id)
                                    ->where('qty_stock_status','Available')
                                    ->first();
                                $price = $product_purchase_detail_info->mrp_price;
                                $invoice_no = $product_purchase_detail_info->invoice_no;

                                $data['id'] = $product->id;
                                $data['name'] = $product->name;
                                $data['qty'] = 1;
                                $data['price'] = $price;
                                $data['options']['barcode'] = $barcode;
                                $data['options']['invoice_no'] = $invoice_no;

                                Cart::add($data);
                            }
                            $data['countCart'] = Cart::count();
                        }else{
                            $data['product_check_exists'] = 'No Product Stock Found!';
                        }
                    }
                }else{
                    $product_current_stock_check_exists = Stock::where('product_id',$product_check_exists)->latest()->pluck('current_stock')->first();
                    if($product_current_stock_check_exists > 0){
                        $data['product_check_exists'] = 'Product Found!';
                        $product = DB::table('products')
                            ->where('barcode',$barcode)
                            ->first();

                        if(!empty($product)){
                            $product_purchase_detail_info = ProductPurchaseDetail::where('product_id',$product->id)
                                ->where('qty_stock_status','Available')
                                ->first();
                            $price = $product_purchase_detail_info->mrp_price;
                            $invoice_no = $product_purchase_detail_info->invoice_no;

                            $data['id'] = $product->id;
                            $data['name'] = $product->name;
                            $data['qty'] = 1;
                            $data['price'] = $price;
                            $data['options']['barcode'] = $barcode;
                            $data['options']['invoice_no'] = $invoice_no;

                            Cart::add($data);
                        }
                        $data['countCart'] = Cart::count();
                    }else{
                        $data['product_check_exists'] = 'No Product Stock Found!';
                    }
                }

            }else{
                $data['product_check_exists'] = 'No Product Found!';
            }

        }
        return response()->json(['success'=> true, 'response'=>$data]);
    }

    public function deleteCartProduct($rowId){
        if($rowId){
            Cart::remove($rowId);
        }
        $info['success'] = true;
        echo json_encode($info);
    }

    public function deleteAllCartProduct(){

        Cart::destroy();
        $info['success'] = true;
        echo json_encode($info);
    }
}
