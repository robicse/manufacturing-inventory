@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i>Party Discount</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
{{--                <li class="breadcrumb-item">--}}
{{--                    @if($start_date != '' && $end_date != '')--}}
{{--                        <a class="btn btn-warning" href="{{ url('loss-profit-filter-export/'.$start_date."/".$end_date) }}">Export Data</a>--}}
{{--                    @else--}}
{{--                        <a class="btn btn-warning" href="{{ route('loss.profit.export') }}">Export Data</a>--}}
{{--                    @endif--}}
{{--                </li>--}}
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Party Discount</h3>
                <form class="form-inline" action="{{ route('transaction.partyDiscount') }}">
                    <div class="form-group col-md-3">
                        <label for="party_id">Party:</label>
                        <select class="form-control" name="party_id">
                            <option value="">Select</option>
                            @if(count($party) > 0)
                                @foreach($party as $data)
                                    <option value="{{$data->id}}" {{$data->id == $party_id ? 'selected' : ''}}>{{$data->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="start_date">Start Date:</label>
                        <input type="text" name="start_date" class="datepicker form-control" value="{{$start_date}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_date">End Date:</label>
                        <input type="text" name="end_date" class="datepicker form-control" value="{{$end_date}}">
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="{!! route('transaction.partyDiscount') !!}" class="btn btn-primary" type="button">Reset</a>
                    </div>
                </form>
                <div>&nbsp;</div>
                @if(!empty($stores))
                    @foreach($stores as $store)
                        <div class="col-md-12">
                            <h1 class="text-center">{{$store->name}}</h1>
                        </div>
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="5%">SL NO</th>
                                    <th width="10%">Invoice No</th>
                                    <th width="10%">Date</th>
                                    <th width="15%">Party</th>
                                    <th width="15%">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $party_discounts = party_discounts($store->id,$party_id,$start_date,$end_date);
                                @endphp
                                @if(!empty($party_discounts))
                                    @php
                                        $sum_discount = 0;
                                    @endphp
                                    @foreach($party_discounts as $key => $party_discount)
                                        @php
                                            $sum_discount += $party_discount->discount_amount;
                                        @endphp
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $party_discount->invoice_no}}</td>
                                            <td>{{ $party_discount->date}}</td>
                                            <td>{{ $party_discount->name}}</td>
                                            <td>{{ $party_discount->discount_amount}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="tile-footer">
                                <table>
                                    <tr>
                                        <td colspan="4" class="text-right">Total:</td>
                                        <td>{{ $sum_discount}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </main>
@endsection


