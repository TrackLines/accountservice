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
 * Date: 14/08/2017
 * Time: 14:55
 */

namespace TracklinesTest\Controller\Account;

use Tracklines\Controller\AccountController;
use TracklinesTest\Controller\BaseTest;

class RetrieveTest extends BaseTest
{
    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Should should not allow retrieve invalid number
     */
    public function testInvalidAccountDoesNotCrash()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

        $this->dispatch("/account/-1", "GET");
        $this->assertResponseStatusCode(404);
    }

    /**
     * Should not allow get list
     */
    public function testGetListCannotBeAccessed()
    {
        $this->dispatch("/account", "GET");
        $this->assertResponseStatusCode(405);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     * Should get clientid with valid token
     */
    public function testRetrieveAccountValidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

        $this->dispatch("/account", "PATCH", $this->retrieveData);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     * Should give 400 with invalid token
     */
    public function testRetrieveAccountInvalidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenInvalid);

        $this->dispatch("/account", "PATCH", $this->retrieveData);
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     * Should give data based on id
     */
    public function testGetAccountCanBeAccessed()
    {
        // Login to the token
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

        $this->dispatch("/account", "PATCH", $this->retrieveData);

        $response = $this->getResponse()->getContent();
        if ($response) {
            $responseParsed = \GuzzleHttp\json_decode($response);
            $apiToken = $responseParsed->keys->api;

            // get account based on token
            $request = $this->getRequest();
            $headers = $request->getHeaders();
            $headers->addHeaderLine("tokenName", $this->tokenName);
            $headers->addHeaderLine("tokenValue", $this->tokenValue);
            $headers->addHeaderLine("apiToken", $apiToken);

            $this->dispatch("/account/1", "GET");
            $this->assertResponseStatusCode(200);
            $this->assertModuleName("tracklines");
            $this->assertControllerName(AccountController::class);
            $this->assertControllerClass("AccountController");
            $this->assertMatchedRouteName("account");
        }
    }

    /**
     * Should give 400 because invalid token no data
     */
    public function testGetAccountCannotBeAccessed()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenInvalid);

        $this->dispatch("/account/1", "GET");
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }
}
