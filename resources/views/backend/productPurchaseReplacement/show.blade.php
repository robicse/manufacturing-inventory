@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Product Purchase Replacement And Details</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{!! route('productPurchaseReplacement.index') !!}" class="btn btn-sm btn-primary" type="button">Back</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <ul class="app-breadcrumb breadcrumb">
{{--                    <li class="breadcrumb-item" style="margin-left: 88%"> <a href="{!! route('productSaleReplacement-invoice',$productSaleReplacement->id) !!}" class="btn btn-sm btn-primary"  type="button">Print Invoice Page</a></li>--}}
                </ul>
                <h3 class="tile-title">Product Purchase Replace</h3>
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>User</th>
                            <td>{{$productPurchaseReplacement->user->name}}</td>
                        </tr>
                        <tr>
                            <th>Store</th>
                            <td>{{$productPurchaseReplacement->store->name}}</td>
                        </tr>
                        <tr>
                            <th>Party</th>
                            <td>{{$productPurchaseReplacement->party->name}}</td>
                        </tr>

                        <tr>
                            <th>Date</th>
                            <td>{{$productPurchaseReplacement->date}}</td>
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
                <h3 class="tile-title">Product Purchase Replace Details</h3>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Category</th>
{{--                        <th>Sub Category</th>--}}
                        <th>Brand</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Reason</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($productPurchaseReplacementDetails as $productPurchaseReplacementDetail)
                        <tr>
                            <td>{{$productPurchaseReplacementDetail->product->product_category->name}}</td>
{{--                            <td>--}}
{{--                                {{$productSaleReplacementDetail->product->product_sub_category ? $productSaleReplacementDetail->product->product_sub_category->name : ''}}--}}
{{--                            </td>--}}
                            <td>{{$productPurchaseReplacementDetail->product->product_brand->name}}</td>
                            <td>
                                <img src="{{asset('uploads/product/'.$productPurchaseReplacementDetail->product->image)}}" width="50" height="50" />
                            </td>
                            <td>{{$productPurchaseReplacementDetail->product->name}}</td>
                            <td>{{$productPurchaseReplacementDetail->replace_qty}}</td>
                            <td>{{$productPurchaseReplacementDetail->reason}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection


