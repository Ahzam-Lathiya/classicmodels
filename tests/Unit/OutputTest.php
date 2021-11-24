<?php

declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class OutputTest extends TestCase
{
  /** @test */
  public function testExpectFooActualFoo(): void
  {
    $this->expectOutputString('foo');
    
    print 'foo';
  }
  
  
  public function testExpectBarActualBaz(): void
  {
    $this->expectOutputString('Bar');
    
    print 'Bar';
  }
}

?>
