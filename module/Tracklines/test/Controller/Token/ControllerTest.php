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

namespace TracklinesTest\Controller\Token;

use TracklinesTest\Controller\BaseTest;

/**
 * Class AccountControllerTest
 * @package TracklinesTest\Controller
 */
class ControllerTest extends BaseTest
{
    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Should return 404 invalid address
     */
    public function testInvalidRouteDidNotCrash()
    {
        $this->dispatch("/token/bob", "GET");
        $this->assertResponseStatusCode(404);
    }
}
