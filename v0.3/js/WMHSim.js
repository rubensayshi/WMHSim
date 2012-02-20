var WMHSimBase		= require('../js/WMHSimBase'),
	WMHSim 			= function() { this.init(); };
WMHSim.prototype = {
	/*
	 * properties
	 */ 
	
	/*
	 * constructor
	 */ 
	init			: function() {},
	
	/*
	 * methods
	 */
	simulate		: function(attackerClass, defenderClass) {
		success = 0;
		
		for(var i = 0; i < 2; i++) {
			console.log('--------------');
			
			var attacker = new attackerClass();
			var defender = new defenderClass();
		
			if(attacker.attack(defender)) success++;
		}
		
		console.log(success);
	}
};

WMHSim.prototype.__proto__ = WMHSimBase.prototype;

exports = module.exports = WMHSim;