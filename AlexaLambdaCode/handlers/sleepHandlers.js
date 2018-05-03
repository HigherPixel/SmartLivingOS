let knex = require("../helpers/knexFile");
let axios = require("../helpers/axiosFile");

// DayID INT(10), Quality TINYINT(1), Duration TINYINT(1), TimeInBed VARCHAR(4), TimeFellAsleep VARCHAR(4), TimeOfWake VARCHAR(4), TimeOutOfBed VARCHAR(4)
let logSleep = function(duration, quality, timeInBed, timeFellAsleep, timeOfWake, timeOutOfBed, userID) { return new Promise ((resolve) => {
    knex("sleep")
        .insert({
            userID: userID,
            DayID: null,
            log_file_input: { Duration: duration},
            log_file_input: { Quality: quality},
            log_file_input: { TimeInBed: timeInBed},
            log_file_input: { TimeFellAsleep: timeFellAsleep},
            log_file_input: { TimeOfWake: timeOfWake},
            log_file_input: { TimeOutOfBed: timeOutOfBed}
        })
        .then((data) => {
            resolve();
        })
        .catch((err) => {
            console.log('logSleep Error', err);
            this.emit(':ask', 'Saving failed, please try again');
        })
})};
module.exports = {
    'AddSleep': async function() {
        let duration = this.event.request.slots.SleepAmount.value;
        let quality = this.event.request.slots.SleepQuality.value;
        let timeInBed = this.event.request.slots.TimeInBed.value;
        let timeFellAsleep = this.event.request.slots.TimeFellAsleep.value;
        let timeOfWake = this.event.request.slots.TimeOfWake.value;
        let timeOutOfBed = this.event.request.slots.TimeOutOfBed.value;
        await logSleep.bind(this)(duration, quality, timeInBed, timeFellAsleep, timeOfWake, timeOutOfBed, this.event.session.user.userId);
        this.emit(':ask', 'Sleep registered');
    }
};