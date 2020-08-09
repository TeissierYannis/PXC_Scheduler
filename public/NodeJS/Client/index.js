const puppeteer = require('puppeteer')

const fs = require('fs')

let email, password

let date
process.argv.forEach(function (val, index, array) {
        if(index == 2) email = val
       
        if(index == 3) password = val
  });
  
(async () => {
    
    date = new Date
    
    fs.appendFile('log-', 'Début de l\'update pour ' + email + "\n", function (err) {
        if(err) throw err
    })
    
    if(email !== undefined && password !== undefined){
        function randomItems(max){
        
            let items = require('./items.json')
            
            current_items = []
        
            for(let i = 0; i < max; i++){
                rnd = Math.floor(Math.random() * items.length + 1)
                if(!current_items.includes(items[rnd])){
                    
                    current_items.push(items[rnd])
                }
            }
        
            return current_items
        }

        const browser = await puppeteer.launch();
        const page = await browser.newPage();

        fs.appendFile('log-', 'Recherche de la page \n', function (err) {
            if(err) throw err
        })
        
        await page.goto('https://www.planetminecraft.com/account/sign_in/', {waitUntil: 'networkidle2'});
        
        fs.appendFile('log-', 'Page trouvé \n', function (err) {
            if(err) throw err
        })
        
        fs.appendFile('log-', 'Ecriture de l\'email \n', function (err) {
            if(err) throw err
        })
        
        /** Email */
        await page.focus('#email')
        await page.keyboard.type(email)
        
        fs.appendFile('log-', 'Ecriture de l\'email réussie\n', function (err) {
            if(err) throw err
        })
        
        fs.appendFile('log-', 'Ecriture du mot de passe \n', function (err) {
            if(err) throw err
        })
        
        /** Password */
        await page.focus('#password')
        await page.keyboard.type(password)
        
        fs.appendFile('log-', 'Ecriture du mot de passe réussie\n', function (err) {
            if(err) throw err
        })
        
        fs.appendFile('log-', 'Tentative de connexion\n', function (err) {
            if(err) throw err
        })
        
        /** Connexion */
        await page.click('[value="Log in"]')
        await delay(5000)
        
        fs.appendFile('log-', 'Tentative de connexion réussie\n', function (err) {
            if(err) throw err
        })
        
        fs.appendFile('log-', 'Redirection vers les différents packs\n', function (err) {
            if(err) throw err
        })
        
        // Redirection vers tous les packs
        await page.goto('https://www.planetminecraft.com/account/manage/all/', {waitUntil: 'networkidle2'})
        await delay(5000)
        
        fs.appendFile('log-', 'Redirection vers les différents packs réussie \n', function (err) {
            if(err) throw err
        })
        
        
        fs.appendFile('log-', 'Comptage des packs \n', function (err) {
            if(err) throw err
        })
        
        // Nombre de pack sur le compte
        let packNumber = 0
        let exist = false
        while(exist != true){
            try {
                await page.$eval('[title="Edit Submission"]', e => e.setAttribute("title", ""))
            } catch (error) {
                exist = true
            }
            packNumber++
        }
        packNumber -= 1
        
        fs.appendFile('log-', packNumber + ' packs trouvé \n', function (err) {
            if(err) throw err
        })
        
        fs.appendFile('log-', 'Retour aux packs \n', function (err) {
            if(err) throw err
        })
        
        //Refresh
        await page.goto('https://www.planetminecraft.com/account/manage/all/', {waitUntil: 'networkidle2'})
        await delay(5000)

        fs.appendFile('log-', 'Début des updates \n', function (err) {
            if(err) throw err
        })
        
        for(let i = 0; i < packNumber; i++){
            // Click sur la première submission
            
             fs.appendFile('log-', 'Premier pack trouvé \n', function (err) {
                if(err) throw err
            })
            
            await page.click('[title="Edit Submission"]')
            
             fs.appendFile('log-', 'Edition du pack \n', function (err) {
                if(err) throw err
            })
           
            await delay(8000)
            
            await page.click('[class="material-icons md-24 assignment"]')
            
            fs.appendFile('log-', 'Onglet update trouvé \n', function (err) {
                if(err) throw err
            })
    
            await delay(6000)

            
             fs.appendFile('log-', 'Ecriture des maj \n', function (err) {
                if(err) throw err
            })
            
            // Ecriture dans update  
            let funct = randomItems(5)
            await page.evaluate((funct) => {

                let itemsList = []
                funct.forEach(e => itemsList += e + "<br>")
                
                document.querySelectorAll('iframe#content_ifr')[0].contentDocument.activeElement.innerHTML = itemsList
            }, funct)

            
             fs.appendFile('log-', 'Ecriture des updates réussie\n', function (err) {
                if(err) throw err
            })
            
            await delay(5000)

             fs.appendFile('log-', 'Tentative de sauvegarde \n', function (err) {
                if(err) throw err
            })
            
            // Sauvegarde des logs
            await page.click("#log_form > input.submit_log.site_btn")
            await delay(5000)
            
             fs.appendFile('log-', 'Sauvegarde maj réussie \n', function (err) {
                if(err) throw err
            })
            
             fs.appendFile('log-', 'Retour au pack \n', function (err) {
                if(err) throw err
            })
            
            await page.click("#ui-id-1 > span")
            await delay(5000)
            
             fs.appendFile('log-', 'Sauvegarde du pack \n', function (err) {
                if(err) throw err
            })
            // Sauvegarder
            await page.click("#tab_content > div.public_menu.embed_box > div > div.site_btn.save_submission")
            await delay(10000)
            
             fs.appendFile('log-', 'Sauvegarde réussie \n', function (err) {
                if(err) throw err
            })
            
            await page.goto('https://www.planetminecraft.com/account/manage/all/', {waitUntil: 'networkidle2'})
            await delay(12000)
            
             fs.appendFile('log-', 'Premier pack trouvé \n', function (err) {
                if(err) throw err
            })
            
            // Supprimer la precedente submission
            for(let j = 0; j <= i; j++){

                await page.$eval('[title="Edit Submission"]', e => e.setAttribute("title", ""))
            }
        }
        
        fs.appendFile('log-', 'Fin de la mise à jour des packs \n', function (err) {
                if(err) throw err
            })
        
        await browser.close();
    }

    function delay(time) {
    return new Promise(function(resolve) { 
        setTimeout(resolve, time)
    });
    }
})();