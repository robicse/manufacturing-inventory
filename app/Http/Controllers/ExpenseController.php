<?php

namespace App\Http\Controllers;

use App\Expense;
use App\OfficeCostingCategory;
use App\Store;
use App\Transaction;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        $start_date = $request->start_date ? $request->start_date : '';
        $end_date = $request->end_date ? $request->end_date : '';
        $office_costing_category_id = $request->office_costing_category_id ? $request->office_costing_category_id : '';
        if($start_date && $end_date && $office_costing_category_id){
            if($auth_user == "Admin"){
                $expenses = Expense::where('date','>=',$start_date)->where('date','<=',$end_date)->where('office_costing_category_id',$office_costing_category_id)->orderBy('id','desc')->get();
            }else{
                $expenses = Expense::where('date','>=',$start_date)->where('date','<=',$end_date)->where('office_costing_category_id',$office_costing_category_id)->where('user_id',$auth_user_id)->orderBy('id','desc')->get();
            }
        }elseif($start_date && $end_date){
            if($auth_user == "Admin"){
                $expenses = Expense::where('date','>=',$start_date)->where('date','<=',$end_date)->orderBy('id','desc')->get();
            }else{
                $expenses = Expense::where('date','>=',$start_date)->where('date','<=',$end_date)->where('user_id',$auth_user_id)->orderBy('id','desc')->get();
            }
        }else{
            if($auth_user == "Admin"){
                $expenses = Expense::orderBy('id','desc')->get();
            }else{
                $expenses = Expense::where('user_id',$auth_user_id)->orderBy('id','desc')->get();
            }
        }
        $officeCostingCategories = OfficeCostingCategory::all();
        return view('backend.expense.index',compact('expenses','officeCostingCategories','start_date','end_date','office_costing_category_id'));
    }

    public function create()
    {
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        $officeCostingCategories = OfficeCostingCategory::all() ;
        if($auth_user == "Admin"){
            $stores = Store::all();
        }else{
            $stores = Store::where('user_id',$auth_user_id)->get();
        }
        return view('backend.expense.create',compact('officeCostingCategories','stores'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'payment_type'=> 'required',
            'amount'=> 'required',
        ]);
        $expense = new Expense();
        $expense->user_id = Auth::id();
        $expense->store_id = $request->store_id;
        $expense->office_costing_category_id = $request->office_costing_category_id;
        $expense->payment_type = $request->payment_type;
        $expense->cheque_number = $request->cheque_number ? $request->cheque_number : NULL;
        $expense->amount = $request->amount;
        $expense->date = $request->date;
        $expense->save();
        $insert_id = $expense->id;
        if($insert_id){
            // transaction
            $transaction = new Transaction();
            $transaction->user_id = Auth::id();
            $transaction->store_id = $request->store_id;
            $transaction->ref_id = $insert_id;
            $transaction->transaction_type = 'expense';
            $transaction->payment_type = $request->payment_type;
            $transaction->cheque_number = $request->cheque_number ? $request->cheque_number : '';
            $transaction->amount = $request->amount;
            $transaction->date = $request->date;
            $transaction->save();
        }
        Toastr::success('Expense Created Successfully', 'Success');
        return redirect()->route('expenses.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        $officeCostingCategories = OfficeCostingCategory::all() ;
        if($auth_user == "Admin"){
            $stores = Store::all();
        }else{
            $stores = Store::where('user_id',$auth_user_id)->get();
        }
        $expense = Expense::find($id);
        return view('backend.expense.edit',compact('expense','officeCostingCategories','stores'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'payment_type'=> 'required',
            'amount'=> 'required',
        ]);
        $expense = Expense::find($id);
        $expense->user_id = Auth::id();
        $expense->office_costing_category_id = $request->office_costing_category_id;
        $expense->payment_type = $request->payment_type;
        $expense->cheque_number = $request->cheque_number ? $request->cheque_number : NULL;
        $expense->amount = $request->amount;
        $affectedRows = $expense->save();
        if($affectedRows){
            $transaction = Transaction::where('ref_id',$id)->first();
            $transaction->payment_type = $request->payment_type;
            $transaction->cheque_number = $request->cheque_number ? $request->cheque_number : '';
            $transaction->amount = $request->amount;
            $transaction->save();
        }
        Toastr::success('Expense Updated Successfully', 'Success');
        return redirect()->route('expenses.index');
    }

    public function destroy($id)
    {
        Expense::destroy($id);
        Toastr::success('Expense Updated Successfully', 'Success');
        return redirect()->route('expenses.index');
    }

    public function newOfficeCostingCategory(Request $request){
        $this->validate($request, [
            'name' => 'required',
        ]);
        $officeCostingCategory = new OfficeCostingCategory();
        $officeCostingCategory->name = $request->name;
        $officeCostingCategory->slug = Str::slug($request->name);
        $officeCostingCategory->save();
        $insert_id = $officeCostingCategory->id;
        if ($insert_id){
            $sdata['id'] = $insert_id;
            $sdata['name'] = $officeCostingCategory->name;
            echo json_encode($sdata);
        }
        else {
            $data['exception'] = 'Some thing mistake !';
            echo json_encode($data);

        }
    }
}
