<?php

namespace App\Services\api;


class ApiResponseService{

    static  function response($status=200,$message=null,$data=[]){

       $response= [
          "status"=>$status,
          "message"=>$message,
          "data"=>$data,
       ];

       return response()->json($response,$status);

    }
}
