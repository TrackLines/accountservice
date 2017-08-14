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
 * Time: 14:51
 */

namespace TracklinesTest\Controller\Account;


use Tracklines\Controller\AccountController;
use TracklinesTest\Controller\BaseTest;

class UpdateTest extends BaseTest
{
    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     *
     */
    public function testUpdateAccountValidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

        $this->dispatch("/account/1", "PUT", $this->accountTestData1);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     *
     */
    public function testUpdateAccountInvalidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenInvalid);

        $this->dispatch("/account/1", "PUT", $this->accountTestData1);
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     *
     */
    public function testUpdateAccountInvalidData()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenInvalid);

        $this->dispatch("/account/1", "PUT", []);
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     *
     */
    public function testPatchAccountValidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

        $this->dispatch("/account/1", "PATCH", $this->updateTestData2);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     *
     */
    public function testPatchAccountInvalidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenInvalid);

        $this->dispatch("/account/1", "PATCH", $this->updateTestData2);
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     *
     */
    public function testPatchAccountInvalidData()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenInvalid);

        $this->dispatch("/account/1", "PATCH", []);
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     * should not allow update invalid number
     */
    public function testUpdateInvalidDoesNotCrash()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

        $this->dispatch("/account/-1", "PUT", $this->updateTestData1);
        $this->assertResponseStatusCode(404);
    }

    /**
     * should not allow update list
     */
    public function testReplaceListNotAllowed()
    {
        $this->dispatch("/account", "PUT");
        $this->assertResponseStatusCode(405);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }
}
