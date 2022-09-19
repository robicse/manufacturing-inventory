@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Product Purchase Return And Details</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{!! route('productSaleReturns.index') !!}" class="btn btn-sm btn-primary" type="button">Back</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Product Purchase Returns</h3>
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>Invoice NO</th>
                            <td>{{$productPurchaseReturn->invoice_no}}</td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td>{{$productPurchaseReturn->user->name}}</td>
                        </tr>
                        <tr>
                            <th>Store</th>
                            <td>{{$productPurchaseReturn->store->name}}</td>
                        </tr>
                        <tr>
                            <th>Party</th>
                            <td>{{$productPurchaseReturn->party->name}}</td>
                        </tr>
                        <tr>
                            <th>Payment Type</th>
                            <td>
                                @php
                                  $transaction = \Illuminate\Support\Facades\DB::table('transactions')
                                  ->where('invoice_no',$productPurchaseReturn->invoice_no)
                                  ->where('ref_id',$productPurchaseReturn->id)
                                  ->first();
                                @endphp
                                {{$transaction->payment_type}}
                                @if($transaction->payment_type == 'Cheque')
                                    ( Cheque Number: {{$transaction->cheque_number}} )
                                @endif
                            </td>
                        </tr>
{{--                        <tr>--}}
{{--                            <th>Discount Type</th>--}}
{{--                            <td>{{$productPurchaseReturn->discount_type}}</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th>Discount Amount</th>--}}
{{--                            <td>{{$productPurchaseReturn->discount_amount}}</td>--}}
{{--                        </tr>--}}
                        <tr>
                            <th>Amount</th>
                            <td>{{$productPurchaseReturn->total_amount}}</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="tile-footer">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Product Purchase Details</h3>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Category</th>
{{--                        <th>Sub Category</th>--}}
                        <th>Brand</th>
{{--                        <th>Return Condition</th>--}}
                        <th>Reason</th>
                        <th>Product Image</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($productPurchaseReturnDetails as $productPurchaseReturnDetail)
                        <tr>
                            <td>{{$productPurchaseReturnDetail->product->product_category->name}}</td>

                            <td>{{$productPurchaseReturnDetail->product->product_brand->name}}</td>
{{--                            <td>{{$productSaleReturnDetail->return_type}}</td>--}}
                            <td>{{$productPurchaseReturnDetail->reason}}</td>
                            <td>
                                <img src="{{asset('uploads/product/'.$productPurchaseReturnDetail->product->image)}}" width="50" height="50" />
                            </td>
                            <td>{{$productPurchaseReturnDetail->product->name}}</td>
                            <td>{{$productPurchaseReturnDetail->qty}}</td>
                            <td>{{$productPurchaseReturnDetail->price}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="tile-footer">
                </div>
            </div>
        </div>
    </main>
@endsection


