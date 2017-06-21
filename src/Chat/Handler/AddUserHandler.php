<?php
/**
 * Created by PhpStorm.
 * User: Tomas Sedlacek
 * Mail: mail@kedlas.cz
 * Date: 21/06/2017
 * Time: 12:43
 */

namespace App\Chat\Handler;


use App\Chat\Chat;
use Ratchet\ConnectionInterface;

class AddUserHandler implements HandlerInterface
{

    public const KEY = 'add_user';

    /**
     * @var Chat
     */
    private $chat;

    /**
     * AddUserHandler constructor.
     * @param Chat $chat
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return self::KEY;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(ConnectionInterface $connection, array $data)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function process(ConnectionInterface $connection, array $data)
    {
        $this->chat->addUser($connection, $data['username'] ?? '');
    }

}
