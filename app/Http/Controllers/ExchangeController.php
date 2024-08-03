<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    //

    public function getProductImg($id){

        $imageContent = Product::where('id',$id)->first()->img;
     
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageContent);
     //   $mimeType = 'image/jpeg'; // Adjust this based on your actual image type
        return Response::make($imageContent, 200, ['Content-Type' => $mimeType]);
    
    }

    public function getAll()
    {
        $data = [];
        return response()->json($data);
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }

    public function create(Request $request)
    {

        $data = [];
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {

        $data = [];
        return response()->json($data);
    }

    public function delete($id)
    {

        $data = [];
        return response()->json($data);
    }
}
