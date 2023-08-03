# PHP-EventMethodEmitter
A PHP class that automatically emit 'before' and 'after' events on methods calls of classes than extends this class.

## Pourquoi ?

La POO permet déjà d'étendre verticalement des classes : Une classe qui en étend une autre peut hériter ou surcharger des méthodes de sa classe parente. Cependant, lorsque j'étends une classe déjà abondamment utilisée dans un programme, tout les appels s'effectuent vers la classe parente, et non pas vers la nouvelle, sauf si je modifie tout le programme.
C'est là qu'intervient la classe PHP-EventMethodEmitter. Une classe qui étend cette classe émet automatiquement des événements "before" et "after" dans certaines conditions.
Ainsi, je peux impacter le fonctionnement de ma classe plus tard, sans la modifier, et sans modifier les appels du programme pour qu'ils pointent vers une autre nouvelle classe.

## Quand ?

J'utilise cette classe pour donner un moyen facile à un développeur tiers d'impacter mes méthodes sans avoir à les modifier.

## Comment ?

A utiliser avec modération Je ne comble pas tout les défauts de flexibilité avec cette classe. Je l'utilise pour permettre des impacts légers, des finitions.  A l'intérieur des mes listeners, j'évite d'ajouter trop de complexité ou d'appels extérieurs. Attention au code spaghetti!

## Static Class Example

    <?php
    use Flavi1\EventMethodEmitter\EventMethodEmitter;
    
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

Result : 

    Dear John Dupont,
    
    A short message for John.
    
    See U later Mr Dupont !


## Instanciated Class Example

    <?
    use Flavi1\EventMethodEmitter\EventMethodEmitter;
    
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

Result :

    Dear John Dupont,
    
    A short message for John.
    I just want to say hello to you!
    
    See U later Mr Dupont !
