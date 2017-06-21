<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-21
 * Time: 下午6:01
 */

namespace Notadd\Vuser;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Notadd\Vuser\Listeners\RouteRegister;

class ModuleServiceProvider extends ServiceProvider {

    public function boot() {
        $this->app->make(Dispatcher::class)->subscribe(RouteRegister::class);
    }

}
