<?php

require(__DIR__ . '/vendor/autoload.php');



$options = json_decode(
    file_get_contents(__DIR__ . '/options.json'), true
);

$provider = new \Prosveshcheniye\Auth\Provider($options);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider. This returns the urlAuthorize
    // option and generates and applies any necessary parameters (e.g. state)
    $authorizationUrl = $provider->getAuthorizationUrl();
    // Get the state generated for you and store it to the session
    $_SESSION['oauth2state'] = $provider->getState();
    // Redirect the user to the authorization URL
    header('Location: ' . $authorizationUrl);
    exit();

}
elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

    // Check given state against previously stored one to mitigate CSRF attack
    if (isset($_SESSION['oauth2state'])) { unset($_SESSION['oauth2state']); }
    exit('Invalid state');

}
else {

    try {
        // Try to get an access token using the authorization code grant
        $accessToken = $provider->getAccessToken(
            'authorization_code',
            array(
                'code' => $_GET['code'],
                'scope' => 'profile email location'
            )
        );
        // We have an access token, which we may use in authenticated
        // requests against the service provider's API
        echo('Access Token: ' . $accessToken->getToken() . '<br>');
        echo('Refresh Token: ' . $accessToken->getRefreshToken() . '<br>');
        echo('Expired in: ' . $accessToken->getExpires() . '<br>');
        echo('Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . '<br>');
        // Using the access token, we may look up details about the resource owner
        $resourceOwner = $provider->getResourceOwner($accessToken);
        $uuid = $resourceOwner->uuid(); // Identifier of the authorized resource owner
        $profile = $resourceOwner->profile(); // Complete data for profile's scope
        $raw = $resourceOwner->toArray(); // We can get all IdP response data as raw
        echo ('<pre>' . json_encode($raw, JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT) . '</pre>');
    }
    catch (\Exception $e) {
        // Failed to get the access token or user details
        exit($e->getMessage());
    }

}