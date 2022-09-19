@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Due Purchase</h1>
            </div>
{{--            <ul class="app-breadcrumb breadcrumb">--}}
{{--                <li class="breadcrumb-item"> <a href="{!! route('productSales.create') !!}" class="btn btn-sm btn-primary" type="button">Add Product Sales</a></li>--}}
{{--            </ul>--}}
        </div>
        <div class="col-md-12">
            <div class="tile">

                <h3 class="tile-title">Supplier Due</h3>
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5%">#Id</th>
                            <th>Invoice NO</th>
                            <th>Supplier</th>
                            <th>Phone</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Due Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($productPurchases as $key => $productPurchase)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $productPurchase->invoice_no}}</td>
                            <td>{{ $productPurchase->party->name}}</td>
                            <td>{{ $productPurchase->party->phone}}</td>
                            <td>{{ $productPurchase->total_amount}}</td>
                            <td>{{ $productPurchase->paid_amount}}</td>
                            <td>
                                {{ $productPurchase->due_amount}}
                                @if($productPurchase->total_amount != $productPurchase->paid_amount)
                                    <a href="" class="btn btn-warning btn-sm mx-1" data-toggle="modal" data-target="#exampleModal-<?= $productPurchase->id;?>"> Pay Due</a>
                                @endif
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal-{{$productPurchase->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Pay Due</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('supplier.pay.due')}}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label for="due">Enter Due Amount</label>
                                                <input type="hidden" class="form-control" name="product_purchase_id" value="{{$productPurchase->id}}">
                                                <input type="number" class="form-control" id="due" aria-describedby="emailHelp" name="new_paid" min="" max="{{$productPurchase->due_amount}}" placeholder="Enter Amount">
                                            </div>
                                            <div class="form-group">
                                                <label for="payment_type">Payment Type</label>
                                                <select name="payment_type" id="payment_type" class="form-control" required>
                                                    <option value="">Select One</option>
                                                    <option value="Cash">Cash</option>
                                                    <option value="Cheque">Cheque</option>
                                                </select>
                                                <span>&nbsp;</span>
                                                <input type="text" name="cheque_number" id="cheque_number" class="form-control" placeholder="Cheque Number">
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @push('js')
                                    <script>
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
                            </div>
                        </div>
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


