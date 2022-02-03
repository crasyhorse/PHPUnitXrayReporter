<?php

namespace Crasyhorse\PhpunitXrayReporter\Xray;

/**
 * Every Xray type has to implement this methods.
 * 
 * @author Florian Weidinger
 */
interface Serializable {
    
    /**
     * Serializes the class into a JSON string.
     * 
     * @return string
     */
    public function toJson(): string;
}