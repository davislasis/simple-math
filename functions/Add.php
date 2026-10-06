<?php
function add(string $a, string $b): string
{
    $i = strlen($a) - 1;
    $j = strlen($b) - 1;

    $carry = 0;
    $result = '';

    while ($i >= 0 || $j >= 0 || $carry > 0) {
        $sum = $carry;

        if ($i >= 0) {
            $sum += (int)$a[$i];
            $i--;
        }

        if ($j >= 0) {
            $sum += (int)$b[$j];
            $j--;
        }

        $result .= (string)($sum % 10);

        $carry = intdiv($sum, 10);
    }

    return strrev($result);
}