<?php

/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-22
 * Time: 下午2:55
 */
namespace Notadd\Vuser\Controllers\Api;

use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Vuser\Handlers\Vuser\VuserHandler;

class VuserController extends Controller {

    public function getIndex(VuserHandler $handler) {
        return $handler->toResponse()->generateHttpResponse();
    }

}