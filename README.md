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

TODO : exemples
