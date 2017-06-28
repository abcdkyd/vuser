<?php

/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-22
 * Time: 下午2:55
 */
namespace Notadd\Vuser\Controllers\Api;

use Illuminate\Auth\AuthManager;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Vuser\Handlers\Vuser\LoginHandler;
use Illuminate\Support\Facades\Auth;

class VuserController extends Controller {

    protected $translator;

    public function __construct() {
        parent::__construct();
    }

    public function show() {
        return view('vuser::index');
    }

    public function access(AuthManager $auth) {

        dd(Auth::id());

        if($auth->guard('api')->user()) {

            return response() -> json([
                'status' => 'ok',
                'msg' => '已登录',
                'code' => 200
            ]);
        }

        return response() -> json([
            'status' => 'error',
            'msg' => '请先登录',
            'code' => 410
        ]);
    }

    public function token(LoginHandler $handler) {
        return $handler->toResponse()->generateHttpResponse();
    }

}