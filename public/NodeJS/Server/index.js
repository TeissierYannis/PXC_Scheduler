let mysql = require('mysql');
let schedule = require('node-schedule')

const { exec } = require("child_process")

let config = require('./config.json')

const fs = require('fs')

let date = new Date


fs.appendFile('log-', 'Connexion...' + date + '\n', function (err) {
        if(err) throw err
    })
 
let conn = mysql.createConnection({
  database: config.database,
  host: config.host,
  user: config.user,
  password: config.password
});
 
(async () => {
    await conn.connect(function(err) {
        if (err) throw err;
        fs.appendFile('log-', 'Connecté\n', function (err) {
            if(err) throw err
        })
      });
    
    let queue = []

    let req = "SELECT * FROM events WHERE DATE(start) = CURDATE() ORDER BY start ASC"
  
    fs.appendFile('log-', 'Recupération de la queue\n', function (err) {
        if(err) throw err
    })
    await conn.query(req, function(err, rows, fields) {
        if (err) throw err
        
             rows.forEach(el => {
                
                if(new Date() < new Date(el.start)){
                  
                    queue.push({"compte": el.name, "heure": el.start, "password": el.description})
                    
                }
                 console.log(queue)
                for(let i = 0; i < queue.length; i++){
                  launch(new Date(queue[i].heure), queue[i].compte, queue[i].password)
                }
              })
        
    });
    
    
    async function launch(date, username, password){
      let s = schedule.scheduleJob(date, function() {
      fs.appendFile('log-', 'Lancement de l\'update pour ' + username + '\n', function (err) {
        if(err) throw err
      })
        exec("node ../client/index.js "+ username + " " + password, (error, stdout, stderr) => {
              s.nextInvocation()
            if(error){
                fs.appendFile('log-', 'Erreur lors de l\'execution du script de ' + username + '\n', function (err) {
                    if(err) throw err
                })
            }
        })
      })
    }

    // let date = new Date("2020-04-21 09:08:00")
    // let date2 = new Date("2020-04-21 09:07:00")
    //await launch(date)
    //await launch(date2)

    

    // exec("node test.js "+ el.description, (error, stdout, stderr) => {
    //   if (error) {
    //       console.log(`error: ${error.message}`);
    //       return;
    //   }
    //   if (stderr) {
    //       console.log(`stderr: ${stderr}`);
    //       return;
    //   }
    //   console.log(`stdout: ${stdout}`);
    // })
})();