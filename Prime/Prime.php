<?php
namespace Prime;

use util\Math;

/**
 * Prime.php
 */
class Prime {

    public function __construct()
    { 
    }

    /**
     * get_primes returns n amount of primes
     *
     * @param <integer> quantity of requested prime numbers
     * @return <array> returns an array of size $n with consecutive primes
     */
    final public function get_primes($n) 
    {
        if ($n == 0) {
            return 0;
        }

        $result = Array();
        $i = 2;
        do {
            if ($this->is_prime($i)) {
                $result[] = $i;
                $n--;
            }
            $i++;
        } while ($n > 0);

        return $result;
    }

    /**
     * Verifies if number is a prime.
     *
     * @param $number <integer>
     * @return boolean
     */
    final public function is_prime($number) 
    {
        /* cast to ensure we are working with numbers */
        $number = (int) $number;

        /* base cases - order matters - working with numbers greater than 3 */
        if ($number < 2) return false;
        if ($number === 2 || $number === 3) return true;
        if ($number % 2 === 0) return false;

        $a = Math::random(2, $number - 1);
        $product = Math::mod_pow($a, $number - 1, $number);
        $result = (bool) ($product == 1);

        /* if primality succeeds, we need to ensure its accuracy, 
         * implement Rabin and Miller primality test 
         */
        $result = $result ?  $this->rabin_miller_primality_test($number) : $result;

        return $result;
    }




    final public function rabin_miller_primality_test($number, $k = 100)
    {
        /* cast to ensure we are working with numbers */
        $number = (int) $number;

        /* base cases - order matters - working with numbers greater than 3 */
        if ($number < 2) return false;
        if ($number === 2 || $number === 3) return true;
        if ($number % 2 === 0) return false;

        /* array for memoization */
        $verified = [];

        list($t, $u) = $this->rabin_miller_vars($number - 1);
        $r = $t - 1;

        do {
            if (count($verified) == $number - 2) {
                /* break do loop */
                $k = 0;
            } else {
                /* get a new unique base */
                do {
                    $base = Math::random(2, $number - 1);
                }while(in_array($base, $verified));

                /* memoize the base and get another unique base */
                $verified[] = $base;
                $witness = Math::mod_pow($base, $u, $number);
                if ($witness != 0 && $witness !== 1  && $witness !== $number - 1) {
                    for($i = 1; $i <= $t; $i++) {
                        $prev_witness = $witness;
                        $witness = Math::mod_pow($witness, 2, $number);
                        if ($witness == 1) {

                            /* Determine if Non-trivial square root */
                            if ($prev_witness != 1 && $prev_witness != $number - 1) {
                                /* composite */
                                return false;
                            } else {
                                /* loop again for accuracy */
                                $i = $t;
                            }
                        } else if ($i == $t && $witness != 1) {
                            /* composite */
                            return false;
                        }
                    }
                }
            }
        } while(--$k > 0);

        /* most likely a prime */
        return true;
    }

    final public function rabin_miller_vars($number)
    {
        /* number must be even */
        if ($number % 2 !== 0) {
            throw new \Exception($number . " is not even");
        }

        $t = 1;
        $u = $number / 2;
        while(($u % 2) === 0) {
            ++$t;
            $u /= 2;
        }
        return [$t, $u];
    }
}

