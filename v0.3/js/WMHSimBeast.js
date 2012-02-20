var WMHSimBase		= require('../js/WMHSimBase');

var WMHSimBeast 	= function() { this.init(); };
WMHSimBeast.prototype =	{
	/*
	 * properties
	 */ 
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
	 * constructor
	 */ 
	init			: function() {
		this.dmg = this.stats.dmg;
	},
	
	/*
	 * methods
	 */		
	attack 			: function(defender) {
		// free attacks
		for (w in this.weapons) {
			weapon = this.weapons[w];
			this.doAttack(defender, weapon);
		}
		
		// buy attacks with first weap
		// ?
		
		return !defender.alive;
	},
	
	doAttack 		: function(defender, weapon) {
		if(this.doHit(defender, weapon)) {
			this.doDamage(defender, weapon);
		}
	},

	doHit 			: function(defender, weapon) {
		if(this.stats.mat + this.roll(2) > defender.getDef()) {
			weapon.trigger('do-hit');
			return true;
		}
		
		return false;
	},
	
	doDamage 		: function(defender, weapon) {
		var damage	= this.stats.str + weapon.getPow() + this.roll(2);
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
};

WMHSimBeast.prototype.__proto__ = WMHSimBase.prototype;

exports = module.exports = WMHSimBeast;