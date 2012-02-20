WMHSim.weapon = function(name, stats) {
	this.name = name;
	
	_.each(stats, function(v, k) {
		this.stats[k] = v;	
	}, this);
};
_.extend(WMHSim.weapon.prototype, WMHSimBase.prototype, {
	/*
	 * properties
	 */ 
	name			: 'anon',
	stats			: {
		pow				: 0,
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
});