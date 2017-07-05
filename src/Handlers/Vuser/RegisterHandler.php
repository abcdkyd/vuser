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
use Notadd\Vcaptcha\Models\VcaptchaLog;
use Notadd\Member\Models\Member;

class RegisterHandler extends Handler {

    public function __construct(Container $container) {
        parent::__construct($container);
    }

    public function execute() {

        $code = $this -> request -> input('verifycode');
        $name = $this -> request -> input('name');

        $query = VcaptchaLog::query() -> where(['phone' => $name]) -> orderBy('created_at', 'desc') -> first();

        if($code == $query['code_sended']) {
            $data = [
                'name' => $this -> request -> input('name'),
                'password' => bcrypt($this -> request -> input('password')),
                'email' => '',
            ];
            if (Member::query()->create($data)) {
                return $this->withCode(200)->withMessage('注册成功！');
            } else {
                return $this->withCode(500)->withError('注册失败2！');
            }
        }

        return $this -> withCode(500) -> withError('注册失败1');

    }

}