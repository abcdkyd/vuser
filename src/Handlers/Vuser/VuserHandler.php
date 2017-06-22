<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-22
 * Time: 下午2:59
 */

namespace Notadd\Member\Handlers\User;

use Illuminate\Container\Container;
use Notadd\Foundation\Routing\Abstracts\Handler;

class VuserHandler extends Handler {

    public function __construct(Container $container) {
        parent::__construct($container);
    }

    /**
     * Execute Handler.
     *
     * @throws \Exception
     */
    protected function execute()
    {
        $data = [
            'msg' => 'this is a test'
        ];
        return json_encode($data);
    }

}