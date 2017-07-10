<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-28
 * Time: 下午5:07
 */

namespace Notadd\Vuser\Handlers\Vuser;

use Exception;
use Illuminate\Container\Container;
use Notadd\Foundation\Routing\Abstracts\Handler;
use League\OAuth2\Server\AuthorizationServer;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Illuminate\Auth\AuthManager;
use Zend\Diactoros\Response as Psr7Response;

class AccessHandler extends Handler {

    protected $auth;
    protected $server;
    protected $client_id;
    protected $client_secret;

    public function __construct(Container $container, AuthorizationServer $server, AuthManager $auth) {
        parent::__construct($container);
        $this -> auth = $auth;
        $this -> server = $server;
    }

    protected function execute() {
        if ($this -> auth ->guard('api')->user()) {
            try {
                $this->request->offsetSet('grant_type', 'client_credentials');
                $this->request->offsetSet('client_id', $this->client_id);
                $this->request->offsetSet('client_secret', $this->client_secret);
                $this->request->offsetSet('scope', '*');
                $request = (new DiactorosFactory)->createRequest($this->request);
                $back = $this->server->respondToAccessTokenRequest($request, new Psr7Response());
                $back = json_decode((string)$back->getBody(), true);
                if (isset($back['access_token'])) {
                    return $this -> withCode(200)
                        -> withData($back)
                        -> withMessage('vuser::login.1003');
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

        return $this -> withCode(414)
            -> withError('vuser::login.1004');
    }

}