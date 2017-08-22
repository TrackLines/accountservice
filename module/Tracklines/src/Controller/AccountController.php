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
 * Date: 11/08/2017
 * Time: 13:19
 */

namespace Tracklines\Controller;

use Tracklines\DataObjects\Client;
use Tracklines\DataObjects\ContactDetails;
use Tracklines\DataObjects\Create;
use Tracklines\DataObjects\Credentials;
use Tracklines\DataObjects\Delete;
use Tracklines\DataObjects\Update;
use Tracklines\Service\Account\Account;
use Tracklines\Service\Config\Config;
use Tracklines\Service\Token\Token;
use Tracklines\Service\Token\Validator;
use Tracklines\Utils\Utilities;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class AccountController
 * @package Tracklines\Controller
 */
class AccountController extends AbstractRestfulController
{
    /**
     * Get Account
     * @param int $id
     * @return JsonModel
     */
    public function get($id)
    {
        $utilities = new Utilities();

        $tokenName = $this->getRequest()->getHeader("tokenName");
        if ($tokenName) {
            $tokenValue = $this->getRequest()->getHeader("tokenValue");
            if ($tokenValue) {
                $apiToken = $this->getRequest()->getHeader("apiToken");
                if ($apiToken) {
                    $apiTokenValue = $apiToken->getFieldValue();
                    $token = new Token();
                    if ($token->validateApiToken($apiTokenValue, $id)) {
                        $tokenNameValue = $tokenName->getFieldValue();
                        $tokenValueValue = $tokenValue->getFieldValue();

                        $validator = new Validator();
                        $validator->setToken($tokenNameValue);
                        $validator->setTokenValue($tokenValueValue);
                        if ($validator->validateToken()) {
                            $account = new Account();

                            $client = new Client();
                            $client->setClientId($id);

                            return new JsonModel($account->get($client));
                        }
                    }
                }
            }
        }


        $this->getResponse()->setStatusCode(400);
        return $utilities->returnError("Invalid Token");
    }

    /**
     * Patch used to get the account id
     * @param array $data
     * @return JsonModel
     */
    public function patchList($data)
    {
        $utilities = new Utilities();

        $tokenName = $this->getRequest()->getHeader("tokenName");
        if ($tokenName) {
            $tokenValue = $this->getRequest()->getHeader("tokenValue");
            if ($tokenValue) {
                $tokenNameValue = $tokenName->getFieldValue();
                $tokenValueValue = $tokenValue->getFieldValue();

                $validator = new Validator();
                $validator->setToken($tokenNameValue);
                $validator->setTokenValue($tokenValueValue);
                if ($validator->validateToken()) {
                    $account    = new Account();
                    $dataObject = $utilities->convertToObject($data);

                    $retrieve = new Credentials();
                    $retrieve->setUsername($dataObject->username);
                    $retrieve->setPassword($dataObject->password);

                    return new JsonModel($account->retrieve($retrieve));
                }
            }
        }


        $this->getResponse()->setStatusCode(400);
        return $utilities->returnError("Invalid Token");
    }

    /**
     * Create Account
     * @param array $data
     * @return JsonModel
     */
    public function create($data)
    {
        $utilities = new Utilities();
        $error = "";

        $tokenName = $this->getRequest()->getHeader("tokenName");
        if ($tokenName) {
            $tokenValue = $this->getRequest()->getHeader("tokenValue");
            if ($tokenValue) {
                $tokenNameValue     = $tokenName->getFieldValue();
                $tokenValueValue    = $tokenValue->getFieldValue();

                $validator = new Validator();
                $validator->setToken($tokenNameValue);
                $validator->setTokenValue($tokenValueValue);
                if ($validator->validateToken()) {
                    $dataObject = $utilities->convertToObject($data);
                    if ($utilities->validCreateDataObject($dataObject)) {
                        $credentials = new Credentials();
                        $credentials->setUsername($dataObject->credentials->username);
                        $credentials->setPassword($dataObject->credentials->password);

                        $contactDetails = new ContactDetails();
                        $contactDetails->setContactNumber($dataObject->contactDetails->number);
                        $contactDetails->setEmail($dataObject->contactDetails->email);

                        $createObject = new Create();
                        $createObject->setContactDetails($contactDetails);
                        $createObject->setCredentials($credentials);
                        $createObject->setParentId($dataObject->parentId);

                        $account = new Account();
                        return new JsonModel($account->create($createObject));
                    } else {
                        $error = "Invalid Data";
                    }
                } else {
                    $error = "Token Error 1";
                }
            } else {
                $error = "Token Error 2";
            }
        } else {
            $error = "Token Error 3";
        }

        $this->getResponse()->setStatusCode(400);
        return $utilities->returnError($error);
    }

    /**
     * Update Account
     * @param int $id
     * @param array $data
     * @return JsonModel
     */
    public function update($id, $data)
    {
        $utilities = new Utilities();

        $tokenName = $this->getRequest()->getHeader("tokenName");
        if ($tokenName) {
            $tokenValue = $this->getRequest()->getHeader("tokenValue");
            if ($tokenValue) {
                $tokenNameValue     = $tokenName->getFieldValue();
                $tokenValueValue    = $tokenValue->getFieldValue();

                $validator = new Validator();
                $validator->setToken($tokenNameValue);
                $validator->setTokenValue($tokenValueValue);
                if ($validator->validateToken()) {
                    $dataObject = $utilities->convertToObject($data);
                    if ($utilities->validUpdateDataObject($dataObject)) {
                        $updateObject = new Update();
                        $account = new Account();

                        // id
                        $updateObject->setClientId($id);

                        // active
                        $updateObject->setActive($dataObject->active);

                        // New Credentials
                        $newCredentials     = new Credentials();
                        if (isset($dataObject->newCredentials)) {
                            if (isset($dataObject->newCredentails->password)) {
                                $newCredentials->setPassword($dataObject->newCredentails->password);
                            }
                            $updateObject->setNewCredentials($newCredentials);
                        }

                        // New Contact Details
                        $newContactDetails  = new ContactDetails();
                        if (isset($dataObject->newContactDetails)) {
                            if (isset($dataObject->newContactDetails->email)) {
                                $newContactDetails->setEmail($dataObject->newContactDetails->email);
                            }

                            if (isset($dataObject->newContactDetails->number)) {
                                $newContactDetails->setContactNumber($dataObject->newContactDetails->number);
                            }
                            $updateObject->setNewContactDetails($newContactDetails);
                        }

                        // Old Credentials
                        $oldCredentials     = new Credentials();
                        $oldCredentials->setUsername($dataObject->originalCredentials->username);
                        $oldCredentials->setPassword($dataObject->originalCredentials->password);
                        $updateObject->setOriginalCredentials($oldCredentials);

                        if ($account->update($updateObject)) {
                            return new JsonModel([
                                "updated" => true
                            ]);
                        }
                    } else {
                        $this->getResponse()->setStatusCode(400);
                        return $utilities->returnError("Wrong Data");
                    }
                }
            }
        }

        $this->getResponse()->setStatusCode(400);
        return $utilities->returnError("Invalid Token");
    }

    /**
     * This forwards to Update Account
     * @param int $id
     * @param array $data
     * @return JsonModel
     */
    public function patch($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Delete Account
     * @param array $data
     * @return JsonModel
     */
    public function deleteList($data)
    {
        $utilities = new Utilities();

        $tokenName = $this->getRequest()->getHeader("tokenName");
        if ($tokenName) {
            $tokenValue = $this->getRequest()->getHeader("tokenValue");
            if ($tokenValue) {
                $tokenNameValue     = $tokenName->getFieldValue();
                $tokenValueValue    = $tokenValue->getFieldValue();

                $validator = new Validator();
                $validator->setToken($tokenNameValue);
                $validator->setTokenValue($tokenValueValue);
                if ($validator->validateToken()) {
                    $dataObject = $utilities->convertToObject($data);
                    $account = new Account();

                    $client = new Delete();
                    $client->setClientId($dataObject->clientId);
                    $client->setActive(false);

                    $credentials = new Credentials();
                    $credentials->setPassword($dataObject->credentials->password);
                    $credentials->setUsername($dataObject->credentials->username);
                    $client->setCredentials($credentials);

                    if ($account->safeDelete($client)) {
                        return new JsonModel([
                            "updated" => true
                        ]);
                    } else {
                        $this->getResponse()->setStatusCode(400);
                        return $utilities->returnError("Wrong Data");
                    }
                }
            }
        }

        $this->getResponse()->setStatusCode(400);
        return $utilities->returnError("Invalid Token");
    }

    //<editor-fold desc="These Things Do Nothing">
    /**
     * This does nothing
     * @param int $id
     * @return JsonModel
     */
    public function delete($id)
    {
        return new JsonModel(parent::delete($id));
    }

    /**
     * This does nothing
     * @param array $data
     * @return JsonModel
     */
    public function replaceList($data)
    {
        return new JsonModel(parent::replaceList($data));
    }

    /**
     * This does nothing
     * @return JsonModel
     */
    public function getList()
    {
        return new JsonModel(parent::getList());
    }
    //</editor-fold>
}
