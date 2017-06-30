<?php

/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-22
 * Time: 下午2:55
 */
namespace Notadd\Vuser\Controllers\Api;

use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Vuser\Handlers\Vuser\AccessHandler;
use Notadd\Vuser\Handlers\Vuser\LoginHandler;
use Notadd\Vuser\Handlers\Vuser\RegisterHandler;

class VuserController extends Controller {

    protected $translator;

    public function __construct() {
        parent::__construct();
    }

    public function show() {
        return view('vuser::index');
    }

    public function access(AccessHandler $handler) {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function token(LoginHandler $handler) {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function register(RegisterHandler $handler) {
        return $handler->toResponse()->generateHttpResponse();
    }

}