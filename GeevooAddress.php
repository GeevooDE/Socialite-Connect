<?php

namespace GeevooDE\OAuth2;

use GuzzleHttp\Psr7\Response;

class GeevooAddress
{
    /**
     * The Guzzle Response object.
     *
     * @var \GuzzleHttp\Psr7\Response
     */
    public $response;

    /**
     * Set the response of the avatar.
     *
     * @param string $response
     *
     * @return $this
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Return the content type of the avatar.
     *
     * @return string
     */
    public function getContentType(): string
    {
        return $this->response->getHeader('content-type')[0];
    }

    /**
     * Return the avatar in binary.
     */
    public function getContents()
    {
        return (array) json_decode((string) $this->response->getBody());
    }

    public function getStreet()
    {
        return $this->getContents()['street'];
    }

    public function getCity()
    {
        return $this->getContents()['city'];
    }

    public function getPostal()
    {
        return $this->getContents()['postal'];
    }
}