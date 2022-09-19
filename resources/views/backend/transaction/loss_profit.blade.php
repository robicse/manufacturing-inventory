@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i>Loss/Profit</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item">
                    @if($start_date != '' && $end_date != '')
                        <a class="btn btn-warning" href="{{ url('loss-profit-filter-export/'.$start_date."/".$end_date) }}">Export Data</a>
                    @else
                        <a class="btn btn-warning" href="{{ route('loss.profit.export') }}">Export Data</a>
                    @endif
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Loss/Profit Table</h3>
                <form class="form-inline" action="{{ route('transaction.lossProfit') }}">
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
                        <a href="{!! route('transaction.lossProfit') !!}" class="btn btn-primary" type="button">Reset</a>
                    </div>
                </form>
                <div>&nbsp;</div>
                @if(!empty($stores))
                    @foreach($stores as $store)
                        <div class="col-md-12">
                            <h1 class="text-center">{{$store->name}}</h1>
                            <table>
                                <thead>
                                @php
                                    //die($end_date);
                                    //$product_sale_return_discount = product_sale_return_discount($store->id,$start_date,$end_date);
                                    //$product_sale_discount = product_sale_discount($store->id,$start_date,$end_date);
                                    $loss_profit = loss_profit($store->id,$start_date,$end_date);
                                    $sale_discount = product_sale_discount($store->id,$start_date,$end_date);
                                    $product_sale_return_discount = product_sale_return_discount($store->id,$start_date,$end_date);
                                    $loss_profit_after_sale_discount = $loss_profit - ($sale_discount - $product_sale_return_discount);
                                    $total_expense = total_expense($store->id,$start_date,$end_date);
                                    //dd($sale_discount);
                                    //$loss_profit = $loss_profit_after_sale_discount - $total_expense;
                                    $loss_profit = $loss_profit_after_sale_discount;
                                @endphp
                                <tr>
                                    <th colspan="10">Sum Product Based Loss/Profit: </th>
                                    <th>
                                        @php
                                            if($loss_profit >= 0){
                                                $loss_profit_string_flag = 'Profit';
                                                $product_loss_profit = $loss_profit;
                                            }else{
                                                 $remove_minus_sign_from_loss = abs($loss_profit);
                                                 $loss_profit_string_flag = 'Loss';
                                                 $product_loss_profit = $remove_minus_sign_from_loss;
                                            }
                                        @endphp
                                        <span style="font-size: 18px">{{$loss_profit_string_flag}}:</span> {{number_format($product_loss_profit, 2, '.', '')}} Tk.
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="10">&nbsp;</th>
                                    <th>
                                        <span style="font-size: 18px">Expense:</span> {{number_format($total_expense, 2, '.', '')}} Tk.
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="10">Final(After Deduction Expense):</th>
                                    <th>
                                        @php
                                            $final_loss_profit = 0;
                                            if($loss_profit_string_flag == 'Loss'){
                                                $final_loss_profit_string_flag = 'Loss';
                                                $final_loss_profit = $remove_minus_sign_from_loss + $total_expense;
                                            }elseif( ($loss_profit_string_flag == 'Profit') && ($product_loss_profit > $total_expense) ){
                                                $final_loss_profit_string_flag = 'Profit';
                                                $final_loss_profit = $product_loss_profit - $total_expense;
                                            }else{
                                                $final_loss_profit_string_flag = 'Loss';
                                                $final_loss_profit = $total_expense - $product_loss_profit;
                                            }
                                        @endphp
                                        <span style="font-size: 18px">{{$final_loss_profit == 0 ? 'Loss/Profit' : $final_loss_profit_string_flag}}:</span> {{number_format($final_loss_profit, 2, '.', '')}} Tk.


                                    </th>
                                </tr>
                                </thead>
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


