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

use Tracklines\Service\Config\Config;
use Tracklines\Service\Token\Validator;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class AccountController extends AbstractRestfulController
{
    private function returnBlank()
    {
        return new JsonModel();
    }

    private function returnError()
    {
        $this->getResponse()->setStatusCode(405);
        return new JsonModel(["message" => "invalid token"]);
    }

    public function get($id)
    {
        return $this->returnBlank();
    }

    public function getList()
    {
        return $this->returnBlank();
    }

    public function create($data)
    {
        $token = $this->getRequest()->getHeader("token");
        if ($token) {
            $tokenValue = $token->getFieldValue();
            $config = new Config();
            $tokens = $config->getS3Config("tokens");

            if ($tokens) {
                $validator = new Validator();
                $validator->setTokens($tokens);
                $validator->setToken("account");
                $validator->setTokenValue($tokenValue);

                if ($validator->validateToken()) {
                    return $this->returnBlank();
                }
            }
        }

        return $this->returnError();
    }
}
