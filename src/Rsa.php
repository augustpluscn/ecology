<?php

namespace Jmcc\Ecology;

class Rsa
{

    private $rsaPrivateKey = '';

    private $rsaPublicKey = '';

    private $privateKeyRule = '';

    private $publicKeyRule = '';

    /**
     * Rsa constructor.
     * @param $privateKeyRule 私钥路径
     * @param $publicKeyRule 公钥路径
     * @param string $rsaPrivateKey 私钥 一行字符串的形式
     * @param string $rsaPublicKey 公钥 一行字符串的形式
     */
    public function __construct($privateKeyRule, $publicKeyRule, $rsaPrivateKey = '', $rsaPublicKey = '')
    {
        $this->rsaPrivateKey = $rsaPrivateKey;
        $this->rsaPublicKey = $rsaPublicKey;
        $this->privateKeyRule = $privateKeyRule;
        $this->publicKeyRule = $publicKeyRule;
        $this->rsaPublicKey = "-----BEGIN PUBLIC KEY-----\n" .
        wordwrap($this->rsaPublicKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        if (empty($rsaPrivateKey) && empty($rsaPublicKey)) {
            $this->rsaPrivateKey = $this->getPrivateKey();
            $this->rsaPublicKey = $this->getPublicKey();
        }
    }

    /**
     * 获取私钥
     * @return bool|resource
     */
    private function getPrivateKey()
    {
        $absPath = $this->privateKeyRule;
        $content = file_get_contents($absPath);
        return openssl_pkey_get_private($content);
    }

    /**
     * 获取公钥
     * @return bool|resource
     */
    private function getPublicKey()
    {
        $absPath = $this->privateKeyRule;
        $content = file_get_contents($absPath);
        return openssl_pkey_get_public($content);
    }

    /**
     * 私钥加密
     * @param string $data
     * @return null|string
     */
    public function privEncrypt($data = '')
    {
        if (!is_string($data)) {
            return null;
        }
        return openssl_private_encrypt($data, $encrypted, $this->rsaPrivateKey) ? base64_encode($encrypted) : null;
    }

    /**
     * 公钥加密
     * @param string $data
     * @return null|string
     */

    public function publicEncrypt($data = '')
    {
        if (!is_string($data)) {
            return null;
        }
        return openssl_public_encrypt($data, $encrypted, $this->rsaPublicKey) ? base64_encode($encrypted) : null;
    }

    /**
     * 私钥解密
     * @param string $encrypted
     * @return null
     */
    public function privDecrypt($encrypted = '')
    {
        if (!is_string($encrypted)) {
            return null;
        }
        return (openssl_private_decrypt(base64_decode($encrypted), $decrypted, $this->rsaPrivateKey)) ? $decrypted : null;
    }

    /**
     * 公钥解密
     * @param string $encrypted
     * @return null
     */
    public function publicDecrypt($encrypted = '')
    {
        if (!is_string($encrypted)) {
            return null;
        }
        return (openssl_public_decrypt(base64_decode($encrypted), $decrypted, $this->rsaPublicKey)) ? $decrypted : null;
    }
}
