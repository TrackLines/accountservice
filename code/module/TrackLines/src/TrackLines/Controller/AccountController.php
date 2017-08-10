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


namespace TrackLines\Controller;

use TrackLines\Service\Account\Account;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class AccountController
 * @package TrackLines\Controller
 */
class AccountController extends AbstractRestfulController
{
    /**
     * @var Account
     */
    private $account;

    /**
     * @deprecated
     * @return JsonModel
     */
    private function returnBlank()
    {
        return new JsonModel();
    }

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->account = new Account();
    }

    /**
     * @param int $id
     * @return JsonModel
     */
    public function get($id)
    {
        $this->account->setAccountId($id);

        return $this->returnBlank();
    }

    /**
     * @param int $id
     * @param mixed $data
     */
    public function update($id, $data)
    {
        $this->account->setAccountId($id);
        $this->account->setUsername($data['username']);

        $this->returnBlank();
    }

    /**
     * @return JsonModel
     */
    public function getList()
    {
        return $this->returnBlank();
    }

    /**
     * @param mixed $data
     * @return JsonModel
     */
    public function create($data)
    {
        return $this->returnBlank();
    }
}
