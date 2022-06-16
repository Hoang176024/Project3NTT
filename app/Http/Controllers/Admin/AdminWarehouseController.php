<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Models\Product;
use App\Models\Transfer;
use App\Models\WareHouse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminWarehouseController extends Controller
{
    public function import()
    {
        $warehouses = Warehouse::select('id', 'wh_name')->get();
        $products = Product::all();
        $data = [
            'products' => $products,
            'warehouses' => $warehouses
        ];
        return view('admin.warehouse.import',$data);
    }
    public function importProduct(Request $request,$id)
    {
        $product = Product::find($id);
        $warehouse_id = $request->warehouse_id;
        $wh_id = WareHouse::find($warehouse_id)->id;
        $warehouse = WareHouse::find($wh_id);
        if($product->pro_number + $request->product_number < 0)
        {
            $request->session()->flash('import_error', 'Sản phẩm "'.$product->pro_name.' " mã sản phẩm là '.$id.' chỉ còn '.$product->pro_number.' trong kho !');
            return redirect()->route('admin.warehouse.import');
        }
        
        //$add = $warehouse->Product()->where('warehouse_id',$wh_id)->where('wh_product_id',$id)->firstOrFail()->pivot->quantity;
        if($warehouse->Product()->where('warehouse_id',$wh_id)->where('wh_product_id',$id)->exists())
        {
        $add = $warehouse->Product()->where('warehouse_id',$wh_id)->where('wh_product_id',$id)->firstOrFail()->pivot->quantity;
        
        $warehouse->Product()->updateExistingPivot($id,['quantity'=>$request->product_number + $add]);
        }
        else
        { 
        $warehouse->Product()->attach($wh_id,['wh_product_id'=>$id,'quantity'=>$request->product_number]);
        }

        //$total_quantity = $warehouse->Product()->where('wh_product_id',$id)->get()->sum('pivot.quantity');
        $product->pro_number = $product->pro_number + $request->product_number;
        $product->save();

        // WareHouse::insert(
        //     [
        //         'wh_product_id' => $id,
        //         'wh_number_import' => $request->product_number,
        //         'wh_name' => $request->warehouse_name,
        //         'time_import' => Carbon::now()
        //     ]
        // );
        $request->session()->flash('import_success', 'Đã thêm '.$request->product_number.' sản phẩm "'.$product->pro_name.' " mã sản phẩm là '.$id.' vào kho !');
        return redirect()->route('admin.warehouse.import');
    }
    public function history()
    {
        $warehouse = WareHouse::all();
        $data = [
            'warehouse' => $warehouse
        ];
        return view('admin.warehouse.history',$data);
    }
    public function iventory()
    {
        $before_month =  Carbon::now()->subMonths(1);
        $product = Product::where('pro_number','>','10')->get();
        $products_iventory =  array();
        foreach($product as $pro)
        {
            if(isset($pro->Warehouse->sortByDesc('time_import')->first()->time_import))
            {
                if($before_month >= $pro->Warehouse->sortByDesc('time_import')->first()->time_import)
                {
                    array_push($products_iventory,$pro);
                }
            }
        }
        $data = [
            'products' => $products_iventory
        ];
        return view('admin.warehouse.iventory',$data);
    }
    public function bestseller()
    {
        $product_best_seller = Product::where('pro_status',1)->orderBy('pro_pay','DESC')->limit(5)->get();
        $data = [
            'product_best_seller' => $product_best_seller
        ];
        return view('admin.warehouse.bestseller',$data);
    }
    public function hotproduct($id)
    {
        $product = Product::find($id);
        $product->pro_hot = ( $product->pro_hot==1)?0:1;
        $product->save();
        return redirect()->back();
    }
    public function stock()
    {
        $warehouses = WareHouse::with('Product')->get();
        $products = Product::with('Warehouse')->get();
        return view('admin.warehouse.stock')->with(array('warehouses'=>$warehouses,'products'=>$products));
    }
    public function importWhPro(Request $request)
    {
        $product_id = $request->product_id;
        $pro_id = Product::find($product_id)->id;

        $warehouse_id = $request->warehouse_id;
        $wh_id = WareHouse::find($warehouse_id)->id;

        $warehouse = WareHouse::find($wh_id);   
        $warehouse->Product()->attach($wh_id,['wh_product_id'=>$pro_id,'quantity'=>0]);
        return redirect()->route('admin.warehouse.stock');
    }

    public function transfer(Request $request)
    {
        $product_id = $request->product_id;
        $pro_id = Product::find($product_id)->id;

        $warehouse_id_1 = $request->warehouse_id_1;
        $wh_id_1 = WareHouse::find($warehouse_id_1)->id;

        $warehouse_id_2 = $request->warehouse_id_2;
        $wh_id_2 = WareHouse::find($warehouse_id_2)->id;

        $warehouse1 = WareHouse::find($wh_id_1);
        $warehouse2 = WareHouse::find($wh_id_2);

        $quantity1 = $warehouse1->Product()->where('warehouse_id',$wh_id_1)->where('wh_product_id',$pro_id)->firstOrFail()->pivot->quantity;
        //$quantity2 = $warehouse2->Product()->where('warehouse_id',$wh_id_2)->where('wh_product_id',$pro_id)->firstOrFail()->pivot->quantity;

        if($warehouse2->Product()->where('warehouse_id',$wh_id_2)->where('wh_product_id',$pro_id)->exists())
        {
            $quantity2 = $warehouse2->Product()->where('warehouse_id',$wh_id_2)->where('wh_product_id',$pro_id)->firstOrFail()->pivot->quantity;
            $warehouse2->Product()->updateExistingPivot($pro_id,['quantity'=> $quantity2 +  $request->product_num]);
        }
        else
        {
            $warehouse2->Product()->attach($wh_id_2,['wh_product_id'=>$pro_id,'quantity'=>$request->product_num]);
        }

        $quantity = $quantity1 -  $request->product_num;
        
        if($quantity < 0)
        {
            return back()->with(['error' => 'không đủ hàng']);
        }
        else
        $warehouse1->Product()->updateExistingPivot($pro_id,['quantity'=> $quantity]);

       // $warehouse2->Product()->updateExistingPivot($pro_id,['quantity'=> $quantity2 +  $request->product_num]);
        
        return redirect()->route('admin.warehouse.stock');
    }

    public function create(Request $request)
    {
        return view('admin.warehouse.create');
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $data = array_filter($data, 'strlen');
        Warehouse::create($data);
        return redirect(route('admin.warehouse.stock'))->with('success', __('Create Warehouse successfully!'));
    }
}
