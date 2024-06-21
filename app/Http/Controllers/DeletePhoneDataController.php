<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpdateModel;

class DeletePhoneDataController extends Controller
{
    //
    public function deletePhone(Request $request)
    {
        $message = file_get_contents('php://input');
        $message_decode = json_decode($message, true);
        $phoneNumber = $message_decode['phonenumber'];
        UpdateModel::where('phonnumber', $phoneNumber)->delete();
        return response()->json(['message' => $phoneNumber . ' deleted successfully']);
    }

    public function deleteMessage(Request $request)
    {
        $message = file_get_contents('php://input');
        $message_decode = json_decode($message, true);
        $id = $message_decode['id'];
        UpdateModel::where('id', $id)->delete();
        return response()->json(['message' => $id . ' deleted successfully']);
    }

}
