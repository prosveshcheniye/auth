<?php namespace Prosveshcheniye\Auth;

use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\GenericProvider;



/**
 * Prosveshcheniye IdP provider representation
 *
 * @author      Victor Yasinovsky <vyasinovskiy@rosuchebnik.ru>
 * @package     Prosveshcheniye\Auth
 */
class Provider extends GenericProvider {



    /**
     * Prosveshcheniye IdP options
     * @var array
     */
    private $_options = array(
        'urlAuthorize' => 'https://id.prosv.ru/oauth2/server/authorize',
        'urlAccessToken' => 'https://id.prosv.ru/oauth2/server/token',
        'urlResourceOwnerDetails' => 'https://id.prosv.ru/oauth2/server/resource',
    );



    /**
     * Constructor
     * @param array $options
     * @param array $collaborators
     */
    public function __construct(array $options = array(), array $collaborators = array()) {
        parent::__construct(array_merge($this->_options, $options), $collaborators);
    }



    /**
     * Requests and returns the resource owner of given access token.
     * @param AccessToken $token
     * @return ResourceOwner
     */
    public function getResourceOwner(AccessToken $token) {
        $response = $this->fetchResourceOwnerDetails($token);
        return new ResourceOwner($response, 'id');
    }



}