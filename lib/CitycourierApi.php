<?php

use \League\OAuth2\Client\Provider as OCP;

/**
 * Class CitycourierApi
 *
 * @author Patrik Svajda <patrik@svajda.sk>
 */
class CitycourierApi
{

    /** @var array */
    private $config = [];

    /** @var \League\OAuth2\Client\Token\AccessToken $accessToken */
    private $AccessToken;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * autorizacia uzivatela podla mena a hesla
     *
     * @param string $user
     * @param string $password
     * @return bool|\League\OAuth2\Client\Token\AccessToken
     * @author Patrik Svajda <patrik@svajda.sk>
     */
    public function passwordGrant($user, $password)
    {
        try
        {
            /* resource owner password credentials grant */
            $accessToken = $this->getProvider()->getAccessToken('password', [
                'username' => $user,
                'password' => $password
            ]);

            /* ulozime access token storage */
            $this->setAccessToken($accessToken);
            return $accessToken;

        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e)
        {
            return false;
        } catch (\Exception $e)
        {
            /* ine chyby ako invalid_grant by sa objavovat nemali */
            //TODO: log  
            return false;
        }
    }

    /**
     * GET - ziska autorizovanu ziadost z API servera
     *
     * @param string $urlEndpoint
     * @return object|false
     * @author Patrik Svajda <patrik@svajda.sk>
     */
    public function getResponse(string $urlEndpoint)
    {
        return $this->getParsedResponse('GET', $urlEndpoint);
    }


    /**
     * POST - odosle autorizovanu ziadost na API server
     *
     * @param string $urlEndpoint
     * @param array $body
     * @return false|object
     * @author Patrik Svajda <patrik@svajda.sk>
     */
    public function postResponse(string $urlEndpoint, array $body)
    {
        return $this->getParsedResponse('POST', $urlEndpoint, $body);
    }


    /**
     * odosle autorizovanu ziadost na API server podla zadanej metody
     *
     * @param string $method
     * @param string $urlEndpoint
     * @param array $body
     * @param array $options
     * @return false|object
     * @author Patrik Svajda <patrik@svajda.sk>
     */
    private function getParsedResponse(string $method, string $urlEndpoint, array $body = [], array $options = [])
    {
        /* refresh tokenu sa nepodarilo */
        if ($this->refreshToken() === false)
        {
            //TODO:
        }

        if (!empty($body))
        {
            $defaults['body'] = json_encode($body);
        }

        $defaults['headers'] = ['Content-type' => 'application/json'];

        $options = array_merge_recursive($defaults, $options);

        /* ocistime od dvojitych zatvoriek */
        $endpoint = preg_replace('/([^:])(\/{2,})/', '$1/', $this->config['redirectUri'] . $urlEndpoint);

        try
        {
            $request = $this->getProvider()->getAuthenticatedRequest($method, $endpoint, $this->getAccessToken(), $options);
            $response = $this->getProvider()->getParsedResponse($request);

        } catch (\Exception $e)
        {
            $response = [
                'status' => 'ERR',
                'resp'   => ['statusCode'   => $e->getCode(),
                             'errorMessage' => $e->getMessage()]
            ];
        }

        return $response;
    }


    /**
     * get provider
     *
     * @return OCP\GenericProvider
     * @author Patrik Svajda <patrik@svajda.sk>
     */
    private function getProvider()
    {
        return new OCP\GenericProvider(['clientId'                => $this->config['clientId'],
                                        'clientSecret'            => $this->config['clientSecret'],
                                        'redirectUrl'             => $this->config['redirectUri'],
                                        'urlAuthorize'            => $this->config['urlAuthorize'],
                                        'urlAccessToken'          => $this->config['urlAccessToken'],
                                        'urlResourceOwnerDetails' => $this->config['urlResourceOwnerDetails']
                                       ]);
    }


    /**
     * ziska novy access token, ak ten stary uz vyprsal
     *
     * @return bool
     * @author Patrik Svajda <patrik@svajda.sk>
     */
    public function refreshToken()
    {
        if (!$this->getAccessToken())
            return false;

        if ($this->getAccessToken()->hasExpired())
        {
            try
            {
                $newAccessToken = $this->getProvider()->getAccessToken('refresh_token', [
                    'refresh_token' => $this->AccessToken->getRefreshToken()
                ]);

                /* nahradime ulozeny token za refreshnuty */
                $this->setAccessToken($newAccessToken);

                return true;

            } catch (OCP\Exception\IdentityProviderException $e)
            {
                //Debugger::log($e);
                return false;
            }
        }

        return true;
    }

    /**
     * vrati ulozeny access token
     * TODO: implementujte si vlastny storage
     *
     * @return \League\OAuth2\Client\Token\AccessToken|false
     * @author Patrik Svajda <patrik@svajda.sk>
     */
    private function getAccessToken()
    {
        return $this->AccessToken;
    }


    /**
     * ulozi access token
     * TODO: implementujte si vlastny storage
     *
     * @param \League\OAuth2\Client\Token\AccessToken $accessToken
     * @author Patrik Svajda <patrik@svajda.sk>
     */
    private function setAccessToken(\League\OAuth2\Client\Token\AccessToken $accessToken)
    {
        $this->AccessToken = $accessToken;
    }

}