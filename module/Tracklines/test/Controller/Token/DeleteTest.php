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
 * Time: 14:57
 */

namespace TracklinesTest\Controller\Token;

use Tracklines\Controller\TokenController;
use TracklinesTest\Controller\BaseTest;

class DeleteTest extends BaseTest
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
    public function testDeleteTokenValidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenValue);

        $content = http_build_query($this->deleteTokendata);
        $request->setContent($content);

        $this->dispatch("/token", "DELETE");
        $this->assertResponseStatusCode(200);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(TokenController::class);
        $this->assertControllerClass("TokenController");
        $this->assertMatchedRouteName("token");
    }

    /**
     *
     */
    public function testDeleteTokenInvalidToken()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine("tokenName", $this->tokenName);
        $headers->addHeaderLine("tokenValue", $this->tokenInvalid);

        $content = http_build_query($this->deleteData);
        $request->setContent($content);

        $this->dispatch("/token", "DELETE");
        $this->assertResponseStatusCode(400);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(TokenController::class);
        $this->assertControllerClass("TokenController");
        $this->assertMatchedRouteName("token");
    }

    /**
     * should not allow delete list
     */
    public function testDeleteIdNotAllowed()
    {
        $this->dispatch("/token/1", "DELETE");
        $this->assertResponseStatusCode(405);
        $this->assertModuleName("tracklines");
        $this->assertControllerName(TokenController::class);
        $this->assertControllerClass("TokenController");
        $this->assertMatchedRouteName("token");
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

        $this->dispatch("/token/-1", "DELETE");
        $this->assertResponseStatusCode(404);
    }
}
