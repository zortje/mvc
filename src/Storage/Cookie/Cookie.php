<?php
declare(strict_types = 1);

namespace Zortje\MVC\Storage\Cookie;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Claim;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Zortje\MVC\Configuration\Configuration;
use Zortje\MVC\Storage\Cookie\Exception\CookieUndefinedIndexException;

/**
 * Class Cookie
 *
 * @package Zortje\MVC\Storage
 */
class Cookie
{

    const ISSUER = 'zortje/mvc';

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var string[] Internal cookie values
     */
    protected $values = [];

    /**
     * Cookie constructor.
     *
     * @param Configuration $configuration Configuration
     * @param string        $token         JWT string
     */
    public function __construct(Configuration $configuration, string $token = '')
    {
        $this->configuration = $configuration;
        $this->values        = $this->parseAndValidateToken($token);
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
     *
     * @throws CookieUndefinedIndexException
     */
    public function get(string $key): string
    {
        if (isset($this->values[$key]) === false) {
            throw new CookieUndefinedIndexException([$key]);
        }

        return $this->values[$key];
    }

    /**
     * @return string JWT string
     */
    public function getTokenString(): string
    {
        /**
         * Build Token
         */
        $builder = (new Builder());
        $builder->setIssuer(self::ISSUER);
        $builder->setExpiration((new \DateTime($this->configuration->get('Cookie.TTL')))->getTimestamp());

        foreach ($this->values as $key => $value) {
            $builder->set($key, $value);
        }

        /**
         * Sign and generate new token
         */
        $builder->sign(new Sha256(), $this->configuration->get('Cookie.Signer.Key'));

        $token = $builder->getToken();

        return (string)$token;
    }

    protected function parseAndValidateToken(string $token)
    {
        try {
            $token = (new Parser())->parse($token);

            // @todo How to test: It will use the current time to validate (iat, nbf and exp)
            $data = new ValidationData();
            $data->setIssuer(self::ISSUER);

            $values = [];

            if ($token->validate($data) && $token->verify(new Sha256(), $this->configuration->get('Cookie.Signer.Key'))) {
                /**
                 * @var Claim $claim
                 */
                $ignored = array_fill_keys(['iss', 'exp'], true);

                foreach ($token->getClaims() as $claim) {
                    if (isset($ignored[$claim->getName()])) {
                        continue;
                    }

                    $values[$claim->getName()] = $claim->getValue();
                }
            }

            return $values;
        } catch (\InvalidArgumentException $e) {
            return [];
        }
    }
}
