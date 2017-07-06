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
use Notadd\Vcaptcha\Models\VerifyCode;
use Notadd\Member\Models\Member;

class RegisterHandler extends Handler {

    public function __construct(Container $container) {
        parent::__construct($container);
    }

    public function execute() {

        $this->validate($this->request, [
            $this -> username() => 'required|regex:/^[0-9a-zA-Z_\/-]+$/',
            'password' => 'required|regex:/^[0-9a-zA-Z_\/-]+$/',
            'verifycode' => 'required|regex:/^\d{6}+$/'
        ], [
            $this -> username().'.regex' => '用户名包含非法字符',
            $this -> username().'.required' => '请输入用户名',
            'password.regex' => '密码包含非法字符',
            'password.required' => '请输入密码',
            'verifycode.regex' => '请输入正确的手机验证码',
            'verifycode.required' => '请输入手机验证码',
        ]);

        $request_data = $this -> request -> all();

        // 检查用户是否存在
        if(Member::query() -> where(['name' => $request_data['name']]) -> exists()) {
            return $this -> withCode(423) -> withError('vuser::register.1014');
        }

        // 检查验证码
        $query = VerifyCode::query() -> where(['phone' => $request_data['name']]) -> orderByDesc('created_at') -> first();

        if(!$query) {
            return $this -> withCode(416) -> withError('vuser::register.1012');
        }

        $query_data = $query->toArray();

        // todo add verify time limit
        if($request_data['verifycode'] == $query_data['code_sended']) {

            $data = [
                'name' => $request_data['name'],
                'password' => bcrypt($request_data['password']),
                'email' => '',
                'phone' => $request_data['name']
            ];

            if (Member::query()->create($data)) {
                return $this->withCode(200)->withMessage('vuser::register.1010');
            } else {
                return $this -> withCode(421) -> withError('vuser::register.1011');
            }
        } else {
            return $this -> withCode(422) -> withError('vuser::register.1013');
        }


    }

    public function username() {
        return 'name';
    }

}