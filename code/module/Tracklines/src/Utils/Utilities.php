<?php
/**
 * Created by IntelliJ IDEA.
 * User: Keloran
 * Date: 11/08/2017
 * Time: 23:14
 */

namespace Tracklines\Utils;

use Zend\View\Model\JsonModel;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class Utilities
 * @package Tracklines\Utils
 */
class Utilities extends AbstractActionController
{
    /**
     * @return JsonModel
     */
    public function blankJson() : JsonModel
    {
        return new JsonModel();
    }

    /**
     * @param string $error
     * @return JsonModel
     */
    public function returnError(string $error) : JsonModel
    {
        return new JsonModel([
            "message" => $error
        ]);
    }

    /**
     * Convert the array to object
     * @param array $array
     * @return \stdClass
     */
    public function convertToObject(array $array) : \stdClass
    {
        $object = new \stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->convertToObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }

    /**
     * Make sure the object is valid
     * @param \stdClass $dataObject
     * @return bool
     */
    public function validDataObject(\stdClass $dataObject) : bool
    {
       $valid = false;

       $parentValid = false;
       if (isset($dataObject->parentId) && is_int($dataObject->parentId)) {
           $parentValid = true;
       }

       $credentialsValid = false;
       if (isset($dataObject->credentials) && $credentials = $dataObject->credentials) {
           $credentialsValid = $this->validateCredentials($credentials);
       }

       $contactValid = false;
       if (isset($dataObject->contactDetails) && $contact = $dataObject->contactDetails) {
           $contactValid = $this->validateContactDetails($contact);
       }

       if ($parentValid) {
           if ($credentialsValid) {
               if ($contactValid) {
                   $valid = true;
               }
           }
       }

       return $valid;
    }

    /**
     * Validate the credentials object
     * @param \stdClass $credentials
     * @return bool
     */
    private function validateCredentials(\stdClass $credentials) : bool
    {
        $valid = false;

        $usernameValid = false;
        if (isset($credentials->username) && $username = $credentials->username) {
            if (strlen($username) >= 5) {
                $usernameValid = true;
            }
        }

        $passwordValid = false;
        if (isset($credentials->password) && $password = $credentials->password) {
            if (strlen($password) >= 10) {
                $passwordValid = true;
            }
        }

        if ($usernameValid) {
            if ($passwordValid) {
                $valid = true;
            }
        }
        return $valid;
    }

    /**
     * Validate the contact details object
     * @param \stdClass $contactDetails
     * @return bool
     */
    private function validateContactDetails(\stdClass $contactDetails) : bool
    {
        $valid = false;

        $emailValid = false;
        if (isset($contactDetails->email) && $email = $contactDetails->email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailValid = true;
            }
        }

        $numberValid = false;
        if (isset($contactDetails->number) && $number = $contactDetails->number) {
            $numberValid = true;
        }

        if ($emailValid) {
            if ($numberValid) {
                $valid = true;
            }
        }

        return $valid;
    }
}
