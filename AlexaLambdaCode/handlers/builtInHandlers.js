var handleYesNo = function(bool) {
	let lastQuestion = this.attributes.lastQuestion;
	if (lastQuestion === undefined) {
		this.emit("Unhandled");
	} else {
		switch (lastQuestion.type) {
			case "Help":
				{
					this.emit(":ask", "What do you want to do?");
				}
				break;
			default:
				this.emit("Unhandled");
		}
	}
};

module.exports = {
	"AMAZON.StopIntent": function() {
		this.emit(":tell", "Stopping");
	},
	"AMAZON.CancelIntent": function() {
		this.emit(":tell", "Goodbye");
	},
	"AMAZON.YesIntent": function() {
		let bool = true;
		handleYesNo.bind(this)(bool);
	},
	"AMAZON.NoIntent": function() {
		let bool = false;
		handleYesNo.bind(this)(bool);
	}
};
