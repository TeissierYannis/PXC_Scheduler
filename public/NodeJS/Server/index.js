const schedule = require('node-schedule')
const {exec} = require("child_process")

const http = require('requestify')
const today = new Date()


console.log('Get all events')

http.get("https://scheduler-pmc.teissieryannis.com/api/events")
    .then((result) => {


        (JSON.parse(result.getBody()))['hydra:member'].forEach(entity => {

            let updateTime

            if ((new Date(entity.scheduler_datetime)).getDate() == today.getDate()) {

                console.log('Event finded to be scheduled today')

                updateTime = entity.scheduler_datetime

                console.log('Reach account infos')

                http.get("https://scheduler-pmc.teissieryannis.com" + entity.account)
                    .then((result) => {

                        console.log('Account info successfully reached')

                        result = JSON.parse(result.getBody())

                        console.log('Try to schedule event')

                        launch(new Date(updateTime), result.AccountLogin, result.AccountPassword)

                    })
            }
        })
    })


async function launch(date, username, password) {
    let s = schedule.scheduleJob(date, function () {

        console.log('Starting scheduler function')

        exec("node ../client/index.js " + username + " " + password, (error, stdout, stderr) => {

            s.nextInvocation()

            if (error) {

                console.log('Error : Failed to schedule the event')
            }

            if (stdout) {
                console.log('Event successfully added');
            }
        })
    })
}
