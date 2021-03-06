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

        $this->router->group(['middleware' => ['cross', 'web'], 'prefix' => 'api/vuser'], function () {
            $this -> router -> post('/token', VuserController::class . '@token');
            $this -> router -> post('/', VuserController::class . '@register');
            $this -> router -> post('/password', VuserController::class . '@forgetPassword');
        });

        $this->router->group(['middleware' => ['auth:api','cross', 'web'], 'prefix' => 'api/vuser'], function () {
            $this -> router -> put('/password', VuserController::class . '@modifyPassword');
        });

//        $this->router->group(['middleware' => ['api', 'cross', 'web'], 'prefix' => 'api/vuser'], function () {
//            $this -> router -> post('/', VuserController::class . '@access');
//        });

        $this -> router -> group(['prefix' => 'test/vuser'], function() {
            $this -> router -> get('/', VuserController::class . '@show');
        });

    }




}