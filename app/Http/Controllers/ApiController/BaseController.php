<?php


namespace App\Http\Controllers\ApiController;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            //'data'    => $result,
            'message' => $message
        ];
		if(gettype($result) == 'array'){
			$merge = array_merge($result,$response);
			return response()->json($merge, 200);
		}else if(gettype($result) == 'object'){
			$result->success = true;
			$result->message = $message;
			return response()->json($result, 200);
		}else{
			if(count($result)>0){
				$result[0]->success = true;
				$result[0]->message = $message;
				return response()->json($result, 200);
			}else{
				$result->success = true;
				$result->message = $message;
				return response()->json($result, 200);
			}
		}
		
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}