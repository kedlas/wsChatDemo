<?php
/**
 * Created by PhpStorm.
 * User: Tomas Sedlacek
 * Mail: mail@kedlas.cz
 * Date: 21/06/2017
 * Time: 13:18
 */

namespace App\Chat;


class User
{

    public const DEFAULT_NAME = 'Unknown user';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $username;

    /**
     * User constructor.
     * @param string $id
     * @param string $ip
     * @param string $username
     */
    public function __construct(string $id, string $ip, $username = self::DEFAULT_NAME)
    {
        $this->id = $id;
        $this->ip = $ip;
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function hasDefaultUsername()
    {
        if ($this->username === self::DEFAULT_NAME) {
            return true;
        }

        return false;
    }

}
