let knex = require("../helpers/knexFile");
let axios = require("../helpers/axiosFile");


let getFoodAPI = function(food) { return new Promise((resolve, reject) => {

    axios.post("/", {
        query: food
    })
        .then((res) => {
            resolve(res.data.foods);
        })
        .catch((err) => {
            console.log('getFoodAPI error', err);
            this.emit(":ask", "I don't recognize that.");
        })
})}

let logFood = function(food, userID) { return new Promise((resolve) => {

    console.log('Log food called', food, userID);

    knex("foodordrink")
        .insert({
            userID: userID,
            DayID: null,
            log_file_input: JSON.stringify({ food: food})
        })
        .then((data) => {
            resolve();
        })
        .catch((err) => {
            console.log('logFood Error', err);
            this.emit(':ask', 'Saving failed, please try again');
        })
})};

let foodTypes = {
    'nf_calories': [
        'Calories', 'Cals'
    ],
    'nf_total_fat': [
		'total fat', 'fat'
	],
    'nf_sodium': [
		'Sodium', 'Salt'
    ],
    'nf_cholestorol': [
        'cholesterol'
    ],
    'nf_saturated_fat': [
		'Saturated Fat'
	],
    'nf_total_carbohydrates': [
        'total carbohydrates', 'carbohydrates', 'carbs'
    ],
    'nf_protein': [
        'protein'
    ]
};

let foodInfoResponse = (food, infoToGet) => {
	let res = food['food_name'] + ' has ';

    switch (infoToGet) {
        case 'calories':
			return res + food['nf_calories'] + ' calories';

        case 'fat':
			return res + food['nf_total_fat'] + ' total fat';

        case 'sodium':
			return res + food['nf_sodium'] + ' sodium ';

        case 'saturated fat':
			return res + food['nf_saturated_fat'] + ' saturated fat ';

        case 'sugar':
            return res + food['nf_sugar'];

        default:
            res += food['nf_calories'] + ' calories ';
            res += food['nf_total_fat'] + ' total fat ';
            res += food['nf_sodium'] + ' sodium ';
            return res;
    }
};


module.exports = {
    'AddFood': async function() {
        let FoodName = this.event.request.intent.slots.FoodName.value;

        let food = await getFoodAPI.bind(this)(FoodName);
        await logFood.bind(this)(food, this.event.session.user.userId);
        this.emit(':ask', 'Food registered');
    },
    "GetFoodInfo": async function() {
        let FoodName = this.event.request.intent.slots.FoodName.value;
        let infoToGet = this.event.request.intent.slots.nutritionInfo.value;

        let foods = await getFoodAPI.bind(this)(FoodName);
        let response = foodInfoResponse(foods[0], infoToGet);
        this.emit(':ask', response);
    }
};

