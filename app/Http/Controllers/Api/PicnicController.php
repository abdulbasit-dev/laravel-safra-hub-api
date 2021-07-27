<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Picnic;
use Illuminate\Http\Request;

class PicnicController extends Controller
{
    public function index()
    {
        return "all picnics";
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Picnic  $picnic
     * @return \Illuminate\Http\Response
     */
    public function show(Picnic $picnic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Picnic  $picnic
     * @return \Illuminate\Http\Response
     */
    public function edit(Picnic $picnic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Picnic  $picnic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Picnic $picnic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Picnic  $picnic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Picnic $picnic)
    {
        //
    }
}
