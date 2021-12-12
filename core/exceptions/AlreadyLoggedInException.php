<?php

namespace app\core\exceptions;

class AlreadyLoggedInException extends \Exception
{
  protected $code = 450;
  protected $message = 'This ID is already logged in, in another session';
}

?>
