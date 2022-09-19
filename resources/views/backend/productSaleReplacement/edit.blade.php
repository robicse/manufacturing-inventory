@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Edit Sale Replace Product</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('productSaleReplacement.index') }}" class="btn btn-sm btn-primary col-sm" type="button">All Sale Product</a>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Edit Sale Replace Product</h3>
                <div class="tile-body tile-footer">
                    @if(session('response'))
                        <div class="alert alert-success">
                            {{ session('response') }}
                        </div>
                    @endif
                    <form method="post" action="{{ route('productSaleReplacement.update',$productSaleReplacement->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group row" @if(Auth::user()->roles[0]->name == 'User') style="display: none" @endif>
                            <label class="control-label col-md-3 text-right">Store  <small class="requiredCustom">*</small></label>
                            <input type="hidden" name="purchase_Sale_replacement_id" value="{{$productSaleReplacement->id}}">
                            <div class="col-md-8">
                                <select name="store_id" id="store_id" class="form-control" disabled>
                                    <option value="">Select One</option>
                                    @foreach($stores as $store)
                                        <option value="{{$store->id}}" {{$store->id == $productSaleReplacement->store_id ? 'selected' : ''}}>{{$store->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Party  <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <select name="party_id" id="party_id" class="form-control select2" disabled>
                                    <option value="">Select One</option>
                                    @foreach($parties as $party)
                                        <option value="{{$party->id}}" {{$party->id == $productSaleReplacement->party_id ? 'selected' : ''}}>{{$party->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
{{--                        <div class="form-group row">--}}
{{--                            <label class="control-label col-md-3 text-right">Date <small class="requiredCustom">*</small></label>--}}
{{--                            <div class="col-md-8">--}}
{{--                                <input type="text" name="date" class="datepicker form-control" value="{{date('Y-m-d')}}">--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Product QTY</th>
                                    <th>Already Return Quantity</th>
                                    <th>Already Replace Quantity</th>
                                    <th>Purchase Invoice</th>
                                    <th>Replace pQty</th>
                                    <th>Reason</th>
                                </tr>
                                </thead>
                                <tbody class="neworderbody">
                                @foreach($productSaleReplacementDetails as $key => $productSaleReplacementDetail)
                                    <tr>
                                        @php
                                            $sale_qty = saleReplacementAlreadySale($productSaleReplacementDetail->product_id,$productSaleReplacementDetail->id);
                                            //dd($sale_qty);
                                        @endphp
                                        @php
                                            $current_row = $key+1;
                                        @endphp
                                        <td width="12%">
                                            {{$productSaleReplacementDetail->product->name}}
                                            @php
                                                $productSale = \App\ProductSale::where('id',$productSaleReplacement->product_sale_id)->first();

                                                $check_sale_return_qty = check_sale_return_qty($productSale->store_id,$productSaleReplacementDetail->product_id,$productSale->invoice_no);
                                                //dd($check_sale_return_qty);
                                                $check_sale_replace_qty = check_sale_replace_qty($productSale->store_id,$productSaleReplacementDetail->product_id,$productSale->invoice_no);


                                            @endphp
                                            <input type="hidden" class="form-control" name="product_id[]" value="{{$productSaleReplacementDetail->product_id}}" >
                                            <input type="hidden" class="form-control" name="product_Sale_replacement_detail_id[]" value="{{$productSaleReplacementDetail->id}}" >
                                        </td>
                                        <td width="8%">
                                            {{$sale_qty}}
                                            <input type="hidden" class="form-control" name="qty[]" id="qty_{{$current_row}}" value="{{$sale_qty - $check_sale_return_qty}}" />
                                        </td>
                                        <td width="8%">{{$check_sale_return_qty}}</td>
                                        <td width="8%">{{$check_sale_replace_qty}}</td>
{{--                                        <td width="8%">--}}
{{--                                            @php--}}
{{--                                              //$purchase_invoice_lists = purchase_invoice_lists($productSaleReplacementDetail->product_id);--}}
{{--                                            @endphp--}}
{{--                                            <select name="purchase_invoice_list[]" id="purchase_invoice_list_{{$current_row}}" class="form-control select2" disabled >--}}
{{--                                                <option value="">Select One</option>--}}
{{--                                                @foreach($purchase_invoice_lists as $purchase_invoice_list)--}}
                                                    @php
                                                        $current_stock = currentInvoiceStock($productSale->store_id,$productSaleReplacementDetail->product_id,$productSaleReplacementDetail->purchase_invoice_no);
                                                    @endphp
{{--                                                    <option value="{{$purchase_invoice_list->invoice_no .'=>'. $current_stock}}" {{$productSaleReplacementDetail->purchase_invoice_no == $purchase_invoice_list->invoice_no ? "selected" : ""}}> {{$purchase_invoice_list->invoice_no."=>".$current_stock}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </td>--}}
                                        <td width="8%">
                                            <input type="text" class="form-control" name="purchase_invoice_list[]" id="purchase_invoice_list_{{$current_row}}" value="{{$productSaleReplacementDetail->purchase_invoice_no."=>".$current_stock}}" readonly>
                                        </td>
                                        <td width="8%">
                                            <input type="text" min="1" max="" class="form-control" name="replace_qty[]" onkeyup="replace_qty({{$current_row}},this);" value="{{$productSaleReplacementDetail->replace_qty}}" required >
                                        </td>
                                        <td width="8%">
                                            <textarea rows="3" class="form-control" name="reason[]">{{$productSaleReplacementDetail->reason}}</textarea>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                            <div class="form-group row">
                                <label class="control-label col-md-3"></label>
                                <div class="col-md-8">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Product Sales Replace</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tile-footer">
                </div>
            </div>
        </div>
    </main>
@endsection

@push('js')
    <script>



            // ajax
            function replace_qty(row,sel) {
                var current_row = row;
                console.log(current_row)
                var current_replace_qty = sel.value;
                //console.log(current_row);
                //console.log(current_product_id);
                //var current_product_id = $('#product_id_'+current_row).val();
                var check_sale_return_qty = $('#check_sale_return_qty_'+current_row).val();
                //console.log('check_sale_return_qty = ' + check_sale_return_qty);
                //console.log('check_sale_return_qty= ' + typeof check_sale_return_qty);
                var check_sale_replace_qty = $('#check_sale_replace_qty_'+current_row).val();
                //console.log('check_sale_replace_qty = ' + check_sale_replace_qty);
                //console.log('check_sale_replace_qty= ' + typeof check_sale_replace_qty);
                var current_sale_qty = $('#qty_'+current_row).val();
                var current_purchase_invoice_no = $('#purchase_invoice_list_'+current_row).val();
                var split_last_purchase_stock_qty = current_purchase_invoice_no.split("=>");
                var current_last_purchase_stock_qty = parseInt(split_last_purchase_stock_qty[1]);
                //alert(last[1]);


                if(check_sale_return_qty > 0){
                    current_sale_qty -= check_sale_return_qty
                }
                if(check_sale_replace_qty > 0){
                    current_sale_qty -= check_sale_replace_qty
                }
                console.log('current_replace_qty = ' + typeof current_replace_qty);
                console.log('current_sale_qty = ' + typeof current_sale_qty);
                console.log('current_last_purchase_stock_qty = ' + typeof current_last_purchase_stock_qty);

                if(current_replace_qty > current_last_purchase_stock_qty){
                    alert('You have limit cross of current purchase invoice stock qty, please select another invoice!');
                    $('#replace_qty_'+current_row).val(0);
                }
                if(current_replace_qty > current_sale_qty){
                    alert('You have limit cross of stock qty!');
                    $('#replace_qty_'+current_row).val(0);
                }

            }


    </script>
@endpush


