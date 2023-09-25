<?php

namespace go280286sai\laravel_openssl\Http\Controllers;

use App\Http\Controllers\Controller;
use go280286sai\laravel_openssl\Log\LogMessage;
use go280286sai\laravel_openssl\Models\Ssl_search;
use Illuminate\Http\Request;

class OpenSslController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function index(Request $request): string
    {
        $id = Ssl_search::where('name', $request->get('name'))->first('id');
        try {
            $result = Ssl_search::decrypt($request->get('text'), Ssl_search::get_public_key($id->id . '_id_public'));
            LogMessage::send($result);

            return 'ok';
        }
        catch (\Throwable $th) {
            LogMessage::send('error: '. $th->getMessage());

            return $th->getMessage();
        }
    }
}
