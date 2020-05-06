<?php

namespace App\Http\Controllers\Stock;

use App\Product;
use App\Http\Controllers\Controller;
use App\StockTransaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $table = Product::with('brand', 'company', 'productCategory', 'unit')->orderBy('id', 'DESC')->get();

            $data = [];
            foreach ($table as $row){
                $rowData['id'] = $row->id;
                $rowData['sku'] = $row->sku;
                $rowData['name'] = $row->name;
                $rowData['sell_price'] = $row->sell_price;
                $rowData['purchase_price'] = $row->purchase_price;
                $rowData['product_type'] = $row->product_type;
                $rowData['alert_quantity'] = $row->alert_quantity;
                $rowData['description'] = $row->description;
                $rowData['stock'] = $row->stock;
                $rowData['current_stock'] = $row->currentStock();
                $rowData['brand'] = $row->brand['name'];
                $rowData['company'] = $row->company['name'];
                $rowData['productCategory'] = $row->productCategory['name'];
                $rowData['unit'] = $row->unit['name'];
                $data[] = $rowData;
            }

        }catch (QueryException $ex) {
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }

        return response()->json(db_result($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku' => 'string|required|min:4|max:191',
            'name' => 'string|required|min:3|max:191',
            'sell_price' => 'numeric|required|min:0',
            'purchase_price' => 'numeric|required|min:0',
            'product_type' => 'string|required|min:4|max:5',
            'stock' => 'numeric|required|min:0',
            'product_categories_id' => 'numeric|required|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        try{

            $table = new Product();
            $table->sku = $request->sku ?? mt_rand();
            $table->name = $request->name;
            $table->sell_price = $request->sell_price;
            $table->purchase_price = $request->purchase_price;
            $table->product_type = $request->product_type;
            $table->enable_stock = $request->enable_stock ?? 0;
            $table->enable_expire = $request->enable_expire ?? 0;
            $table->enable_serial = $request->enable_serial ?? 0;
            $table->alert_quantity = $request->alert_quantity ?? 0;
            $table->alert_expire_day = $request->alert_expire_day ?? 0;
            $table->barcode = $request->barcode;
            $table->description = $request->description;
            $table->stock = $request->stock;
            $table->product_categories_id = $request->product_categories_id;
            //$table->sub_product_categories_id = $request->sub_product_categories_id;
            $table->brands_id = $request->brands_id;
            $table->companies_id = $request->companies_id;
            $table->units_id = $request->units_id;
            $table->vet_texes_id = $request->vet_texes_id;
            $table->tax_type = $request->tax_type;
            $table->save();

        }catch (Exception  $ex) {
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }

        return response()->json(config('naz.save'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{

            $table = Product::find($id);

        }catch (QueryException $ex) {
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }

        return response()->json(db_result($table));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sku' => 'string|required|min:4|max:191',
            'name' => 'string|required|min:3|max:191',
            'sell_price' => 'numeric|required|min:0',
            'purchase_price' => 'numeric|required|min:0',
            'product_type' => 'string|required|min:4|max:5',
            'stock' => 'numeric|required|min:0',
            'product_categories_id' => 'numeric|required|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        DB::beginTransaction();
        try{

            $table = Product::find($id);
            $table->sku = $request->sku ?? mt_rand();
            $table->name = $request->name;
            $table->sell_price = $request->sell_price;
            $table->purchase_price = $request->purchase_price;
            $table->product_type = $request->product_type;
            $table->enable_stock = $request->enable_stock ?? 0;
            $table->enable_expire = $request->enable_expire ?? 0;
            $table->enable_serial = $request->enable_serial ?? 0;
            $table->alert_quantity = $request->alert_quantity ?? 0;
            $table->alert_expire_day = $request->alert_expire_day ?? 0;
            $table->barcode = $request->barcode;
            $table->description = $request->description;
            $table->stock = $request->stock;
            $table->product_categories_id = $request->product_categories_id;
            //$table->sub_product_categories_id = $request->sub_product_categories_id;
            $table->brands_id = $request->brands_id;
            $table->companies_id = $request->companies_id;
            $table->units_id = $request->units_id;
            $table->vet_texes_id = $request->vet_texes_id;
            $table->tax_type = $request->tax_type;
            $table->save();

            DB::commit();
        }catch (QueryException $ex) {
            DB::rollback();
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }

        return response()->json(config('naz.edit'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{

            Product::destroy($id);

            DB::commit();
        }catch (QueryException $ex) {
            DB::rollback();
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }
        return response()->json(config('naz.del'));
    }


    public function transaction($id){
        try{

            $table = StockTransaction::where('products_id', $id)->orderBy('id', 'DESC')->get();

            $data = [];
            foreach ($table as $row){
                $rowData['sku'] = $row->sku;
                $rowData['name'] = $row->name;
                $rowData['in_stock'] = ($row->transaction_type == 'IN' ? $row->quantity : 0);
                $rowData['out_stock'] = ($row->transaction_type == 'OUT' ? $row->quantity : 0);
                $rowData['transaction_point'] = $row->transaction_point;
                $rowData['ref'] = $row->ref();
                $data[] = $rowData;
            }

        }catch (QueryException $ex) {
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }

        return response()->json(db_result($data));
    }
}
