WMHSim.weapon = WMHSimBase.extend({
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
		$.each(stats, $.proxy(function(k, v) {
			this.stats[k] = v;				
		}, this));
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