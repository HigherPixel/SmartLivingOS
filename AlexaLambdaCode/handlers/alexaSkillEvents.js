module.exports = {
	"AlexaSkillEvent.SkillAccountLinked": function() {
		const userId = this.event.context.System.user.userId;
		console.log(`LINKED: ${userId}`);
		let userId2 = this.event.session.user.userId;
        console.log(`LINKED: ${userId2}`);
		console.log("event", this.event);
	},
	"AlexaSkillEvent.SkillEnabled" : function() {
		const userId = this.event.context.System.user.userId;
		console.log(`ENSABLED: ${userId}`);
        let userId2 = this.event.session.user.userId;
        console.log(`ENSABLED: ${userId2}`);
		console.log("event", this.event);
	},
	"AlexaSkillEvent.SkillDisabled": function() {
		const userId = this.event.context.System.user.userId;
		console.log(`DISABLED: ${userId}`);
        let userId2 = this.event.session.user.userId;
        console.log(`DISABLED: ${userId2}`);
		console.log("event", this.event);
	}
};