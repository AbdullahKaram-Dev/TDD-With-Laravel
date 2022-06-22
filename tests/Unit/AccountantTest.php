<?php

use PHPUnit\Framework\TestCase;
use App\AccountantHelper;

class AccountantTest extends TestCase
{
    /**
     * @test
     */
    public function test_find_profit()
    {
        $accountantHelper = new AccountantHelper();
        $amount = $accountantHelper->findProfit(100);
        $this->assertEquals(10,$amount);
        $this->assertLessThan(20,$amount);
    }
}
