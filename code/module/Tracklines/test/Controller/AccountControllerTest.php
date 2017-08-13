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
     * @var string
     */
    private $tokenName = "";

    /**
     * @var string
     */
    private $tokenValue = "";

    /**
     * @var string
     */
    private $tokenInvalid = "";

    /**
     *
     */
    public function setUp()
    {
        $this->tokenName = "bob";
        $this->tokenValue = "bill";
        $this->tokenInvalid = "ryan";

        $this->accountTestData = [
            "credentials" => [
                "username"  => "bob",
                "password"  => "bob",
            ],
            "contactDetails" => [
                "email"     => "bob@bob.bob",
                "number"    => "0123456798",
            ],
            "parentId" => 0,
            "active" => true,
        ];

        parent::setUp();
    }

    //<editor-fold desc="Create Tests">

    /**
     * Should try and insert data valid token
     */
    public function testCreateAccountValidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

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
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenInvalid);

        $this->dispatch("/account", "POST", $this->accountTestData);
        $this->dispatch("/account");
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    //</editor-fold>

    //<editor-fold desc="Update Tests">

    /**
     *
     */
    public function testUpdateAccountValidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

        $this->dispatch("/account/1", "PUT", $this->accountTestData);
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

        $this->dispatch("/account/1", "PUT", $this->accountTestData);
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

        $this->dispatch("/account/1", "PATCH", $this->accountTestData);
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

        $this->dispatch("/account/1", "PATCH", $this->accountTestData);
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    //</editor-fold>

    //<editor-fold desc="Get Tests">

    /**
     * Should give data based on id
     */
    public function testGetAccountCanBeAccessed()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

        $this->dispatch("/account/1", "GET");
        $this->assertResponseStatusCode(200);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
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

    /**
     * Should get clientid with valid token
     */
    public function testRetrieveAccountValidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

        $this->dispatch("/account", "PATCH", $this->accountTestData);
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

        $this->dispatch("/account", "PATCH", $this->accountTestData);
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    //</editor-fold>

    //<editor-fold desc="Delete Tests">

    /**
     *
     */
    public function testDeleteAccountValidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

        $this->dispatch("/account/1", "DELETE");
        $this->assertResponseStatusCode(200);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    /**
     *
     */
    public function testDeleteAccountInvalidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenInvalid);

        $this->dispatch("/account/1", "DELETE");
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
    }

    //</editor-fold>

    //<editor-fold desc="List Tests">

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
     * should not allow delete list
     */
    public function testDeleteListNotAllowed()
    {
        $this->dispatch("/account", "DELETE");
        $this->assertResponseStatusCode(405);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(AccountController::class);
        $this->assertControllerClass("AccountController");
        $this->assertMatchedRouteName("account");
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

    //</editor-fold>

    //<editor-fold desc="Invalid Tests">

    /**
     * Should return 404 invalid address
     */
    public function testInvalidRouteDidNotCrash()
    {
        $this->dispatch("/account/bob", "GET");
        $this->assertResponseStatusCode(404);
    }

    /**
     * Should not allow to delete invalid numbers
     */
    public function testDeleteInvalidNumberDoesNotCrash()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

        $this->dispatch("/account/-1", "DELETE");
        $this->assertResponseStatusCode(404);
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

        $this->dispatch("/account/-1", "PUT", $this->accountTestData);
        $this->assertResponseStatusCode(404);
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

    //</editor-fold>
}
