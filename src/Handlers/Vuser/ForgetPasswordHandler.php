<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-7-10
 * Time: 下午2:30
 */

namespace Notadd\Vuser\Handlers\Vuser;

use Illuminate\Container\Container;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Vuser\Models\Member;
use Notadd\Verify\Models\VerifyCode;

class ForgetPasswordHandler extends Handler {

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
        if(!Member::query() -> where(['name' => $request_data['name']]) -> exists()) {
            return $this -> withCode(424) -> withError('vuser::password.1001');
        }

        // 检查验证码
        $query = VerifyCode::query() -> where(['phone' => $request_data['name']]) -> orderByDesc('created_at') -> first();

        if(!$query) {
            return $this -> withCode(416) -> withError('vuser::password.1004');
        }

        $query_data = $query->toArray();

        // todo add verify time limit
        if($request_data['verifycode'] == $query_data['code_sended']) {

            $data = [
                'password' => bcrypt($request_data['password']),
            ];

            if (Member::query() -> where(['name' => $request_data['name']]) -> update($data)) {

                return $this->withCode(200)
                    ->withMessage('vuser::password.1002');

            } else {
                return $this -> withCode(425) -> withError('vuser::password.1003');
            }
        } else {
            return $this -> withCode(422) -> withError('vuser::password.1005');
        }

    }

    public function username() {
        return 'name';
    }

}