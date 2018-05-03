let knex = require("../helpers/knexFile");
let axios = require("../helpers/axiosFile");

let logEmotions = function(anxiety, anger, sadness, numbness, runimation, lossOfAppetite, excessiveAppetite, troubleSleeping, mania, tiredness, unmotivated, moodSwings, userID) { return new Promise ((resolve) => {
    knex("Mood")
        .insert({
            userID: userID,
            DayID: null,
            log_file_input: { Anxiety: anxiety},
            log_file_input: { Anger: anger},
            log_file_input: { Sadness: sadness},
            log_file_input: { Numbness: numbness},
            log_file_input: { Rumination: rumination},
            log_file_input: { LossOfAppitite: lossOfAppetite},
            log_file_input: { ExcessiveAppitite: excessiveAppetite},
            log_file_input: { TroubleSleeping: troubleSleeping},
            log_file_input: { Mania: mania},
            log_file_input: { Tiredness: tiredness},
            log_file_input: { Unmotivated: unmotivated},
            log_file_input: { MoodSwings: moodSwings},
            OverallMood: null
        })
        .then ((data) => {
            resolve();
        })
        .catch((err) => {
            console.log('logEmotions Error', err);
            this.emit(':ask', 'Saving failed, please try again');
        })
})};
module.exports = {
    'AddEmotions': async function() {
        let anxiety = this.event.request.slots.Anxiety.value;
        let anger = this.event.request.slots.Anger.value;
        let sadness = this.event.request.slots.Sadness.value;
        let numbness = this.event.request.slots.Numbness.value;
        let rumination = this.event.request.slots.Rumination.value;
        let lossOfAppetite = this.event.request.slots.LossOfAppetite.value;
        let excessiveAppetite = this.event.request.slots.ExcessiveAppetite.value;
        let troubleSleeping = this.event.request.slots.TroubleSleeping.value;
        let mania = this.event.request.slots.Mania.value;
        let tiredness = this.event.request.slots.Tiredness.value;
        let unmotivated = this.event.request.slots.Unmotivated.value;
        let moodSwings = this.event.request.slots.MoodSwings.value;
    await logEmotions.bind(this)(anxiety, anger, sadness, numbness, runimation, lossOfAppetite, excessiveAppetite, troubleSleeping, mania, tiredness, unmotivated, moodSwings, this.event.session.user.userId);
    this.emit(':ask', 'Emotions registered');
    }
};