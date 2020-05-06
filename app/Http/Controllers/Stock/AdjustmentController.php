<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\StockAdjustment;
use App\StockAdjustmentItem;
use App\StockTransaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $table = StockAdjustment::with('warehouse')->orderBy('id', 'DESC')->get();

            $data = [];
            foreach ($table as $row){
                $rowData['id'] = $row->id;
                $rowData['code'] = $row->code;
                $rowData['recover_amount'] = $row->recover_amount;
                $rowData['document'] = $row->document;
                $rowData['description'] = $row->description;
                $rowData['warehouses_id'] = $row->warehouses_id;
                $rowData['warehouses'] = $row->warehouse['name'];;
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
            'code' => 'required|string|min:4|max:191',
            'recover_amount' => 'required|numeric|min:0',
            'warehouses_id' => 'required|numeric',
            'items'    => "required|array"
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        DB::beginTransaction();
        try{

            $items = $request->items;

            $table = new StockAdjustment();
            $table->code = $request->code ?? mt_rand();
            $table->recover_amount  = $request->recover_amount  ?? 0;
            $table->description = $request->description;
            $table->warehouses_id = $request->warehouses_id;

            if($request->hasFile('document')){
                $file_name = $request->file('document')->store('adjustment', 'attachment');
                $table->document = $file_name;
            }
            $table->save();
            $stock_adjustments_id = $table->id;

            foreach ($items as $row){
                $trItem = new StockAdjustmentItem();
                $trItem->name = $row['name'];
                $trItem->sku = $row['sku'];
                $trItem->quantity = $row['quantity'];
                $trItem->amount = $row['amount'];
                $trItem->unit = $row['unit'];
                $trItem->adjustment_action = $row['adjustment_action'];
                $trItem->products_id = $row['id'];
                $trItem->warehouses_id = $request->warehouses_id;
                $trItem->stock_adjustments_id = $stock_adjustments_id;
                $trItem->save();
            }

            DB::commit();
        }catch (Exception  $ex) {
            if($request->hasFile('document')) {
                del_file($file_name, 'attachment');
            }
            DB::rollback();
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

            $table = StockAdjustment::where('id', $id)->with('stockAdjustmentItems')->get();

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
            'code' => 'required|string|min:4|max:191',
            'recover_amount' => 'required|numeric|min:0',
            'warehouses_id' => 'required|numeric',
            'items'    => "required|array"
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        DB::beginTransaction();
        try{

            $items = $request->items;

            $table = StockAdjustment::find($id);
            $table->code = $request->code ?? mt_rand();
            $table->recover_amount  = $request->recover_amount  ?? 0;
            $table->description = $request->description;
            $table->warehouses_id = $request->warehouses_id;

            if($request->hasFile('document')){
                $file_name = $request->file('document')->store('adjustment', 'attachment');
                $table->document = $file_name;
            }
            $table->save();

            $select_item = StockAdjustmentItem::select('id')->where('stock_adjustments_id', $id)->pluck('id')->toArray();

            StockTransaction::whereIn('stock_adjustment_items_id', $select_item)->delete();

            StockAdjustmentItem::where('stock_adjustments_id', $id)->delete();

            foreach ($items as $row){

                StockAdjustmentItem::updateOrCreate(
                    ['stock_adjustments_id' => $id, 'products_id' => $row['id']],
                    [
                        'name' => $row['name'],
                        'sku' => $row['sku'],
                        'quantity' => $row['quantity'],
                        'amount' => $row['amount'],
                        'unit' => $row['unit'],
                        'adjustment_action' => $row['adjustment_action'],
                        'warehouses_id' =>  $request->warehouses_id
                    ]
                );
            }


            DB::commit();
        }catch (Exception $ex) {
            if($request->hasFile('document')) {
                del_file($file_name, 'attachment');
            }
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
        try{
            $table = StockAdjustment::find($id);
            $fileName = $table->document;

            StockAdjustment::destroy($id);

        }catch (QueryException $ex) {
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }

        if($fileName){
            del_file($fileName, 'attachment');
        }

        return response()->json(config('naz.del'));
    }
}
