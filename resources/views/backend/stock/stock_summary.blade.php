@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Stock Summary</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
{{--                <li class="breadcrumb-item"><a class="btn btn-warning" href="{{ route('stock.export') }}">Export Data</a></li>--}}
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Stock Summary</h3>
                @if(!empty($stores))
                    @foreach($stores as $store)
                        <div class="col-md-12">
                            <h1 class="text-center">{{$store->name}}</h1>
                        </div>
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="5%">#Id</th>
{{--                                    <th width="10%">Store</th>--}}
                                    <th width="15%">Product Type</th>
                                    <th width="12%">Brand</th>
                                    <th width="12%">Product</th>
{{--                                    <th width="12%">Average Purchase Price</th>--}}
{{--                                    <th width="12%">Average Sale Price</th>--}}
                                    <th width="12%">Current Stock</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $stocks = \App\Stock::where('store_id',$store->id)
                                    ->whereIn('id', function($query) {
                                           $query->from('stocks')->groupBy('product_id')->selectRaw('MAX(id)');
                                        })->latest('id')->get();
                                @endphp
                                @php $i = 0; @endphp
                                @foreach($stocks as $key => $stock)
                                    @php $i++; @endphp
                                    <tr>
{{--                                        <td>{{ $key+1 }}</td>--}}
                                        <td>{{$i }}</td>
                                        <td>{{ $stock->product->product_type}}</td>
                                        <td>{{ $stock->product->product_brand->name}}</td>
                                        <td>{{ $stock->product->name}}</td>
{{--                                        <td>--}}
{{--                                            @php--}}
{{--                                                $purchase_average_price = 0;--}}
{{--                                                $sale_average_price = 0;--}}

{{--                                                $productPurchaseDetails = DB::table('product_purchase_details')--}}
{{--                                                ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')--}}
{{--                                                ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))--}}
{{--                                                ->where('product_purchases.store_id',$store->id)--}}
{{--                                                ->where('product_purchase_details.product_id',$stock->product->id)--}}
{{--                                                ->groupBy('product_id')--}}
{{--                                                ->groupBy('product_category_id')--}}
{{--                                                ->groupBy('product_sub_category_id')--}}
{{--                                                ->groupBy('product_brand_id')--}}
{{--                                                ->get();--}}

{{--                                                if(!empty($productPurchaseDetails)){--}}
{{--                                                    foreach($productPurchaseDetails as $key => $productPurchaseDetail){--}}
{{--                                                        $purchase_average_price = $productPurchaseDetail->sub_total/$productPurchaseDetail->qty;--}}


{{--                                                        // sale--}}
{{--                                                        $productSaleDetails = DB::table('product_sale_details')--}}
{{--                                                            ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))--}}
{{--                                                            ->where('product_id',$productPurchaseDetail->product_id)--}}
{{--                                                            ->where('product_category_id',$productPurchaseDetail->product_category_id)--}}
{{--                                                            ->where('product_sub_category_id',$productPurchaseDetail->product_sub_category_id)--}}
{{--                                                            ->where('product_brand_id',$productPurchaseDetail->product_brand_id)--}}
{{--                                                            ->groupBy('product_id')--}}
{{--                                                            ->groupBy('product_category_id')--}}
{{--                                                            ->groupBy('product_sub_category_id')--}}
{{--                                                            ->groupBy('product_brand_id')--}}
{{--                                                            ->first();--}}

{{--                                                        if(!empty($productSaleDetails))--}}
{{--                                                        {--}}
{{--                                                            $sale_total_qty = $productSaleDetails->qty;--}}
{{--                                                            //$sum_sale_price += $productSaleDetails->sub_total;--}}
{{--                                                            $sale_average_price = $productSaleDetails->sub_total/ (int) $productSaleDetails->qty;--}}
{{--                                                        }--}}
{{--                                                    }--}}
{{--                                                }--}}
{{--                                            @endphp--}}
{{--                                            {{number_format($purchase_average_price, 2, '.', '')}}--}}
{{--                                        </td>--}}
{{--                                        <td>{{number_format($sale_average_price, 2, '.', '')}}</td>--}}
                                        <td>{{ $stock->current_stock}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="tile-footer">
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </main>
@endsection


