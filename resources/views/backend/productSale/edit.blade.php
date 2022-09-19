@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Edit Sale Product</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('productSales.index') }}" class="btn btn-sm btn-primary col-sm" type="button">All Sale Product</a>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Edit Sale Product</h3>
                <div class="tile-body tile-footer">
                    @if(session('response'))
                        <div class="alert alert-success">
                            {{ session('response') }}
                        </div>
                    @endif
                    <form method="post" action="{{ route('productSales.update',$productSale->id) }}">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="stock_id" value="{{$stock_id}}">
                        <div class="form-group row" @if(Auth::user()->roles[0]->name == 'User') style="display: none" @endif>
                            <label class="control-label col-md-3 text-right">Store  <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <select name="store_id" id="store_id" class="form-control" required>
                                    <option value="">Select One</option>
                                    @foreach($stores as $store)
                                        <option value="{{$store->id}}" {{$store->id == $productSale->store_id ? 'selected' : ''}}>{{$store->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Party  <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <select name="party_id" id="party_id" class="form-control select2" required>
                                    <option value="">Select One</option>
                                    @foreach($parties as $party)
                                        <option value="{{$party->id}}" {{$party->id == $productSale->party_id ? 'selected' : ''}}>{{$party->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Payment Type  <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <select name="payment_type" id="payment_type" class="form-control" required>
                                    <option value="">Select One</option>
                                    <option value="Cash" {{'Cash' == $transaction->payment_type ? 'selected' : ''}}>Cash</option>
                                    <option value="Cheque" {{'Cheque' == $transaction->payment_type ? 'selected' : ''}}>Cheque</option>
                                </select>
                                <span>&nbsp;</span>
                                <input type="text" name="cheque_number" id="cheque_number" class="form-control" value="{{$transaction->cheque_number}}" placeholder="Cheque Number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Date <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <input type="text" name="date" class="datepicker form-control" value="{{date('Y-m-d')}}">
                            </div>
                        </div>

                        {{--                        <div class="form-group row">--}}
{{--                            <label class="control-label col-md-3 text-right">Delivery Services  <small class="requiredCustom">*</small></label>--}}
{{--                            <div class="col-md-8">--}}
{{--                                <select name="delivery_service" id="delivery_service" class="form-control" >--}}
{{--                                    <option value="">Select One</option>--}}
{{--                                    <option value="Sundorban Kuriar Service" {{'Sundorban Kuriar Service' == $productSale->delivery_service ? 'selected' : ''}}>Sundorban Kuriar Service</option>--}}
{{--                                    <option value="SA Paribahan" {{'SA Paribahan' == $productSale->delivery_service ? 'selected' : ''}}>SA Paribahan</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label class="control-label col-md-3 text-right">Delivery Services Charge <small class="requiredCustom">*</small></label>--}}
{{--                            <div class="col-md-8">--}}
{{--                                <input type="number" class="form-control" name="delivery_service_charge" value="{{$productSale->delivery_service_charge}}" />--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="table-responsive">
                            {{--<input type="button" class="btn btn-primary add " style="margin-left: 804px;" value="Add More Product">--}}
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th style="display: none">Category</th>
                                    <th style="display: none">Sub Category</th>
                                    <th>Brand</th>
                                    <th style="display: none">Unit</th>
                                    <th>Returnable</th>
                                    <th>Purchase Invoice</th>
                                    <th>Stock Qty</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Sub Total</th>
                                </tr>
                                </thead>
                                <tbody class="neworderbody">
                                @foreach($productSaleDetails as $key => $productSaleDetail)
                                    @php
                                        //$current_stock = edited_current_invoice_stock($productSale->store_id,$productSaleDetail->purchase_invoice_no,$productSaleDetail->product_id,$productSale->invoice_no,$productSaleDetail->id);
                                        $current_stock = edited_current_invoice_stock($productSale->store_id,$productSaleDetail->product_id,$productSaleDetail->qty);
                                        //dd($current_stock);
                                    @endphp
                                    <tr>
                                        @php
                                            $current_row = $key+1;
                                        @endphp
                                        <td width="20%">
                                            <select class="form-control product_id select2" name="product_id[]" onchange="getval({{$current_row}},this);" required>
                                                <option value="">Select  Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{$product->id}}" {{$product->id == $productSaleDetail->product_id ? 'selected' : ''}}>{{$product->name}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" class="form-control" name="product_Sale_detail_id[]" value="{{$productSaleDetail->id}}" >
                                        </td>
                                        <td width="12%" style="display: none">
                                            <div id="product_category_id_{{$current_row}}">
                                                <select class="form-control product_category_id" name="product_category_id[]" readonly required>
                                                    <option value="">Select  Category</option>
                                                    @foreach($productCategories as $productCategory)
                                                        <option value="{{$productCategory->id}}" {{$productCategory->id == $productSaleDetail->product_category_id ? 'selected' : ''}}>{{$productCategory->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td width="12%" style="display: none">
                                            <div id="product_sub_category_id_{{$current_row}}">
                                                <select class="form-control product_sub_category_id" name="product_sub_category_id[]" readonly>
                                                    <option value="">Select  Sub Category</option>
                                                    @foreach($productSubCategories as $productSubCategory)
                                                        <option value="{{$productSubCategory->id}}" {{$productSubCategory->id == $productSaleDetail->product_sub_category_id ? 'selected' : ''}}>{{$productSubCategory->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td width="12%">
                                            <div id="product_brand_id_{{$current_row}}">
                                                <select class="form-control product_brand_id" name="product_brand_id[]" readonly required>
                                                    <option value="">Select  Brand</option>
                                                    @foreach($productBrands as $productBrand)
                                                        <option value="{{$productBrand->id}}" {{$productBrand->id == $productSaleDetail->product_brand_id ? 'selected' : ''}}>{{$productBrand->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td style="display: none">
                                            <div id="product_unit_id_{{$current_row}}">
                                                <select class="form-control product_unit_id" name="product_unit_id[]" readonly required>
                                                    <option value="">Select  Unit</option>
                                                    @foreach($productUnits as $productUnit)
                                                        <option value="{{$productUnit->id}}" {{$productUnit->id == $productSaleDetail->product_unit_id ? 'selected' : ''}}>{{$productUnit->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td width="12%">
                                            <select name="return_type[]" id="return_type_id_{{$current_row}}" class="form-control" >
                                                <option value="returnable"  {{'returnable' == $productSaleDetail->return_type ? 'selected' : ''}}>returnable</option>
                                                <option value="not returnable" {{'not returnable' == $productSaleDetail->return_type ? 'selected' : ''}}>not returnable</option>
                                            </select>
                                        </td>
                                        <td>
                                            @php
                                                $purchase_invoice_nos = purchase_invoice_nos($productSale->store_id,$productSaleDetail->product_id)
                                            @endphp
                                            <div id="invoice_no_1">
{{--                                                <select class="form-control invoice_no select2" name="invoice_no[]"  onchange="getInvoiceVal(1,this);" required>--}}
{{--                                                    @foreach($purchase_invoice_nos as $purchase_invoice_no)--}}
{{--                                                        <option value="{{$purchase_invoice_no->invoice_no}}" {{$purchase_invoice_no->invoice_no == $productSaleDetail->purchase_invoice_no ? 'selected' : ''}}>{{$purchase_invoice_no->invoice_no}}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
                                                <input type="text" id="invoice_no_1" class="invoice_no form-control" name="invoice_no[]" value="{{$productSaleDetail->purchase_invoice_no}}" readonly >
                                            </div>
                                        </td>
                                        <td width="10%">
                                            <input type="number" id="stock_qty_1" class="stock_qty form-control" name="stock_qty[]" value="{{$current_stock}}" readonly >
                                        </td>
                                        <td width="8%">
                                            <input type="text" min="1" max="" class="qty form-control" name="qty[]" value="{{$productSaleDetail->qty}}" required >
                                        </td>
                                        <td width="12%">
                                            <input type="text" min="1" max="" class="price form-control" name="price[]" value="{{$productSaleDetail->price}}" required >
                                        </td>
                                        <td width="12%">
                                            <input type="text" class="amount form-control" name="sub_total[]" value="{{$productSaleDetail->sub_total}}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th width="10%">
                                        Type:

                                        @if($productSale->discount_amount > 0)
                                            <input type="text" id="discount_type" class="form-control" name="discount_type" value="{{$productSale->discount_type}}" readonly>
                                        @else
                                            <select name="discount_type" id="discount_type" class="form-control" >
                                                <option value="flat" {{'flat' == $productSale->discount_type ? 'selected' : ''}}>flat</option>
                                                <option value="percentage" {{'percentage' == $productSale->discount_type ? 'selected' : ''}}>percentage</option>
                                            </select>
                                        @endif
                                    </th>
                                    <th>
                                        Discount:
                                        <input type="text" id="discount_amount" class="discount_amount form-control" name="discount_amount" onkeyup="discountAmount('')" value="{{$productSale->discount_amount}}" @if($productSale->discount_amount > 0) readonly @endif>
                                        <input type="hidden" id="discount_percentage" class="form-control" name="discount_percentage" value="{{$productSale->discount_percentage}}">
                                    </th>
                                    <th>
                                        Total:
                                        <input type="hidden" id="store_total_amount" class="form-control" value="{{$productSale->total_amount}}">
                                        <input type="text" id="total_amount" class="form-control" name="total_amount" value="{{$productSale->total_amount}}">
                                    </th>
                                    <th colspan="2">
                                        Paid Amount:
                                        <input type="text" id="paid_amount" class="getmoney form-control" name="paid_amount" onkeyup="paidAmount('')" value="{{$productSale->paid_amount}}">
                                    </th>
                                    <th>
                                        Due Amount:
                                        <input type="text" id="due_amount" class="backmoney form-control" name="due_amount" value="{{$productSale->due_amount}}">
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="form-group row">
                                <label class="control-label col-md-3"></label>
                                <div class="col-md-8">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Product Sales</button>
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

        function totalAmount(){
            var t = 0;
            $('.amount').each(function(i,e){
                var amt = $(this).val()-0;
                t += amt;
            });
            $('#store_total_amount').val(t);
            $('#total_amount').val(t);
        }

        // onkeyup
        function discountAmount(){
            var discount_type = $('#discount_type').val();

            //var total = $('#total_amount').val();
            //console.log('total= ' + total);
            //console.log('total= ' + typeof total);
            //total = parseInt(total);
            //console.log('total= ' + typeof total);

            var store_total_amount = $('#store_total_amount').val();
            console.log('store_total_amount= ' + store_total_amount);
            console.log('store_total_amount= ' + typeof store_total_amount);
            store_total_amount = parseInt(store_total_amount);
            console.log('total= ' + typeof store_total_amount);

            var discount_amount = $('#discount_amount').val();
            console.log('discount_amount= ' + discount_amount);
            console.log('discount_amount= ' + typeof discount_amount);
            discount_amount = parseInt(discount_amount);
            console.log('discount_amount= ' + typeof discount_amount);

            if(discount_type == 'flat'){
                var final_amount = store_total_amount - discount_amount;
            }
            else{
                var per = (store_total_amount*discount_amount)/100;
                var final_amount = store_total_amount - per;
            }
            console.log('final_amount= ' + final_amount);
            console.log('final_amount= ' + typeof final_amount);

            var paid_amount = $('#paid_amount').val();
            console.log('paid_amount= ' + paid_amount);
            console.log('paid_amount= ' + typeof paid_amount);
            paid_amount = parseInt(paid_amount);
            console.log('paid_amount= ' + typeof paid_amount);

            var due_amount = final_amount - paid_amount;

            $('#total_amount').val(final_amount);
            $('#due_amount').val(due_amount);
        }

        // onkeyup
        function paidAmount(){
            console.log('okk');
            var total = $('#total_amount').val();
            console.log('total= ' + total);
            console.log('total= ' + typeof total);

            var paid_amount = $('#paid_amount').val();
            console.log('paid_amount= ' + paid_amount);
            console.log('paid_amount= ' + typeof paid_amount);

            var due = total - paid_amount;
            console.log('due= ' + due);
            console.log('due= ' + typeof due);

            $('.backmoney').val(due);
        }

        $('.add').click(function () {
            var productCategory = $('.product_category_id').html();
            var productSubCategory = $('.product_sub_category_id').html();
            var productBrand = $('.product_brand_id').html();
            var productUnit = $('.product_unit_id').html();
            var product = $('.product_id').html();
            var n = ($('.neworderbody tr').length - 0) + 1;
            var tr = '<tr><td class="no">' + n + '</td>' +
                '<td><select class="form-control product_id select2" name="product_id[]" id="product_id_'+n+'" onchange="getval('+n+',this);" required>' + product + '</select></td>' +
                '<td style="display: none"><div id="product_category_id_'+n+'"><select class="form-control product_category_id select2" name="product_category_id[]" required>' + productCategory + '</select></div></td>' +
                '<td style="display: none"><div id="product_sub_category_id_'+n+'"><select class="form-control product_sub_category_id select2" name="product_sub_category_id[]" required>' + productSubCategory + '</select></div></td>' +
                '<td style="display: none"><div id="product_brand_id_'+n+'"><select class="form-control product_brand_id select2" name="product_brand_id[]" id="product_brand_id_'+n+'" required>' + productBrand + '</select></div></td>' +
                '<td><div id="product_unit_id_'+n+'"><select class="form-control product_unit_id select2" name="product_unit_id[]" id="product_unit_id_'+n+'" required>' + productUnit + '</select></div></td>' +
                '<td><div id="invoice_no_'+n+'"><select class="form-control invoice_no select2" name="invoice_no[]" id="invoice_no_'+n+'" onchange="getInvoiceVal('+n+',this);" required>' + invoiceNo + '</select></div></td>' +
                '<td><input type="number" min="1" max="" class="qty form-control" name="qty[]" required></td>' +
                '<td><input type="text" min="1" max="" class="price form-control" name="price[]" value="" required></td>' +
                //'<td><input type="number" min="0" value="0" max="100" class="dis form-control" name="discount[]" required></td>' +
                '<td><input type="text" class="amount form-control" name="sub_total[]" required></td>' +
                '<td><input type="button" class="btn btn-danger delete" value="x"></td></tr>';

            $('.neworderbody').append(tr);

            //initSelect2();

            $('.select2').select2();

        });

        $('.neworderbody').delegate('.delete', 'click', function () {
            $(this).parent().parent().remove();
            totalAmount();
        });

        $('.neworderbody').delegate('.qty, .price', 'keyup', function () {
            var gr_tot = 0;
            var tr = $(this).parent().parent();
            if(tr.find('.qty').val() && isNaN(tr.find('.qty').val())){
                alert("Must input numbers");
                tr.find('.qty').val('')
                return false;
            }
            var qty = tr.find('.qty').val() - 0;
            var stock_qty = tr.find('.stock_qty').val() - 0;
            if(qty > stock_qty){
                alert('You have limit cross of stock qty!');
                tr.find('.qty').val(0)
            }

            //var dis = tr.find('.dis').val() - 0;
            var price = tr.find('.price').val() - 0;

            //var total = (qty * price) - ((qty * price)/100);
            //var total = (qty * price) - ((qty * price * dis)/100);
            //var total = price - ((price * dis)/100);
            //var total = price - dis;
            var total = (qty * price);

            tr.find('.amount').val(total);
            //Total Price
            $(".amount").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
            });
            var final_total = gr_tot;
            console.log(final_total);
            var discount = $("#discount_amount").val();
            var final_total     = gr_tot - discount;
            //$("#total_amount").val(final_total.toFixed(2,2));
            $("#total_amount").val(final_total);
            var t = $("#total_amount").val(),
                a = $("#paid_amount").val(),
                e = t - a;
            //$("#remaining_amnt").val(e.toFixed(2,2));
            $("#due_amount").val(e);
            totalAmount();
        });

        $('#hideshow').on('click', function(event) {
            $('#content').removeClass('hidden');
            $('#content').addClass('show');
            $('#content').toggle('show');

        });


        // ajax
        function getval(row,sel)
        {
            //alert(row);
            //alert(sel.value);
            var current_row = row;
            var current_product_id = sel.value;

            $.ajax({
                url : "{{URL('product-relation-data')}}",
                method : "get",
                data : {
                    current_product_id : current_product_id
                },
                success : function (res){
                    //console.log(res)
                    console.log(res.data)
                    //console.log(res.data.categoryOptions)
                    $("#product_category_id_"+current_row).html(res.data.categoryOptions);
                    $("#product_sub_category_id_"+current_row).html(res.data.subCategoryOptions);
                    $("#product_brand_id_"+current_row).html(res.data.brandOptions);
                },
                error : function (err){
                    console.log(err)
                }
            })
        }

        function getInvoiceVal(row,sel)
        {
            var store_id = $('#store_id').val();
            var current_product_id = $('#product_id_'+row).val();
            if(store_id){
                // console.log(store_id)
                // console.log(row)
                // console.log(sel.value)

                var current_row = row;
                var current_invoice_no = sel.value;
                if(current_row > 1){
                    var previous_row = current_row - 1;
                    var previous_product_id = $('#product_id_'+previous_row).val();
                    if(previous_product_id === current_product_id){
                        $('#product_id_'+current_row).val('');
                        alert('You selected same product, Please selected another product!');
                        return false
                    }
                }

                $.ajax({
                    url : "{{URL('product-sale-invoice-data')}}",
                    method : "get",
                    data : {
                        store_id : store_id,
                        current_product_id : current_product_id,
                        current_invoice_no : current_invoice_no,
                        current_row : current_row,
                    },
                    success : function (res){
                        //console.log(res)
                        console.log(res.data)
                        $("#stock_qty_"+current_row).val(res.data.current_stock);
                        $("#price_"+current_row).val(res.data.mrp_price);
                    },
                    error : function (err){
                        console.log(err)
                    }
                })
            }else{
                alert('Please select first store!');
                location.reload();
            }
        }

        $(function() {
            <?php
            if($transaction->payment_type == 'Cash'){
            ?>
            $('#cheque_number').hide();
            <?php } ?>
            $('#payment_type').change(function(){
                if($('#payment_type').val() == 'Cheque') {
                    $('#cheque_number').show();
                } else {
                    $('#cheque_number').val('');
                    $('#cheque_number').hide();
                }
            });
        });
    </script>
@endpush


