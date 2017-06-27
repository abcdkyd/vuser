<?php

/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-22
 * Time: 下午2:55
 */
namespace Notadd\Vuser\Controllers\Api;

use Exception;
use Illuminate\Auth\AuthManager;
use League\OAuth2\Server\AuthorizationServer;
use Notadd\Foundation\Auth\AuthenticatesUsers;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Illuminate\Support\Facades\Auth;
use Notadd\Foundation\Translation\Translator;

class VuserController extends Controller {

    use AuthenticatesUsers;

    protected $translator;

    public function __construct(Translator $translator) {
        parent::__construct();
        $this -> translator = $translator;
    }

    public function show() {
        return view('index');
    }

    public function access(AuthManager $auth) {
        if($auth->guard('api')->user()) {




            return response() -> json([
                'status' => 'ok',
                'msg' => '已登录',
                'code' => 200
            ]);
        }

        return response() -> json([
            'status' => 'error',
            'msg' => '请先登录',
            'code' => 410
        ]);
    }

    public function login() {

        $this->validateLogin($this->request);
        if ($this->hasTooManyLoginAttempts($this->request)) {
            $this->fireLockoutEvent($this->request);
            $seconds = $this->limiter()->availableIn($this->throttleKey($this->request));
            $message = $this->translator->get('auth.throttle', ['seconds' => $seconds]);

            return response() -> json([
                'code'  => 403,
                'message' => $message,
            ]);
        }

        $credentials = $this->credentials($this->request);

        if ($this->guard()->attempt($credentials, $this->request->has('remember'))) {
            $this->request->session()->regenerate();
            $this->clearLoginAttempts($this->request);
            try {

            } catch (Exception $exception) {

            }
        }

        $this->incrementLoginAttempts($this->request);

        return response() -> json([
            'status' => 'error',
            'msg' => 'false',
            'code' => 310
        ]);

    }

    public function username() {
        return 'name';
    }


}