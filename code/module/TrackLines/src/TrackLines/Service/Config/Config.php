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


namespace TrackLines\Service\Config;

use Aws\S3\S3Client;
use TrackLines\AbstractEntity\ConfigAbstract;

/**
 * Class Config
 * @package TrackLines\Service\Config
 */
class Config extends ConfigAbstract implements ConfigInterface
{
    /**
     * @var \Monolog\Logger
     */
    public $logger;

    /**
     * @return \Monolog\Logger
     */
    public function getLogger() : \Monolog\Logger
    {
        return $this->logger;
    }

    /**
     * @param \Monolog\Logger $logger
     */
    public function setLogger(\Monolog\Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $configName
     * @return null|object
     */
    public function getS3Config(string $configName)
    {
        try {
            $s3creds    = false;
            $file       = false;

            $config = $this->getConfig();

            // Creds
            if (isset($config['s3creds'])) {
                $s3creds = $config['s3creds'];
            } else {
                return null;
            }

            // file to parse
            if (isset($config['s3file'])) {
                $file = $config['s3file'];
            } else {
                return null;
            }

            $s3 = new S3Client($s3creds);
            $result = $s3->getObject($file);
            $body = (string)$result['Body'];
            $bodyObj = \GuzzleHttp\json_decode($body);

            return $bodyObj->{$configName};
        } catch (\Exception $e) {
            if ($this->getLogger()) {
                $this->getLogger()->getLogger()->error($e->getMessage());
            }
        }

        return null;
    }
}
