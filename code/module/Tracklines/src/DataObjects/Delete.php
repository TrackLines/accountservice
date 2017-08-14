<?php
/**
 * Created by IntelliJ IDEA.
 * User: hootonm
 * Date: 14/08/2017
 * Time: 16:21
 */

namespace Tracklines\DataObjects;


/**
 * Class Delete
 * @package Tracklines\DataObjects
 */
class Delete
{
    /**
     * @var int
     */
    private $clientId;

    /**
     * @var Credentials
     */
    private $credentials;

    /**
     * @var bool
     */
    private $active;

    /**
     * @return Credentials
     */
    public function getCredentials(): Credentials
    {
        return $this->credentials;
    }

    /**
     * @param Credentials $credentials
     */
    public function setCredentials(Credentials $credentials)
    {
        $this->credentials = $credentials;
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
}
