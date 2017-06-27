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

        $this->router->group(['middleware' => ['cross', 'web'], 'prefix' => 'vuser'], function () {
            $this -> router -> post('token', VuserController::class . '@token');
        });

        $this->router->group(['middleware' => ['api', 'cross', 'web'], 'prefix' => 'vuser'], function () {
            $this -> router -> get('/', VuserController::class . '@show');
            $this -> router -> post('/', VuserController::class . '@access');

        });

    }




}