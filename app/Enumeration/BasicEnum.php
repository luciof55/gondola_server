<?php

namespace App\Enumeration;

use Illuminate\Support\Facades\Log;
use App;

abstract class BasicEnum { 
    private static $constCacheArray = NULL; 

	private function __construct(){ 
      /* 
        Preventing instance :) 
      */ 
     } 

    private static function getConstants() { 
        if (self::$constCacheArray == NULL) { 
            self::$constCacheArray = []; 
        } 
        $calledClass = get_called_class(); 
        if (!array_key_exists($calledClass, self::$constCacheArray)) { 
            $reflect = new \ReflectionClass($calledClass); 
            self::$constCacheArray[$calledClass] = $reflect->getConstants(); 
        } 
        return self::$constCacheArray[$calledClass]; 
    } 
	
	public static function getEnum() {
		$constants = self::getConstants();
		$result = array_keys($constants);	
		return $result;
	}
	
	public static function getEnumTranslate() {
		$constants = self::getConstants();
		$result = collect([]);		
		$locale = App::getLocale();
		foreach($constants as $key => $value) {
			$translated = trans('messages.'.$key);
			$result->put($value, $translated);  
		}
		
		return $result;
	}

    public static function isValidName($name, $strict = false) { 
        $constants = self::getConstants(); 

        if ($strict) { 
            return array_key_exists($name, $constants); 
        } 

        $keys = array_map('strtolower', array_keys($constants)); 
        return in_array(strtolower($name), $keys); 
    } 

    public static function isValidValue($value) { 
        $values = array_values(self::getConstants()); 
        return in_array($value, $values, $strict = true); 
    } 
} 