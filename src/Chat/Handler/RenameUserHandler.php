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
use InvalidArgumentException;
use Ratchet\ConnectionInterface;

class RenameUserHandler implements HandlerInterface
{

    public const KEY = 'rename_user';

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
        if (!isset($data['username'])) {
            throw new InvalidArgumentException('Please provide your new username.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function process(ConnectionInterface $connection, array $data)
    {
        $this->chat->addUser($connection, $data['username']);
    }

}
