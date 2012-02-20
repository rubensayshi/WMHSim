WMHSim.faction = WMHSimBase.extend({
	/*
	 * properties
	 */ 
	name			: 'anon',
	beasts			: {},
	
	/*
	 * constructor
	 */ 
	init			: function(name) {
		this.name = name;
	},
	
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