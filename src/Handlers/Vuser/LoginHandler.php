<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-28
 * Time: 上午11:09
 */
namespace Notadd\Vuser\Handlers\Vuser;

use Exception;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\Auth\AuthenticatesUsers;
use Notadd\Foundation\Translation\Translator;
use Illuminate\Container\Container;
use League\OAuth2\Server\AuthorizationServer;
use Illuminate\Routing\UrlGenerator;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Zend\Diactoros\Response as Psr7Response;
use Laravel\Passport\Client as PassportClient;

class LoginHandler extends Handler {

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

    protected function execute() {

        $this->validateLogin($this->request);

        $this->validate($this->request, [
            $this -> username() => 'regex:/^[0-9a-zA-Z_\/-]+$/',
            'password' => 'regex:/^[0-9a-zA-Z_\/-]+$/'
        ], [
            $this -> username().'.regex' => '用户名包含非法字符',
            'password.regex' => '密码包含非法字符',
        ]);

        if ($this->hasTooManyLoginAttempts($this->request)) {
            $this->fireLockoutEvent($this->request);
            $seconds = $this->limiter()->availableIn($this->throttleKey($this->request));
            $message = $this->translator->get('auth.throttle', ['seconds' => $seconds]);

            return $this -> withCode(413)
                -> withError($message);
        }

        $this->incrementLoginAttempts($this->request);

        $credentials = $this->credentials($this->request);

        if ($this->guard()->attempt($credentials, $this->request->has('remember'))) {
            $this->request->session()->regenerate();
            $this->clearLoginAttempts($this->request);

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

                    return $this -> withCode(200)
                        -> withData($back)
                        -> withMessage('vuser::login.1000');

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
        }

        return $this -> withCode(415)
            -> withMessage('vuser::login.1001');

    }

    public function username() {
        return 'name';
    }

}