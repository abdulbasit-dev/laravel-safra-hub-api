<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Picnic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PicnicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }




    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validation
        $validator = Validator::make(
            $request->all(),
            [
                'location' => ['required', 'string', 'max:100'],
                'description' => ['string'],
                'currency_type' => ['required', 'integer'],
                'type' => ['required', 'integer'],
                'start_at' => ['required'],
            ]
        );

        if ($validator->fails()) {
            return response()->validation(422, $validator->errors()->all());
        }


        try {
            //generate code
            $code = strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 5));

            //generate qrcode
            $location_slug = Str::slug($request->location);
            $qr = QrCode::format('svg');
            $qr->margin(1);
            $qr->size(300);
            $qr->errorCorrection('H');
            $qr->generate(
                'http://www.simplesoftware.io',
                '../public/uploads/qrcodes/picnic/' . $location_slug . '.svg'
            );

            $picnic = Picnic::create(
                $request->all() + [
                    'created_by' => auth()->id(),
                    'code' =>$code,
                    'qrcode' =>'/uploads/qrcodes/picnic/' . $location_slug . '.svg',
                ]
            );
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        return response()->created(201, __('api.picnic_created'), $picnic);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Picnic $picnic
     * @return \Illuminate\Http\Response
     */
    public function show(Picnic $picnic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Picnic $picnic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Picnic $picnic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Picnic $picnic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Picnic $picnic)
    {
        //
    }
}
