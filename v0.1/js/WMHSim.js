var WMHSim = (function() {
	var classes				= {};	
	var presets				= [];	
	var factions			= [];
	var panel				= null;
	
	// fn
	function rollDice(dice, discardLowest) {
		dice			= dice || 2;
		discardLowest	= discardLowest || false;
		
		var rolls		= [];
		for(i = 0; i < dice; i++) {
			var roll = Math.floor(Math.random() * (6 + 1));
			rolls.push(roll);
		}
		
		if(discardLowest) {
			rolls.sort();
			rolls.shift();
		}	
		
		var total = 0;
		for(i in rolls) {
			total += rolls[i];
		}
		
		return total;	
	};
	
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
		for(var i = 0; i < 1; i++) {
			var simulation = new classes.simulation(attacker, defender);

			simulation.announce();
			simulation.run();
		}
	};
	
	// factory
	function beastFromCnf(beastCnf) {
		var beast = new classes.beast(beastCnf.getName(), beastCnf.getStats());
		
		$.each(beastCnf.getWeaponCnfs(), function(w, weaponCnf) {
			beast.addWeapon(weaponFromCnf(weaponCnf));
		});
		
		return beast;
	};
	
	// factory
	function weaponFromCnf(weaponCnf) {
		var weapon = new classes.weapon(weaponCnf.getName(), weaponCnf.getStats());
		
		return weapon;
	}
	
	/*
	 * CLASSES
	 */
	classes.simulation = function(_attacker, _defender) {
		var attacker;
		var defender;
		var self = this;

		var announce = function() {
			console.log(attacker.getName() + " VS " + defender.getName());
		};
		
		var run = function() {
			attacker.attack(defender);
		};
		
		return (function() {
			attacker = _attacker;
			defender = _defender;
			
			return {
				announce	: announce,
				run			: run
			};
		})();
	};
	
	classes.beastCnf = function(_name, _stats) {
		var name		= 'anon';
		var stats		= {};
		var weaponCnfs	= [];
		var self		= this;

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
		var self 		= this;

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
	
	classes.beast = function(_name, _stats) {
		var name		= 'anon';
		var self		= this;
		
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
		
		var attack = function(defender) {
			// free attacks
			$.each(weapons, function(w, weapon) {
				doAttack(defender, weapon);
			});
			
			// buy attacks with first weap
		};
		
		var doAttack = function(defender, weapon) {
			if(doHit(defender, weapon)) {
				doDamage(defender, weapon);
			}
		};

		var doHit = function(defender, weapon) {
			if(stats.mat + rollDice(2) > defender.getDef()) {
				
				return true;
			}
			
			return false;
		};
		
		var doDamage = function() {
			
		};
		
		var getName = function() {
			return name;
		};
		
		var addWeapon = function(weapon) {
			weapons.push(weapon);
		};
		
		return (function() {
			name = _name;
			$.each(_stats, function(k, v) {
				stats[k] = v;				
			});
			
			return {
				getName		: getName,
				addWeapon	: addWeapon,
				attack		: attack
			};
		})();
	};
	
	classes.weapon = function(_name, _stats) {
		var name		= 'fist';
		var self 		= this;
		var stats		= {};

		var getName = function() {
			return name;
		};
		
		return (function() {
			name	= _name;
			stats	= _stats;
			
			return {
				getName			: getName
			};
		})();
	};
		
	classes.panel = function() {
		var self 				= this;
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