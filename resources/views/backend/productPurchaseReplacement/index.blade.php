@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Replacement Purchase Product</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{!! route('productPurchaseReplacement.create') !!}" class="btn btn-sm btn-primary" type="button">Add Product Purchase Replacements</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">

                <h3 class="tile-title">All Replacement Purchase Product</h3>
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5%">SL NO</th>
                            <th>Invoice</th>
                            <th>Model</th>
                            <th>User</th>
{{--                            <th>Store</th>--}}
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($productPurchaseReplacements as $key => $productPurchaseReplacement)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $productPurchaseReplacement->invoice_no}}</td>
                            <td>
                                @php
                                    $str_arr = explode (",", purchaseReplacementsProductModels($productPurchaseReplacement->id));
                                @endphp

                                @foreach($str_arr as $key => $str)
                                    @if(($key == 0 && count($str_arr) > 1))
                                        {{$str}},
                                    @elseif( (($key > 0 && $key < count($str_arr))) && ($key+1 != count($str_arr)))
                                        {{$str}},
                                    @else
                                        {{$str}}
                                    @endif
                                    <br/>
                                @endforeach
                            </td>
                            <td>{{ $productPurchaseReplacement->user->name}}</td>
{{--                            <td>{{ $productSaleReplacement->store->name}}</td>--}}
                            <td>{{ $productPurchaseReplacement->party->name}}</td>
                            <td>{{ $productPurchaseReplacement->created_at}}</td>
                            <td>
                                <a href="{{ route('productPurchaseReplacement.show',$productPurchaseReplacement->id) }}" class="btn btn-sm btn-info float-left" style="margin-left: 5px">Show</a>
                                <a href="{{ route('productPurchaseReplacement.edit',$productPurchaseReplacement->id) }}" class="btn btn-sm btn-primary float-left" style="margin-left: 5px"><i class="fa fa-edit"></i></a>
                                <form method="post" action="{{ route('productPurchaseReplacement.destroy',$productPurchaseReplacement->id) }}" >
                                   @method('DELETE')
                                    @csrf
                                    <button class="btn btn-sm btn-danger" style="margin-left: 5px" type="submit" onclick="return confirm('You Are Sure This Delete !')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
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


