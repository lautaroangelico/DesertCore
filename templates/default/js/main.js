$(function() {
	// Initiate Server Time
	serverTime.init("tServerTime", "tLocalTime");
	
	// Initiate bootstrap tooltips
	$('[data-toggle="tooltip"]').tooltip();
});

var serverTime = {
	weekDays: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
	monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
	serverDate: null,
	localDate: null,
	dateOffset: null,
	nowDate: null,
	eleServer: null,
	eleLocal: null,
	init: function(e, c) {
		var f = this;
		f.eleServer = e;
		f.eleLocal = c;
		$.getJSON(baseUrl + "api/servertime.php", function(a) {
			f.serverDate = new Date(a.ServerTime);
			f.localDate = new Date();
			f.dateOffset = f.serverDate - f.localDate;
			document.getElementById(f.eleServer).innerHTML = f.dateTimeFormat(f.serverDate);
			document.getElementById(f.eleLocal).innerHTML = f.dateTimeFormat(f.localDate);
			setInterval(function() {
				f.update()
			}, 1000)
		})
	},
	update: function() {
		var b = this;
		b.nowDate = new Date();
		document.getElementById(b.eleLocal).innerHTML = b.dateTimeFormat(b.nowDate);
		b.nowDate.setTime(b.nowDate.getTime() + b.dateOffset);
		document.getElementById(b.eleServer).innerHTML = b.dateTimeFormat(b.nowDate)
	},
	dateTimeFormat: function(e) {
		var c = this;
		var f = [];
		f.push(c.digit(e.getHours()));
		f.push(":");
		f.push(c.digit(e.getMinutes()));
		return f.join("")
	},
	digit: function(b) {
		b = String(b);
		b = b.length == 1 ? "0" + b : b;
		return b
	}
};

function confirmationMessage(actionPath, cmTitle="Are you sure?", cmText="", cmConfirmBtn="Confirm", cmCancelBtn="Cancel") {
	swal({
		title: cmTitle,
		text: cmText,
		showCancelButton: true,
		confirmButtonColor: '#dd4441',
		cancelButtonColor: '#333',
		confirmButtonText: cmConfirmBtn,
		cancelButtonText: cmCancelBtn
	}).then(function (result) {
		if (result.value) {
			window.location.href = actionPath;
		}
	});
}