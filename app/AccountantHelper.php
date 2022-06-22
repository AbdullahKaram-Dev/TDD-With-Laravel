<?php

namespace App;

class AccountantHelper
{
    public function findProfit($amount)
    {
        $profitPercent = 10;
        return $profitPercent * $amount / 100;
    }
}
