/**
 * the purpose of this mini project is to create something simple to define classes in a friendly way
 * I'll first try out the old fashion ways to demonstrate that they fail and why/how
 * 
 * whenever I use 'class' or other OOP related terms I do know that javascript doesn't really offer anything like this
 * but we need to talk about it somehow, it basicly comes down to 'mixin' with some added functionality to look like a class inheretence model
 */

/**
 * now I'm ignore all frameworks/modules for this purpose which I've found so far
 * since most either require very different, non normal js notations
 * or they still fail in making it a friendly notation
 */

/**
 * and all the things which work withouth THIS. become messy very fast too so let's not even go there
 */

/**
 * everything inside the constructor... 
 * - everytime we initiatize this class everything is redefined which makes it really inefficient on speed (relative ofcourse, it's not really slow)
 * - I've placed the code so it's still fairly easy to oversee, but when this would be a big class ... enjoy the complete lack of overview
 * - This aproach allows you to use var instead of this to make things private ... that really makes it a lot easier to read ... NOT
 * - The constructor feels most natural at the bottom, but what are the chances I'll never forget updating both the constructor and the initial parameters
 */
var MyUglyAndInEfficientClass = function(propOne) {
	this.propOne = 1;
	this.propTwo = [];
		
	this.init 		= function(propOne) { this.propOne = propOne; this.propTwo.push('item'); };
	this.output		= function() { return 'propOne ['+this.propOne+'] & propTwo length ['+this.propTwo.length+']'; };
	
	this.init(propOne);	
};

/**
 * everything in prototype
 * eventhough I could live with this syntax, it doesn't work ..
 * the coll property acts as a static and is the same in both instances ..
 */

var MyPrototypedClass = function() { this.init(); };
MyPrototypedClass.prototype = {
	coll 			: [],
		
	init 			: function() { this.coll.push('item'); },
	output			: function() { return 'coll length['+this.coll.length+']'; },
};

/**
 * below an example which would show that it doesn't work well ..
 
	o1 = new MyPrototypedClass();
	console.log(o1.output());
	o2 = new MyPrototypedClass();
	console.log(o2.output());
*/

/**
 * now to get things working with the prototype we have to define the properties in the constructor
 * since I already hate this syntax loosing it's overview I at least want the real constructor (with the functionality in it) to be seperated
 */
var MyPrototypedClass2 = function() { this.coll = []; this.init(); }
MyPrototypedClass2.prototype = {
	init 			: function() { this.coll.push('item'); },
	output			: function() { return 'coll length['+this.coll.length+']'; },
};

/**
 * below an example which would show that this does work
 
	o1 = new MyPrototypedClass2();
	console.log(o1.output());
	o2 = new MyPrototypedClass2();
	console.log(o2.output());
*/

/**
 * so if you really like one of those sytaxes you should try googling for their inherentence syntax ... 
 * ok so we need something for this that works but remains very simple and lightweight
 * like all the frameworks and modules do we should put any required work in the definition part instead of initialization
 */

/**
 * this is the classDef to work with, I want to wrap this in a function call like MyClass = Class({classDefGoesHere});
 * I've seperated it so that I can try a few different things with it
 * 
 * it contains the properties, a constructor and some methods
 * it's simple and you can maintain a good overview of what happens since nothing extra is done at all to work around the issues that javascript has
 */
var classDef = {
		propOne			: 1,
		propTwo			: [],
		init			: function(propOne) { this.propOne = propOne; this.propTwo.push('item'); },
		output			: function() { return 'propOne ['+this.propOne+'] & propTwo length ['+this.propTwo.length+']'; },
		incPropTwo		: function() { this.propTwo++; }
};

/**
 * so I figure I should just do something with this classDef to create the MyPrototypedClass2 result since that seems to work best
 * for that we need to 
 *  - create a constructor function
 *  - let it call the 'init' function so that that one can behave like the constructor
 *  - define the properties inside the constructor so that we don't have the problems in the MyPrototypedClass case
 */
var Class = function(classDef) {
	var retClass = function(propOne) {
		this.init(propOne);
	};
	retClass.prototype = classDef;
	
	return retClass;
};

MyClass = Class(classDef);

o1 = new MyClass(1);
console.log(o1.output());
o2 = new MyClass(2);
console.log(o2.output());
