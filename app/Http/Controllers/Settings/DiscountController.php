<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Discount;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $table = Discount::orderBy('id', 'DESC')->get();

        }catch (QueryException $ex) {
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }

        return response()->json(db_result($table));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'string|required|max:191',
            'amount' => 'numeric|required',
            'discount_type' => 'string|required|min:5|max:10',
            'apply_only' => 'string|required|min:3|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        try{

            $table = new Discount();
            $table->name = $request->name;
            $table->priority = $request->priority ?? 0;
            $table->amount = $request->amount;
            $table->apply_only = $request->apply_only ?? 'General';
            $table->discount_type = $request->discount_type ?? 'Fixed';
            $table->start = $request->start;
            $table->end = $request->end;
            $table->warehouses_id = $request->warehouses_id;
            $table->brands_id = $request->brands_id;
            $table->product_categories_id = $request->product_categories_id;
            $table->customer_categories_id = $request->customer_categories_id;
            $table->save();

        }catch (QueryException $ex) {
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

            $table = Discount::find($id);

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
            'name' => 'string|required|max:191',
            'amount' => 'numeric|required',
            'discount_type' => 'string|required|min:5|max:10',
            'apply_only' => 'string|required|min:3|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        try{

            $table = Discount::find($id);
            $table->name = $request->name;
            $table->priority = $request->priority ?? 0;
            $table->amount = $request->amount;
            $table->apply_only = $request->apply_only ?? 'General';
            $table->discount_type = $request->discount_type ?? 'Fixed';
            $table->start = $request->start;
            $table->end = $request->end;
            $table->warehouses_id = $request->warehouses_id;
            $table->brands_id = $request->brands_id;
            $table->product_categories_id = $request->product_categories_id;
            $table->customer_categories_id = $request->customer_categories_id;
            $table->save();

        }catch (QueryException $ex) {
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

            Discount::destroy($id);

        }catch (QueryException $ex) {
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }
        return response()->json(config('naz.del'));
    }
}
