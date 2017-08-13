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

use Tracklines\Service\Account\Account;
use Tracklines\Service\Config\Config;
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
                $tokenNameValue = $tokenName->getFieldValue();
                $tokenValueValue = $tokenValue->getFieldValue();

                $config = new Config();
                $tokens = $config->getS3Config("tokens");

                if ($tokens) {
                    $validator = new Validator();
                    $validator->setTokens($tokens);
                    $validator->setToken($tokenNameValue);
                    $validator->setTokenValue($tokenValueValue);
                    if ($validator->validateToken()) {
                        $account = new Account();

                        $account->setClientId($id);

                        return new JsonModel($account->get());
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

                $config = new Config();
                $tokens = $config->getS3Config("tokens");

                if ($tokens) {
                    $validator = new Validator();
                    $validator->setTokens($tokens);
                    $validator->setToken($tokenNameValue);
                    $validator->setTokenValue($tokenValueValue);
                    if ($validator->validateToken()) {
                        $account    = new Account();
                        $dataObject = $utilities->convertToObject($data);

                        $account->setUsername($dataObject->credentials->username);
                        $account->setPassword($dataObject->credentials->password);

                        return new JsonModel($account->retrieve());
                    }
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

        $tokenName = $this->getRequest()->getHeader("tokenName");
        if ($tokenName) {
            $tokenValue = $this->getRequest()->getHeader("tokenValue");
            if ($tokenValue) {
                $tokenNameValue     = $tokenName->getFieldValue();
                $tokenValueValue    = $tokenValue->getFieldValue();

                $config = new Config();
                $tokens = $config->getS3Config("tokens");

                if ($tokens) {
                    $validator = new Validator();
                    $validator->setTokens($tokens);
                    $validator->setToken($tokenNameValue);
                    $validator->setTokenValue($tokenValueValue);
                    if ($validator->validateToken()) {
                        $dataObject = $utilities->convertToObject($data);

                        $account = new Account();

                        $account->setContactNumber($dataObject->contactDetails->number);
                        $account->setEmail($dataObject->contactDetails->email);
                        $account->setParentId($dataObject->parentId);
                        $account->setUsername($dataObject->credentials->username);
                        $account->setPassword($dataObject->credentials->password);

                        return new JsonModel($account->create());
                    }
                }
            }

        }

        $this->getResponse()->setStatusCode(400);
        return $utilities->returnError("Invalid Token");
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

                $config = new Config();
                $tokens = $config->getS3Config("tokens");

                if ($tokens) {
                    $validator = new Validator();
                    $validator->setTokens($tokens);
                    $validator->setToken($tokenNameValue);
                    $validator->setTokenValue($tokenValueValue);
                    if ($validator->validateToken()) {
                        $dataObject = $utilities->convertToObject($data);

                        $account = new Account();
                        $account->setContactNumber($dataObject->contactDetails->number);
                        $account->setEmail($dataObject->contactDetails->email);
                        $account->setParentId($dataObject->parentId);
                        $account->setUsername($dataObject->credentials->username);
                        $account->setPassword($dataObject->credentials->password);
                        $account->setClientId($id);
                        $account->setActive($dataObject->active);

                        if ($account->update()) {
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
     * @param int $id
     * @return JsonModel
     */
    public function delete($id)
    {
        $utilities = new Utilities();

        $tokenName = $this->getRequest()->getHeader("tokenName");
        if ($tokenName) {
            $tokenValue = $this->getRequest()->getHeader("tokenValue");
            if ($tokenValue) {
                $tokenNameValue     = $tokenName->getFieldValue();
                $tokenValueValue    = $tokenValue->getFieldValue();

                $config = new Config();
                $tokens = $config->getS3Config("tokens");

                if ($tokens) {
                    $validator = new Validator();
                    $validator->setTokens($tokens);
                    $validator->setToken($tokenNameValue);
                    $validator->setTokenValue($tokenValueValue);
                    if ($validator->validateToken()) {
                        $account = new Account();

                        $account->setClientId($id);
                        $account->setActive(false);

                        if ($account->safeDelete()) {
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

        }

        $this->getResponse()->setStatusCode(400);
        return $utilities->returnError("Invalid Token");
    }

    //<editor-fold desc="These Things Do Nothing">
    /**
     * This does nothing
     * @param array $data
     * @return mixed
     */
    public function deleteList($data)
    {
        return new JsonModel(parent::deleteList($data));
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
