<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-22
 * Time: 下午3:05
 */
namespace Notadd\Vuser\Listeners;

use Notadd\Foundation\Routing\Abstracts\RouteRegister as AbstractRouteRegister;
use Notadd\Vuser\Controllers\Api\VuserController;


class RouteRegister extends AbstractRouteRegister {

    public function handle() {
        $this -> router -> group(['prefix' => 'api/vuser'], function () {
            $this -> router -> get('getIndex', VuserController::class . '@getIndex');
        });
    }

}