<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Union;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $table = Union::with('upaZilla', 'upaZilla.zilla', 'upaZilla.zilla.division')->orderBy('id', 'DESC')->get();

            $data = [];
            foreach ($table as $row){
                $rowData['id'] = $row->id;
                $rowData['name'] = $row->name;
                $rowData['upazilla_id'] = $row->upaZilla['id'];
                $rowData['upazilla'] = $row->upaZilla['name'];
                $rowData['zilla_id'] = $row->upaZilla->zilla['id'];
                $rowData['zilla'] = $row->upaZilla->zilla['name'];
                $rowData['division_id'] = $row->upaZilla->zilla->division['id'];
                $rowData['division'] = $row->upaZilla->zilla->division['name'];
                $data[] = $rowData;
            }

            //dd($table);

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
            'name' => 'string|required|max:30',
            'upa_zillas_id' => 'numeric|required'
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        try{

            $table = new Union();
            $table->name = $request->name;
            $table->upa_zillas_id = $request->upa_zillas_id;
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

            $table = Union::find($id);

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
            'name' => 'string|required|max:30',
            'upa_zillas_id' => 'numeric|required'
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        try{

            $table = Union::find($id);
            $table->name = $request->name;
            $table->upa_zillas_id = $request->upa_zillas_id;
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

            Union::destroy($id);

        }catch (QueryException $ex) {
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }
        return response()->json(config('naz.del'));
    }
}
