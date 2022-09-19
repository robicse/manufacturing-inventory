@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Product Purchases</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{!! route('productPurchaseRawMaterials.create') !!}" class="btn btn-sm btn-primary" type="button">Add Product Purchases</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Product Purchases Table</h3>
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5%">SL NO</th>
                            <th>Invoice NO</th>
                            <th>Purchase User</th>
{{--                            <th>Store</th>--}}
                            <th>Supplier</th>
    {{--                        <th>Payment Type</th>--}}
                            <th>Product Type</th>
                            <th>Total Amount</th>
                            <th>Date</th>
                            <th>Due Amount</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($productPurchases as $key => $productPurches)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $productPurches->invoice_no}}</td>
                            <td>{{ $productPurches->user->name}}</td>
{{--                            <td>{{ $productPurches->store->name}}</td>--}}
                            <td>{{ $productPurches->party->name}}</td>
    {{--                        <td>{{ $productPurches->payment_type}}</td>--}}
                            <td>{{ $productPurches->purchase_product_type}}</td>
                            <td>{{ $productPurches->total_amount}}</td>
                            <td>{{ $productPurches->created_at}}</td>
                            <td>
                                {{ $productPurches->due_amount}}
                                @if($productPurches->total_amount != $productPurches->paid_amount)
                                    <a href="" class="btn btn-warning btn-sm mx-1" data-toggle="modal" data-target="#exampleModal-<?= $productPurches->id;?>"> Pay Due</a>
                                @endif
                            </td>
                            <td class="d-inline-flex">
                                <a href="{{ route('productPurchaseRawMaterials.show',$productPurches->id) }}" class="btn btn-sm btn-info float-left" style="margin-left: 5px">Show</a>
                                <a href="{{ route('productPurchaseRawMaterials.edit',$productPurches->id) }}" class="btn btn-sm btn-primary float-left" style="margin-left: 5px"><i class="fa fa-edit"></i></a>
                                <form method="post" action="{{ route('productPurchaseRawMaterials.destroy',$productPurches->id) }}" >
                                   @method('DELETE')
                                    @csrf
                                    <button class="btn btn-sm btn-danger" style="margin-left: 5px" type="submit" onclick="return confirm('You Are Sure This Delete !')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <div class="modal fade" id="exampleModal-{{$productPurches->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                <input type="hidden" class="form-control" name="product_purchase_id" value="{{$productPurches->id}}">
                                                <input type="number" class="form-control" id="due" aria-describedby="emailHelp" name="new_paid" min="" max="{{$productPurches->due_amount}}" placeholder="Enter Amount">
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


