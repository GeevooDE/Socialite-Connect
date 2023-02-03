<?php

namespace GeevooDE\OAuth2;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use SocialiteProviders\Manager\OAuth2\User;

class GeevooUser extends User
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->user['first_name'].' '.$this->user['last_name'];
    }

    /**
     * {@inheritdoc}
     */
    public function getAvatar()
    {
        try {
            $response = $this->getWithAuth('http://localhost:8000/oauth/avatar', [
                'Accept' => 'image/*',
            ]);

            return (new GeevooAvatar())->setResponse($response);
        } catch (ClientException|GuzzleException $e) {
            return null;
        }
    }

    public function getCard()
    {
        try {
            $response = $this->getWithAuth('http://localhost:8000/oauth/card', [
                'Accept' => 'image/*',
            ]);

            return (new GeevooCard())->setResponse($response);
        } catch (ClientException|GuzzleException $e) {
            return null;
        }
    }

    public function getQr($extended = false)
    {
        $response = $this->getWithAuth('http://localhost:8000/oauth/qr', [
            'Accept' => 'image/*',
        ], [
            'detailed' => $extended,
        ]);

        return (new GeevooQr())->setResponse($response);
        try {

        } catch (ClientException|GuzzleException $e) {
            return null;
        }
    }

    public function getAddress()
    {
        try {
            $response = $this->getWithAuth('http://localhost:8000/oauth/address');

            return (new GeevooAddress())->setResponse($response);
        } catch (ClientException|GuzzleException $e) {
            return null;
        }
    }

    public function getDateOfBirth()
    {
        return $this->date_of_birth;
    }

    private function getWithAuth($url, $headers = [], $body = [])
    {
        $client = new Client();

        return $client->get(
            $url,
            [
                'form_params' => $body,

                RequestOptions::HEADERS => [
                    ...$headers,
                    'Authorization' => 'Bearer '.$this->token,
                ],
            ]
        );
    }
}