var axios = require("axios");

const instance = axios.create({
	baseURL: "https://trackapi.nutritionix.com/v2/natural/nutrients",
	timeout: 3000,
	headers: {
		"x-app-id":"45b5d9b2",
		"x-app-key":"9de1adfb502b337871a0d25be4315619"
	}
});

module.exports = instance;
