<?php
namespace Flavi1\EventMethodEmitter;
// todo add psr interfaces

class EventMethodDispatcher
{	
	protected $listenerProvider = null;
	
	public function __construct($listenerProvider)
	{
		$this->listenerProvider = $listenerProvider;
	}
	
	public function dispatch($ev)
	{
		$LP = $this->listenerProvider;
//echo "DISPATCH method event : ".$ev->type." ".$ev->method." on ".$LP->getClassName()."\n";
		foreach($LP->getListenersForEvent($ev) as $listener)
		{
			$listener($ev);
		}
	}
}
