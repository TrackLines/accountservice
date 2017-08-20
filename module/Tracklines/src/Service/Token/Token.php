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

/**
 * Created by IntelliJ IDEA.
 * User: hootonm
 * Date: 18/08/2017
 * Time: 11:11
 */

namespace Tracklines\Service\Token;


use PHPUnit\Runner\Exception;
use Tracklines\DataObjects\Keys;
use Tracklines\Service\Config\Config;
use Tracklines\Utils\Utilities;

/**
 * Class Token
 * @package Tracklines\Service\Token
 */
class Token
{
    /**
     * @var \PDO
     */
    private $databaseConnection;

    /**
     * @var string
     */
    private $tokenValue = "";

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
     * @return string
     */
    public function getTokenValue(): string
    {
        return $this->tokenValue;
    }

    /**
     * @param string $tokenValue
     */
    public function setTokenValue(string $tokenValue)
    {
        $this->tokenValue = $tokenValue;
    }

    /**
     * @param int $clientId
     * @return Keys
     */
    public function getTokens(int $clientId) : Keys
    {
        $keys = new Keys();

        try {
            $statement = $this->databaseConnection->prepare("
              SELECT
                id, 
                api,
                apiLastAccess, 
                interface,
                interfaceLastAccess
              FROM client_keys 
              WHERE clientId = :clientId 
              LIMIT 1");
            $statement->execute([
                "clientId" => $clientId,
            ]);
            $clientKeys = $statement->fetchObject();
            if ($clientKeys) {
                $keys->setId($clientKeys->id);
                $keys->setApi($clientKeys->api);
                $keys->setApiLastAccess($clientKeys->apiLastAccess);
                $keys->setInterface($clientKeys->interface);
                $keys->setApiLastAccess($clientKeys->interfaceLastAccess);
            }
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return $keys;
    }

    /**
     * @param int $clientId
     * @return Keys
     */
    public function createTokens(int $clientId) : Keys
    {
        $keys = new Keys();

        try {
            $utils = new Utilities();
            $time = time();

            $statement = $this->databaseConnection->prepare("
              INSERT INTO client_keys (
                clientId, 
                api, 
                apiLastAccess,
                interface,
                interfaceLastAccess
              ) VALUES (
                :clientId, 
                :api, 
                :apiLastAccess,
                :interface,
                :interfaceLastAccess
              )");

            $api        = $utils->generateKey($clientId);
            $interface  = $utils->generateKey($clientId);

            $statement->execute([
                "clientId" => $clientId,
                "api" => $api,
                "apiLastAccess" => $time,
                "interface" => $interface,
                "interfaceLastAccess" => $time,
            ]);

            $keys->setApi($api);
            $keys->setInterface($interface);
            $keys->setApiLastAccess($time);
            $keys->setInterfaceLastAccess($time);
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return $keys;
    }

    /**
     * @param int $clientId
     * @param int $tokenId
     */
    public function updateApiAccess(int $clientId, int $tokenId)
    {
        try {
            $statement = $this->databaseConnection->prepare("
                UPDATE client_keys
                SET apiLastAccess = :updateTime
                WHERE clientId = :clientId
                  AND id = :tokenId
                LIMIT 1");
            $statement->execute([
                "updateTime" => time(),
                "clientId" => $clientId,
                "tokenId" => $tokenId,
            ]);
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }

    /**
     * @param int $clientId
     * @param int $tokenId
     */
    public function updateInterfaceAccess(int $clientId, int $tokenId)
    {
        try {
            $statement = $this->databaseConnection->prepare("
                UPDATE client_keys
                SET interfaceLastAccess = :updateTime
                WHERE clientId = :clientId
                  AND id = :tokenId
                LIMIT 1");
            $statement->execute([
                "updateTime" => time(),
                "clientId" => $clientId,
                "tokenId" => $tokenId,
            ]);
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }

    /**
     * @param $token
     * @param $clientId
     * @return bool
     */
    public function validateApiToken($token, $clientId) : bool
    {
        try {
            $statement = $this->databaseConnection->prepare("
                SELECT TRUE 
                FROM client_keys
                WHERE clientId = :clientId
                  AND api = :token
                LIMIT 1");
            $statement->execute([
                "clientId" => $clientId,
                "token" => $token,
            ]);
            $result = $statement->fetchObject();
            if ($result) {
                return $result->TRUE;
            }
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return false;
    }

    /**
     * @param $token
     * @param $clientId
     * @return bool
     */
    public function validateInterfaceToken($token, $clientId) : bool
    {
        try {
            $statement = $this->databaseConnection->prepare("
                SELECT TRUE 
                FROM client_keys
                WHERE clientId = :clientId
                  AND interface = :token
                LIMIT 1");
            $statement->execute([
                "clientId" => $clientId,
                "token" => $token,
            ]);
            return $statement->fetch();
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return false;
    }

    /**
     * @param int $tokenId
     */
    public function getApiToken(int $tokenId)
    {
        try {
            $statement = $this->databaseConnection->prepare("
                SELECT api
                FROM client_keys
                WHERE id = :tokenId
                LIMIT 1");
            $statement->execute([
                "tokenId" => $tokenId,
            ]);
            $results = $statement->fetchObject();
            if ($results) {
                $this->setTokenValue($results->api);
            }
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }

    /**
     * @param int $tokenId
     */
    public function getInterfaceToken(int $tokenId)
    {
        try {
            $statement = $this->databaseConnection->prepare("
                SELECT interface
                FROM client_keys
                WHERE id = :tokenId
                LIMIT 1");
            $statement->execute([
                "tokenId" => $tokenId,
            ]);
            $results = $statement->fetchObject();
            if ($results) {
                $this->setTokenValue($results->interface);
            }
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }

    /**
     * @param int $clientId
     * @return Keys
     */
    public function updateToken(int $clientId) : Keys
    {
        $keys = new Keys();

        try {
            $utils = new Utilities();

            $api        = $utils->generateKey($clientId);
            $interface  = $utils->generateKey($clientId);

            $statement = $this->databaseConnection->prepare("
                UPDATE client_keys
                SET 
                  api = :api,
                  interface = :interface
                WHERE clientId = :clientId
                LIMIT 1");
            $statement->execute([
                "clientId" => $clientId,
                "api" => $api,
                "interface" => $interface,
            ]);

            $keys->setInterface($interface);
            $keys->setApi($api);
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return $keys;
    }

    /**
     * @param Keys $keys
     * @return array
     */
    public function deleteToken(Keys $keys) : array
    {
        $returnData = new Keys();

        try {
            $statement = $this->databaseConnection->prepare("
                DELETE FROM client_keys WHERE id = :id LIMIT 1");
            $statement->execute([
                "id" => $keys->getId(),
            ]);
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return $returnData->toArray();
    }
}