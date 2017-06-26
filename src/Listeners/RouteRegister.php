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
        $this -> router -> group(['middleware' => ['auth:api', 'cross', 'web'], 'prefix' => 'api/vuser'], function () {
            $this -> router -> get('getIndex', VuserController::class . '@getIndex');
        });

        $this -> router -> group(['middleware' => ['cross', 'web'], 'prefix' => 'vuser'], function () {
            $this -> router -> post('token', VuserController::class . '@token');
        });
    }




}