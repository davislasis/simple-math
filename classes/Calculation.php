<?php

class Calculation
{
    public string $separator = '-';

    public function calculate(string $a, string $b, string $action): string
    {
        return match ($action) {
            '+' => $this->add($a, $b),
            '-' => $this->subtract($a, $b),
            '*' => $this->multiply($a, $b),
            default => 0,
        };
    }

    public function format(string $a, string $b, string $action, string $result): string
    {
        return match ($action) {
            '*' => $this->formatMultiply($a, $b, $result),
            default => $this->formatSimple($a, $b, $action, $result),
        };
    }

    /**
     * @param string $separator
     * @return Calculation
     */
    public function setSeparator(string $separator = ''): self
    {
        $this->separator = $separator;

        return $this;
    }

    public function add(string $a, string $b): string
    {
        $output = '';
        $carry = 0;

        $a = strrev($a);
        $b = strrev($b);

        $length = max(strlen($a), strlen($b));

        for ($i = 0; $i < $length; $i++) {
            $sum = ($a[$i] ?? 0) + ($b[$i] ?? 0) + $carry;

            $output .= $sum % 10;
            $carry = intdiv($sum, 10);
        }

        if ($carry) {
            $output .= $carry;
        }

        return strrev($output);
    }

    public function subtract(string $a, string $b): string
    {
        $output = '';
        $borrow = 0;

        $a = strrev($a);
        $b = strrev($b);

        for ($i = 0; $i < strlen($a); $i++) {
            $digitA = (int)$a[$i] - $borrow;
            $digitB = (int)($b[$i] ?? 0);

            if ($digitA < $digitB) {
                $digitA += 10;
                $borrow = 1;
            } else {
                $borrow = 0;
            }

            $output .= $digitA - $digitB;
        }

        return ltrim(strrev($output), '0') ?: '0';
    }

    public function multiply(string $a, string $b): string
    {
        $output = '0';
        $shift = 0;

        for ($i = strlen($b) - 1; $i >= 0; $i--, $shift++) {
            $partial = $this->multiplyByDigit($a, (int) $b[$i]);

            if ($partial === '0') {
                continue;
            }

            $partial .= str_repeat('0', $shift);
            $output = $this->add($output, $partial);
        }

        return $output;
    }

    private function multiplyByDigit(string $number, int $digit = 0): string
    {
        if ($digit === 0) {
            return '0';
        }

        $output = '';
        $carry = 0;

        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $value = ((int) $number[$i] * $digit) + $carry;

            $output .= $value % 10;
            $carry = intdiv($value, 10);
        }

        if ($carry) {
            $output .= $carry;
        }

        return strrev($output);
    }

    private function getWidth(string $a, string $b, string $result)
    {
        return max(strlen($a), strlen($b) + 1, strlen($result));
    }

    private function formatSimple(string $a, string $b, string $action, string $result): string
    {
        $width = $this->getWidth($a, $b, $result);

        $output = str_pad($a, $width, ' ', STR_PAD_LEFT) . PHP_EOL;
        $output .= $action . str_pad($b, $width - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
        $output .= str_repeat($this->separator, $width) . PHP_EOL;
        $output .= str_pad($result, $width, ' ', STR_PAD_LEFT) . PHP_EOL;

        return $output;
    }

    private function formatMultiply(string $a, string $b, string $result): string
    {
        $width = $this->getWidth($a, $b, $result);
        $lineWidth = max(strlen($a), strlen($b) + 1);

        $output = str_pad($a, $width, ' ', STR_PAD_LEFT) . PHP_EOL;
        $output .= str_pad('*' . $b, $width, ' ', STR_PAD_LEFT) . PHP_EOL;

        if (strlen($b) === 1) {
            // single digit has no partial

            $output .= str_repeat($this->separator, $width) . PHP_EOL;
            $output .= str_pad($result, $width, ' ', STR_PAD_LEFT) . PHP_EOL;

            return $output;
        }

        $output .= str_repeat($this->separator, $width) . PHP_EOL;

        $partials = $this->getPartials($a, $b);

        foreach ($partials as $shift => $partial) {
            $value = $partial . str_repeat(' ', $shift);

            $output .= str_pad($value, $width, ' ', STR_PAD_LEFT) . PHP_EOL;
        }

        $output .= str_repeat($this->separator, $width) . PHP_EOL;
        $output .= str_pad($result, $width, ' ', STR_PAD_LEFT) . PHP_EOL;

        return $output;
    }

    private function getPartials(string $a, string $b): array
    {
        $output = [];

        foreach (str_split(strrev($b)) as $digit) {
            $output[] = $this->multiplyByDigit($a, (int) $digit);
        }

        return $output;
    }

    public function doTest(string $type, array $line, int $calculated, int $expected): void
    {

    }
}