<?php

namespace App\Midjourney\Utils;

use React\Http\Browser;
use function React\Async\await;

class Client
{
    protected $browser;
    public function __construct(
        protected array $config = [],
        // protected string $url,
        // protected string $method = 'GET',
        // protected array $headers = [],
        // protected array | string $body = '',
        // protected string $httpProxy = '',

    ) {
        $connector = null;

        if (config('midjourney.http_proxy')) {
            $proxy = new \Clue\React\HttpProxy\ProxyConnector(config('midjourney.http_proxy'));
            $connector = new \React\Socket\Connector([
                'tcp' => $proxy,
                'dns' => false
            ]);
        }
        $this->browser = new Browser($connector);

        if (isset($this->config['timeout'])) {
            $this->browser = $this->browser->withTimeout($this->config['timeout']);
        }

        if (isset($this->config['max_conn_per_addr'])) {
            // todo 
        }
    }

    public function __call($method, $args)
    {
        $this->browser->$method(...$args);
        return $this;
    }

    public function request($url, $options)
    {

       
        return $this->browser->request($options['method'], $url, $options['headers'] ?? [] ,  is_array($options['data']) ?  json_encode($options['data']) : $options['data'])->then(
            function ($response) use ($options) {
                if (isset($options['success'])) {
                    $options['success']($response);
                }
                return $response;
            },
            function ($e) use ($options) {
                if (isset($options['error'])) {
                    $options['error']($e);
                }
                throw $e;
            }
        );
    }

    public function requestStreaming($url, $options)
    {
        return $this->browser->requestStreaming($options['method'], $url, $options['headers'] ?? [] ,  is_array($options['data']) ?  json_encode($options['data']) : $options['data']);
    }

}