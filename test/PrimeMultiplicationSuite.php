<?php
namespace test;

use Prime\PrimeMultiplication as PM;
use Prime\Prime;
use util\Math;

interface TestSuite 
{
    public function print_results();
    public function assertion($bool, $name, $desc = "");
    public function pass($method, $append = "");
    public function fail($method, $append = "");
}

class PrimeMultiplicationSuite implements TestSuite 
{

    private $prime = null;
    private $results = array();
    private $passed = 0;
    private $failed = 0;

    private $red = "\033[0;31m";
    private $green = "\033[0;32m";
    private $blue = "\033[1;34m";
    private $nocolor = "\033[0m";

    public function __construct() 
    {
        $this->prime = new Prime();
    }

    public function test_get_random()
    {
        $break_at = 1000;
        $min = 3;
        $max = -1;
        do {
            $rand = Math::random(2,3);
            $min = $rand < $min ? $rand : $min;
            $max = $rand > $max ? $rand : $max;
        } while(--$break_at > 0);
        $this->assertion($max === 3 && $min === 2, __METHOD__, "random does hit extremes");
    }

    public function test_mod_pow()
    {
        $this->assertion(Math::mod_pow(8, 4, 9) === 1, __METHOD__, "8^4 (mod 9) == 1");
        $this->assertion(Math::mod_pow(5, 117, 19) === 1, __METHOD__, "5^117 (mod 19) == 1");
        $this->assertion(Math::mod_pow(3486784409, 5, 9) === 8, __METHOD__, "3486784409^5 (mod 9) == 8");
        $this->assertion(Math::mod_pow(14691589, 7742931, 15485863) === 15485862, __METHOD__, "14691589^7742931 (mod 15485863) == 15485862");
    }

    public function test_is_not_prime() 
    {
        $this->assertion(! $this->prime->is_prime(0), __METHOD__, "Number 0 is not a prime");
        $this->assertion(! $this->prime->is_prime(1), __METHOD__, "Number 1 is not a prime");
        $this->assertion(! $this->prime->is_prime(6), __METHOD__, "Number 6 is not a prime");
        $this->assertion(! $this->prime->is_prime(20), __METHOD__, "Number 20 is not a prime");
        $this->assertion(! $this->prime->is_prime(40), __METHOD__, "Number 40 is not a prime");
        $this->assertion(! $this->prime->is_prime(60), __METHOD__, "Number 60 is not a prime");
        $this->assertion(! $this->prime->is_prime(341), __METHOD__, "Number 341 is not a prime");
        $this->assertion(! $this->prime->is_prime(561), __METHOD__, "Number 561 is not a prime");
        $this->assertion(! $this->prime->is_prime(1024), __METHOD__, "Number 1024 is not a prime");
    }

    public function test_is_prime() 
    {
        $this->assertion($this->prime->is_prime(2), __METHOD__, "Number 2 is a prime");
        $this->assertion($this->prime->is_prime(3), __METHOD__, "Number 3 is a prime");
        $this->assertion($this->prime->is_prime(7), __METHOD__, "Number 7 is a prime");
        $this->assertion($this->prime->is_prime(17), __METHOD__, "Number 17 is a prime");
        $this->assertion($this->prime->is_prime(29), __METHOD__, "Number 29 is a prime");
        $this->assertion($this->prime->is_prime(31), __METHOD__, "Number 31 is a prime");
        $this->assertion($this->prime->is_prime(15485863) === true, __METHOD__, "Number 15485863 is a prime");
        $this->assertion($this->prime->is_prime(472882027) === true, __METHOD__, "Number 472882027 is a prime");
        $this->assertion($this->prime->is_prime(433494437) === true, __METHOD__, "Number 433494437 is a prime");
        $this->assertion($this->prime->is_prime(2971215073) === true, __METHOD__, "Number 2971215073 is a prime");
    }

    public function test_get_rabin_miller_vars() 
    {
        $this->assertion($this->prime->rabin_miller_vars(6) === [1,3], __METHOD__, "6 -> [t,u] === [1,3]");
        $this->assertion($this->prime->rabin_miller_vars(220) === [2,55], __METHOD__, "220 -> [t,u] === [2,55]");
        $this->assertion($this->prime->rabin_miller_vars(1024) === [10,1], __METHOD__, "1024 -> [t,u] === [10,1]");
    }

    public function test_rabin_miller_primality_small_odd_numbers()
    {
        $this->assertion($this->prime->rabin_miller_primality_test(3), __METHOD__, "Number 3 is a prime");
        $this->assertion($this->prime->rabin_miller_primality_test(7), __METHOD__, "Number 7 is a prime");
        $this->assertion($this->prime->rabin_miller_primality_test(11), __METHOD__, "Number 11 is a prime");
    }

    public function test_rabin_miller_primality_small_odd_numbers_not_primes()
    {
        $this->assertion(! $this->prime->rabin_miller_primality_test(0), __METHOD__, "Number 0 is not a prime");
        $this->assertion(! $this->prime->rabin_miller_primality_test(27), __METHOD__, "Number 27 is not a prime");
        $this->assertion(! $this->prime->rabin_miller_primality_test(45), __METHOD__, "Number 45 is not a prime");
    }

    public function test_rabin_miller_primality_loop_test()
    {
        for($i = 0; $i < 1000; $i++) {
            $prime = $this->prime->rabin_miller_primality_test(341);
            if ($prime) {
                $i = 1000;
            }
        }
        $this->assertion($prime === false, __METHOD__, "Number 341 is not a prime");
    }

    public function test_carmichael_number_loop_test()
    {
        for($i = 0; $i < 1000; $i++) {
            $prime = $this->prime->rabin_miller_primality_test(561);
            if ($prime) {
                $i = 1000;
            }
        }
        $this->assertion($prime === false, __METHOD__, "Number 561 is not a prime");
    }

    public function test_prime_multiplication_get_value()
    {
        $table = new PM(10);
        $this->assertion($table->get_value(0, 0) == "", __METHOD__, "[0,0] == blank");
        $this->assertion($table->get_value(0, 1) == 2, __METHOD__, "[0,1] == 2");
        $this->assertion($table->get_value(10, 10) == 841, __METHOD__, "[10,10] == 841");
        $this->assertion($table->get_value(3, 7) == 85, __METHOD__, "[3,7] == 85");
        $this->assertion($table->get_value(10, 1) == 58, __METHOD__, "[10,1] == 58");
    }

    public function assertion($bool, $name, $desc = "")
    {
        if ($bool) {
            $this->pass($name, $desc);
        } else {
            $this->fail($name, $desc);
        }
    }

    public function pass($method, $append = "") 
    {
        $this->results[$method][] = $this->green . "Pass: $append" . $this->nocolor;
        $this->passed++;
    }

    public function fail($method, $append = "") 
    {
        $this->results[$method][] = $this->red . "Fail: $append" . $this->nocolor;
        $this->failed++;
    }

    public function print_results() 
    {

        $qty = 0;

        foreach($this->results as $name => $result) {
            $label = explode("::", $name);
            $label = isset($label[1]) ? ucwords(str_replace("_", " ", $label[1])) : $label[0];

            printf("%s:\n", $label);
            foreach($result as $i => $line) {
                printf("\t%d: %s\n", $qty + 1, $line);
                $qty++;
            }
        }

        printf("\n%sExecuted %d test(s). %sFailed(%d/%d) %sPassed(%d/%d)%s\n\n", 
            $this->blue,
            $qty,
            $this->red,
            $this->failed,
            $qty,
            $this->green,
            $this->passed,
            $qty,
            $this->nocolor
        );
    }
}
