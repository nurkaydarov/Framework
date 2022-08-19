<?php

namespace app\core;

class Response
{
    public function setStatucCode(int $code)
    {
        http_response_code($code);
    }

}