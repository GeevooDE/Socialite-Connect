<?php

namespace GeevooDE\OAuth2;

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    public const IDENTIFIER = 'GEEVOOPROVIDER';

    /**
     * {@inheritdoc}
     */
    protected $scopes = [
        'user-info',
        'user-avatar',
        'user-address',
        'card',
        'qr',
    ];

    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritdoc}
     */
    public static function additionalConfigKeys()
    {
        return [
            'host',
            'authorize_uri',
            'token_uri',
            'userinfo_uri',
            'userinfo_key',
            'user_id',
            'user_first_name',
            'user_last_name',
            'user_email',
            'user_date_of_birth',
            'guzzle',
        ];
    }

    /**
     * Get the authentication URL for the provider.
     *
     * @param string $state
     *
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getGeevooUrl('authorize_uri'), $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->getGeevooUrl('token_uri');
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param string $token
     *
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getGeevooUrl('userinfo_uri'), [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return (array) json_decode((string) $response->getBody(), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param array $user
     *
     * @return GeevooUser
     */
    protected function mapUserToObject(array $user)
    {
        $key = $this->getConfig('userinfo_key', null);
        $data = is_null($key) === true ? $user : Arr::get($user, $key, []);

        return (new GeevooUser())
            ->setRaw($data)
            ->map([
                'id'       => $this->getUserData($data, 'id'),
                'first_name'     => $this->getUserData($data, 'first_name'),
                'last_name'     => $this->getUserData($data, 'last_name'),
                'email'    => $this->getUserData($data, 'email'),
                'date_of_birth'    => $this->getUserData($data, 'date_of_birth'),
            ]);
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param string $code
     *
     * @return array
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }

    protected function getGeevooUrl($type)
    {
        # http://localhost:8000
        return rtrim('http://localhost:8000', '/').'/'.ltrim(($this->getConfig($type, Arr::get([
            'authorize_uri' => 'oauth/authorize',
            'token_uri'     => 'oauth/token',
            'userinfo_uri'  => 'oauth/user',
            'avatar_uri'  => 'oauth/avatar',
            'address_uri'  => 'oauth/address',
            'qr_uri'  => 'oauth/qr',
            'card_uri'  => 'oauth/card',
        ], $type))), '/');
    }

    protected function getUserData($user, $key)
    {
        return Arr::get($user, $this->getConfig('user_'.$key, $key));
    }
}
