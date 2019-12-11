<?php

namespace app\api\behavior;

use think\Response;

class CORS
{
    public function appInit(&$params)
    {


        if (request()->isOptions()) {
            header('Access-Control-Allow-Origin:http://127.0.0.1:8000');
            header('Access-Control-Allow-Headers:Accept,Referer,Host,Keep-Alive,User-Agent,X-Requested-With,Cache-Control,Content-Type,Cookie,access_token,token');
            header('Access-Control-Allow-Credentials:true');
            header('Access-Control-Allow-Methods:GET,POST,OPTIONS');
            header('Access-Control-Max-Age:1728000');
            header('Content-Type:text/plain charset=UTF-8');
            header('Content-Length: 0', true);
            header('status: 204');
            header('HTTP/1.0 204 No Content');
        }else{
            header('Access-Control-Allow-Origin:http://127.0.0.1:8000');
            header('Access-Control-Allow-Headers:Accept,Referer,Host,Keep-Alive,User-Agent,X-Requested-With,Cache-Control,Content-Type,Cookie,token,access_token');
            header('Access-Control-Allow-Credentials:true');
            header('Access-Control-Allow-Methods:GET,POST,OPTIONS');
        }
    }
}
