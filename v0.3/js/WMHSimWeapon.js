var WMHSimBase		= require('../js/WMHSimBase'),
	WMHSimWeapon 	= function(name, stats) { this.init(name, stats); };
	WMHSimWeapon.prototype =	{
	/*
	 * properties
	 */ 
	name			: 'anon',
	stats			: {
		pow				: 0,
	},
	
	/*
	 * constructor
	 */ 
	init			: function(name, stats) {
		this.name = name;
		for(k in stats) {
			this.stats[k] = stats[k];				
		}
	},
	
	/*
	 * methods
	 */		
	getPow			: function() {
		return this.stats.pow;
	},
	
	getName			: function() {
		return this.name;
	},
};

WMHSimWeapon.prototype.__proto__ = WMHSimBase.prototype;

exports = module.exports = WMHSimWeapon;