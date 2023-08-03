<?php
namespace Flavi1\EventMethodEmitter;
include('EventMethodDispatcher.php');	// todo : replace with autoload
use Flavi1\EventMethodEmitter\EventMethodDispatcher;

class EventMethodEmitter
{
	static $initialized = false;
	static $EventMethodDispatcher = null;
	
	public static function __init()
	{
		if(static::$initialized)
			return;
		static::$EventMethodDispatcher = new EventMethodDispatcher(get_called_class());
		static::$initialized = true;
	}
	
    public function __call($m, $args)
    {
        return self::__call__($m, $args, $this);
    }

    public static function __callStatic($m, $args)
    {
		return self::__call__($m, $args);
	}
	
    private static function __call__($m, $args, $_this = false)
    {
		if(substr($m, 0, 1) == '_' or substr($m, -1) == '_') {
			/* TODO : THROW ERROR to avoid possible ____unwanted__ complexity */
echo 'ERR : '.$m;
			return;
		}
		
        if(!$_this)
			$_this = get_called_class();
		
		$before = method_exists($_this, '_'.$m) ? [$_this, '_'.$m] : false;
		$both = method_exists($_this, '_'.$m.'_') ? [$_this, '_'.$m.'_'] : false;
		$after = method_exists($_this, $m.'_') ? [$_this, $m.'_'] : false;
		
		$callMethod = false;
		foreach([$before, $both, $after] as $_m)
		{
			$isPrivate = ($_m) ? ( new ReflectionMethod($_this, $_m[1]) )->isPrivate() : false;
			if($_m and !$isPrivate)
				$callMethod = $_m;
		}
		
		if(!$callMethod)
			return;
		
		$response = null;
		
		if($before or $both)
			static::__methodEventEmitter('before', $_this, $m, $args, $response);
		
		echo '(('.$callMethod[1].'))'."\n";
		$response = call_user_func_array($callMethod, $args);
		
        if($after or $both)
			static::__methodEventEmitter('after', $_this, $m, $args, $response);
		
		return $response;
    }
    
	protected static function __methodEventEmitter($evType, $target, $method, &$args, &$response) {
		if(!static::$initialized)
			static::__init();
echo "\n$evType (ARGS : " . implode(', ', $args). ")\n";
		$ev = (object) [
			'type' => $evType,
			'method' => $method,
			'target' => $target,
			'arguments' => &$args,
			'response' => &$response,
		];
		static::$EventMethodDispatcher->dispatch($ev);
	}
	
	public static function addBeforeListener($method, $listener, $order = 0, $evType = 'before') {
		if(!static::$initialized)
			static::__init();
		static::$EventMethodDispatcher->addMethodListener($evType, $method, $listener, $order);
	}
	
	public static function addAfterListener($method, $listener, $order = 0) {
		static::addBeforeListener($method, $listener, $order, 'after');
	}
}
