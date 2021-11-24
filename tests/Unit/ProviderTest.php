<?php

declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;


/*

Given When Then
Arrange Act Assert

*/

final class ProviderTest extends TestCase
{
  /**
  * @dataProvider additionNamedProvider
  */
  
  public function testSum(int $a, int $b, int $c): void
  {
    $this->assertSame($c, $a + $b);
  }
  
  
  public function additionProvider(): array
  {
    return [
             [1,0,1],
             [0,0,0],
             [2,3,5],
             [1,1,3]
    ];
  }
  
  public function additionNamedProvider(): array
  {
    return [
             'one plus zero'  => [1,0,1],
             'zero plus zero' => [0,0,0],
             'two plus three' => [2,3,6],
             'one plus one'   => [1,1,2]
    ];
  }
}

?>
