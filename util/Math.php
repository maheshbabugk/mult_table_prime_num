<?php
namespace util;

class Math {
    private function __construct() 
    {
    }

    final static public function random($min, $max)
    {
        return rand($min, $max);
    }

    final static public function mod_pow($base, $exp, $mod)
    {
        if ($base < 0 || $exp < 0 || $mod < 1) {
            throw new \Exception("Invalid inputs");
        }

        $bin = str_split(decbin($exp), 1);
        $len = count($bin);

        $product = $base % $mod;

        if ($product === 0.0) {
            return 0;
        }

        $extract = function ($i, $acc, $carry) use (&$extract, $mod) {
            if ($i == 0) {
                return $acc;
            } else {
                $sqr = ($carry * $carry) % $mod;
                $acc[] = $sqr;
                return $extract(--$i, $acc, $sqr);
            }
        };
        $sqr = $extract($len - 1, array($product), $product);

        $product = 1;
        for ($i = $len - 1; $i >= 0; $i--) {
            if ($bin[$i] == 1) {
                $product = ($product * $sqr[$len - 1 - $i]) % $mod;
            }
        }
        return $product;
    }

    final static private function pow2($exp, $acc = 1)
    {
        if ($exp > 63 || $exp < 0) {
            throw new \Exception("Invalid exponent Input");
        } else if ($exp == 0) {
            return (int) $acc;
        } else {
            return self::pow2(--$exp, $acc * 2);
        }
    }
}
