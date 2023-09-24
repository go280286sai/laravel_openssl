<?php

namespace go280286sai\laravel_openssl\Http\Controllers;

use App\Http\Controllers\Controller;
use go280286sai\laravel_openssl\Models\Ssl_search;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class OpenSslController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|string
     */
    public function index(Request $request): JsonResponse|string
    {
        $request->validate([
            'name'=> 'required|string',
            'text' => 'required|string',
        ]);
        $id = Ssl_search::where('name', $request->name)->first('id');
        if(!empty($id)){
            $data = $request->text;
            $data[0]=trim($data[0]);
            $data[1]=trim($data[1]);
            return Ssl_search::decrypt($data, Ssl_search::get_public_key($id['id'].'_id_public'));
        } else{

            return Response::json(['message' => 'Not found'], 404);
        }
  }
}
