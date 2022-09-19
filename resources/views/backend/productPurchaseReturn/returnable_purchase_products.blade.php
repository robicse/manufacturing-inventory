@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            {{--<div>
                <h1><i class=""></i> Add Sales Product</h1>
            </div>--}}
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Returnable Purchase Product</h3>
                <div class="tile-body tile-footer">
                    @if(session('response'))
                        <div class="alert alert-success">
                            {{ session('response') }}
                        </div>
                    @endif
                    <form method="post" action="{{route('purchase.product.return')}}">
                        @csrf
                        <div class="form-group row">
                            <label class="control-label col-md-3 text-right">Invoice  <small class="requiredCustom">*</small></label>
                            <div class="col-md-5">
                                <select name="product_purchase_id" class="form-control select2" id="sale_invoice_no" required>
                                    <option value="">Select One</option>
                                    @foreach($productPurchases as $productPurchase)
                                        <option value="{{$productPurchase->id}}">{{$productPurchase->invoice_no}} </option>
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
            var purchase_id = $(this).val();
            console.log(purchase_id);

            $.ajax({
                url : "{{ URL('/get-returnable-purchase-product') }}/" + purchase_id,
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
        function productType(){
            var arr = $('#payment_type').val();
            console.log(arr);
            if(arr == "Cheque"){ $("#cheque_number").removeAttr("readonly"); }
            if(arr == "Cash"){ $("#cheque_number").attr("readonly", "readonly"); }
        }
        // ajax
        function return_qty(row,sel) {

            var current_row = row;
            var current_return_qty = sel.value;
            //console.log('current_row = ' + current_row);
            //console.log('current_return_qty = ' + current_return_qty);
            //console.log('current_return_qty= ' + typeof current_return_qty);
            //var current_product_id = $('#product_id_'+current_row).val();
            var current_sale_qty = $('#qty_'+current_row).val();
            //console.log('current_sale_qty = ' + current_sale_qty);
            //console.log('current_sale_qty= ' + typeof current_sale_qty);
            var check_sale_return_qty = $('#check_sale_return_qty_'+current_row).val();
            //console.log('check_sale_return_qty = ' + check_sale_return_qty);
            //console.log('check_sale_return_qty= ' + typeof check_sale_return_qty);
            current_return_qty = parseInt(current_return_qty);
            //console.log('current_return_qty= ' + typeof current_return_qty);
            current_sale_qty = parseInt(current_sale_qty);
            if(check_sale_return_qty > 0){
                current_sale_qty -= check_sale_return_qty
            }
            //console.log('current_sale_qty= ' + typeof current_sale_qty);
            if(current_return_qty > current_sale_qty){
                alert('You have limit cross of stock qty!');
                $('#return_qty_'+current_row).val(0);
            }
        }
    </script>
@endpush


