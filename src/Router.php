<?php
/**
 * Created by PhpStorm.
 * User: Tomas Sedlacek
 * Mail: mail@kedlas.cz
 * Date: 07/05/2017
 * Time: 10:30
 */

namespace App;

use App\Chat\Chat;
use App\Chat\Handler\AddMessageHandler;
use App\Chat\Handler\AddUserHandler;
use App\Chat\Handler\DisconnectHandler;
use App\Chat\Handler\HandlerInterface;
use App\Chat\Handler\RenameUserHandler;
use LogicException;
use Ratchet\ConnectionInterface;

class Router
{

    /**
     * @var Chat
     */
    private $chat;

    /**
     * @var HandlerInterface[]
     */
    private $handlers = [];

    /**
     * MessageRouter constructor.
     * @param Chat $chat
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
        $this->handlers[AddUserHandler::KEY] = new AddUserHandler($chat);
        $this->handlers[RenameUserHandler::KEY] = new RenameUserHandler($chat);
        $this->handlers[AddMessageHandler::KEY] = new AddMessageHandler($chat);
        $this->handlers[DisconnectHandler::KEY] = new DisconnectHandler($chat);
    }

    /**
     * @param ConnectionInterface $conn
     * @param string              $key
     * @param array               $data
     */
    public function handleMessage(ConnectionInterface $conn, string $key, array $data)
    {
        $handler = null;
        if (!array_key_exists($key, $this->handlers)) {
            throw new LogicException(sprintf('Invalid message type: "%s"', $key));
        }

        $handler = $this->handlers[$key];
        $handler->validate($conn, $data);
        $handler->process($conn, $data);
    }

}
