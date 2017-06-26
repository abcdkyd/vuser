<?php

/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-22
 * Time: 下午2:55
 */
namespace Notadd\Vuser\Controllers\Api;

use Notadd\Vuser\Handlers\Vuser\VuserHandler;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Routing\UrlGenerator;
use Laravel\Passport\Client as PassportClient;
use League\OAuth2\Server\AuthorizationServer;
use Notadd\Foundation\Auth\AuthenticatesUsers;
use Notadd\Foundation\Extension\ExtensionManager;
use Notadd\Foundation\Module\ModuleManager;
use Notadd\Foundation\Passport\Responses\ApiResponse;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Foundation\Translation\Translator;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Zend\Diactoros\Response as Psr7Response;


class VuserController extends Controller {

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

    /**
     * AdminController constructor.
     *
     * @param \League\OAuth2\Server\AuthorizationServer $server
     * @param \Notadd\Foundation\Translation\Translator $translator
     */
    public function __construct(AuthorizationServer $server, Translator $translator)
    {
        parent::__construct();
        $this->client_id = 1;
        $client = PassportClient::query()->findOrFail($this->client_id);
        $this->client_secret = $client->getAttribute('secret');
        $this->translator = $translator;
        $this->url = $this->container->make(UrlGenerator::class);
        $this->server = $server;
    }

    public function index() {
        return view('index');
    }

    public function access(AuthManager $auth, ApiResponse $response)
    {
        if ($auth->guard('api')->user()) {
            try {
                $this->request->offsetSet('grant_type', 'client_credentials');
                $this->request->offsetSet('client_id', $this->client_id);
                $this->request->offsetSet('client_secret', $this->client_secret);
                $this->request->offsetSet('scope', '*');
                $request = (new DiactorosFactory)->createRequest($this->request);
                $back = $this->server->respondToAccessTokenRequest($request, new Psr7Response());
                $back = json_decode((string)$back->getBody(), true);
                if (isset($back['access_token'])) {
                    return $response->withParams([
                        'status' => 'success',
                    ])->withParams($back)->generateHttpResponse();
                }
            } catch (Exception $exception) {
                return $response->withParams([
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                ])->generateHttpResponse();
            }
        }

        return $response->withParams([
            'status'  => 'error',
            'message' => 'Please Login !',
        ])->generateHttpResponse();
    }

    public function token(ApiResponse $response)
    {
        $this->validateLogin($this->request);
        if ($this->hasTooManyLoginAttempts($this->request)) {
            $this->fireLockoutEvent($this->request);
            $seconds = $this->limiter()->availableIn($this->throttleKey($this->request));
            $message = $this->translator->get('auth.throttle', ['seconds' => $seconds]);

            return $response->withParams([
                'code'  => 403,
                'message' => $message,
            ])->generateHttpResponse();
        }
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
                    return $response->withParams([
                        'status' => 'success',
                        'message' => $this->translator->trans('administration::login.success'),
                    ])->withParams($back)->generateHttpResponse();
                }
            } catch (Exception $exception) {
                return $response->withParams([
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                ])->generateHttpResponse();
            }
        }

        return $response->withParams([
            'code'  => 403,
            'message' => $this->translator->trans('administration::login.fail'),
        ])->generateHttpResponse();
    }

}