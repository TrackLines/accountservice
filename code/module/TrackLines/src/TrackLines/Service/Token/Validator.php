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

namespace TrackLines\Service\Token;

use TrackLines\AbstractEntity\ValidatorAbstract;

/**
 * Class Validator
 * @package TrackLines\Service\Token
 */
class Validator extends ValidatorAbstract
{
    /**
     * @var object
     */
    private $logger;

    /**
     * @return object
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param object $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    private function validateToken() : bool
    {
        if ($token = $this->getToken()) {
            if ($tokens = $this->getTokens()) {
                if (isset($tokens->{$token})) {
                    $validToken = $tokens->{$token};

                    return true;
                }
            }
        }

        if ($this->getLogger()) {
            $this->getLogger()->getLogger()->error("Invalid Token", [
                $this->getToken(),
                $_SERVER
            ]);
        }

        return false;
    }
}
