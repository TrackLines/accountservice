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

namespace TracklinesTest\Controller;

use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Tracklines\Service\Config\Config;

/**
 * Class BaseTest
 * This is just for not having to repeat the same code, its test is to stop warnings
 * @package TracklinesTest\Controller
 */
class BaseTest extends AbstractHttpControllerTestCase {
    /**
     * @var string
     */
    protected $tokenName;

    /**
     * @var string
     */
    protected $tokenValue;

    /**
     * @var string
     */
    protected $tokenInvalid;

    /**
     * @var array
     */
    protected $createTestData;

    /**
     * @var array
     */
    protected $updateTestData1;

    /**
     * @var array
     */
    protected $updateTestData2;

    /**
     * @var array
     */
    protected $retrieveData;

    /**
     * @var array
     */
    protected $deleteData;

    /**
     *
     */
    public function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        $this->tokenName = "bob";
        $this->tokenValue = "bill";
        $this->tokenInvalid = "ryan";

        $this->createTestData = [
            "credentials" => [
                "username"  => "testUsername",
                "password"  => "testPassword",
            ],
            "contactDetails" => [
                "email"     => "bob@bob.bob",
                "number"    => "0123456798",
            ],
            "parentId" => 0,
            "active" => true,
        ];

        $this->updateTestData1 = [
            "active" => true,
            "originalCredentials" => [
                "username"  => "testUsername",
                "password"  => "testPassword",
            ],
            "newCredentials" => [
                "password" => "testPasswordUpdate",
            ],
        ];

        $this->updateTestData2 = [
            "active" => true,
            "originalCredentials" => [
                "username"  => "testUsername",
                "password"  => "testPassword",
            ],
            "newContactDetails" => [
                "email" => "bob@bob.bobs",
            ],
        ];

        $this->retrieveData = [
            "username" => "testUsername",
            "password" => "testPassword",
        ];

        $this->deleteData = [
            "credentials" => [
                "username" => "testUsername",
                "password" => "testPassword",
            ],
            "clientId" => 1,
        ];

        parent::setUp();
    }

    /**
     * This exists just to avoid warning
     */
    public function testBase()
    {
        $result = true;
        $this->assertTrue($result);
    }
}
