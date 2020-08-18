const schedule = require('node-schedule')
const {exec} = require("child_process")

const log = require('log-to-file')

const fs = require('fs')

const http = require('requestify')
const today = new Date()


fs.writeFile('./logs/Server - '+ today.toLocaleDateString() + '.log', '', function (err) {
    if (err) console.log(err)
    console.log('File is created successfully.');
});

log('Get all events', './logs/Server - '+ today.toLocaleDateString() + '.log')
console.log('Get all events')

http.get("https://scheduler-pmc.teissieryannis.com/api/events")
    .then((result) => {


        (JSON.parse(result.getBody()))['hydra:member'].forEach(entity => {

            let updateTime

            if ((new Date(entity.scheduler_datetime)).getDate() == today.getDate()) {

                log('Event finded to be scheduled today', './logs/Server - '+ today.toLocaleDateString() + '.log')

                updateTime = entity.scheduler_datetime

                log('Reach account infos', './logs/Server - '+ today.toLocaleDateString() + '.log')

                http.get("https://scheduler-pmc.teissieryannis.com" + entity.account)
                    .then((result) => {

                        log('Account info successfully reached', './logs/Server - '+ today.toLocaleDateString() + '.log')

                        result = JSON.parse(result.getBody())

                        log('Try to schedule event', './logs/Server - '+ today.toLocaleDateString() + '.log')

                        launch(new Date(updateTime), result.AccountLogin, result.AccountPassword)

                    })
            }
        })
    })


async function launch(date, username, password) {

    let s = schedule.scheduleJob(date, function () {

        log('Starting scheduler function', './logs/Server - '+ today.toLocaleDateString() + '.log')

        exec("node ../client/index.js " + username + " " + password, (error, stdout, stderr) => {

            s.nextInvocation()

            if (error) {

                log('Error : Failed to schedule the event', './logs/Server - '+ today.toLocaleDateString() + '.log')

            }

            if (stdout) {
                log('Event successfully added', './logs/Server - '+ today.toLocaleDateString() + '.log')
            }
        })
    })
}
