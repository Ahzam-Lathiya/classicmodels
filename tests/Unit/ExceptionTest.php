<?php

declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class ExceptionTest extends TestCase
{
  /** @test */
  public function testException(): void
  {
    $this->expectException(InvalidArgumentException::class);
  }
  
}

?>
