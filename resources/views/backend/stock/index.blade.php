@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Stock</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a class="btn btn-warning" href="{{ route('stock.export') }}">Export Data</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Stock Table</h3>

                <form method="get" action="" class="form-inline">
                    {{--@csrf--}}
                    <div class="form-group" style="margin-left: 5px">
                        <select class="form-control select2" name="product_id">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{$product->id}}" {{$product_id == $product->id ? 'selected' : ''}}>{{$product->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-left: 5px">
                        <select class="form-control select2" name="stock_product_type">
                            <option value="">Select Stock Product Type</option>
                            <option value="Finish Goods" {{$stock_product_type == 'Finish Goods' ? 'selected' : ''}}>Finish Goods</option>
                            <option value="Raw Materials" {{$stock_product_type == 'Raw Materials' ? 'selected' : ''}}>Raw Materials</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-left: 5px">
                        <select class="form-control select2" name="stock_type">
                            <option value="">Select Stock Type</option>
                            <option value="purchase" {{$stock_type == 'purchase' ? 'selected' : ''}}>Purchase</option>
                            <option value="purchase return" {{$stock_type == 'purchase return' ? 'selected' : ''}}>Purchase Return</option>
                            <option value="purchase replace" {{$stock_type == 'purchase replace' ? 'selected' : ''}}>Purchase Replace</option>
                            <option value="production" {{$stock_type == 'production' ? 'selected' : ''}}>Production</option>
                            <option value="sale" {{$stock_type == 'sale' ? 'selected' : ''}}>Sale</option>
                            <option value="sale return" {{$stock_type == 'sale return' ? 'selected' : ''}}>Sale Return</option>
                            <option value="replace" {{$stock_type == 'replace' ? 'selected' : ''}}>Sale Replace</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-left: 5px">
                        <button class="btn btn-sm btn-primary float-left p-2">Advanced Search</button><span>&nbsp;</span>
                        <a href="{{ route('stock.index') }}" class="btn btn-sm btn-info float-right p-2" role="button">Reset</a>
                    </div>
                </form>
                <br/>

                @if(!empty($stores))
                    @foreach($stores as $store)
{{--                        <div class="col-md-12">--}}
{{--                            <h1 class="text-center">--}}
{{--                                {{$store->name}}--}}
{{--                                <a href="{{ route('stock_sync') }}" class="btn btn-sm btn-success float-right p-2" role="button">Stock Synchronize</a>--}}
{{--                            </h1>--}}
{{--                        </div>--}}
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>SL NO</th>
                                    <th>Product Type</th>
                                    <th>Brand</th>
                                    <th>Product</th>
                                    <th>Party</th>
                                    <th>Stock Type</th>
                                    <th>Previous Stock</th>
                                    <th>Stock In</th>
                                    <th>Stock Out</th>
                                    <th>Current Stock</th>
                                    <th style="width: 15%">Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    //$stocks = \App\Stock::where('store_id',$store->id)->latest()->get();

                                    if($stock_product_type && $stock_type && $product_id)
                                    {
                                        $stocks = \App\Stock::where('store_id',$store->id)
                                        ->where('stock_product_type',$stock_product_type)
                                        ->where('stock_type',$stock_type)
                                        ->where('product_id',$product_id)
                                        ->latest()
                                        ->get();
                                    }
                                    else if($stock_product_type && $stock_type)
                                    {
                                        $stocks = \App\Stock::where('store_id',$store->id)
                                        ->where('stock_product_type',$stock_product_type)
                                        ->where('stock_type',$stock_type)
                                        ->latest()
                                        ->get();
                                    }
                                    else if($stock_product_type && $product_id)
                                    {
                                        $stocks = \App\Stock::where('store_id',$store->id)
                                        ->where('stock_product_type',$stock_product_type)
                                        ->where('product_id',$product_id)
                                        ->latest()
                                        ->get();
                                    }
                                    else if($stock_type && $product_id)
                                    {
                                        $stocks = \App\Stock::where('store_id',$store->id)
                                        ->where('stock_type',$stock_type)
                                        ->where('product_id',$product_id)
                                        ->latest()
                                        ->get();
                                    }
                                    else if($stock_product_type)
                                    {
                                        $stocks = \App\Stock::where('store_id',$store->id)
                                        ->where('stock_product_type',$stock_product_type)
                                        ->latest()
                                        ->get();
                                    }
                                    else if($stock_type)
                                    {
                                        $stocks = \App\Stock::where('store_id',$store->id)
                                        ->where('stock_type',$stock_type)
                                        ->latest()
                                        ->get();
                                    }
                                    else if($product_id)
                                    {
                                        $stocks = \App\Stock::where('store_id',$store->id)
                                        ->where('product_id',$product_id)
                                        ->latest()
                                        ->get();
                                    }else{
                                        $stocks = \App\Stock::where('store_id',$store->id)
                                        ->latest('id')
                                        ->get();
                                        //dd($stocks);
                                    }
                                @endphp
                                @php $i = 0; @endphp
                                @foreach($stocks as $key => $stock)
                                    @php $i++; @endphp
                                    <tr>
{{--                                        <td>{{ $key+1 }}</td>--}}
{{--                                        <td>{{ $stock->id }}</td>--}}
                                        <td>{{$i }}</td>
                                        <td>{{ $stock->product->product_type}}</td>
                                        <td>{{ $stock->product->product_brand->name}}</td>
                                        <td>{{ $stock->product->name}}</td>
                                        <td>
                                            @php
                                                if($stock->stock_type == 'purchase'){
                                                    echo $party_name = DB::table('stocks')
                                                    ->join('product_purchases','stocks.ref_id','product_purchases.id')
                                                    ->join('parties','product_purchases.party_id','parties.id')
                                                    ->where('stocks.id',$stock->id)
                                                    ->pluck('parties.name')
                                                    ->first();
                                                }elseif($stock->stock_type == 'purchase return'){
                                                    echo $party_name = DB::table('stocks')
                                                    ->join('product_purchase_returns','stocks.ref_id','product_purchase_returns.id')
                                                    ->join('parties','product_purchase_returns.party_id','parties.id')
                                                    ->where('stocks.id',$stock->id)
                                                    ->pluck('parties.name')
                                                    ->first();
                                                }elseif($stock->stock_type == 'purchase replace'){
                                                    echo $party_name = DB::table('stocks')
                                                    ->join('product_purchase_replacements','stocks.ref_id','product_purchase_replacements.id')
                                                    ->join('parties','product_purchase_replacements.party_id','parties.id')
                                                    ->where('stocks.id',$stock->id)
                                                    ->pluck('parties.name')
                                                    ->first();
                                                }elseif($stock->stock_type == 'sale'){
                                                    echo $party_name = DB::table('stocks')
                                                    ->join('product_sales','stocks.ref_id','product_sales.id')
                                                    ->join('parties','product_sales.party_id','parties.id')
                                                    ->where('stocks.id',$stock->id)
                                                    ->pluck('parties.name')
                                                    ->first();
                                                }elseif($stock->stock_type == 'sale return'){
                                                    echo $party_name = DB::table('stocks')
                                                    ->join('product_sale_returns','stocks.ref_id','product_sale_returns.id')
                                                    ->join('parties','product_sale_returns.party_id','parties.id')
                                                    ->where('stocks.id',$stock->id)
                                                    ->pluck('parties.name')
                                                    ->first();
                                                }elseif($stock->stock_type == 'replace'){
                                                    echo $party_name = DB::table('stocks')
                                                    ->join('product_sale_replacements','stocks.ref_id','product_sale_replacements.id')
                                                    ->join('parties','product_sale_replacements.party_id','parties.id')
                                                    ->where('stocks.id',$stock->id)
                                                    ->pluck('parties.name')
                                                    ->first();
                                                }else{
                                                    echo $party_name = DB::table('parties')
                                                    ->where('type','own')
                                                    ->pluck('name')
                                                    ->first();
                                                }
                                            @endphp
                                        </td>
                                        <td>
                                            @if($stock->product->product_type == 'Raw Materials' && $stock->stock_type == 'production')
                                                {{ $stock->stock_type}} =>
                                                <?php
                                                echo $finish_good_product = DB::table('products')
                                                    ->join('product_purchase_details','product_purchase_details.product_id','products.id')
                                                    ->where('product_purchase_details.ref_id',$stock->ref_id)
                                                    ->pluck('products.name')
                                                    ->first();
                                                ?>
                                            @else
                                                {{ ucfirst($stock->stock_type)}}
                                            @endif
                                        </td>
                                        <td>{{ $stock->previous_stock}}</td>
                                        <td>{{ $stock->stock_in}}</td>
                                        <td>{{ $stock->stock_out}}</td>
                                        <td>{{ $stock->current_stock}}</td>
                                        <td>{{ $stock->created_at}}</td>
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


