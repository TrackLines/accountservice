<?php
/**
 * Created by IntelliJ IDEA.
 * User: hootonm
 * Date: 22/08/2017
 * Time: 11:47
 */

namespace Tracklines\Service\ContactDetails;

use Tracklines\Service\Config\Config;
use \Tracklines\DataObjects\ContactDetails as ContactDetailsObject;

/**
 * Class ContactDetails
 * @package Tracklines\Service\ContactDetails
 */
class ContactDetails
{
    /**
     * @var \PDO
     */
    private $databaseConnection;


    /**
     * ContactDetails constructor.
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
     * @param int $clientId
     * @return \Tracklines\DataObjects\ContactDetails
     */
    public function getContactDetails(int $clientId) : ContactDetailsObject
    {
        $returnData = new \Tracklines\DataObjects\ContactDetails();

        try {
            $statement = $this->databaseConnection->prepare("SELECT email, number FROM client_contact WHERE clientId = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $clientId,
            ]);
            $clientContactData = $statement->fetchObject();
            if ($clientContactData) {
                $returnData->setContactNumber($clientContactData->number);
                $returnData->setEmail($clientContactData->email);
            }
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return $returnData;
    }

    /**
     * @param int $clientId
     * @param string $email
     * @return bool
     */
    public function updateEmail(int $clientId, string $email) : bool
    {
        try {
            $statement = $this->databaseConnection->prepare("UPDATE client_contact SET email = :email WHERE id = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $clientId,
                "email" => $email,
            ]);

            return true;
        } catch (\Exception $exception) {
            print_r($exception);
        }

        return false;
    }

    /**
     * @param int $clientId
     * @param string $number
     * @return bool
     */
    public function updateNumber(int $clientId, string $number) : bool
    {
        try {
            $statement = $this->databaseConnection->prepare("UPDATE client_contact SET number = :number WHERE id = :clientId LIMIT 1");
            $statement->execute([
                "clientId" => $clientId,
                "number" => $number,
            ]);

            return true;
        } catch (\Exception $exception) {
            print_r($exception);
        }

        return false;
    }

    public function createContactDetails(int $clientId, ContactDetailsObject $contactDetails) : ContactDetailsObject
    {
        try {
            $statement = $this->databaseConnection->prepare("INSERT INTO client_contact (clientId, email, number) VALUES (:clientId, :email, :number)");
            $statement->execute([
                "clientId"  => $clientId,
                "email"     => $contactDetails->getEmail(),
                "number"    => $contactDetails->getContactNumber(),
            ]);
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

        return $contactDetails;
    }
}