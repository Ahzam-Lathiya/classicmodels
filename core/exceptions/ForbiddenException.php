<?php

namespace app\core\exceptions;

class ForbiddenException extends \Exception
{
  protected $code = 403;
  protected $message = 'First login to access this resource.';
}

?>
