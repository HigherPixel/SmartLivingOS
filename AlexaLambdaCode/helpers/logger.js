
var logger = function(event) {
	var intentType = event.request.type;
	if (intentType === "IntentRequest") {
		intentType = event.request.intent.name;
		console.log(intentType);
	}
	else {
		console.log(intentType);
	}
};

module.exports = logger;
