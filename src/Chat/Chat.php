<?php
/**
 * Created by PhpStorm.
 * User: Tomas Sedlacek
 * Mail: mail@kedlas.cz
 * Date: 21/06/2017
 * Time: 12:09
 */

namespace App\Chat;

use App\Chat\Handler\AddMessageHandler;
use App\Chat\Handler\AddUserHandler;
use App\Server;
use LogicException;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

class Chat
{

    /**
     * TODO - limit the chat size due to memory swelling
     */
    const MAX_MESSAGES_LIMIT = 10000;

    /**
     * @var SplObjectStorage
     */
    private $clients;

    /**
     * @var array
     */
    private $users = [];

    /**
     * @var array
     */
    private $messages = [];

    /**
     * @var User
     */
    private $admin;

    /**
     * Chat constructor.
     */
    public function __construct()
    {
        $this->clients = new SplObjectStorage();
        $this->admin = new User(1, '127.0.0.1', 'Chuck Norris');
    }

    /**
     * @param array                    $data
     * @param ConnectionInterface|null $except
     */
    public function notifyAll(array $data, ConnectionInterface $except = null)
    {
        $msg = json_encode($data);

        /** @var ConnectionInterface $client */
        foreach ($this->clients as $client) {
            if ($except && $except === $client) {
                continue;
            }
            $client->send($msg);
        }
    }

    /**
     * @param ConnectionInterface $connection
     * @param array               $data
     */
    public function notifyOne(ConnectionInterface $connection, array $data)
    {
        $msg = json_encode($data);
        $connection->send($msg);
    }

    /**
     * @param User   $author
     * @param string $msg
     * @return Message
     */
	public function addMessage(User $author, string $msg)
	{
	    $message = new Message($author, $msg);
		$this->messages[] = $message;

		$this->notifyAll($this->formatMessageResponse($message));

		return $message;
	}

    /**
     * @param ConnectionInterface $connection
     * @param string|null         $username
     */
	public function addUser(ConnectionInterface $connection, string $username = null)
	{
        $this->clients->attach($connection);

        $id = $connection->resourceId;
        $user = new User($id, $connection->remoteAddress, $username);
		$this->users[$id] = $user;

		$this->notifyOne($connection, $this->formatAddUserResponse());
        $this->addMessage($this->admin, sprintf('User "%s" joined the chat.', $user->getUsername()));

		if ($user->hasDefaultUsername()) {
		    $this->notifyOne(
		        $connection,
                $this->formatInfoResponse('Welcome, new user. Please set your name and be polite.')
            );
        } else {
            $this->notifyOne(
                $connection,
                $this->formatInfoResponse(sprintf('Welcome, %s. Enjoy chatting.', $user->getUsername()))
            );
        }
	}

    /**
     * @param ConnectionInterface $connection
     */
    public function removeUser(ConnectionInterface $connection)
    {
        try {
            $user = $this->getUser($connection);
            $this->addMessage($user, 'Bye bye, I\'m leaving this chat.');
        }  catch (LogicException $e) {
            // Be silent
        }

        $this->clients->detach($connection);
        unset($this->users[$connection->resourceId]);
	}

    /**
     * @param ConnectionInterface $connection
     * @param string              $username
     */
    public function renameUser(ConnectionInterface $connection, string $username)
    {
        $user = $this->getUser($connection);
        $origName = $user->getUsername();
        $user->setUsername(substr($username, 0, 50));
        $this->addMessage($user, sprintf('I changed my name from "%s" to "%s"', $origName,  $username));
	}

    /**
     * @param ConnectionInterface $connection
     * @return User
     * @throws LogicException
     */
    public function getUser(ConnectionInterface $connection): User
    {
        $id = $connection->resourceId;

        if (!$this->clients->contains($connection) ||
            !isset($this->users[$id])
        ) {
            throw new LogicException(sprintf('Cannot retrieve user "%s".', $id));
        }

        return $this->users[$id];
    }

    /**
     * @param Message $message
     * @return array
     */
    private function formatMessageResponse(Message $message)
    {
        return [
            'type' => AddMessageHandler::KEY,
            'data' => [
                'content' => $message->getContent(),
                'author' => $message->getAuthor()->getUsername(),
                'created' => $message->getCreated()->getTimestamp(),
            ],
        ];
    }

    /**
     * @return array
     */
    private function formatAddUserResponse()
    {
        $messages = [];
        /** @var Message $m */
        foreach ($this->messages as $m) {
            $messages[] = [
                'content' => $m->getContent(),
                'author' => $m->getAuthor()->getUsername(),
                'created' => $m->getCreated()->getTimestamp(),
            ];
        }

        return [
            'type' => AddUserHandler::KEY,
            'data' => [
                'messages' => $messages,
            ],
        ];
    }

    /**
     * @param string $info
     * @return array
     */
    private function formatInfoResponse(string $info)
    {
        return [
            'type' => Server::TYPE_INFO,
            'data' => [
                'message' => $info,
            ],
        ];
    }
	
}
