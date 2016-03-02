<?php
declare(strict_types = 1);

namespace Zortje\MVC\Storage\Cookie;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Claim;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;

/**
 * Class Cookie
 *
 * @package Zortje\MVC\Storage
 */
class Cookie
{

    const ISSUER = 'zortje/mvc';

    /**
     * @var string[] Internal cookie values
     */
    protected $values = [];

    /**
     * Cookie constructor.
     *
     * @param string $token JWT token string
     */
    public function __construct(string $token = '')
    {
        // @todo SECRET key should be in configuration
        $secret = 'super-secret-key';

        try {
            $token = (new Parser())->parse($token);

            $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
            $data->setIssuer(self::ISSUER);

            if ($token->validate($data) && $token->verify(new Sha256(), $secret)) {
                /**
                 * @var Claim $claim
                 */
                $ignored = array_fill_keys(['iss', 'exp'], true);

                foreach ($token->getClaims() as $claim) {
                    if (isset($ignored[$claim->getName()])) {
                        continue;
                    }

                    $this->values[$claim->getName()] = $claim->getValue();
                }
            }
        } catch (\InvalidArgumentException $e) {
        }
    }

    /**
     * Set value in cookie
     *
     * @param string $key   Cookie key
     * @param string $value Cookie value
     */
    public function set(string $key, string $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * Get value from cookie
     *
     * @param string $key Cookie key
     *
     * @return string Cookie value
     */
    public function get(string $key): string
    {
        return $this->values[$key];
    }

    public function getTokenString(): string
    {
        // @todo SECRET key should be in configuration
        $secret = 'super-secret-key';

        // @todo should cookie TTL be set in a configuration?
        $cookieTTL = '+1 hour';

        /**
         * Build Token
         */
        $builder = (new Builder());
        $builder->setIssuer(self::ISSUER);
        $builder->setExpiration((new \DateTime($cookieTTL))->getTimestamp());

        foreach ($this->values as $key => $value) {
            $builder->set($key, $value);
        }

        /**
         * Sign and generate new token
         */
        $builder->sign(new Sha256(), $secret);

        $token = $builder->getToken();

        return (string) $token;
    }
}
