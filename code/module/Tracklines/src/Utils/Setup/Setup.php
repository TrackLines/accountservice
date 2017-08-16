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

namespace Tracklines\Utils\Setup;

use Tracklines\Service\Config\Config;

/**
 * Class Setup
 * @package Tracklines\Setup
 */
class Setup
{
    /**
     * @var \PDO
     */
    private $databaseConnection;

    /**
     * Setup constructor.
     */
    public function __construct($databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    /**
     *
     */
    public function buildDatabase()
    {
        $this->createClient();
        $this->createContact();
        $this->createKeys();
    }

    public function destroyDatabase()
    {
        $this->deleteClient();
        $this->deleteContact();
        $this->deleteKeys();
    }

    /**
     *
     */
    private function createClient()
    {
        try {
            $statement = $this->databaseConnection->prepare("
          CREATE TABLE `client` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `parentId` int(11) NOT NULL DEFAULT '1',
            `username` varchar(50) DEFAULT NULL,
            `password` varchar(255) DEFAULT NULL,
            `active` bool DEFAULT '1',
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $statement->execute();
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }

    /**
     *
     */
    private function createContact()
    {
        try {
            $statement = $this->databaseConnection->prepare("
            CREATE TABLE `client_contact` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `clientId` int(11) DEFAULT NULL,
              `email` varchar(255) DEFAULT NULL,
              `number` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $statement->execute();
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }

    private function createKeys()
    {
        try {
            $statement = $this->databaseConnection->prepare("
            CREATE TABLE `client_keys` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `clientId` int(11) DEFAULT NULL,
                `api_key` varchar(255) DEFAULT NULL,
                `interface_key` varchar(255) DEFAULT NULL,
                PRIMARY_KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $statement->execute();
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }

    private function deleteClient()
    {
        try {
            $statement = $this->databaseConnection->prepare("
                DROP TABLE `client`;");
            $statement->execute();
        } catch (\Exception $exception) {
            print_r($exception->getMessage();
        }
    }

    private function deleteContact()
    {
        try {
            $statement = $this->databaseConnection->prepare("
                DROP TABLE `client_contact`;");
            $statement->execute();
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }

    private function deleteKeys()
    {
        try {
            $statement = $this->databaseConnection->prepare("
                DROP TABLE `client_keys`;");
            $statement->execute();
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }
}
