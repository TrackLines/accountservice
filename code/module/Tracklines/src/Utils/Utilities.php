<?php
/**
 * Created by IntelliJ IDEA.
 * User: Keloran
 * Date: 11/08/2017
 * Time: 23:14
 */

namespace Tracklines\Utils;

use Zend\View\Model\JsonModel;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class Utilities
 * @package Tracklines\Utils
 */
class Utilities extends AbstractActionController
{
    /**
     * @return JsonModel
     */
    public function blankJson() : JsonModel
    {
        return new JsonModel();
    }

    /**
     * @param string $error
     * @return JsonModel
     */
    public function returnError(string $error) : JsonModel
    {
        return new JsonModel([
            "message" => $error
        ]);
    }

    /**
     * @param array $array
     * @return \stdClass
     */
    public function convertToObject(array $array) : \stdClass
    {
        $object = new \stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->convertToObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }
}
