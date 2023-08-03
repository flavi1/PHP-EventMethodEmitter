<?php
namespace Flavi1\EventMethodEmitter;
include('EventMethodEmitter.php');	// todo : replace with autoload
use Flavi1\EventMethodEmitter\EventMethodEmitter;

class A extends EventMethodEmitter
{
	/*
	protected static function __EventEmitter($event, $target, &$args) {
		echo 'A custom event handler :'.$event."\n";
		//var_dump($target);
	}
	*/
	
	public static function _publicStatic() {
		echo '[publicStatic]'."\n";
	}
	
	protected static function _protectedStatic_() {
		return 'Hello';
	}
	
	private static function privateStatic_() {
		echo '[privateStatic]'."\n";
	}
	
	public function _publicM_($firstname, $lastname) {
		return 'A short message for '.$firstname.'.';
	}
	
	protected function _protectedM_() {
		echo 'protectedM'."\n";
	}
	
	private function privateM_() {
		echo 'privateM'."\n";
	}
	
	public static function testStatic() {

	} 
	
}

A::addBeforeListener('protectedStatic', function($ev) {
	$ev->arguments[0]++;
	var_dump($ev);
});

A::addAfterListener('protectedStatic', function($ev) {
	//var_dump($ev);
	$ev->response .= ' World!';
});

A::addBeforeListener('publicM', function($ev) {
	$firstname = $ev->arguments[0];
	$lastname = $ev->arguments[1];
	echo 'Dear '.$firstname.' '.$lastname.",\n\n";
});

A::addAfterListener('publicM', function($ev) {
	//var_dump($ev);
	$lastname = $ev->arguments[1];
	$ev->response .= "\nSee U later Mr ".$lastname.' !';
});

A::addAfterListener('protectedM', function($ev) {
	var_dump($ev);
	// event obj on instance
});


A::publicStatic(1,2);
echo A::protectedStatic(3,4);
A::privateStatic(5,6);

echo "\n";

$A = new A(7,8);

echo $A->publicM('John', 'Doe');
$A->protectedM(11,12);
$A->privateM(13);


class Message extends EventMethodEmitter
{
	public static function _prepareMessageTo_($firstName, $lastName)
	{
		return 'A short message for '.$firstName.'.'."\n";
	}
}

Message::addBeforeListener('prepareMessageTo', function($ev) {
	$firstname = $ev->arguments[0];
	$ev->arguments[1] = 'Dupont';	// replace lastname before method call.
	$lastname = $ev->arguments[1];
	echo 'Dear '.$firstname.' '.$lastname.",\n\n";
});
Message::addAfterListener('prepareMessageTo', function($ev) {
	//var_dump($ev);
	$lastname = $ev->arguments[1];
	$ev->response .= "\nSee U later Mr ".$lastname.' !';
});

echo Message::prepareMessageTo('John', 'Doe');

echo "\n\n\n";

class Friend extends EventMethodEmitter
{
	public $firstname = '';
	public $lastname = '';
	
	public function __construct($firstname, $lastname)
	{
		$this->firstname = $firstname;
		$this->lastname = $lastname;
	}
	
	public function _prepareMessage_($msg)
	{
		return 'A short message for '.$this->firstname.'.'."\n".$msg."\n";
	}
}

Friend::addBeforeListener('prepareMessage', function($ev) {
	$firstname = $ev->target->firstname;
	$ev->target->lastname = 'Dupont';	// replace lastname before method call.
	$lastname = $ev->target->lastname;
	echo 'Dear '.$firstname.' '.$lastname.",\n\n";
});
Friend::addAfterListener('prepareMessage', function($ev) {
	$lastname = $lastname = $ev->target->lastname;
	$ev->response .= "\nSee U later Mr ".$lastname.' !';
});

$john = new Friend('John', 'Doe');
echo $john->prepareMessage('I just want to say hello to you!');
