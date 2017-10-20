<?php

namespace app\components\registers\Adapter;

use app\components\registers\Exception\HttpException;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttpAdapter implements AdapterInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Response|ResponseInterface
     */
    protected $response;

    /**
     * GuzzleHttpAdapter constructor.
     * @param ClientInterface|null $client
     * @param array $options
     */
    public function __construct(ClientInterface $client = null, $options = [])
    {
        $this->client = $client ?: new Client($options);
    }

    /**
     * @param string $url
     * @param array $data
     * @return \GuzzleHttp\Psr7\Stream|\Psr\Http\Message\StreamInterface
     */
    public function get($url, array $data = [])
    {
        try {
            $this->response = $this->client->get($url);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }
        return $this->response->getBody();
    }

    /**
     * @param string $url
     * @param string $content
     * @return \GuzzleHttp\Psr7\Stream|\Psr\Http\Message\StreamInterface
     */
    public function post($url, $content = '')
    {
        $options = [];
//        $options['form_params'] = $content;
        $options[is_array($content) ? 'json' : 'body'] = $content;
        try {
            $this->response = $this->client->post($url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }
        return $this->response->getBody();
    }

    /**
     * @throws HttpException
     */
    protected function handleError()
    {
        $body = (string)$this->response->getBody();
        $code = (int)$this->response->getStatusCode();
        $content = json_decode($body);
        throw new HttpException(isset($content->message) ? $content->message : 'Запрос не обработан.', $code);
    }

}
