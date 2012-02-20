WMHSim.faction.legion = function() {
	this.name = 'Legion';
	this.addBeast('Carnivean',		WMHSim.faction.legion.carnivean);
	this.addBeast('Shredder',		WMHSim.faction.legion.shredder);

	WMHSim.faction.call(this);
};
_.extend(WMHSim.faction.legion.prototype, WMHSim.faction.prototype);

// --
WMHSim.faction.legion.carnivean = function() {		
	this.weapons = [
	                new WMHSim.weapon('Head', 		{pow : 6}),
	                new WMHSim.weapon('Left claw', 	{pow : 4}),
	                new WMHSim.weapon('Right claw',	{pow : 4})
	];

	WMHSim.beast.call(this);
};
_.extend(WMHSim.faction.legion.carnivean.prototype, WMHSim.beast.prototype, {
	/*
	 * properties
	 */ 
	name			: 'Carnivean',
	stats			: {
		fury			: 4,
	
		mat				: 8,
		str				: 12,
	
		dmg				: 30,
		def				: 11,
		arm				: 18
	}
	
	/*
	 * methods
	 */
});

//--
WMHSim.faction.legion.shredder = function() {
	this.weapons = [
	        	    new WMHSim.weapon('Bite', 		{pow : 4})
	];
};
_.extend(WMHSim.faction.legion.shredder.prototype, WMHSim.beast.prototype, {
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
	}
	
	/*
	 * methods
	 */
});
