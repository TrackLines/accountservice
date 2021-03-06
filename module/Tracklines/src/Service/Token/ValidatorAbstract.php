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


namespace Tracklines\Service\Token;

/**
 * Class ValidatorAbstract
 * @package Tracklines\Service\Token
 */
abstract class ValidatorAbstract
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var \stdClass
     */
    private $tokens;

    /**
     * @var string
     */
    private $tokenValue;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return \stdClass
     */
    public function getTokens(): \stdClass
    {
        return $this->tokens;
    }

    /**
     * @param \stdClass $tokens
     */
    public function setTokens(\stdClass $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @return string
     */
    public function getTokenValue(): string
    {
        return $this->tokenValue;
    }

    /**
     * @param string $tokenValue
     */
    public function setTokenValue(string $tokenValue)
    {
        $this->tokenValue = $tokenValue;
    }


}
