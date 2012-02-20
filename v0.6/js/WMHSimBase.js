var WMHSimBase = function() {};
_.extend(WMHSimBase.prototype, Backbone.Events, {
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
});