<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Supplier;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $table = Supplier::orderBy('id', 'DESC')->get();

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
        $validator = Validator::make($request->all(), [
            'name' => 'string|required|max:191',
            'email' => 'string|required|min:4|max:191',
            'contact' => 'string|required|min:4|max:191',
            'supplier_categories_id' => 'number|required',
            'warehouses_id' => 'number|required'
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        try{

            $table = new Supplier();
            $table->code = $request->code ?? mt_rand();
            $table->name = $request->name;
            $table->address = $request->address;
            $table->email = $request->email;
            $table->contact = $request->contact;
            $table->phone = $request->phone;
            $table->alternate_contact = $request->alternate_contact;
            $table->description = $request->description;
            $table->credit_limit = $request->credit_limit ?? 0;
            $table->balance = $request->balance ?? 0;
            $table->supplier_categories_id = $request->supplier_categories_id;
            $table->warehouses_id = $request->warehouses_id;
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

            $table = Supplier::find($id);

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
            'email' => 'string|required|min:4|max:191',
            'contact' => 'string|required|min:4|max:191',
            'supplier_categories_id' => 'number|required',
            'warehouses_id' => 'number|required'
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        try{

            $table = Supplier::find($id);
            $table->code = $request->code ?? mt_rand();
            $table->name = $request->name;
            $table->address = $request->address;
            $table->email = $request->email;
            $table->contact = $request->contact;
            $table->phone = $request->phone;
            $table->alternate_contact = $request->alternate_contact;
            $table->description = $request->description;
            $table->credit_limit = $request->credit_limit ?? 0;
            $table->balance = $request->balance ?? 0;
            $table->supplier_categories_id = $request->supplier_categories_id;
            $table->warehouses_id = $request->warehouses_id;
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

            Supplier::destroy($id);

        }catch (QueryException $ex) {
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }
        return response()->json(config('naz.del'));
    }
}
