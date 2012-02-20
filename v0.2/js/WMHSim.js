var WMHSim = WMHSimBase.extend({
	/*
	 * properties
	 */ 
	panel			: null,
	factions		: {},
	attackerChoices	: null,
	defenderChoices	: null,
	
	/*
	 * constructor
	 */ 
	init			: function() {
		this.initFactions();
		this.initPanel();
	},
	
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
		success = 0;
		
		for(var i = 0; i < 2; i++) {
			console.log('--------------');
			
			var attacker = new attackerClass(this);
			var defender = new defenderClass(this);
		
			if(attacker.attack(defender)) success++;
		}
		
		console.log(success);
	},
	
	/*
	 * DOM event handlers
	 */
	runSim			: function() {
		var attacker = this.attackerChoices.find('option:selected').data('beast');
		var defender = this.defenderChoices.find('option:selected').data('beast');
		
		console.log();
	
		this.simulate(attacker, defender);
	}
});