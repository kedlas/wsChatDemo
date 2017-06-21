<?php
/**
 * Created by PhpStorm.
 * User: Tomas Sedlacek
 * Mail: mail@kedlas.cz
 * Date: 21/06/2017
 * Time: 12:44
 */

namespace App\Chat\Handler;


use Ratchet\ConnectionInterface;

interface HandlerInterface
{

    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @param ConnectionInterface $connection
     * @param array               $data
     *
     * @return void
     */
    public function validate(ConnectionInterface $connection, array $data);

    /**
     * @param ConnectionInterface $connection
     * @param array               $data
     *
     * @return void
     */
    public function process(ConnectionInterface $connection, array $data);

}
