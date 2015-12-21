<?php

namespace Application\Util;

class Curl
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_HEAD = 'HEAD';

    const STATUS_OK = 200;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_SERVER_ERROR = 500;

    private $request = null;

    /**
     * @param string $url
     * @param array|string $vars
     * @return array|bool
     * @throws \Exception
     */
    public function get($url, $vars = array())
    {
        if (!empty($vars)) {
            $url .= (stripos($url, '?') !== false) ? '&' : '?';
            $url .= (is_string($vars)) ? $vars : http_build_query($vars, '', '&');
        }
        return $this->callRequest(self::METHOD_GET, $url);
    }

    /**
     * @param string $method
     * @param string $url
     * @return array|bool
     * @throws \Exception
     */
    private function callRequest($method, $url)
    {
        $this->request = curl_init($url);

        if ($this->request) {
            // curl_setopt($this->request, CURLOPT_USERPWD, $login . ':' . $password);
            curl_setopt($this->request, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($this->request, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->request, CURLOPT_RETURNTRANSFER, true);

            //$username = 'test';
            //$password = 'test';
            //curl_setopt($this->request, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            //curl_setopt($this->request, CURLOPT_USERPWD, "$username:$password");

            $accesstoken = 'cd786f52-40ac-4d18-8afc-a76e9441c856';
            curl_setopt($this->request, CURLOPT_POSTFIELDS, urlencode($accesstoken));

            $userAgent = 'MyRobot/1.0 (baptiste.comet@gmail.com)';
            curl_setopt($this->request, CURLOPT_USERAGENT, $userAgent);

            $this->setRequestMethod($method);

            $reply = curl_exec($this->request);
            // echo '<pre>' . var_dump($reply) . '</pre><hr />';

            if ($reply === false) {
                throw new \Exception('An error occurred : ' . curl_error($this->request), 0);
            } else {
                $returnArray = array(
                    'status' => (int) curl_getinfo($this->request, CURLINFO_HTTP_CODE)
                );
                $return = (!empty($reply)) ? array_merge($returnArray, json_decode($reply, true)) : $returnArray;
            }
            curl_close($this->request);
        } else {
            throw new \Exception('An error occurred : curl_init(' . $url . ') = ' . $this->request, 0);
        }

        return $return;
    }

    /**
     * @param string $method
     */
    private function setRequestMethod($method)
    {
        switch (strtoupper($method)) {
            case self::METHOD_HEAD:
                curl_setopt($this->request, CURLOPT_NOBODY, true);
                break;
            case self::METHOD_GET:
                curl_setopt($this->request, CURLOPT_HTTPGET, true);
                break;
            case self::METHOD_POST:
                curl_setopt($this->request, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($this->request, CURLOPT_CUSTOMREQUEST, $method);
        }
    }
}
