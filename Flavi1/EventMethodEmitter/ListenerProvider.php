<?php
namespace Flavi1\EventMethodEmitter;
// todo add autoload
// todo add psr interfaces

class ListenerProvider
{
	protected $className = '';
	protected $methodListeners = [];
	
	public function __construct($className)
	{
		$this->className = $className;
	}
	
	public function getClassName()
	{
		return $this->className;
	}
	
	public function addMethodListener($evType, $method, $listener, $order = 0)
	{
//echo "\nADDED method listener : $evType $method on ".$this->className."\n";
		$this->methodListeners[$method][$evType][] = [$order, $listener];
	}
	
	public function getListenersForEvent($ev)
	{
		$result = [];
		$arr = $this->methodListeners[$ev->method][$ev->type] ?? false;
		if($arr and count($arr)) {
			usort($arr, function($a, $b) {
				return $a[0] <=> $b[0];
			});
			foreach($arr as $listener)
				$result[] = $listener[1];
		}
		return $result;
	}	
}
