<?php
/**
 * Created by PhpStorm.
 * User: Tomas Sedlacek
 * Mail: mail@kedlas.cz
 * Date: 21/06/2017
 * Time: 13:01
 */

namespace App\Chat\Handler;


use App\Chat\Chat;
use InvalidArgumentException;
use Ratchet\ConnectionInterface;

class AddMessageHandler implements HandlerInterface
{

    public const KEY = 'message';

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
        $this->chat->getUser($connection);

        if (!isset($data['content'])) {
            throw new InvalidArgumentException('Please enter your message content.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function process(ConnectionInterface $connection, array $data)
    {
        $this->chat->addMessage(
            $this->chat->getUser($connection),
            $data['content']
        );
    }

}
