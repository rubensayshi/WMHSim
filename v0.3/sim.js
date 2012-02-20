var WMHSim			= require('./js/WMHSim');
var WMHSimLegion	= require('./js/WMHSimLegion');

var sim = new WMHSim();
sim.simulate(WMHSimLegion.carnivean, WMHSimLegion.shredder);

console.log('-----');