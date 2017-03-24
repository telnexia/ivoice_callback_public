var iconnectelSchedule = {
		scheduleCallback : function(inst) {
			
			$.post(inst.attr('action'), inst.serialize(), function(data) {
				
				if (data.error == true) {
					$('#response-handler').html('<p class="alert alert-danger"></p>').find('p').text(data.msg);
				} else {
					$('#response-handler').html('<p class="alert alert-success"></p>').find('p').text(data.msg);
				}
				
			}, "json");
			
			return false;
		}
}