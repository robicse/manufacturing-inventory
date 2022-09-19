@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Expenses</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{!! route('expenses.create') !!}" class="btn btn-sm btn-primary" type="button">Add Expenses</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">

                <h3 class="tile-title">Expenses Table</h3>
                <form class="form-inline" action="{{ route('expenses.index') }}">
                    <div class="form-group col-md-3">
                        <label for="start_date">Start Date:</label>
                        <input type="text" name="start_date" class="datepicker form-control" value="{{$start_date}}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_date">End Date:</label>
                        <input type="text" name="end_date" class="datepicker form-control" value="{{$end_date}}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_date">Category:</label>
                        <select class="form-control" name="office_costing_category_id">
                            @if($officeCostingCategories)
                                <option value="">select one</option>
                                @foreach($officeCostingCategories as $officeCostingCategory)
                                    <option value="{{$officeCostingCategory->id}}" {{$officeCostingCategory->id == $office_costing_category_id ? 'selected' : ''}}>{{$officeCostingCategory->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="{!! route('expenses.index') !!}" class="btn btn-primary" type="button">Reset</a>
                    </div>
                </form>
                <div>&nbsp;</div>
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5%">#Id</th>
                            <th>Office Costing Category</th>
                            <th>Payment Type</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $sum = 0;
                        @endphp
                        @foreach($expenses as $key => $expense)
                            @php
                                $sum += $expense->amount;
                            @endphp
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $expense->office_costing_category->name}}</td>
                            <td>
                                {{ $expense->payment_type}}
                                @if($expense->payment_type == 'Cheque')
                                    ({{ $expense->cheque_number}})
                                @endif
                            </td>
                            <td>{{ $expense->amount}}</td>
                            <td>{{ $expense->date}}</td>
                            <td>
    {{--                            <a href="{{ route('expenses.show',$expense->id) }}" class="btn btn-sm btn-info float-left" style="margin-left: 5px">Show</a>--}}
                                <a href="{{ route('expenses.edit',$expense->id) }}" class="btn btn-sm btn-primary float-left" style="margin-left: 5px"><i class="fa fa-edit"></i></a>
                                <form method="post" action="{{ route('expenses.destroy',$expense->id) }}" >
                                   @method('DELETE')
                                    @csrf
                                    <button class="btn btn-sm btn-danger" style="margin-left: 5px" type="submit" onclick="return confirm('You Are Sure This Delete !')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="tile-footer text-right">
                        <h3><strong><span style="margin-right: 50px;">Total: {{$sum}}</span></strong></h3>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection


