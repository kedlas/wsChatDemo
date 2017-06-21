<?php
/**
 * Created by PhpStorm.
 * User: Tomas Sedlacek
 * Mail: mail@kedlas.cz
 * Date: 01/05/2017
 * Time: 19:02
 */

namespace App;

use App\Chat\Handler\DisconnectHandler;
use Exception;
use InvalidArgumentException;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Server implements MessageComponentInterface
{

    public const TYPE_INFO = 'info';
    public const TYPE_MESSAGE = 'message';
    public const TYPE_ERROR = 'error';

    /**
     * @var Router
     */
    private $router;

    /**
     * Chat constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        echo "New client connected to ws! ({$conn->resourceId}, from IP: $conn->remoteAddress)\n";
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->router->handleMessage($conn, DisconnectHandler::KEY, []);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    /**
     * @param ConnectionInterface $conn
     * @param Exception           $e
     */
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * @param ConnectionInterface $from
     * @param string              $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        try {
            $msg = $this->validateMessage($msg);
            $this->router->handleMessage($from, $msg['type'], $msg['data']);
        } catch (Exception $e) {
            echo $e->getMessage();
            $msg = [
                'type' => self::TYPE_ERROR,
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ];
            $from->send(json_encode($msg));
        }
    }

    /**
     * @param string $msg
     *
     * @return array
     */
    public function validateMessage(string $msg): array
    {
        $msg = json_decode($msg, true);

        if (json_last_error() > 0) {
            throw new InvalidArgumentException(sprintf('Invalid message. Error: %s', json_last_error_msg()));
        }

        if (!isset($msg['type']) || !isset($msg['data'])) {
            throw new InvalidArgumentException(sprintf('Invalid message. Error: %s', json_encode($msg)));
        }

        return $msg;
    }

}
