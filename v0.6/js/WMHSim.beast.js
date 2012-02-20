WMHSim.beast = function(sim) {
	this.dmg = this.stats.dmg;
	this.sim = sim;
};
_.extend(WMHSim.beast.prototype, WMHSimBase.prototype, {
	/*
	 * properties
	 */ 
	sim				: null,
	stats			: {
		fury			: 0,
	
		mat				: 0,
		str				: 0,
	
		dmg				: 0,
		def				: 0,
		arm				: 0
	},	
	weapons		:	[],
	fury		:	0,
	dmg			:	0,
	alive		:	true,
	
	/*
	 * methods
	 */		
	attack 			: function(defender) {
		// free attacks		
		$.each(this.weapons, $.proxy(function(w, weapon) {
			this.doAttack(defender, weapon);
		}, this));
		
		// buy attacks with first weap
		while (this.fury < this.stats.fury) {
			this.fury++;
			this.doAttack(defender, this.weapons[0]);
		}
		
		return !defender.alive;
	},
	
	doAttack 		: function(defender, weapon) {
		if(this.doHit(defender, weapon)) {
			this.doDamage(defender, weapon);
		}
	},

	doHit 			: function(defender, weapon) {
		var roll	= this.roll(2);
		console.log("mat ["+this.stats.mat+"] + roll ["+roll+"] > ["+defender.getDef()+"]" );
		if(this.stats.mat + roll > defender.getDef()) {
			weapon.trigger('do-hit');
			return true;
		}
		
		return false;
	},
	
	doDamage 		: function(defender, weapon) {
		var roll	= this.roll(2);
		var damage	= this.stats.str + weapon.getPow() + roll;
		console.log("{"+weapon.getName()+"} - PS ["+this.stats.str+" + "+weapon.getPow()+"] + roll ["+roll+"] = ["+damage+"]" );
		var arm 	= defender.getArm();
		var result	= damage - arm;
		
		if(result > 0) {
			weapon.trigger('do-damage', result);
		}
		
		defender.takeDamage(result);
	},

	getDef			: function() {
		return this.stats.def;
	},
	
	getArm			: function() {
		return this.stats.arm;
	},
	
	getStr			: function() {
		return this.stats.str;
	},
	
	takeDamage		: function(damage) {
		
		if(damage > 0) {
			this.trigger('take-damage');
			console.log(this.dmg + ' -= ' + damage);
			this.dmg -= damage;
			
			if(this.dmg <= 0 && this.alive) {
				this.trigger('died');
				this.alive = false;
			}
		}
	},
	
	getName			: function() {
		return this.name;
	},
	
	addWeapon		: function(weapon) {
		this.weapons.push(weapon);
	}
});