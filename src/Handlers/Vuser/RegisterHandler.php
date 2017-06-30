<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-30
 * Time: 上午9:36
 */

namespace Notadd\Vuser\Handlers\Vuser;


use Illuminate\Container\Container;
use Notadd\Foundation\Routing\Abstracts\Handler;

class RegisterHandler extends Handler {

    public function __construct(Container $container) {
        parent::__construct($container);
    }

    public function execute() {
        dd(config('vcaptcha.type'));
    }

}