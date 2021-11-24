<?php

declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class StackTest extends TestCase
{
  /** @test */
  public function testPushAndPop(): void
  {
    $stack = [];
    
    $this->assertSame(0, count($stack) );
    
    array_push($stack, 'foo');
    $this->assertSame('foo', $stack[count($stack) - 1] );
    $this->assertSame(1, count($stack) );
    
    $this->assertSame('foo', array_pop($stack));
    $this->assertSame(0, count($stack));
  }
  
  public function testStrings(): void
  {
    $value = '[1,2]';
    $value2 = settype($value, 'array');
    
    $this->assertSame(gettype($value2), "boolean" );
  }
}

?>
