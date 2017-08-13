<?php
/**
 * MIT License
 *
 * Copyright (c) 2017 TrackLines
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Tracklines\Service\Account;

use Tracklines\Service\Config\Config;
use Tracklines\Setup\Setup;

/**
 * Class Account
 * @package Tracklines\Service\Account
 */
class Account extends AccountAbstract
{
    /**
     * @var \PDO
     */
    private $databaseConnection;

    /**
     * Account constructor.
     */
    public function __construct()
    {
        $config     = new Config();
        $dbConfig   = $config->getDatabaseConfig();

        $dsn  = "mysql:";
        $dsn .= ("dbname=" . $dbConfig->database . ";");
        $dsn .= ("host=" . $dbConfig->address);

        $this->databaseConnection = new \PDO($dsn, $dbConfig->username, $dbConfig->password);
    }

    /**
     * @return array
     */
    public function create()
    {
        $returnData = new \stdClass();

        try {
            $statement = $this->databaseConnection->prepare("INSERT INTO client (parentId, username, password) VALUES (:parentId, :username, :password)");
            $statement->execute([
                "parentId" => $this->getParentId(),
                "username" => $this->getUsername(),
                "password" => password_hash($this->getPassword(), PASSWORD_DEFAULT),
            ]);

            $clientId = $this->databaseConnection->lastInsertId();

            $statement = $this->databaseConnection->prepare("INSERT INTO client_contact (clientId, email, number) VALUES (:clientId, :email, :number)");
            $statement->execute([
                "clientId"  => $clientId,
                "email"     => $this->getEmail(),
                "number"    => $this->getContactNumber(),
            ]);

            $returnData->clientId = $clientId;
        } catch (\Exception $exception) {
            $setup = new Setup();
            $setup->buildDatabase();
            $this->create();
        }

        return (array)$returnData;
    }

    /**
     * @return bool
     */
    public function update()
    {
        try {
            $statement = $this->databaseConnection->prepare("UPDATE client SET password = :password, active = :active WHERE id = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $this->getClientId(),
                "active" => $this->isActive(),
                "password" => password_hash($this->getPassword(), PASSWORD_DEFAULT),
            ]);

            $statement = $this->databaseConnection->prepare("UPDATE client_contact SET email = :email, number = :number WHERE id = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $this->getClientId(),
                "email" => $this->getEmail(),
                "number" => $this->getContactNumber(),
            ]);

            return true;
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return false;
    }

    /**
     * @return bool
     */
    public function safeDelete()
    {
        try {
            $statement = $this->databaseConnection->prepare("UPDATE client SET active = :active WHERE clientId = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $this->getClientId(),
                "active" => $this->isActive(),
            ]);

            return true;
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return false;
    }

    /**
     * @return array
     */
    public function retrieve()
    {
        $returnData = new \stdClass();

        try {
            $statement = $this->databaseConnection->prepare("SELECT id, active, parentId FROM client WHERE username = :username AND password = :password LIMIT 1");
            $statement->execute([
                "username" => $this->getUsername(),
                "password" => password_hash($this->getPassword(), PASSWORD_DEFAULT),
            ]);
            $clientData = $statement->fetch();

            $returnData->parentId   = $clientData['parentId'];
            $returnData->active     = $clientData['active'];
            $returnData->clientId   = $clientData['id'];

            $statement = $this->databaseConnection->prepare("SELECT email, number FROM client_contact WHERE clientId = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $clientData['id'],
            ]);
            $clientContactData = $statement->fetch();

            $returnData->email  = $clientContactData['email'];
            $returnData->number = $clientContactData['number'];
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return (array)$returnData;
    }

    /**
     * @return array
     */
    public function get()
    {
        $returnData = new \stdClass();

        try {
            $statement = $this->databaseConnection->prepare("SELECT active, parentId FROM client WHERE id = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $this->getClientId(),
            ]);
            $clientData = $statement->fetch();

            $returnData->parentId   = $clientData['parentId'];
            $returnData->active     = $clientData['active'];

            $statement = $this->databaseConnection->prepare("SELECT email, number FROM client_contact WHERE clientId = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $this->getClientId(),
            ]);
            $clientContactData = $statement->fetch();

            $returnData->email  = $clientContactData['email'];
            $returnData->number = $clientContactData['number'];
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return (array)$returnData;
    }
}
