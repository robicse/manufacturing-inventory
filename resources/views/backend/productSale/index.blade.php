@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Product Sale</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{!! route('productSales.create') !!}" class="btn btn-sm btn-primary" type="button">Add Product Sales</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">

                <h3 class="tile-title">Product Sales Table</h3>
                <form class="form-inline" action="{{ route('productSales.index') }}">
                    @csrf
                    <div class="form-group col-md-4">
                        <label for="start_date">Start Date:</label>
                        <input type="text" name="start_date" class="datepicker form-control" value="{{$start_date}}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="end_date">End Date:</label>
                        <input type="text" name="end_date" class="datepicker form-control" value="{{$end_date}}">
                    </div>
                    <div class="form-group col-md-4">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="{!! route('productSales.index') !!}" class="btn btn-primary" type="button">Reset</a>
                    </div>
                </form>
                <div>&nbsp;</div>
                <table id="example1" class="table table-bordered table-striped">

                    <thead>
                    <tr>
                        <th width="5%">SL NO</th>
                        <th>Sale User</th>
                        <th>Invoice No</th>
{{--                        <th>Model</th>--}}
                        <th width="20%">Customer</th>
{{--                        <th>Payment Type</th>--}}
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th width="14%">Date</th>
                        <th>Due Amount</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $sum_total_amount = 0;
                        $sum_paid_amount = 0;
                        $sum_due_amount = 0;
                    @endphp
                    @foreach($productSales as $key => $productSale)
                        @php
                            $sum_total_amount += $productSale->total_amount;
                            $sum_paid_amount += $productSale->paid_amount;
                            $sum_due_amount += $productSale->due_amount;
                        @endphp
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $productSale->user->name}}</td>
                        <td @if($productSale->discount_amount > 0) style="color: red;" @endif>{{ $productSale->invoice_no}}</td>
{{--                        <td>--}}
{{--                            @php--}}
{{--                                $str_arr = explode (",", salesProductModels($productSale->id));--}}
{{--                            @endphp--}}

{{--                            @foreach($str_arr as $key => $str)--}}
{{--                                @if(($key == 0 && count($str_arr) > 1))--}}
{{--                                    {{$str}},--}}
{{--                                @elseif( (($key > 0 && $key < count($str_arr))) && ($key+1 != count($str_arr)))--}}
{{--                                    {{$str}},--}}
{{--                                @else--}}
{{--                                    {{$str}}--}}
{{--                                @endif--}}
{{--                                <br/>--}}
{{--                            @endforeach--}}
{{--                        </td>--}}
                        <td>{{ $productSale->party->name}}</td>
{{--                        <td>{{ $productSale->payment_type}}</td>--}}
                        <td>{{ $productSale->total_amount}}</td>
                        <td>{{ $productSale->paid_amount}}</td>
                        <td>{{ $productSale->created_at}}</td>
                        <td>
                            {{ $productSale->due_amount}}
                            @if($productSale->total_amount != $productSale->paid_amount)
                                <a href="" class="btn btn-warning btn-sm mx-1" data-toggle="modal" data-target="#exampleModal-<?= $productSale->id;?>"> Pay Due</a>
                            @endif
                        </td>
                        <td class="d-inline-flex">
                            <a href="{{ route('productSales.show',$productSale->id) }}" class="btn btn-sm btn-info float-left" style="margin-left: 5px">Show</a>
                            <a href="{!! route('productSales-invoice',$productSale->id) !!}" target="__blank" class="btn btn-sm btn-warning" style="margin-left: 5px" type="button">Bill</a>
                            <a href="{!! route('productSales-challan',$productSale->id) !!}" target="__blank" class="btn btn-sm btn-warning" style="margin-left: 5px" type="button">Challan</a>
                            <a href="{{ route('productSales.edit',$productSale->id) }}" class="btn btn-sm btn-primary float-left" style="margin-left: 5px"><i class="fa fa-edit"></i></a>
{{--                            <form method="post" action="{{ route('productSales.destroy',$productSale->id) }}" >--}}
{{--                               @method('DELETE')--}}
{{--                                @csrf--}}
{{--                                <button class="btn btn-sm btn-danger" style="margin-left: 5px" type="submit" onclick="return confirm('You Are Sure This Delete !')"><i class="fa fa-trash"></i></button>--}}
{{--                            </form>--}}
                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal-{{$productSale->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Pay Due</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{route('pay.due')}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="due">Enter Due Amount</label>
                                            <input type="hidden" class="form-control" name="product_sale_id" value="{{$productSale->id}}">
                                            <input type="number" class="form-control" id="due" aria-describedby="emailHelp" name="new_paid" min="" max="{{$productSale->due_amount}}" placeholder="Enter Amount">
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
                <div class="tile-footer text-right">
                    <h3><strong><span style="margin-right: 50px;">Total Amount: {{$sum_total_amount}}</span></strong></h3>
                    <h3><strong><span style="margin-right: 50px;">Paid Amount: {{$sum_paid_amount}}</span></strong></h3>
                    <h3><strong><span style="margin-right: 50px;">Due Amount: {{$sum_due_amount}}</span></strong></h3>
                </div>
            </div>

        </div>
    </main>
@endsection


