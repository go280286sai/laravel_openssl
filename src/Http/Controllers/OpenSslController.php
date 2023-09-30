<?php

namespace go280286sai\laravel_openssl\Http\Controllers;

use App\Http\Controllers\Controller;
use go280286sai\laravel_openssl\Log\LogMessage;
use go280286sai\laravel_openssl\Models\Ssl_search;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class OpenSslController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'text' => 'required',
        ]);
        $id = Ssl_search::where('name', $request->input('name'))->first('id');

        if(!empty($id)){
            $data = $request->input('text');
            $data[0]=trim($data[0]);
            $data[1]=trim($data[1]);
            $res = Ssl_search::decrypt($data, Ssl_search::get_public_key($id['id'].'_id_public'));
//            LogMessage::send($res);
            return $res;
        } else{
            return Response::json(['message' => 'Not found'], 404);
        }
    }
}
