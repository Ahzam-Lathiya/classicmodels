<?php

declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class DependTest extends TestCase
{
  /** @test */
  public function testproducerOne(): string
  {
    $a = 'first';
    $this->assertSame($a, 'first');
    
    return $a;
  }
  
  
  public function testproducerTwo(): string
  {
    $b = 'second';
    $this->assertSame($b, 'second');
    
    return $b;
  }
  
  /**
  * @depends testproducerOne
  * @depends testproducerTwo
  */
  
  public function testConsumer(string $a, string $b): void
  {
    $this->assertSame($a, 'first');
    $this->assertSame($b, 'second');
  }
  
}

?>
