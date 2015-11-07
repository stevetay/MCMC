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

final class AuthCheckException
{
    public function __construct($data, $message, $code = 0)
    {
        $error_data = array();

        if (!empty($data)) {
            if (is_array($data)) {
                $error_data = $data;
            } else {
                $error_data = array(
                    'field' => $data,
                );
            }
        }

        throw new \App\Seeties\Exceptions\Exception($error_data, $message, 400);
    }
}
