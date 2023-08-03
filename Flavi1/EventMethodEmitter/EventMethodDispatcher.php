<?php
namespace Flavi1\EventMethodEmitter;
// todo add autoload
// todo add psr interfaces

class EventMethodDispatcher
{
	protected $className = '';
	protected $methodListeners = [];
	
	public function __construct($className)
	{
		$this->className = $className;
	}
	
	public function addMethodListener($evType, $method, $listener, $order = 0)
	{
echo "\nADDED method listener : $evType $method on ".$this->className."\n";
		if($evType == 'before' or $evType == 'after')
			$this->methodListeners[$method][$evType][] = [$order, $listener];
		else {
			/*TODO THROW ERROR*/
		}
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
	
	public function dispatch($ev)
	{
echo "DISPATCH method event : ".$ev->type." ".$ev->method." on ".$this->className."\n";
		foreach($this->getListenersForEvent($ev) as $listener)
		{
			$listener($ev);
		}
	}
}
