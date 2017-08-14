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
 * Time: 12:37
 */

namespace Tracklines\DataObjects;


/**
 * Class Update
 * @package Tracklines\DataObjects
 */
class Update
{
    /**
     * @var int
     */
    private $clientId;

    /**
     * @var Credentials
     */
    private $originalCredentials;

    /**
     * @var Credentials
     */
    private $newCredentials;

    /**
     * @var ContactDetails
     */
    private $originalContactDetails;

    /**
     * @var ContactDetails
     */
    private $newContactDetails;

    /**
     * @var bool
     */
    private $active;

    /**
     * @return Credentials
     */
    public function getOriginalCredentials(): Credentials
    {
        return $this->originalCredentials;
    }

    /**
     * @param Credentials $originalCredentials
     */
    public function setOriginalCredentials(Credentials $originalCredentials)
    {
        $this->originalCredentials = $originalCredentials;
    }

    /**
     * @return Credentials
     */
    public function getNewCredentials(): Credentials
    {
        if (isset($this->newCredentials)) {
            return $this->newCredentials;
        }

        return new Credentials();
    }

    /**
     * @param Credentials $newCredentials
     */
    public function setNewCredentials(Credentials $newCredentials)
    {
        $this->newCredentials = $newCredentials;
    }

    /**
     * @return ContactDetails
     */
    public function getOriginalContactDetails(): ContactDetails
    {
        return $this->originalContactDetails;
    }

    /**
     * @param ContactDetails $originalContactDetails
     */
    public function setOriginalContactDetails(ContactDetails $originalContactDetails)
    {
        $this->originalContactDetails = $originalContactDetails;
    }

    /**
     * @return ContactDetails
     */
    public function getNewContactDetails(): ContactDetails
    {
        if (isset($this->newContactDetails)) {
            return $this->newContactDetails;
        }

        return new ContactDetails();
    }

    /**
     * @param ContactDetails $newContactDetails
     */
    public function setNewContactDetails(ContactDetails $newContactDetails)
    {
        $this->newContactDetails = $newContactDetails;
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
}
