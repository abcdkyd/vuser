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
use Notadd\Vuser\Injections\Installer;
use Notadd\Vuser\Injections\Uninstaller;

class ModuleServiceProvider extends ServiceProvider {

    public function boot() {
        $this->app->make(Dispatcher::class)->subscribe(RouteRegister::class);
    }

    /**
     * Description of module
     *
     * @return string
     */
    public static function description()
    {
        return 'Notadd 用户管理模块';
    }

    /**
     * Name of module.
     *
     * @return string
     */
    public static function name()
    {
        return '用户管理';
    }


    /**
     * Install module.
     *
     * @return string
     */
    public static function install()
    {
        return Installer::class;
    }

    /**
     * Uninstall module.
     *
     * @return string
     */
    public static function uninstall()
    {
        return Uninstaller::class;
    }
}
