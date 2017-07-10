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
use Notadd\Verify\Models\VerifyCode;
use Notadd\Member\Models\Member;
use Notadd\Foundation\Auth\AuthenticatesUsers;
use Notadd\Foundation\Translation\Translator;
use League\OAuth2\Server\AuthorizationServer;
use Illuminate\Routing\UrlGenerator;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Zend\Diactoros\Response as Psr7Response;
use Laravel\Passport\Client as PassportClient;
use Exception;


class RegisterHandler extends Handler {

    use AuthenticatesUsers;

    /**
     * @var int
     */
    protected $client_id;

    /**
     * @var string
     */
    protected $client_secret;

    /**
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    protected $server;

    /**
     * @var \Notadd\Foundation\Translation\Translator
     */
    protected $translator;

    /**
     * @var \Illuminate\Routing\UrlGenerator
     */
    protected $url;

    public function __construct(Container $container, AuthorizationServer $server, Translator $translator) {
        parent::__construct($container);
        $this->client_id = 1;
        $client = PassportClient::query()->findOrFail($this->client_id);
        $this->client_secret = $client->getAttribute('secret');
        $this->translator = $translator;
        $this->url = $this->container->make(UrlGenerator::class);
        $this->server = $server;
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
                'email' => $request_data['name'],
                'phone' => $request_data['name']
            ];

            if (Member::query()->create($data)) {

                $register_login = [];
                $this->request->session()->regenerate();

                try {
                    $this->request->offsetSet('grant_type', 'password');
                    $this->request->offsetSet('client_id', $this->client_id);
                    $this->request->offsetSet('client_secret', $this->client_secret);
                    $this->request->offsetSet('username', $this->request->offsetGet($this->username()));
                    $this->request->offsetSet('password', $this->request->offsetGet('password'));
                    $this->request->offsetSet('scope', '*');
                    $request = (new DiactorosFactory)->createRequest($this->request);
                    $back = $this->server->respondToAccessTokenRequest($request, new Psr7Response());
                    $back = json_decode((string)$back->getBody(), true);
                    if (isset($back['access_token']) && isset($back['refresh_token'])) {
                        $register_login = $back;
                    }

                } catch (Exception $exception) {

                    return $this -> withCode(414)
                        -> withData([
                            'exception_code' => $exception->getCode(),
                            'message' => $exception->getMessage(),
                            'trace' => $exception->getTraceAsString()
                        ])
                        -> withError('vuser::login.1002');

                }

                return $this->withCode(200)
                    -> withData($register_login)
                    ->withMessage('vuser::register.1010');

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