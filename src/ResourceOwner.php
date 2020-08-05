<?php namespace Prosveshcheniye\Auth;

use League\OAuth2\Client\Provider\GenericResourceOwner;



/**
 * Prosveshcheniye IdP Resource Owner representation
 *
 * @author      Victor Yasinovsky <vyasinovskiy@rosuchebnik.ru>
 * @package     Prosveshcheniye\Auth
 */
class ResourceOwner extends GenericResourceOwner {



    /**
     * Returns the identifier of the authorized resource owner
     * @return string
     */
    public function uuid() {
        return $this->response['id'];
    }



    /**
     * Returns authorized resource owner creation date
     * @return int
     */
    public function created() {
        return $this->response['created'];
    }



    /**
     * Returns authorized resource owner changed date
     * @return int
     */
    public function changed() {
        return $this->response['changed'];
    }



    /**
     * Magic scopes access
     * @param string $name Method name
     * @param array $arguments Arguments
     * @return array
     * @throws \Exception
     */
    public function __call($name, $arguments) {
        if (!array_key_exists($name, $this->response)) {
            throw new \Exception('Scope "' . $name . '" not found');
        }
        return $this->response[$name];
    }



}