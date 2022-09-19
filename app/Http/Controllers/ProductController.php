<?php

namespace App\Http\Controllers;

use App\ProductBrand;
use App\ProductCategory;
use App\ProductSubCategory;
use App\ProductUnit;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
        $this->middleware('permission:product-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $products = Product::latest()->get();
        return view('backend.product.index', compact('products'));
    }

    public function create()
    {
        $productCategories = ProductCategory::all();
        $productSubCategories = ProductSubCategory::all();
        $productBrands = ProductBrand::all();
        $productUnits = ProductUnit::all();
        return view('backend.product.create', compact('productCategories','productSubCategories','productBrands','productUnits'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'model' => 'required',
            'name' => 'required|unique:products,name',
            'barcode' => 'required',
            'product_category_id' => 'required',
            'product_brand_id' => 'required',
        ]);
        $product_name = $request->name . '. ' . $request->model;
        $product = new Product;
        $product->product_type = $request->product_type;
        $product->barcode = $request->barcode;
        $product->name = $product_name;
        $product->slug = Str::slug($product_name);
        $product->product_category_id = $request->product_category_id;
        $product->product_sub_category_id = $request->product_sub_category_id ? $request->product_sub_category_id : Null;
        $product->product_brand_id = $request->product_brand_id;
        $product->product_unit_id = $request->product_unit_id;
        $product->description = $request->description;
        $product->model = $request->model;
        $product->status = $request->status;
        $image = $request->file('image');
        if (isset($image)) {
            //make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imagename = $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
//            resize image for hospital and upload
            $proImage =Image::make($image)->resize(300, 300)->save($image->getClientOriginalExtension());
            Storage::disk('public')->put('uploads/product/'.$imagename, $proImage);
        }else {
            $imagename = "default.png";
        }

        $product->image = $imagename;
        $product->save();
        Toastr::success('Product Created Successfully');
        return redirect()->route('products.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $productCategories = ProductCategory::all();
        $productSubCategories = ProductSubCategory::all();
        $productBrands = ProductBrand::all();
        $productUnits = ProductUnit::all();
        $product = Product::find($id);
        return view('backend.product.edit', compact('product','productCategories','productSubCategories','productBrands','productUnits'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'product_category_id' => 'required',
            'product_brand_id' => 'required',
        ]);

        $product_name = $request->name . '. ' . $request->model;
        $product = Product::find($id);
        $product->product_type = $request->product_type;
        $product->name = $product_name;
        $product->slug = Str::slug($product_name);
        $product->barcode = $request->barcode;
        $product->product_category_id = $request->product_category_id;
        $product->product_sub_category_id = $request->product_sub_category_id ? $request->product_sub_category_id : Null;
        $product->product_brand_id = $request->product_brand_id;
        $product->product_unit_id = $request->product_unit_id;
        $product->description = $request->description;
        $product->model = $request->model;
        $product->status = $request->status;
        $image = $request->file('image');
        if (isset($image)) {
            //make unique name for image
            $currentDate = \Illuminate\Support\Carbon::now()->toDateString();
            $imagename = $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            //delete old image.....
            if(Storage::disk('public')->exists('uploads/product/'.$product->image))
            {
                Storage::disk('public')->delete('uploads/product/'.$product->image);
            }
//            resize image for hospital and upload
            $proImage = Image::make($image)->resize(300, 300)->save($image->getClientOriginalExtension());
            Storage::disk('public')->put('uploads/product/' . $imagename, $proImage);
        }else {
            $imagename = $product->image;
        }
        $product->image = $imagename;
        $product->update();
        Toastr::success('Product Updated Successfully');
        return redirect()->route('products.index');
    }

    public function destroy($id)
    {
        Toastr::warning('Product not deleted possible, Please contact with administrator!');
        return redirect()->route('products.index');
    }

    public function subCategoryList(Request $request){
        $product_category_id = $request->product_category_id;
        $sub_categories = ProductSubCategory::where('product_category_id',$product_category_id)->get();
        if(count($sub_categories) > 0){
            $options = "<option value=''>Select One</option>";
            foreach($sub_categories as $sub_category){
                $options .= "<option value='$sub_category->id'>$sub_category->name</option>";
            }
        }else{
            $options = "<option value=''>No Data Found!</option>";
        }
        return response()->json(['success'=>true,'data'=>$options]);
    }

    public function checkBarcode(Request $request){
        $barcode = $request->barcode;
        $exists_barcode = Product::where('barcode',$barcode)->get();
        if(count($exists_barcode) > 0){
            $barcode_check = 'Found';
        }else{
            $barcode_check = 'Not Found';
        }
        return response()->json(['success'=>true,'data'=>$barcode_check]);
    }
}
