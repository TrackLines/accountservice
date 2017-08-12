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


namespace Tracklines\Service\Config;

use Aws\S3\S3Client;

/**
 * Class Config
 * @package Tracklines\Service\Config
 */
class Config implements ConfigInterface
{
    /**
     * @return \stdClass
     */
    public function getDatabaseConfig() : \stdClass
    {
        $returnObj = new \stdClass();
        $returnObj->username = getenv("DATABASE_USERNAME");
        $returnObj->password = getenv("DATABASE_PASSWORD");
        $returnObj->address = getenv("DATABASE_ADDRESS");
        $returnObj->database = "account";

        return $returnObj;
    }

    /**
     * @param string $configName
     * @return null
     */
    public function getS3Config(string $configName)
    {
        try {
            $s3creds    = [
                "region"        => getenv("S3_REGION"),
                "version"       => "latest",
                "credentials"   => [
                    "key"       => getenv("S3_KEY"),
                    "secret"    => getenv("S3_SECRET"),
                ],
            ];
            $s3file     = [
                "Bucket"    => getenv("S3_BUCKET"),
                "Key"       => getenv("S3_FILE"),
            ];

            $s3         = new S3Client($s3creds);
            $result     = $s3->getObject($s3file);
            $body       = (string)$result['Body'];
            $bodyObj    = \GuzzleHttp\json_decode($body);

            return $bodyObj->{$configName};
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        return null;
    }
}
