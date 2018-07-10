<?php

/**
 * Class StopWatch
 *
 * usage:
 *
 * StopWatch::reset();
 * something_code();
 * StopWatch::say();
 */
class StopWatch
{
    static public $start_at;

    public function __construct()
    {
        $this->reset();
    }

    static public function reset()
    {
        static::$start_at = microtime(1);
    }

    static public function getDeltaMicroSeconds()
    {
        $delta_us = microtime(1) - static::$start_at;

        return $delta_us;
    }

    static public function getDeltaSeconds()
    {
        return static::getDeltaMicroSeconds() ;
    }

    static public function say()
    {
        echo "\n".static::getDeltaSeconds()." sec\n";
    }
}
