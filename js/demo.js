var TxtFind = {
    init : function() {
		var groupBy = 5;
		//Grab All of the files
		var results = $('.results').children('.loc-holder').children('.location');
		var files = [];
    	
		results.each(function() {
			files.push($(this).text());
		});
		
		var group = files.slice(0,groupBy);
		var counter = groupBy;
		var amount = Math.ceil(files.length / groupBy);
		for (var i = 0; i < Math.ceil(files.length / groupBy); i++) {
			//Make a request to grab the contents of the group
			var json = JSON.stringify(group);
			$.get('ajax.php?files=' + json + '&term=' + $('#term').text(), function(data) {
				for (var key in data) {
					var obj = data[key];
					for (var prop in obj) {
      					// important check that this is objects own property 
      					// not from prototype prop inherited
						if (obj.hasOwnProperty(prop)){
							//Find the key and add the result to the page
							results.each(function() {
								if ($(this).text() == key) {
									console.log(obj[prop]);
									$(this).parent().append("<quote><small>" + obj[prop] + "</small></quote>").hide().fadeIn(300);
								}
							});
						}
					}
				} 
			});
			group = files.slice(counter,counter + groupBy);
			counter += groupBy;
		}

	}
}

$(document).ready(function() {
	TxtFind.init();
});
