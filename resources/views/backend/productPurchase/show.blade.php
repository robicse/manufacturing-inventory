@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Product Purchases And Details</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{!! route('productPurchases.index') !!}" class="btn btn-sm btn-primary" type="button">Back</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                {{--<ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item" style="margin-left: 90%"> <a href="{!! route('productPurchases-invoice') !!}" class="btn btn-sm btn-primary"  type="button">Download Page</a></li>
                </ul>--}}
                <h3 class="tile-title">Product Purchases</h3>
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>Invoice No</th>
                            <td>{{$productPurchase->invoice_no}}</td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td>{{$productPurchase->user->name}}</td>
                        </tr>
                        <tr>
                            <th>Store</th>
                            <td>{{$productPurchase->store->name}}</td>
                        </tr>
                        <tr>
                            <th>Party</th>
                            <td>{{$productPurchase->party->name}}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{$productPurchase->date}}</td>
                        </tr>
                        @if(!empty($transactions))
                            <tr>
                                <th>Payment Type</th>
                                <th>
                                    <ul>
                                        @foreach($transactions as $transaction)
                                            <li>
                                                {{$transaction->payment_type}}
                                                @if($transaction->payment_type == 'Cheque')
                                                    ( Cheque Number: {{$transaction->cheque_number}} )
                                                @endif
                                                :
                                                Tk.{{number_format($transaction->amount,2)}} ({{$transaction->created_at}})
                                            </li>
                                        @endforeach
                                    </ul>
                                </th>
                            </tr>
                        @endif
{{--                        @if($transaction->payment_type == 'Cheque')--}}
{{--                            <tr>--}}
{{--                                <th>Cheque Number</th>--}}
{{--                                <td>{{$transaction->cheque_number}}</td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
                        @if($productPurchase->discount_amount > 0)
                            <tr>
                                <th>Discount Type</th>
                                <td>{{ucfirst($productPurchase->discount_type)}}</td>
                            </tr>
                            <tr>
                                <th>Discount Amount</th>
                                <td>{{$productPurchase->discount_amount}}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Total Amount</th>
                            <td>{{$productPurchase->total_amount}}</td>
                        </tr>
                        <tr>
                            <th>Paid Amount</th>
                            <td>{{$productPurchase->paid_amount}}</td>
                        </tr>
                        <tr>
                            <th>Due Amount</th>
                            <td>{{$productPurchase->due_amount}}</td>
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
                <h3 class="tile-title">Product Purchases Details</h3>
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Category</th>
{{--                            <th>Sub Category</th>--}}
                            <th>Brand</th>
                            <th>Product Image</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>MRP Price</th>
                            <th>Sub Total</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($productPurchaseDetails as $productPurchaseDetail)
                                <tr>
                                    <td>{{$productPurchaseDetail->product->product_category->name}}</td>
{{--                                    <td>--}}
{{--                                        {{$productPurchaseDetail->product->product_sub_category ? $productPurchaseDetail->product->product_sub_category->name : ''}}--}}
{{--                                    </td>--}}
                                    <td>{{$productPurchaseDetail->product->product_brand->name}}</td>
                                    <td>
                                        <img src="{{asset('uploads/product/'.$productPurchaseDetail->product->image)}}" width="50" height="50" />
                                    </td>
                                    <td>{{$productPurchaseDetail->product->name}}</td>
                                    <td>{{$productPurchaseDetail->qty}}</td>
                                    <td>{{$productPurchaseDetail->price}}</td>
                                    <td>{{$productPurchaseDetail->mrp_price}}</td>
                                    <td>{{$productPurchaseDetail->sub_total}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="tile-footer">
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


