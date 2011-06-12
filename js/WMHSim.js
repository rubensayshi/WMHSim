var WMHSim = (function() {
	var classes				= {};	
	var presets				= [];	
	var factions			= [];
	var panel				= null;
	
	// fn
	function createPresets() {
		// presets.push(new classes.beastCnf('beasty 1'));
		// presets.push(new classes.beastCnf('beasty 2'));
	};
	
	// fn
	function initFactions() {
		$.each(factions, function(f, faction) {
			factions[f] = faction();			
		});		
	};
	
	// fn
	function simulate(attacker, defender) {
		var simulation = new classes.simulation(attacker, defender);
		
		simulation.announce();
	};
	
	// factory
	function beastFromCnf(beastCnf) {
		var beast = new classes.beast(beastCnf.getName());
		
		return beast;
	};
	
	/*
	 * CLASSES
	 */
	classes.simulation = function(_attacker, _defender) {
		var attacker;
		var defender;
		
		var announce = function() {
			console.log(attacker.getName() + " VS " + defender.getName());
		};
		
		return (function() {
			attacker = _attacker;
			defender = _defender;
			
			return {
				announce : announce
			};
		})();
	};
	
	classes.beastCnf = function(_name, _stats) {
		var name		= 'anon';
		var stats		= {};
		var weaponCnfs	= [];

		var getName = function() {
			return name;
		};

		var getStats = function() {
			return stats;
		};

		var addWeaponCnf = function(weaponCnf) {
			weaponCnfs.push(weaponCnf);
			
			return this;
		};
		
		var getWeaponCnfs = function() {
			return weaponCnfs;
		};
		
		return (function() {
			name	= _name;
			stats	= _stats;
			
			return {
				getName			: getName,
				getStats		: getStats,
				addWeaponCnf	: addWeaponCnf,
				getWeaponCnfs	: getWeaponCnfs
			};
		})();
	};
	
	classes.weaponCnf = function(_name, _stats) {
		var name		= 'anon';
		var stats		= {};


		var getName = function() {
			return name;
		};

		var getStats = function() {
			return stats;
		};
		
		return (function() {
			name	= _name;
			stats	= _stats;
			
			return {
				getName			: getName,
				getStats		: getStats,
			};
		})();
	};
	
	classes.beast = function(_name) {
		var name		= 'anon';
		
		var stats		= {
			fury		: 0,
		
			mat			: 0,
			str			: 0,
		
			life		: 0,
			def			: 0,
			arm			: 0
		};
		
		var weapons		= [];
		
		var fury		= 0;
		var dmg			= 0;
		
		var getName = function() {
			return name;
		};
		
		return (function() {
			name = _name;
			
			return {
				getName : getName
			};
		})();
	};
		
	classes.panel = function() {
		var container 			= $('#panel');
		var attackerChoices		= $('<select />');
		var defenderChoices		= $('<select />');
		var runSim				= $('<input />').attr('type', 'button').attr('value', 'go');

		$.each(presets, function(b, beastCnf) {
			attackerChoices.append(
				$('<option />')	.html(beastCnf.getName())
								.data('beastCnf', beastCnf)
			);
			defenderChoices.append(
				$('<option />')	.html(beastCnf.getName())
								.data('beastCnf', beastCnf)
			);
		});
		
		$.each(factions, function(f, faction) {
			$.each(faction.getBeastCnfs(), function(b, beastCnf) {
				attackerChoices.append(
					$('<option />')	.html(beastCnf.getName())
									.data('beastCnf', beastCnf)
				);
				defenderChoices.append(
					$('<option />')	.html(beastCnf.getName())
									.data('beastCnf', beastCnf)
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
		
		runSim.click(function() {
			var attacker = beastFromCnf(
				attackerChoices.find('option:selected').data('beastCnf')
			);
			var defender = beastFromCnf(
				defenderChoices.find('option:selected').data('beastCnf')
			);
			
			simulate(attacker, defender);
		});
	};
	
	// cn
	var obj = function () {
		createPresets();
		initFactions();
		
		panel = new classes.panel();
	};

	// expose classes
	obj.classes = classes;
	
	// expose factions
	obj.factions = factions;
	
	return obj;
})();