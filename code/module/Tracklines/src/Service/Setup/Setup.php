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

namespace Tracklines\Setup;

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
     *
     */
    public function buildDatabase()
    {
        $this->createClient();
        $this->createContact();
    }

    /**
     *
     */
    private function createClient() {
        try {
            $statement = $this->databaseConnection->prepare("
          CREATE TABLE `client` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `parentId` int(11) NOT NULL DEFAULT '1',
            `username` varchar(50) DEFAULT NULL,
            `password` varchar(255) DEFAULT NULL,
            `active` bool DEFAULT '1',
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
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
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
            $statement->execute();
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }
    }
}