const puppeteer = require('puppeteer')

const fs = require('fs')

let email, password

let date

process.argv.forEach(function (val, index, array) {
        if(index == 2) email = val
       
        if(index == 3) password = val
  });

console.log(email, password);

(async () => {
    
     date = new Date


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



        await page.goto('https://www.planetminecraft.com/account/sign_in/', {waitUntil: 'networkidle2'});

        await page.screenshot({path: '1.png'})

        await page.click('.qc-cmp-button');


        /** Email */
        await page.focus('#email')
        await page.keyboard.type(email)

        await page.screenshot({path: '2.png'})


        /** Password */
        await page.focus('#password')
        await page.keyboard.type(password)


        await page.screenshot({path: '3.png'})


        /** Connexion */
        await page.click('[value="Log in"]')
        await delay(5000)

        await page.screenshot({path: 'log.png'})


        fs.appendFile('log-', 'Redirection vers les différents packs\n', function (err) {
            if(err) throw err
        })

        await page.screenshot({path: '5.png'})

        // Redirection vers tous les packs
        await page.goto('https://www.planetminecraft.com/account/manage/all/', {waitUntil: 'networkidle2'})
        await delay(5000)

        fs.appendFile('log-', 'Redirection vers les différents packs réussie \n', function (err) {
            if(err) throw err
        })

        await page.screenshot({path: '6.png'})

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

        await page.screenshot({path: '7.png'})

        //Refresh
        await page.goto('https://www.planetminecraft.com/account/manage/all/', {waitUntil: 'networkidle2'})
        await delay(5000)

        await page.screenshot({path: '8.png'})

        for(let i = 0; i < packNumber; i++){
            // Click sur la première submission

            await page.click('[title="Edit Submission"]')

            await delay(8000)

            await page.click('[class="material-icons md-24 assignment"]')

            await page.screenshot({path: '9.png'})

            await delay(6000)

            // Ecriture dans update
            let funct = randomItems(5)
            await page.evaluate((funct) => {

                let itemsList = []
                funct.forEach(e => itemsList += e + "<br>")

                document.querySelectorAll('iframe#content_ifr')[0].contentDocument.activeElement.innerHTML = itemsList
            }, funct)

            await page.screenshot({path: '10.png'})

            await delay(5000)

            // Sauvegarde des logs
            await page.click("#log_form > input.submit_log.site_btn")
            await delay(5000)

            await page.screenshot({path: '11.png'})

            await page.click("#ui-id-1 > span")
            await delay(5000)

            // Sauvegarder
            await page.click("#tab_content > div.public_menu.embed_box > div > div.site_btn.save_submission")
            await delay(10000)

            await page.goto('https://www.planetminecraft.com/account/manage/all/', {waitUntil: 'networkidle2'})
            await delay(12000)

            // Supprimer la precedente submission
            for(let j = 0; j <= i; j++){

                await page.$eval('[title="Edit Submission"]', e => e.setAttribute("title", ""))
            }
        }


        await browser.close();
    }

    function delay(time) {
        return new Promise(function(resolve) {
            setTimeout(resolve, time)
        });
    }
})();