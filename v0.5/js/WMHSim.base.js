WMHSimBase = Class.extend({
	/*
	 * properties
	 */
	events			: {},
	
	/*
	 * methods
	 */	
	roll			: function(dice) {
		dice			= dice || 2;
		
		var rolls		= [];
		for(i = 0; i < dice; i++) {
			var roll = Math.floor(Math.random() * (6 + 1));
			rolls.push(roll);
		}
		
		var total = 0;
		for(i in rolls) {
			total += rolls[i];
		}
		
		return total;	
	},
	
	trigger			: function(event, arg1, arg2, arg3, arg4) {
		if(this.events[event]) {
			$.each(this.events[event], function(f, fn) {
				fn(arg1, arg2, arg3, arg4);
			});
		}		
	},
	
	bind			: function(event, fn) {
		if(!this.events[event])
			this.events[event] = [];
		
		this.events[event].push(fn);
	}
});