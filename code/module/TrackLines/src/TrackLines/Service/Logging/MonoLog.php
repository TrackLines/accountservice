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

namespace TrackLines\Service\Logging;

use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use TrackLines\AbstractEntity\MonoLogAbstract;

class MonoLog extends MonoLogAbstract
{
    public function __construct()
    {
        $logger = new Logger('TrackLines');
        $stream = new StreamHandler(__DIR__ . '/../../../../../../../logs/tracklines-json.log', \Monolog\Logger::ERROR, true);
        $stream->setFormatter(new LogstashFormatter('tracklines'));
        $logger->pushHandler($stream);

        $this->setLogger($logger);
    }

    public function error($message, $data = null)
    {
        if ($data) {
            if (!is_array($data)) {
                $data = [
                    'data' => $data,
                ];
            }
            $this->getLogger()->error($message, $data);
        } else {
            $this->getLogger()->error($message);
        }
    }
}
