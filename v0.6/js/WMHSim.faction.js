WMHSim.faction = function() {};
_.extend(WMHSim.faction.prototype, WMHSimBase.prototype, {
	/*
	 * properties
	 */ 
	name			: 'anon',
	beasts			: {},
	
	/*
	 * methods
	 */		
	getBeasts 		: function() {
		return this.beasts;
	},
	
	addBeast		: function(name, beast) {
		this.beasts[name] = beast;
	}
});