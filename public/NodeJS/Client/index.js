const puppeteer = require('puppeteer')

const fs = require('fs')
const log = require('log-to-file')

let email, password

let date

const today = new Date()

fs.writeFile('./logs/Client - ' + today.toLocaleDateString() + '.log', '', function (err) {
    if (err) console.log(err)
    console.log('File is created successfully.');
});


log('Starting client', './logs/Client - '+ today.toLocaleDateString() + '.log')

process.argv.forEach(function (val, index, array) {
        if(index == 2) email = val
       
        if(index == 3) password = val
  });


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

        await log('Reach Main Page', './logs/Client - '+ today.toLocaleDateString() + '.log')


        await page.click('.qc-cmp-button');
        await log('Bypass RDBP', './logs/Client - '+ today.toLocaleDateString() + '.log')


        /** Email */
        await page.focus('#email')
        await page.keyboard.type(email)

        await log('Write loggin', './logs/Client - '+ today.toLocaleDateString() + '.log')

        /** Password */
        await page.focus('#password')
        await page.keyboard.type(password)

        await log('Write password', './logs/Client - '+ today.toLocaleDateString() + '.log')

        await delay(5000)

        /** Connexion */
        await page.click('#full_screen > div.login_content > form > div > input.site_btn.r3submit')
        await log('Try to log-in', './logs/Client - '+ today.toLocaleDateString() + '.log')

        await page.screenshot({path: 'logged.png'})

        await delay(5000)

        await log('Redirect to packs page', './logs/Client - '+ today.toLocaleDateString() + '.log')

        // Redirection vers tous les packs
        await page.goto('https://www.planetminecraft.com/account/manage/all/', {waitUntil: 'networkidle2'})

        await log('Redirect to all packs', './logs/Client - '+ today.toLocaleDateString() + '.log')

        await delay(5000)

        await log('Starting packs count', './logs/Client - '+ today.toLocaleDateString() + '.log')

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

        await log(packNumber + ' pack found', './logs/Client - '+ today.toLocaleDateString() + '.log')

        //Refresh
        await page.goto('https://www.planetminecraft.com/account/manage/all/', {waitUntil: 'networkidle2'})
        await delay(5000)

        await log('Reset pack page. Preparing submission updates', './logs/Client - '+ today.toLocaleDateString() + '.log')

        for(let i = 0; i < packNumber; i++){
            await log('Starting ' + i + ' submission', './logs/Client - '+ today.toLocaleDateString() + '.log')

            await page.click('[title="Edit Submission"]')
            await log('Go to edition page', './logs/Client - '+ today.toLocaleDateString() + '.log')

            await delay(8000)

            await page.click('[class="material-icons md-24 assignment"]')
            await log('Go to update page', './logs/Client - '+ today.toLocaleDateString() + '.log')

            await delay(6000)

            // Ecriture dans update
            let funct = randomItems(5)
            await log('Items generation', './logs/Client - '+ today.toLocaleDateString() + '.log')

            await page.evaluate((funct) => {

                let itemsList = []
                funct.forEach(e => itemsList += e + "<br>")

                document.querySelectorAll('iframe#content_ifr')[0].contentDocument.activeElement.innerHTML = itemsList
            }, funct)

            await log('Item successfully written', './logs/Client - '+ today.toLocaleDateString() + '.log')

            await page.screenshot({path: 'items' + i + '.png'})

            await delay(5000)

            // Sauvegarde des logs
            await page.click("#log_form > input.submit_log.site_btn")
            await log('Saving logs', './logs/Client - '+ today.toLocaleDateString() + '.log')
            await delay(5000)

            await page.click("#ui-id-1 > span")
            await delay(5000)

            // Sauvegarder
            await log('Saving pack', './logs/Client - '+ today.toLocaleDateString() + '.log')
            await page.click("#tab_content > div.public_menu.embed_box > div > div.site_btn.save_submission")
            await delay(10000)

            await page.goto('https://www.planetminecraft.com/account/manage/all/', {waitUntil: 'networkidle2'})
            await delay(12000)

            // Supprimer la precedente submission
            for(let j = 0; j <= i; j++){
                await log('Delete previous submission', './logs/Client - '+ today.toLocaleDateString() + '.log')
                await page.$eval('[title="Edit Submission"]', e => e.setAttribute("title", ""))
            }
        }

        await log('Update script - END', './logs/Client - '+ today.toLocaleDateString() + '.log')
        await browser.close();
    }

    function delay(time) {
        return new Promise(function(resolve) {
            setTimeout(resolve, time)
        });
    }
})();