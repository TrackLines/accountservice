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
 * Time: 12:39
 */

namespace Tracklines\DataObjects;


/**
 * Class Client
 * @package Tracklines\DataObjects
 */
class Client
{
    /**
     * @var Credentials
     */
    private $credentails;

    /**
     * @var ContactDetails
     */
    private $contactDetails;

    /**
     * @var Keys
     */
    private $keys;

    /**
     * @var int
     */
    private $clientId;

    /**
     * @var int
     */
    private $parentId;

    /**
     * @var bool
     */
    private $active;

    /**
     * @return Credentials
     */
    public function getCredentails(): Credentials
    {
        return $this->credentails;
    }

    /**
     * @param Credentials $credentails
     */
    public function setCredentails(Credentials $credentails)
    {
        $this->credentails = $credentails;
    }

    /**
     * @return ContactDetails
     */
    public function getContactDetails(): ContactDetails
    {
        return $this->contactDetails;
    }

    /**
     * @param ContactDetails $contactDetails
     */
    public function setContactDetails(ContactDetails $contactDetails)
    {
        $this->contactDetails = $contactDetails;
    }

    /**
     * @return Keys
     */
    public function getKeys(): Keys
    {
        return $this->keys;
    }

    /**
     * @param Keys $keys
     */
    public function setKeys(Keys $keys)
    {
        $this->keys = $keys;
    }

    /**
     * @return int
     */
    public function getClientId(): int
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     */
    public function setClientId(int $clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     */
    public function setParentId(int $parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $return = get_object_vars($this);
        foreach ($return as $key => $value) {
            if (gettype($value) === "object") {
                $return[$key] = $value->toArray();
            }
        }

        return $return;
    }
}
