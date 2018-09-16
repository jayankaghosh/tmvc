<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Tools\Crypto;


class Hasher
{

    const ALGORITHM_MD5 = "md5";
    const ALGORITHM_SHA1 = "sha1";
    const ALGORITHM_SHA256 = "sha256";
    const ALGORITHM_SHA512 = "sha512";
    const ALGORITHM_WHIRLPOOL = "whirlpool";

    private $salt = "";

    /**
     * @param string $plainText
     * @param string $algorithm
     * @return string
     */
    public function hash($plainText, $algorithm = self::ALGORITHM_SHA256) {
        return hash($algorithm, $this->getSaltedText($plainText));
    }

    protected function getSaltedText($plainText) {
        return $this->salt.$plainText.$this->salt;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     * @return $this
     */
    public function setSalt(string $salt)
    {
        $this->salt = $salt;
        return $this;
    }
}