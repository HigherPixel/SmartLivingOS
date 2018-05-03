var defaultState = {
	isNew: true
};

module.exports = {
	"SessionEndedRequest": function() {
		this.emit(":tell", "Bye");
	},
	"NewSession": function () {
		this.attributes = this.attributes || defaultState;
		this.attributes.lastQuestion = {
			type: "Help",
			command: "default"
		};
		if (this.attributes.isNew) {
			this.emit(
				":ask",
				"This is Tame Cinco's Capstone smart living app with support from the nutrition team"			);
		}
		else {
			this.emit(
				":ask",
				"Tame Cinco greets you with this smart living app"
			);
		}
	},
	"LaunchRequest": function () {
		this.emit("NewSession");
	}
};