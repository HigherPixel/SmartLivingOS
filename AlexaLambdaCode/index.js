var Alexa = require("alexa-sdk");

var logger = require("./helpers/logger");

var foodHandlers = require("./handlers/foodHandlers");
var sleepHandlers = require("./handlers/sleepHandlers");
var exerciseHandlers = require("./handlers/exerciseHandlers");
var emotionHandlers = require("./handlers/emotionHandlers");
var builtInHandlers = require("./handlers/builtInHandlers");
var sessionHandlers = require("./handlers/sessionHandlers");
var alexaSkillEvents = require("./handlers/alexaSkillEvents");

exports.handler = function (event, context, callback) {

	var alexa = Alexa.handler(event, context);
	alexa.appId = "amzn1.ask.skill.9993a510-5609-4c07-aabe-37b77c7987ba";

	logger(event);

	alexa.registerHandlers(
		builtInHandlers,
		sessionHandlers,
		foodHandlers,
		sleepHandlers,
		exerciseHandlers,
		emotionHandlers,
		alexaSkillEvents
	);
	alexa.execute();
};
