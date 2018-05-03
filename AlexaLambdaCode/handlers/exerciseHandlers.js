let knex = require("../helpers/knexFile");
let axios = require("../helpers/axiosFile");

// ExerciseID, ActivityName, Duration, Repititions, sets, Weight
let logExercise = function(activityName, duration, reps, sets, weight, userID) { return new Promise ((resolve) => {
    knex("sleep")
        .insert({
            userID: userID,
            DayID: null,
            log_file_input: { ActivityName: activityName},
            log_file_input: { Duration: duration},
            log_file_input: { Repititions: reps},
            log_file_input: { sets: sets},
            log_file_input: {Weight: weight}
        })
        .then ((data) => {
            resolve();
        })
        .catch((err) => {
            console.log('logExercise Error', err);
            this.emit(':ask', 'Saving failed, please try again');
        })
})};
module.exports = {
    'AddFootTravel': async function() {
        let activityName = 'Cardio';
        let duration = this.event.request.slots.minutes.value;
        let reps = 0;
        let sets = 0;
        let weight = 0;
        await logExercise.bind(this)(activityName, duration, reps, sets, weight, this.event.session.user.userId);
        this.emit(':ask', 'Cardio on foot registered');
    },
    'AddWeightTraining': async function() {
        let activityName = 'Weight Training';
        let reps = this.event.request.slots.reps.value;
        let sets = this.event.request.slots.sets.value;
        let weight = this.event.request.slots.weight.value;
        let duration = 0;
        await logExercise.bind(this)(activityName, duration, reps, sets, weight, this.event.session.user.userId);
        this.emit(':ask', 'Weight training registered');
    }
};