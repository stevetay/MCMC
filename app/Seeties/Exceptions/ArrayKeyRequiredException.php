<?php

namespace App\Seeties\Exceptions;

final class Exception extends \Exception
{
    public $data = array();

    public function __construct(array $data, $message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if ((!empty($data)) && (is_array($data))) {
            $this->data = $data;
        }
    }
}

final class ArrayKeyRequiredException
{
    public function __construct($key, $code = 0)
    {
        $message = 'some keys missing';

        $error_data = array(
            'key' => $key,
        );

        throw new \App\Seeties\Exceptions\Exception($error_data, $message, 400);
    }
}

?>