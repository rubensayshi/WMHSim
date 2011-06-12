WMHSim.faction = function() {
	var beastCnfs 		= [];
	
	var getBeastCnfs = function() {
		return beastCnfs;
	};
	
	var addBeastCnf = function(beastCnf) {
		beastCnfs.push(beastCnf);
		
		return beastCnf;
	};
	
	return {
		getBeastCnfs:	getBeastCnfs,
		addBeastCnf:	addBeastCnf
	};
	
};