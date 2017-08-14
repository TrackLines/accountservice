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

use Tracklines\DataObjects\ContactDetails;
use Tracklines\DataObjects\Credentials;
use Tracklines\Service\Config\Config;
use Tracklines\Utils\Setup\Setup;
use Tracklines\DataObjects\Create;
use Tracklines\DataObjects\Update;
use Tracklines\DataObjects\Client;
use Tracklines\DataObjects\Delete;

/**
 * Class Account
 * @package Tracklines\Service\Account
 */
class Account
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

        $setup = new Setup($this->databaseConnection);
        $setup->buildDatabase();
    }


    /**
     * @param Create $createObject
     * @return array
     */
    public function create($createObject) : array
    {
        $returnData = new Client();

        try {
            $statement = $this->databaseConnection->prepare("INSERT INTO client (parentId, username, password) VALUES (:parentId, :username, :password)");
            $statement->execute([
                "parentId" => $createObject->getParentId(),
                "username" => $createObject->getCredentials()->getUsername(),
                "password" => password_hash($createObject->getCredentials()->getPassword(), PASSWORD_DEFAULT),
            ]);

            $clientId = $this->databaseConnection->lastInsertId();

            $statement = $this->databaseConnection->prepare("INSERT INTO client_contact (clientId, email, number) VALUES (:clientId, :email, :number)");
            $statement->execute([
                "clientId"  => $clientId,
                "email"     => $createObject->getContactDetails()->getEmail(),
                "number"    => $createObject->getContactDetails()->getContactNumber(),
            ]);

            $returnData->setClientId($clientId);
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return (array)$returnData;
    }

    /**
     * @param Update $updateObject
     * @return bool
     */
    public function update($updateObject) : bool
    {
        try {
            $statement = $this->databaseConnection->prepare("SELECT password FROM client WHERE username = :username AND id = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $updateObject->getClientId(),
                "username" => $updateObject->getOriginalCredentials()->getUsername(),
            ]);
            $originalData = $statement->fetch();
            if (password_verify($updateObject->getOriginalCredentials()->getPassword(), $originalData['password'])) {
                if ($updateObject->getNewCredentials()) {
                    if ($updateObject->getNewCredentials()->getPassword() !== "") {
                        $statement = $this->databaseConnection->prepare("UPDATE client SET password = :password, active = :active WHERE id = :clientId LIMIT 1");
                        $statement->execute([
                            "clientId" => $updateObject->getClientId(),
                            "active" => $updateObject->isActive(),
                            "password" => password_hash($updateObject->getNewCredentials()->getPassword(), PASSWORD_DEFAULT),
                        ]);
                    }
                }

                if ($updateObject->getNewContactDetails()) {
                    if ($updateObject->getNewContactDetails()->getEmail() !== "") {
                        $statement = $this->databaseConnection->prepare("UPDATE client_contact SET email = :email, number = :number WHERE id = :clientId LIMIT 1");
                        $statement->execute([
                            "clientId" => $updateObject->getClientId(),
                            "email" => $updateObject->getNewContactDetails()->getEmail(),
                            "number" => $updateObject->getNewContactDetails()->getContactNumber(),
                        ]);
                    }
                }

                return true;
            }
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return false;
    }

    /**
     * @param Delete $updateObject
     * @return bool
     */
    public function safeDelete($updateObject) : bool
    {
        try {
            $statement = $this->databaseConnection->prepare("SELECT password FROM client WHERE username = :username AND id = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $updateObject->getClientId(),
                "username" => $updateObject->getCredentials()->getUsername(),
            ]);
            $originalData = $statement->fetch();
            if (password_verify($updateObject->getCredentials()->getPassword(), $originalData['password'])) {
                $statement = $this->databaseConnection->prepare("UPDATE client SET active = :active WHERE clientId = :clientId LIMIT 1");
                $statement->execute([
                    "clientId" => $updateObject->getClientId(),
                    "active" => $updateObject->isActive(),
                ]);

                return true;
            }
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return false;
    }

    /**
     * @param Credentials $clientObject
     * @return array
     */
    public function retrieve($clientObject) : array
    {
        $returnData = new Client();

        try {

            $statement = $this->databaseConnection->prepare("SELECT id, active, parentId, password FROM client WHERE username = :username LIMIT 1");
            $statement->execute([
                ":username" => $clientObject->getUsername(),
            ]);
            $clientData = $statement->fetch();

            if (password_verify($clientObject->getPassword(), $clientData['password'])) {
                $returnData->setParentId($clientData['parentId']);
                $returnData->setActive($clientData['active']);
                $returnData->setClientId($clientData['id']);

                $statement = $this->databaseConnection->prepare("SELECT email, number FROM client_contact WHERE clientId = :clientId LIMIT 1");
                $statement->execute([
                    "clientId" => $clientData['id'],
                ]);
                $clientContactData = $statement->fetch();

                $contactDetails = new ContactDetails();

                $contactDetails->setEmail($clientContactData['email']);
                $contactDetails->setContactNumber($clientContactData['number']);
                $returnData->setContactDetails($contactDetails);
            }
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return (array)$returnData;
    }

    /**
     * @param Client $clientObject
     * @return array
     */
    public function get($clientObject) : array
    {
        $returnData = new Client();

        try {
            $statement = $this->databaseConnection->prepare("SELECT active, parentId FROM client WHERE id = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $clientObject->getClientId(),
            ]);
            $clientData = $statement->fetch();

            $returnData->setParentId($clientData['parentId']);
            $returnData->setActive($clientData['active']);

            $statement = $this->databaseConnection->prepare("SELECT email, number FROM client_contact WHERE clientId = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $clientObject->getClientId(),
            ]);
            $clientContactData = $statement->fetch();

            $contactDetails = new ContactDetails();
            $contactDetails->setEmail($clientContactData['email']);
            $contactDetails->setContactNumber($clientContactData['number']);
            $returnData->setContactDetails($contactDetails);
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return (array)$returnData;
    }
}
