@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Transaction</h1>
            </div>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <div>
                    <h1><i class=""></i> All Transaction</h1>
                </div>
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><a class="btn btn-warning" href="{{ route('transaction.export') }}">Export Data</a></li>
                </ul>
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
                                    <th width="10%">User</th>
{{--                                    <th width="10%">Store</th>--}}
                                    <th width="15%">Party</th>
                                    <th width="15%">Product Type</th>
                                    <th width="15%">Transaction Type</th>
                                    <th width="15%">Payment Type</th>
                                    <th width="15%">Amount</th>
                                    <th width="15%">Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $transactions = \App\Transaction::where('store_id',$store->id)->latest()->get();
                                @endphp
                                @if(!empty($transactions))
                                    @foreach($transactions as $key => $transaction)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $transaction->user->name}}</td>
{{--                                            <td>{{ $transaction->store->name}}</td>--}}
                                            <td>
                                                @php
                                                    if($transaction->transaction_type == 'expense'){
                                                        echo 'In House';
                                                    }else{
                                                        echo $transaction->party ? $transaction->party->name : '';
                                                    }
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                    if($transaction->transaction_type == 'expense'){

                                                        $expense_category = \Illuminate\Support\Facades\DB::table('transactions')
                                                        ->join('expenses','transactions.ref_id','expenses.id')
                                                        ->join('office_costing_categories','expenses.office_costing_category_id','office_costing_categories.id')
                                                        ->where('transactions.ref_id',$transaction->ref_id)
                                                        ->where('transactions.transaction_type','expense')
                                                        ->pluck('office_costing_categories.name')
                                                        ->first();

                                                        echo $expense_category;
                                                    }else{
                                                        echo $transaction->transaction_product_type;
                                                    }
                                                @endphp
                                            </td>
                                            <td>{{ ucwords($transaction->transaction_type)}}</td>
                                            <td>
                                                {{ $transaction->payment_type}}
                                                @if($transaction->payment_type == 'Cheque')
                                                    ( {{$transaction->cheque_number}} )
                                                @endif
                                            </td>
                                            <td>{{ $transaction->amount}}</td>
                                            <td>{{ $transaction->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="tile-footer">
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </main>
@endsection


