<?php

declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;


/*

Given When Then
Arrange Act Assert

*/



final class DataTest extends TestCase
{
  /**
  * @dataProvider additionProviderPositive
  * @dataProvider additionProviderNegative
  */
  
  public function testSum(int $a, int $b, int $c): void
  {
    $this->assertSame($c, $a + $b);
  }
  
  
  public function additionProviderPositive(): array
  {
    return [
            [1,0,1],
            [2,3,5],
            [9,8,17],
            [5,6,11],
            [10,1,11]
    ];
  }
  
  
  public function additionProviderNegative(): array
  {
    return [
            [-1,0,-2],
            [-2,-3,-5],
            [-10,0,-10]
    ];
  }  
}

?>
