<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpdateModel;
class MessageController extends Controller
{
    public function getData(Request $request)
    {
        $phonenumber = file_get_contents('php://input');
        $decode_phonenumber = json_decode($phonenumber, true);
        $phonenumber_decode = $decode_phonenumber['phonenumber'];
        
        $data = UpdateModel::where('phonnumber', $phonenumber_decode)->get();
        return response()->json($data); // Return the fetched data as JSON response
    }

    public function getPhoneNumbers()
    {
        $phone_number = UpdateModel::select('phonnumber')->distinct()->get();
        // $data = UpdateModel::where('phonnumber', $phone_number)->get();
        return response()->json($phone_number); // Return the fetched data as JSON response
    }
}
