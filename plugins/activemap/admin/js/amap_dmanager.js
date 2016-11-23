function getCurrentMaps(){
	var mapdata = {
		'action':'amapdata',
		'operation':'gettemplates'	
	};
	j$.ajax({
		url: ajax_object.ajax_url,
		type:"POST",
		dataType:"xml",
		data:mapdata,
		success:function(xml){
			
			alert(xml);
			
		}
		
		
	})
	
	
}

function initDataManager(){
	
	j$('#testdata-button').click(function(){
		getCurrentMaps();
	})
	
}

function amap_datamanager(){
	
	this.mymaps = new Array();
	
	
	//this.currmap = 
	
	
	
	
}




