    @extends('backend._partial.dashboard')
    <style>
        .requiredCustom{
            font-size: 20px;
            color: red;
            margin-top: 20px;
        }
    </style>
    @section('content')
        <main class="app-content">
            <div class="app-title">
                <div>
                    <h1><i class=""></i> Add Sales Product Replace</h1>
                </div>
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('productSaleReplacement.index') }}" class="btn btn-sm btn-primary col-sm" type="button">All Replacement Sales Product</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Add Sales Product Replace</h3>
                    <div class="tile-body tile-footer">
                        @if(session('response'))
                            <div class="alert alert-success">
                                {{ session('response') }}
                            </div>
                        @endif
                        <form method="post" action="{{ route('productSaleReplacement.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="control-label col-md-3 text-right">Invoice  <small class="requiredCustom">*</small></label>
                                <div class="col-md-5">
                                    <select name="product_sale_id" class="form-control select2" id="sale_invoice_no" required>
                                        <option value="">Select One</option>
                                        @foreach($productSales as $productSale)
                                            <option value="{{$productSale->id}}">{{$productSale->invoice_no}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div id="loadForm"></div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">

                                </label>
                                <div class="col-md-8">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>
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
            $('#sale_invoice_no').change(function(){
                $('#loadForm').html('');
                var sale_id = $(this).val();
                console.log(sale_invoice_no);

                $.ajax({
                    url : "{{ URL('/get-sale-product') }}/" + sale_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data)
                    {
                        console.log(data);
                        $('#loadForm').html(data);
                    },
                    /*error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error get data from ajax');
                    }*/
                    error: function (data) {
                        console.log(data);
                    }
                })
            })


            // ajax
            function replace_qty(row,sel) {
                var current_row = row;
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


