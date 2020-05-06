<?php

namespace App\Http\Controllers;

use App\Business;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $table = Business::orderBy('id', 'DESC')->get();

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
            'contact' => 'string|required|min:10|max:20',
            'address' => 'string|required|max:191',
            'proprietor' => 'string|required|max:191',
            //'software_key' => 'string|required|max:191|unique:businesses',
            'logo' => 'image|required',
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        try{

            $table = new Business();
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

            $table->software_key = $request->software_key;
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

            $table = Business::find($id);

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
       // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'numeric|required',
            'name' => 'string|required|max:191',
            'contact' => 'string|required|min:10|max:20',
            'address' => 'string|required|max:191',
            'proprietor' => 'string|required|max:191',
            //'software_key' => 'string|required|max:191|unique:businesses,software_key,'.$request->id.',id',
           // 'logo' => 'image|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(validate_error($validator->errors()), config('naz.validation_error_status_code'));
        }

        try{

            $table = Business::find($id);
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

            Business::destroy($id);

        }catch (QueryException $ex) {
            return response()->json(db_error($ex), config('naz.query_error_status_code'));
        }
        return response()->json(config('naz.del'));
    }
}
