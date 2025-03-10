<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
  public function SendResponse($data,$message){
    $response = [
        'status'=>true,
        'message'=>$message,
        'data'=>$data
    ];
    return response()->json($response,200);
  }

  public function sendError($err,$errorMessage = [],$code=404){
        $response =[
            'status'=>false,
            'message'=>$err,
        ];
        if(!empty($errorMessage)){
                $response['data']= $errorMessage;
        }
        return response()->json($response,$code);
  }
}
