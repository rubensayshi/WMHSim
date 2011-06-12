WMHSim.legion = (function() {
	return function () {
		var faction = new WMHSim.faction();

		// Carnivean
		faction.addBeastCnf(new WMHSim.classes.beastCnf('Carnivean', {
			fury: 4, mat: 6, str: 12, def: 11, arm: 18, dmg: 30
		})).addWeaponCnf(new WMHSim.classes.weaponCnf('head', {
			pow: 6
		})).addWeaponCnf(new WMHSim.classes.weaponCnf('claw', {
			pow: 4
		})).addWeaponCnf(new WMHSim.classes.weaponCnf('claw', {
			pow: 4
		}));
		
		
		return faction;
	};
})();

WMHSim.factions.push(WMHSim.legion);