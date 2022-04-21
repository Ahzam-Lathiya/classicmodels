<?php

namespace app\core\exceptions;

class JsonException extends \Exception
{
  protected $code = 403;
  protected $message = 'Some Error in the API.';
}

?>
