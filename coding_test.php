<?php 

use Prime\PrimeMultiplication as PM;
use test\PrimeMultiplicationSuite as Test;

require_once __DIR__ . '/autoload.php';

/* Coding Challenge Test - */

$opt = 10;
$primes_only = false;
if (!empty($argv) && count($argv) > 1) {
    $opt = $argv[1];
    $primes_only = isset($argv[2]) && strtolower($argv[2]) == "notable";
}


if (is_numeric($opt)) {
    $start = microtime(true);
    if ($opt < 2 || $opt > 100000) {
        print("N is limited to 2 <= N <= 10^5 - For N eq to the upper limit took ~50s to execute\n");
    } else if (! $primes_only) {
        $primeMultiplication = new PM($opt);
        $primeMultiplication->paint();
    } else {
        $primeMultiplication = new PM($opt);
        $primeMultiplication->dump_primes();
    }
} else if (strtolower($opt) == "test") {

    $test = new Test();

    $reflector = new ReflectionClass(get_class($test));
    $functions =  $reflector->getMethods();

    foreach($functions as $v) {
        if ($v->class === get_class($test) && strpos($v->name, "test_") === 0) {
            $test->{$v->name}();
        }
    }
    $test->print_results();
} else {
    print("testing");
} 


?>
