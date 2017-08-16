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
 * Time: 12:40
 */

namespace Tracklines\DataObjects;


/**
 * Class ContactDetails
 * @package Tracklines\DataObjects
 */
class ContactDetails
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $contactNumber;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        if (isset($this->email)) {
            return $this->email;
        }

        return "";
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getContactNumber(): string
    {
        if (isset($this->contactNumber)) {
            return $this->contactNumber;
        }

        return "";
    }

    /**
     * @param string $contactNumber
     */
    public function setContactNumber(string $contactNumber)
    {
        $this->contactNumber = $contactNumber;
    }


}
