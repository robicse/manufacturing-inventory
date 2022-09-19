@extends('backend._partial.dashboard')
<style>
    .requiredCustom{
        font-size: 20px;
        color: red;
    }
</style>

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Add Purchases Product</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('productPurchaseRawMaterials.index') }}" class="btn btn-sm btn-primary col-sm" type="button">All Purchases Product</a>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Add Purchases Product</h3>
                <div class="tile-body tile-footer">
                    @if(session('response'))
                        <div class="alert alert-success">
                            {{ session('response') }}
                        </div>
                    @endif
                    <form method="post" action="{{ route('productPurchaseRawMaterials.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Party  <small class="requiredCustom">*</small></label>
                            <div class="col-md-5">
                                <select name="party_id" id="supplier" class="form-control select2" required>
                                    <option value="">Select One</option>
                                    @foreach($parties as $party)
                                        <option value="{{$party->id}}">{{$party->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3"><a type="button" class="test btn btn-primary btn-sm" onclick="modal_supplier()" data-toggle="modal"><i class="fa fa-plus"></i></a></div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Store  <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <select name="store_id" id="store_id" class="form-control" required>
{{--                                    <option value="">Select One</option>--}}
                                    @foreach($stores as $store)
                                        <option value="{{$store->id}}">{{$store->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Payment Type  <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <select name="payment_type" id="payment_type" class="form-control" required>
                                    <option value="Cash" selected>Cash</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                                <span>&nbsp;</span>
                                <input type="text" name="cheque_number" id="cheque_number" class="form-control" placeholder="Cheque Number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Date  <small class="requiredCustom">*</small></label>
                            <div class="col-md-8">
                                <input type="text" name="date" class="datepicker form-control" value="{{date('Y-m-d')}}">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <input type="button" class="btn btn-primary add " style="margin-left: 804px;" value="Add More Product">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th >ID</th>
                                    <th>Product <small class="requiredCustom">*</small></th>
                                    <th style="display: none">Category</th>
{{--                                    <th>Sub Category</th>--}}
                                    <th>Brand</th>
                                    <th>Qty <small class="requiredCustom">*</small></th>
                                    <th>Price <small class="requiredCustom">*</small></th>
                                    <th>MRP Price <small class="requiredCustom">*</small></th>
                                    <th>Sub Total</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody class="neworderbody">
                                <tr>
                                    <td width="5%" class="no">1</td>
                                    <td width="28%">
                                        <select class="form-control product_id select2" name="product_id[]" id="product_id_1" onchange="getval(1,this);" required>
                                            <option value="">Select  Product</option>
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}">{{$product->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="display: none">
                                        <div id="product_category_id_1">
                                            <select class="form-control product_category_id select2" name="product_category_id[]"  required>
                                                <option value="">Select  Category</option>
                                                @foreach($productCategories as $productCategory)
                                                    <option value="{{$productCategory->id}}">{{$productCategory->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
{{--                                    <td width="12%">--}}
{{--                                        <div id="product_sub_category_id_1">--}}
{{--                                            <select class="form-control product_sub_category_id select2" name="product_sub_category_id[]">--}}
{{--                                                <option value="">Select  Sub Category</option>--}}
{{--                                                @foreach($productSubCategories as $productSubCategory)--}}
{{--                                                    <option value="{{$productSubCategory->id}}">{{$productSubCategory->name}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
                                    <td width="12%">
                                        <div id="product_brand_id_1">
                                            <select class="form-control product_brand_id select2" name="product_brand_id[]" required>
                                                <option value="">Select  Brand</option>
                                                @foreach($productBrands as $productBrand)
                                                    <option value="{{$productBrand->id}}">{{$productBrand->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td width="12%">
                                        <input type="number" min="1" max="" class="qty form-control" name="qty[]" value="" required >
                                    </td>
                                    <td width="12%">
                                        <input type="number" min="1" max="" class="price form-control" name="price[]" value="" required >
                                    </td>
                                    <td width="12%">
                                        <input type="number" min="1" max="" class="form-control" name="mrp_price[]" value="" required >
                                    </td>
                                    <td width="15%">
                                        <input type="text" class="amount form-control" name="sub_total[]">
                                    </td>
                                </tr>

                                </tbody>

                                <tfoot>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>
                                        Type:
                                        <select name="discount_type" id="discount_type" class="form-control" >
                                            <option value="">Select</option>
                                            <option value="flat">Flat</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                    </th>
                                    <th>
                                        Discount:
                                        <input type="text" id="discount_amount" class="form-control" name="discount_amount" onkeyup="discountAmount('')" value="0">
                                        <input type="hidden" id="discount_percentage" class="form-control" name="discount_percentage" >
                                    </th>
                                    <th>
                                        Total:
                                        <input type="hidden" id="store_total_amount" class="form-control">
                                        <input type="text" id="total_amount" class="form-control" name="total_amount" readonly>
                                    </th>
                                    <th colspan="2">
                                        Paid Amount:
                                        <input type="text" id="paid_amount" class="getmoney form-control" onkeyup="paidAmount('')" name="paid_amount" value="0">
                                    </th>
                                    <th colspan="2">
                                        Due Amount:
                                        <input type="text" id="due_amount" class="backmoney form-control" name="due_amount">
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="form-group row">
                                <label class="control-label col-md-3"></label>
                                <div class="col-md-8">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save Product Purchases</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tile-footer">
                </div>
            </div>
        </div>

        <!-- Credit Sale -->
        <div id="supplier_modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:green; color: white">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="customerErrr3" class="alert hide"> </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="panel panel-bd lobidrag">
                                    <div class="panel-body">
                                        <form action="{{ route('parties.supplier.store.new') }}" id="supplier_insert" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                                            @csrf
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 text-right">Name <small class="requiredCustom">*</small></label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="hidden" name="type" value="supplier">
                                                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" placeholder="Supplier Name" name="name">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 text-right">Phone <small class="requiredCustom">*</small></label>
                                                <div class="col-md-8">
                                                    <input class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" type="text" placeholder="Supplier Phone" name="phone">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 text-right">Email <small class="requiredCustom">*</small></label>
                                                <div class="col-md-8">
                                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" placeholder="Supplier Email" name="email">
                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 text-right">Address <small class="requiredCustom">*</small></label>
                                                <div class="col-md-8">
                                                    <textarea rows="5" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" type="text" placeholder="Supplier Address" name="address"></textarea>
                                                    @if ($errors->has('address'))
                                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3"></label>
                                                <div class="col-md-8">
                                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save Party</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
            $('.total').html(t);
        }
        $('#discount_type').on('change', function (){
            //alert();
            var discount_type = $('#discount_type').val();

            var store_total_amount = $('#store_total_amount').val();
            console.log('store_total_amount= ' + store_total_amount);
            console.log('store_total_amount= ' + typeof store_total_amount);
            store_total_amount = parseInt(store_total_amount);
            console.log('total= ' + typeof store_total_amount);

            var paid_amount = $('#paid_amount').val();
            console.log('paid_amount= ' + paid_amount);
            console.log('paid_amount= ' + typeof paid_amount);
            paid_amount = parseInt(paid_amount);
            console.log('paid_amount= ' + typeof paid_amount);

            var discount_amount = $('#discount_amount').val();
            console.log('discount_amount= ' + discount_amount);
            console.log('discount_amount= ' + typeof discount_amount);
            discount_amount = parseInt(discount_amount);
            console.log('discount_amount= ' + typeof discount_amount);
            //alert(discount_type);

            if(discount_type == 'flat'){
                var final_amount = store_total_amount - discount_amount;
                var per = 0;
                var last_final_amount = final_amount - paid_amount;
            }
            else{
                var per = (store_total_amount*discount_amount)/100;
                var final_amount = store_total_amount - per;
                var last_final_amount = final_amount - paid_amount;
            }
            console.log('final_amount= ' + final_amount);
            console.log('final_amount= ' + typeof final_amount);

            // alert(final_amount);
            $('#total_amount').val(final_amount);
            $('#due_amount').val(last_final_amount);
            $('#discount_percentage').val(per);
        });
        function discountAmount(){
            var discount_type = $('#discount_type').val();

            if(discount_type == 0){
                alert('You first need to change discount type (flat/percentage)!');
                $('#discount_amount').val(0);
            }

            var store_total_amount = $('#store_total_amount').val();
            console.log('store_total_amount= ' + store_total_amount);
            console.log('store_total_amount= ' + typeof store_total_amount);
            store_total_amount = parseInt(store_total_amount);
            console.log('total= ' + typeof store_total_amount);

            var paid_amount = $('#paid_amount').val();
            console.log('paid_amount= ' + paid_amount);
            console.log('paid_amount= ' + typeof paid_amount);
            paid_amount = parseInt(paid_amount);
            console.log('paid_amount= ' + typeof paid_amount);

            var discount_amount = $('#discount_amount').val();
            console.log('discount_amount= ' + discount_amount);
            console.log('discount_amount= ' + typeof discount_amount);
            discount_amount = parseInt(discount_amount);
            console.log('discount_amount= ' + typeof discount_amount);

            if(discount_type == 'flat'){
                var final_amount = store_total_amount - discount_amount;
                var per = 0;
                var last_final_amount = final_amount - paid_amount;
            }
            else{
                var per = (store_total_amount*discount_amount)/100;
                var final_amount = store_total_amount - per;
                var last_final_amount = final_amount - paid_amount;
            }
            console.log('final_amount= ' + final_amount);
            console.log('final_amount= ' + typeof final_amount);
            // alert(final_amount)
            $('#total_amount').val(final_amount);
            $('#due_amount').val(last_final_amount);
            $('#discount_percentage').val(per);
        }
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
        $(function () {
            // $('.getmoney').change(function(){
            //     var total = $('.total').html();
            //     var getmoney = $(this).val();
            //     //var t = getmoney - total;
            //     var t = total - getmoney;
            //     var t_final_val = t.toFixed(2);
            //     $('.backmoney').val(t_final_val);
            //     $('.total').val(total);
            // });
            $('.add').click(function () {
                var productCategory = $('.product_category_id').html();
                var productSubCategory = $('.product_sub_category_id').html();
                var productBrand = $('.product_brand_id').html();
                var product = $('.product_id').html();
                var n = ($('.neworderbody tr').length - 0) + 1;
                var tr = '<tr><td class="no">' + n + '</td>' +
                    '<td><select class="form-control product_id select2" name="product_id[]" id="product_id_'+n+'" onchange="getval('+n+',this);" required>' + product + '</select></td>' +
                    '<td style="display: none"><div id="product_category_id_'+n+'"><select class="form-control product_category_id select2" name="product_category_id[]" required>' + productCategory + '</select></div></td>' +
                    // '<td><div id="product_sub_category_id_'+n+'"><select class="form-control product_sub_category_id select2" name="product_sub_category_id[]" required>' + productSubCategory + '</select></div></td>' +
                    '<td><div id="product_brand_id_'+n+'"><select class="form-control product_brand_id select2" name="product_brand_id[]" id="product_brand_id_'+n+'" required>' + productBrand + '</select></div></td>' +
                    '<td><input type="text" min="1" max="" class="qty form-control" name="qty[]" required></td>' +
                    '<td><input type="text" min="1" max="" class="price form-control" name="price[]" value="" required></td>' +
                    '<td><input type="text" min="1" max="" class="form-control" name="mrp_price[]" value="" required></td>' +
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



        });
        // $(function () {
        //     $('.getmoney').change(function(){
        //         var total = $('.total').html();
        //         var getmoney = $(this).val();
        //         //var t = getmoney - total;
        //         var t = total - getmoney;
        //         var t_final_val = t.toFixed(2);
        //         $('.backmoney').val(t_final_val);
        //         $('.total').val(total);
        //     });
        //     $('.add').click(function () {
        //         var productCategory = $('.product_category_id').html();
        //         var productSubCategory = $('.product_sub_category_id').html();
        //         var productBrand = $('.product_brand_id').html();
        //         var product = $('.product_id').html();
        //         var n = ($('.neworderbody tr').length - 0) + 1;
        //         var tr = '<tr><td class="no">' + n + '</td>' +
        //             '<td><select class="form-control product_id select2" name="product_id[]" id="product_id_'+n+'" onchange="getval('+n+',this);" required>' + product + '</select></td>' +
        //             '<td style="display: none"><div id="product_category_id_'+n+'"><select class="form-control product_category_id select2" name="product_category_id[]" required>' + productCategory + '</select></div></td>' +
        //             // '<td><div id="product_sub_category_id_'+n+'"><select class="form-control product_sub_category_id select2" name="product_sub_category_id[]" required>' + productSubCategory + '</select></div></td>' +
        //             '<td><div id="product_brand_id_'+n+'"><select class="form-control product_brand_id select2" name="product_brand_id[]" id="product_brand_id_'+n+'" required>' + productBrand + '</select></div></td>' +
        //             '<td><input type="number" min="1" max="" class="qty form-control" name="qty[]" required></td>' +
        //             '<td><input type="text" min="1" max="" class="price form-control" name="price[]" value="" required></td>' +
        //             //'<td><input type="number" min="0" value="0" max="100" class="dis form-control" name="discount[]" required></td>' +
        //             '<td><input type="text" class="amount form-control" name="sub_total[]" required></td>' +
        //             '<td><input type="button" class="btn btn-danger delete" value="x"></td></tr>';
        //
        //         $('.neworderbody').append(tr);
        //
        //         //initSelect2();
        //
        //         $('.select2').select2();
        //
        //     });
        //     $('.neworderbody').delegate('.delete', 'click', function () {
        //         $(this).parent().parent().remove();
        //         totalAmount();
        //     });
        //
        //     $('.neworderbody').delegate('.qty, .price', 'keyup', function () {
        //         var tr = $(this).parent().parent();
        //         var qty = tr.find('.qty').val() - 0;
        //         //var dis = tr.find('.dis').val() - 0;
        //         var price = tr.find('.price').val() - 0;
        //
        //         //var total = (qty * price) - ((qty * price)/100);
        //         //var total = (qty * price) - ((qty * price * dis)/100);
        //         //var total = price - ((price * dis)/100);
        //         //var total = price - dis;
        //         var total = (qty * price);
        //
        //         // tr.find('.amount').val(total);
        //         //Total Price
        //         // $(".amount").each(function() {
        //         //     isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        //         // });
        //         // var final_total = gr_tot;
        //         // console.log(final_total);
        //         // var discount = $("#discount_amount").val();
        //         // var final_total     = gr_tot - discount;
        //
        //         tr.find('.amount').val(total);
        //
        //         var t = $("#total_amount").val(),
        //             a = $("#paid_amount").val(),
        //             e = t - a;
        //         $("#due_amount").val(e);
        //
        //         totalAmount();
        //     });
        //
        //     $('#hideshow').on('click', function(event) {
        //         $('#content').removeClass('hidden');
        //         $('#content').addClass('show');
        //         $('#content').toggle('show');
        //     });
        //
        //
        //
        // });


        // ajax
        function getval(row,sel)
        {
            //alert(row);
            //alert(sel.value);
            var current_row = row;
            var current_product_id = sel.value;
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


        function modal_supplier(){
            $('#supplier_modal').modal('show');
        }

        //new Supplier insert
        $("#supplier_insert").submit(function(e){
            e.preventDefault();
            //var customerMess    = $("#customerMess3");
            //var customerErrr    = $("#customerErrr3");
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                dataType: 'json',
                data: $(this).serialize(),
                beforeSend: function()
                {
                    //customerMess.removeClass('hide');
                    //customerErrr.removeClass('hide');
                },
                success: function(data)
                {
                    console.log(data);
                    if (data.exception) {
                        customerErrr.addClass('alert-danger').removeClass('alert-success').html(data.exception);
                    }else{
                        $('#supplier').append('<option value = "' + data.id + '"  selected> '+ data.name + ' </option>');
                        console.log(data.id);
                        $("#supplier_modal").modal('hide');
                    }
                },
                error: function(xhr)
                {
                    alert('failed!');
                }
            });
        });

        function hidemodal() {
            var x = document.getElementById("supplier_modal");
            x.style.display = "none";
        }

        $(function() {
            $('#cheque_number').hide();
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


