var WMHSimFaction	= require('../js/WMHSimFaction'),
	WMHSimBeast		= require('../js/WMHSimBeast'),
	WMHSimWeapon	= require('../js/WMHSimWeapon');

var WMHSimCarnivean = function() { this.init(); };
WMHSimCarnivean.prototype.__proto__ = WMHSimBeast.prototype;
WMHSimCarnivean.prototype = {
	/*
	 * properties
	 */ 
	name			: 'Carnivean',
	stats			: {
		fury			: 4,
	
		mat				: 6,
		str				: 12,
	
		dmg				: 30,
		def				: 12,
		arm				: 18
	},
	
	/*
	 * constructor
	 */ 
	init			: function() {
		this.weapons = [];
		this.addWeapon(new WMHSimWeapon('Head', 		{pow : 6}));
		this.addWeapon(new WMHSimWeapon('Left claw', 	{pow : 4}));
		this.addWeapon(new WMHSimWeapon('Right claw',	{pow : 4}));
	}

	
	/*
	 * methods
	 */
};

WMHSimCarnivean.prototype.__proto__ = WMHSimBeast.prototype;

var WMHSimShredder = function() { this.init(); };
WMHSimShredder.prototype = {
	/*
	 * properties
	 */ 
	name			: 'Shredder',
	stats			: {
		fury			: 2,
	
		mat				: 7,
		str				: 7,
	
		dmg				: 14,
		def				: 13,
		arm				: 12
	},	
	
	/*
	 * constructor
	 */ 
	init			: function() {
		this.addWeapon(new WMHSimWeapon('Bite', 		{pow : 4}));
	}
	
	/*
	 * methods
	 */
};

WMHSimShredder.prototype.__proto__ = WMHSimBeast.prototype;

exports = module.exports = {
	shredder		: WMHSimShredder,
	carnivean		: WMHSimCarnivean
};