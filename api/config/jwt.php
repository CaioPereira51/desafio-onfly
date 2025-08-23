<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JWT Authentication Secret
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this in your .env file, as it will be used to sign
    | your tokens. A helper command is provided for this:
    | `php artisan jwt:secret`
    |
    | Note: This will be used for Symmetric algorithms only (HMAC),
    | since RSA and ECDSA use a private/public key pair (See below).
    |
    */

    'secret' => env('JWT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | JWT Authentication Keys
    |--------------------------------------------------------------------------
    |
    | The algorithm you are using, will determine whether your tokens are
    | signed with a random string (defined in `JWT_SECRET`) or the key pair
    | below (RSA and ECDSA). Note that ECDSA tokens are the same size as
    | RSA tokens but are more secure, and recommended.
    |
    | For RSA, you can provide the contents of the keys found in:
    | storage/app/oauth-public.key and storage/app/oauth-private.key
    |
    | For ECDSA, you can provide the contents of the keys found in:
    | storage/app/ecdsa-public.key and storage/app/ecdsa-private.key
    |
    | These will be used for Asymmetric algorithms (RSA and ECDSA) only.
    |
    */

    'keys' => [

        /*
        |--------------------------------------------------------------------------
        | Public Key
        |--------------------------------------------------------------------------
        |
        | A path or resource to your public key.
        |
        | E.g. 'file://path/to/public/key'
        |
        */

        'public' => env('JWT_PUBLIC_KEY'),

        /*
        |--------------------------------------------------------------------------
        | Private Key
        |--------------------------------------------------------------------------
        |
        | A path or resource to your private key.
        |
        | E.g. 'file://path/to/private/key'
        |
        */

        'private' => env('JWT_PRIVATE_KEY'),

        /*
        |--------------------------------------------------------------------------
        | Passphrase
        |--------------------------------------------------------------------------
        |
        | The passphrase for your private key. Can be null if none set.
        |
        */

        'passphrase' => env('JWT_PASSPHRASE'),

    ],

    /*
    |--------------------------------------------------------------------------
    | JWT time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token will be valid for.
    | Defaults to 1 hour (60 minutes).
    |
    | You can also set this to null, to yield a never expiring token.
    | Some people may want this behaviour for e.g. a mobile app.
    | This is not particularly recommended, so make sure you have appropriate
    | systems in place to revoke these tokens. For example, when a user
    | logs out, you could delete the token from the database.
    |
    | @see https://github.com/tymondesigns/jwt-auth/issues/145#issuecomment-362353434
    |
    */

    'ttl' => env('JWT_TTL', 60),

    /*
    |--------------------------------------------------------------------------
    | JWT time to live for refresh tokens
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token can be refreshed
    | within. I.E. The user can refresh their token within a 2 week window of
    | the original token being created until they must re-authenticate.
    | Defaults to 2 weeks (20160 minutes).
    |
    | You can also set this to null, to yield an infinite refresh time.
    | Some may want this instead of having infinite TTL, since in this case,
    | the refresh token will never expire.
    |
    */

    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),

    /*
    |--------------------------------------------------------------------------
    | JWT hashing algorithm
    |--------------------------------------------------------------------------
    |
    | Specify the hashing algorithm that will be used to sign the token.
    |
    | See here: https://github.com/tymondesigns/jwt-auth/wiki/Configuring-the-JWT
    |
    | Supported Algorithms: 'HS256', 'HS384', 'HS512', 'RS256', 'RS384',
    | 'RS512', 'ES256', 'ES384', 'ES512'
    |
    */

    'algo' => env('JWT_ALGO', 'HS256'),

    /*
    |--------------------------------------------------------------------------
    | Required Claims
    |--------------------------------------------------------------------------
    |
    | Specify the required claims that must exist in any token.
    | A TokenInvalidException will be thrown in the event of any of these
    | claims not being present. Be careful with the `iat` claim. If you
    | are providing a value for it, make sure you are using a timestamp
    | after the time the token was actually created.
    |
    */

    'required_claims' => [
        'iss',
        'iat',
        'exp',
        'nbf',
        'sub',
        'jti',
    ],

    /*
    |--------------------------------------------------------------------------
    | Persistent Claims
    |--------------------------------------------------------------------------
    |
    | Specify the claim keys to be persisted when refreshing a token.
    | `sub` and `iat` will automatically be persisted, in
    | addition to the these claims.
    |
    | Note: If a claim does not exist then it will be ignored.
    |
    */

    'persistent_claims' => [
        // 'foo',
        // 'bar',
    ],

    /*
    |--------------------------------------------------------------------------
    | Lock Subject
    |--------------------------------------------------------------------------
    |
    | This will determine whether a `prv` claim is automatically added to
    | the token. The presence of this claim is used to determine if the
    | the user should not be able to access the token.
    |
    */

    'lock_subject' => true,

    /*
    |--------------------------------------------------------------------------
    | Leeway
    |--------------------------------------------------------------------------
    |
    | This property gives the jwt timestamp claims some "leeway".
    | Meaning that if you have any unavoidable slight clock skew on
    | any of your servers then this will afford you some level of cushioning.
    |
    | This applies to the claims `iat`, `nbf` and `exp`.
    |
    | Specify in seconds - only if you know you need it.
    |
    */

    'leeway' => env('JWT_LEEWAY', 0),

    /*
    |--------------------------------------------------------------------------
    | Blacklist Enabled
    |--------------------------------------------------------------------------
    |
    | In order to invalidate tokens, you must have the blacklist enabled.
    | If you do not want or need this functionality, then you can
    | safely set this to false.
    |
    */

    'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),

    /*
    | -------------------------------------------------------------------------
    | Blacklist Grace Period
    | -------------------------------------------------------------------------
    |
    | When multiple concurrent requests are made with the same JWT,
    | it is possible that some of them fail, due to token regeneration
    | on every request.
    |
    | If grace period is set, then, within this time window, the token
    | will be considered as blacklisted, and will be handled with
    | the `blacklist_grace_period` delay.
    |
    */

    'blacklist_grace_period' => env('JWT_BLACKLIST_GRACE_PERIOD', 0),

    /*
    |--------------------------------------------------------------------------
    | Blacklist Grace Period
    |--------------------------------------------------------------------------
    |
    | When multiple concurrent requests are made with the same JWT,
    | it is possible that some of them fail, due to token regeneration
    | on every request.
    |
    | If grace period is set, then, within this time window, the token
    | will be considered as blacklisted, and will be handled with
    | the `blacklist_grace_period` delay.
    |
    */

    'blacklist_grace_period' => env('JWT_BLACKLIST_GRACE_PERIOD', 0),

    /*
    |--------------------------------------------------------------------------
    | Cookies encryption
    |--------------------------------------------------------------------------
    |
    | By default JWT uses cookies to store the token. If you disable
    | the encrypt option, the cookies will be stored as plain text.
    | It is recommended that you leave this option enabled.
    |
    */

    'encrypt_cookies' => env('JWT_ENCRYPT_COOKIES', false),

    /*
    |--------------------------------------------------------------------------
    | Decrypt Cookies
    |--------------------------------------------------------------------------
    |
    | By default the cookies are decrypted once they have been read. If you
    | want to decrypt the cookies as they are read, then you can set this
    | to true. This is useful if your app needs to read the token and it
    | is already decrypted once. Setting this to true will decrypt the
    | token as it is read from the cookie.
    |
    */

    'decrypt_cookies' => env('JWT_DECRYPT_COOKIES', false),

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    |
    | Helper Array to Blacklist a token.
    |
    | 'blacklist' => [
    |     'enable' => env('JWT_BLACKLIST_ENABLED', true),
    |
    |     /*  *************************************************************************
     * *********************** IMPORTANT ********************************************
     * *************************************************************************
     * *************************************************************************
     *  You MUST ensure that the same cache is used for both the blacklist
     *  and the default cache, as the blacklist is linked to the cache
     *  that is used to store the blacklist, and the same cache is used
     *  to store the user providers cache.
     * *************************************************************************
     * *************************************************************************
     * *************************************************************************
     *
     |    'cache_provider' => env('JWT_BLACKLIST_CACHE', 'default'),
     |
     |    'prefix' => env('JWT_BLACKLIST_PREFIX', 'tymon.jwt'),
     | ],
     */

    /*
    |--------------------------------------------------------------------------
    | Blacklist
    |--------------------------------------------------------------------------
    |
    | In order to invalidate tokens, you must have the blacklist enabled.
    | If you do not want or need this functionality, then you can
    | safely set this to false.
    |
    */

    'blacklist' => [
        'enable' => env('JWT_BLACKLIST_ENABLED', true),

        /*
         |--------------------------------------------------------------------------
         | Cache Provider
         |--------------------------------------------------------------------------
         |
         | Specify the cache provider to use for storing the blacklist.
         |
         | Supported providers: 'apc', 'array', 'database', 'file',
         |         'memcached', 'redis', 'dynamodb', 'octane', 'null'
         |
         | Note: If you are using a queue worker, you should use the same cache
         |       provider as your queue worker, as the blacklist is linked to
         |       the cache that is used to store the blacklist.
         |
         */

        'cache_provider' => env('JWT_BLACKLIST_CACHE', 'default'),

        /*
         |--------------------------------------------------------------------------
         | Cache Prefix
         |--------------------------------------------------------------------------
         |
         | Specify the cache prefix to use for storing the blacklist.
         |
         */

        'prefix' => env('JWT_BLACKLIST_PREFIX', 'tymon.jwt'),

    ],

];
