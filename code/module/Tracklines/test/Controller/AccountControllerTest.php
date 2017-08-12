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
 * Time: 13:13
 */

namespace TracklinesTest\Controller;

use Tracklines\Controller\AccountController;
use Zend\Stdlib\Parameters;

/**
 * Class AccountControllerTest
 * @package TracklinesTest\Controller
 */
class AccountControllerTest extends BaseTest
{
    /**
     * @var array
     */
    private $accountTestData = [];

    /**
     *
     */
    public function setUp()
    {
        $this->accountTestData = [
            "credentials" => [
                "username"  => "bob",
                "password"  => "bob",
            ],
            "contactDetails" => [
                "email"     => "bob@bob.bob",
                "number"    => "0123456798",
            ],
            "parentId" => 0
        ];

        parent::setUp();
    }

    /**
     * Should give 200 because valid token
     */
    public function testActionIndexCanBeAccessed()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", "bob");
        $headers->addHeaderLine("tokenValue", "bill");

        $this->dispatch("/account", "GET");
        $this->assertResponseStatusCode(200);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     * Should give 400 because invalid token
     */
    public function testActionIndexCannotBeAccessed()
    {
        $this->dispatch("/account", "GET");
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     * Should try and insert data valid token
     */
    public function testCreateAccountValidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", "bob");
        $headers->addHeaderLine("tokenValue", "bill");
        
        $this->dispatch("/account", "POST", $this->accountTestData);
        $this->dispatch("/account");
        $this->assertResponseStatusCode(200);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     * This test should not try and insert data because invalid token
     */
    public function testCreateAccountInvalidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", "bob");
        $headers->addHeaderLine("tokenValue", "ryan");

        $this->dispatch("/account", "POST", $this->accountTestData);
        $this->dispatch("/account");
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     * Should return 404 invalid address
     */
    public function testInvalidRouteDidNotCrash()
    {
        $this->dispatch("/account/bob", "GET");
        $this->assertResponseStatusCode(404);
    }

    /**
     *
     */
    public function testUpdateAccountValidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", "bob");
        $headers->addHeaderLine("tokenValue", "bill");

        $this->dispatch("/account/2", "PUT", $this->accountTestData);
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
        $headers->addHeaderLine("tokenName", "bob");
        $headers->addHeaderLine("tokenValue", "ryan");

        $this->dispatch("/account/2", "PUT", $this->accountTestData);
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }
}
