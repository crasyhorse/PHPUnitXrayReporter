<?php

declare(strict_types=1);

namespace CrasyHorse\PhpunitXrayReporter\Results;

use Carbon\Carbon;
use Exception;

/**
 * Entity class that holds all attributes of a single test run.
 *
 * @author Florian Weidinger
 */
 class TestResult
 {
     /**
      * The FQSEN of the spec.
      *
      * @var class-string
      */
     private $fqsen;

     /**
      * The message passed by PHP-Unit.
      *
      * @var string|null
      */
     private $message;

     /**
      * The name of the PHP-Unit spec.
      *
      * @var string
      */
     private $name;

     /**
      * Timestamp that marks the starting time of the spec.
      *
      * @var Carbon
      */
     private $start;

     /**
      * The duration of the test in seconds.
      *
      * @var float
      */
     private $time;

     /**
      *
      * Constructor.
      *
      * @param class-string $fqsen The fully qualified structural element name of the PHP-Unit spec.
      * @param Carbon $start Timestamp that marks the starting time of the spec.
      * @param float $time The duration of the test in seconds.
      * @param string|null $message The message passed by PHP-Unit.
      */
     public function __construct($fqsen, Carbon $start, float $time, string $message = null)
     {
         $this->fqsen = $fqsen;
         $this->name = $this->toSimpleName($fqsen);
         $this->time = $time * 1000;
         $this->start = $start;
         $this->message = $message;
     }

     /**
      * Computes the timestamp of when the test was finished.
      *
      * @return string
      */
     public function getFinish(): string
     {
         $finish = new Carbon($this->start);

         $millisecs = intval(round($this->time));
         $finish = $finish->addMilliseconds($millisecs)
            ->toImmutable()
            ->toIso8601String();

         return $finish;
     }

     /**
      * Returns the fully qualified structural element name of the PHP-Unit spec.
      *
      * @psalm-mutation-free
      *
      * @return class-string
      */
     public function getFQSEN()
     {
         return $this->fqsen;
     }

     /**
      * Returns the message passed by PHP-Unit.
      *
      * @psalm-mutation-free
      *
      * @return string|null
      */
     public function getMessage()
     {
         return $this->message;
     }

     /**
      * Returns the simple name of the spec.
      *
      * @psalm-mutation-free
      *
      * @return string
      */
     public function getName(): string
     {
         return $this->name;
     }

     public function getStart(): string
     {
         return $this->start->toIso8601String();
     }

     /**
      * Returns the duration of the spec in milliseconds.
      *
      * @psalm-mutation-free
      *
      * @return float
      */
     public function getTime(): float
     {
         return $this->time;
     }

     /**
      * Takes the FQSEN of a PHP-Unit spec and extract the method from it.
      *
      * @param class-string $fqsen The Fully qualified structural element name of the PHP-Unit spec.
      *
      * @return string The method name taken from the FQSEN.
      * @throws Exception Thrown if the regex has not matched anything and therefore, $result is not an array.
      */
     private function toSimpleName($fqsen): string
     {
         preg_match('/(?<=::)[_0-9a-zA-Z]+$/', $fqsen, $result);

         if (empty($result)) {
             throw new Exception("Could not get the method name from {$fqsen}.");
         }

         return $result[0];
     }
 }
