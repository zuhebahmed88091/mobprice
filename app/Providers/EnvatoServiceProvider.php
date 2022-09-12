<?php

namespace App\Providers;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class EnvatoServiceProvider extends AbstractProvider implements ProviderInterface
{

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://api.envato.com/authorization', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://api.envato.com/token';
    }


    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.envato.com/v1/market/private/user/account.json', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        $response = json_decode($response->getBody()->getContents(), true)['account'];
        $response['email'] = $this->getEmailByToken($token);
        $response['username'] = $this->getUsernameByToken($token);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['username'],
            'nickname' => $user['username'],
            'name' => $user['firstname'].' '.$user['surname'],
            'email' => $user['email'],
            'avatar' => $user['image']
        ]);
    }

    /**
     *  Get the account email of the current user.
     *
     * @param string $token
     *
     * @return string
     */
    protected function getEmailByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.envato.com/v1/market/private/user/email.json', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true)['email'];
    }

    /**
     *  Get the account username of the current user.
     *
     * @param string $token
     *
     * @return string
     */
    protected function getUsernameByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.envato.com/v1/market/private/user/username.json', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true)['username'];
    }
}
