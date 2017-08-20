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
 * Time: 10:31
 */

namespace Tracklines\Controller;

use Tracklines\Service\Token\Token;
use Tracklines\Service\Token\Validator;
use Tracklines\Utils\Utilities;
use Tracklines\DataObjects\Keys;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;


class TokenController extends AbstractRestfulController
{
    public function get($id)
    {
        $utilities = new Utilities();

        $tokenName = $this->getRequest()->getHeader("tokenName");
        if ($tokenName) {
            $tokenValue = $this->getRequest()->getHeader("tokenValue");
            if ($tokenValue) {
                $tokenType = $this->getRequest()->getHeader('tokenType');
                if ($tokenType) {
                    $tokenTypeValue = $tokenType->getFieldValue();
                    $tokenNameValue = $tokenName->getFieldValue();
                    $tokenValueValue = $tokenValue->getFieldValue();

                    $validator = new Validator();
                    $validator->setToken($tokenNameValue);
                    $validator->setTokenValue($tokenValueValue);
                    if ($validator->validateToken()) {
                        $token = new Token();

                        if ($tokenTypeValue === "api") {
                            $token->getApiToken($id);
                        } else if ($tokenTypeValue === "interface") {
                            $token->getInterfaceToken($id);
                        }

                        return new JsonModel([
                            $tokenTypeValue => $token->getTokenValue(),
                        ]);
                    }
                }
            }
        }


        $this->getResponse()->setStatusCode(400);
        return $utilities->returnError("Invalid Token");
    }

    public function getList()
    {
        return new JsonModel(parent::getList()); // TODO: Change the autogenerated stub
    }

    public function create($data)
    {
        return new JsonModel(parent::create($data)); // TODO: Change the autogenerated stub
    }

    public function update($id, $data)
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
                    $token = new Token();
                    return new JsonModel($token->updateToken($id));
                }
            }
        }

        $this->getResponse()->setStatusCode(400);
        return $utilities->returnError("Invalid Token");
    }

    public function delete($id)
    {
        return new JsonModel(parent::delete($id)); // TODO: Change the autogenerated stub
    }

    public function deleteList($data)
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
                    $token = new Token();
                    $keys = new Keys();
                    if (isset($data['keys']) && $keyData = $data['keys']) {
                        if (isset($keyData['id'])) {
                            $keys->setId($keyData['id']);

                            return new JsonModel($token->deleteToken($keys));
                        }
                    }
                }
            }
        }

        $this->getResponse()->setStatusCode(400);
        return $utilities->returnError("Invalid Token");
    }

    public function patch($id, $data)
    {
        return new JsonModel(parent::patch($id, $data)); // TODO: Change the autogenerated stub
    }

    public function patchList($data)
    {
        $utilities = new Utilities();

        $this->getResponse()->setStatusCode(400);
        return $utilities->returnError("Invalid Token");
    }
}