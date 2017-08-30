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

use \Tracklines\Service\ContactDetails\ContactDetails;
use Tracklines\DataObjects\Credentials;
use Tracklines\Service\Config\Config;
use Tracklines\DataObjects\Create;
use Tracklines\DataObjects\Update;
use Tracklines\DataObjects\Client;
use Tracklines\DataObjects\Delete;
use Tracklines\Service\Token\Token;
use Tracklines\Utils\Utilities;

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
    }


    /**
     * @param Create $createObject
     * @return array
     */
    public function create($createObject) : array
    {
        $returnData = new Client();

        try {
            $statement = $this->databaseConnection->prepare("INSERT INTO client (parentId, username, password, active) VALUES (:parentId, :username, :password, true)");
            $executed = $statement->execute([
                "parentId" => $createObject->getParentId(),
                "username" => $createObject->getCredentials()->getUsername(),
                "password" => password_hash($createObject->getCredentials()->getPassword(), PASSWORD_DEFAULT),
            ]);
            if ($executed) {
                $clientId = $this->databaseConnection->lastInsertId();

                $contactDetails = new ContactDetails();
                $contactDetails->createContactDetails($clientId, $createObject->getContactDetails());
                $returnData->setContactDetails($createObject->getContactDetails());

                $tokens = new Token();
                $keys = $tokens->createTokens($clientId);
                $returnData->setKeys($keys);

                $returnData->setClientId($clientId);
                $returnData->setParentId($createObject->getParentId());
            }
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return $returnData->toArray();
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
            $originalData = $statement->fetchObject();
            if (password_verify($updateObject->getOriginalCredentials()->getPassword(), $originalData->password)) {
                if ($updateObject->getNewCredentials()) {
                    if ($updateObject->getNewCredentials()->getPassword() !== "") {
                        $statement = $this->databaseConnection->prepare("UPDATE client SET password = :password WHERE id = :clientId LIMIT 1");
                        $statement->execute([
                            "clientId" => $updateObject->getClientId(),
                            "password" => password_hash($updateObject->getNewCredentials()->getPassword(), PASSWORD_DEFAULT),
                        ]);
                    }
                }

                if ($updateObject->getNewContactDetails()) {
                    $contactDetails = new ContactDetails();

                    if ($updateObject->getNewContactDetails()->getEmail() !== "") {
                        $contactDetails->updateEmail($updateObject->getClientId(), $updateObject->getNewContactDetails()->getEmail());
                    }
                    if ($updateObject->getNewContactDetails()->getContactNumber() !== "") {
                        $contactDetails->updateNumber($updateObject->getClientId(), $updateObject->getNewContactDetails()->getContactNumber());
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
            $originalData = $statement->fetchObject();
            if (password_verify($updateObject->getCredentials()->getPassword(), $originalData->password)) {
                $statement = $this->databaseConnection->prepare("UPDATE client SET active = :active WHERE id = :clientId LIMIT 1");
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
            $clientData = $statement->fetchObject();

            if (password_verify($clientObject->getPassword(), $clientData->password)) {
                $returnData->setParentId($clientData->parentId);
                $returnData->setActive($clientData->active);
                $returnData->setClientId($clientData->id);


                $statement = $this->databaseConnection->prepare("SELECT email, number FROM client_contact WHERE clientId = :clientId LIMIT 1");
                $statement->execute([
                    "clientId" => $clientData->id,
                ]);
                $clientContactData = $statement->fetchObject();

                $contactDetails = new ContactDetails();
                $contactDetailsData = $contactDetails->getContactDetails($clientData->id);
                $returnData->setContactDetails($contactDetailsData);

                $token = new Token();
                $keys = $token->getTokens($clientData->id);
                $returnData->setKeys($keys);
            }
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return $returnData->toArray();
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
            $clientData = $statement->fetchObject();

            $returnData->setParentId($clientData->parentId);
            $returnData->setActive($clientData->active);

            $contactDetails = new ContactDetails();
            $clientContactData = $contactDetails->getContactDetails($clientObject->getClientId());
            $returnData->setContactDetails($clientContactData);

            $token = new Token();
            $keys = $token->getTokens($clientObject->getClientId());
            $returnData->setKeys($keys);

            $returnData->setClientId($clientObject->getClientId());
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return $returnData->toArray();
    }
}
