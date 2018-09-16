<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Tools\Crypto;


use Tmvc\Framework\Tools\AppEnv;

class Encryptor
{
    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * @var string
     */
    private $encryptMethod;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $secretIv;

    /**
     * Encryptor constructor.
     * @param Hasher $hasher
     * @param AppEnv $appEnv
     */
    public function __construct(
        Hasher $hasher,
        AppEnv $appEnv
    )
    {
        $this->hasher = $hasher;
        $this->setEncryptMethod("AES-256-CBC");
        $this->setSecretKey($appEnv->read("crypto.key"));
        $this->setSecretIv($appEnv->read("crypto.iv"));
    }

    public function encrypt($plainText) {
        return base64_encode(openssl_encrypt($plainText, $this->getEncryptMethod(), $this->getSecretKey(), 0, $this->getSecretIv()));
    }

    public function decrypt($plainText) {
       return openssl_decrypt(base64_decode($plainText), $this->getEncryptMethod(), $this->getSecretKey(), 0, $this->getSecretIv());
    }

    /**
     * @return string
     */
    public function getEncryptMethod()
    {
        return $this->encryptMethod;
    }

    /**
     * @param string $encryptMethod
     * @return $this
     */
    public function setEncryptMethod(string $encryptMethod)
    {
        $this->encryptMethod = $encryptMethod;
        return $this;
    }

    /**
     * @return string
     */
    protected function getSecretKey()
    {
        return $this->hasher->hash($this->secretKey, Hasher::ALGORITHM_SHA256);
    }

    /**
     * @param string $secretKey
     * @return $this
     */
    protected function setSecretKey(string $secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * @return string
     */
    protected function getSecretIv()
    {
        return substr($this->hasher->hash($this->secretIv, Hasher::ALGORITHM_SHA256), 0, 16);
    }

    /**
     * @param string $secretIv
     * @return $this
     */
    protected function setSecretIv(string $secretIv)
    {
        $this->secretIv = $secretIv;
        return $this;
    }
}