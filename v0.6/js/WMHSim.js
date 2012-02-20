var WMHSim = function() {
	this.initFactions();
	this.initPanel();
};
_.extend(WMHSim.prototype, WMHSimBase.prototype, {
	/*
	 * properties
	 */ 
	panel			: null,
	factions		: {},
	attackerChoices	: null,
	defenderChoices	: null,
	
	/*
	 * constructor helpers
	 */
	initFactions	: function() {
		this.factions['Legion'] = new WMHSim.faction.legion();
	},
	
	initPanel		: function() {
		var container 			= $('#panel');
		var attackerChoices		=
		this.attackerChoices	= $('<select />');
		var defenderChoices		=
		this.defenderChoices	= $('<select />');
		var runSim				= $('<input />').attr('type', 'button').attr('value', 'go');
		
		$.each(this.factions, function(f, faction) {
			console.log(faction);
			$.each(faction.getBeasts(), function(name, beast) {
				attackerChoices.append(
					$('<option />')	.html(name)
									.data('beast', beast)
				);
				defenderChoices.append(
					$('<option />')	.html(name)
									.data('beast', beast)
				);
			});
		});
		
		container.append(
			$('<div />').append(
				$('<b />').html('Attacker')
			).append(
				attackerChoices
			)
		).append(
			$('<div />').html(' V S ')
		).append(
			$('<div />').append(
				$('<b />').html('Defender')
			).append(
				defenderChoices
			)
		).append(
			$('<div />').append(
				runSim
			)
		);
		
		runSim.click($.proxy(this.runSim, this));
	},
	
	/*
	 * methods
	 */
	simulate		: function(attackerClass, defenderClass) {	
		var attacker = new attackerClass(this);
		var defender = new defenderClass(this);
		
		console.log('--'+attacker.getName()+'---VS---'+defender.getName()+'--');
		console.log(defender);
		
		return attacker.attack(defender);
	},
	
	/*
	 * DOM event handlers
	 */
	runSim			: function() {
		var attacker = this.attackerChoices.find('option:selected').data('beast');
		var defender = this.defenderChoices.find('option:selected').data('beast');
		
		var i		= 0;
		var kills	= 0;
		var runs	= 5;
		
		var simulate	= this.simulate;
		var sim			= function (callback) {
			
			if (simulate(attacker, defender)) kills++;
			
			i++;
			
			if (i < runs) {
				setTimeout(function() {
					sim(callback);
				});
			} else {
				callback();
			}
		}
		
		sim(function () {
			console.log(kills + ' kills in ' + runs + ' runs');
		});
	}
});