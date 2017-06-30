<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-21
 * Time: 下午6:01
 */

namespace Notadd\Vuser;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Events\Dispatcher;
use Notadd\Vuser\Listeners\CsrfTokenRegister;
use Illuminate\Support\ServiceProvider;
use Notadd\Vuser\Listeners\RouteRegister;
use Notadd\Vuser\Injections\Installer;
use Notadd\Vuser\Injections\Uninstaller;

class ModuleServiceProvider extends ServiceProvider {

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function boot() {
        $this->app->make(Dispatcher::class)->subscribe(CsrfTokenRegister::class);
        $this->app->make(Dispatcher::class)->subscribe(RouteRegister::class);
        $this->loadTranslationsFrom(realpath(__DIR__ . '/../resources/translations'), 'vuser');
        $this->loadViewsFrom(realpath(__DIR__ . '/../resources/views'), 'vuser');
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

    /**
     * Version of module.
     *
     * @return string
     */
    public static function version() {
        return '0.1.0';
    }
}
