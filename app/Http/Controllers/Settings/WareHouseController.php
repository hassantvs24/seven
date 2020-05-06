<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Warehouse;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class WareHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $table = Warehouse::orderBy('id', 'DESC')->get();

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
            'name' => 'string|required|max:191'
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        try{

            $table = new Warehouse();
            $table->name = $request->name;
            $table->contact = $request->contact;
            $table->contact_alternate = $request->contact_alternate;
            $table->phone = $request->phone;
            $table->address = $request->address;
            $table->email = $request->email;
            $table->website = $request->website;
            $table->proprietor = $request->proprietor;

            if($request->hasFile('logo')){

                //############ 206X32 ###########
                $fileNameSm = 'logo_'.md5(date('d-m-y H:i:s')).'.png';
                $photoSm = Image::make($request->file('logo'));
                $photoSm->resize(206, 32, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $photoSm->resizeCanvas(206, 32, 'center', false, 'rgba(255, 255, 255, 0)');
                $photo_main = $photoSm->stream('png');

                Storage::disk('business')->put($fileNameSm, $photo_main->__toString());
                //############ 206X32 ###########

                //############ 412X64 ###########
                $fileName = md5(date('d-m-y H:i:s')).'.png';
                $photo = Image::make($request->file('logo'));
                $photo->resize(412, 64, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $photo->resizeCanvas(412, 64, 'center', false, 'rgba(255, 255, 255, 0)');
                $photo_main = $photo->stream('png');

                Storage::disk('business')->put($fileName, $photo_main->__toString());
                //############ 412X64 ###########

                $table->logo = $fileName;
            }

            $table->save();

        }catch (QueryException $ex) {
            if($request->hasFile('logo')) {
                $img = $fileName;
                $img2 = $fileNameSm;
                $exists = Storage::disk('business')->exists($img);
                if ($exists) {
                    Storage::disk('business')->delete($img);
                }

                $exists2 = Storage::disk('business')->exists($img2);
                if ($exists2) {
                    Storage::disk('business')->delete($img2);
                }
            }
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

            $table = Warehouse::find($id);

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
    public function update(Request $request)
    {
        $id = $request->id;

        $validator = Validator::make($request->all(), [
            'name' => 'string|required|max:191'
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        try{

            $table = Warehouse::find($id);
            $table->name = $request->name;
            $table->contact = $request->contact;
            $table->contact_alternate = checkNull($request->contact_alternate);
            $table->phone = checkNull($request->phone);
            $table->address = checkNull($request->address);
            $table->email = checkNull($request->email);
            $table->website = checkNull($request->website);
            $table->proprietor = $request->proprietor;

            if($request->hasFile('logo')){

                $img = $table->logo;
                $img2 = 'logo_'.$table->logo;
                $exists = Storage::disk('business')->exists($img);
                if($exists){
                    Storage::disk('business')->delete($img);
                }

                $exists2 = Storage::disk('business')->exists($img2);
                if($exists2){
                    Storage::disk('business')->delete($img2);
                }

                //############ 206X32 ###########
                $fileNameSm = 'logo_'.md5(date('d-m-y H:i:s')).'.png';
                $photoSm = Image::make($request->file('logo'));
                $photoSm->resize(206, 32, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $photoSm->resizeCanvas(206, 32, 'center', false, 'rgba(255, 255, 255, 0)');
                $photo_main = $photoSm->stream('png');

                Storage::disk('business')->put($fileNameSm, $photo_main->__toString());
                //############ 206X32 ###########

                //############ 412X64 ###########
                $fileName = md5(date('d-m-y H:i:s')).'.png';
                $photo = Image::make($request->file('logo'));
                $photo->resize(412, 64, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $photo->resizeCanvas(412, 64, 'center', false, 'rgba(255, 255, 255, 0)');
                $photo_main = $photo->stream('png');

                Storage::disk('business')->put($fileName, $photo_main->__toString());
                //############ 412X64 ###########

                $table->logo = $fileName;
            }

            $table->save();

        }catch (QueryException $ex) {
            if($request->hasFile('logo')) {
                $img = $fileName;
                $img2 = $fileNameSm;
                $exists = Storage::disk('business')->exists($img);
                if ($exists) {
                    Storage::disk('business')->delete($img);
                }

                $exists2 = Storage::disk('business')->exists($img2);
                if ($exists2) {
                    Storage::disk('business')->delete($img2);
                }
            }
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

            $table = Warehouse::find($id);
            if($table->count() > 0){
                $img = $table->logo;
                $img2 = 'logo_'.$table->logo;
                $exists = Storage::disk('business')->exists($img);
                if ($exists) {
                    Storage::disk('business')->delete($img);
                }

                $exists2 = Storage::disk('business')->exists($img2);
                if ($exists2) {
                    Storage::disk('business')->delete($img2);
                }
            }


            Warehouse::destroy($id);

        }catch (QueryException $ex) {
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }
        return response()->json(config('naz.del'));
    }
}
