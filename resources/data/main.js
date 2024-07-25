const { Builder, By } = require('selenium-webdriver');
const chrome = require('selenium-webdriver/chrome');
const fs = require('fs');


const chromeOptions = new chrome.Options();

// Uncomment the line below to run Chrome in headless mode (without opening a visible browser window)
 chromeOptions.addArguments('--headless').addArguments("--disable-gpu").addArguments("--no-sandbox");
chromeOptions.windowSize({ width: 1920, height: 1080 });
// Create a new WebDriver instance
const driver = new Builder().forBrowser('chrome').setChromeOptions(chromeOptions).build();

async function writeButtonsAndDivsToJSON() {
    let data = {}; // Object to hold the data to be saved to json

    try {
        await driver.get('https://crm.daimondrock.com/trade');

        // Wait for 20 seconds
        await driver.sleep(20000);

        // Switch to the iframe
        const iframe = await driver.findElement(By.id('watchlists'));
        await driver.switchTo().frame(iframe);

        const buttons = await driver.findElements(By.css('#id_watchlist-widget-tab-labels_tablist button'));

        // Iterate over each button, click it, and retrieve data
        for (let button of buttons) {
            try {
                // Check if the button is clickable
                if (await button.isEnabled()) {
                    await button.click();
                    const marketName = await button.findElement(By.className('content-mf1FlhVw')).getText();

                    await new Promise(resolve => setTimeout(resolve, 3000));

                    // Wait for the iframe content to load after clicking the button
                    await driver.wait(async () => {
                        const divs = await driver.findElements(By.css('.tv-widget-watch-list__row'));
                        
                        return divs.length > 0;
                    }, 10000, 'Timeout waiting for iframe content to load after button click');
					// Find all divs containg the symbol, price
                    const divs = await driver.findElements(By.css('.tv-widget-watch-list__row'));
                        for (let div of divs) {
                            try {
								let twoImages = false; //if the symbol has 2 images (like FOREX)
                                const divHtml = await div.getAttribute('outerHTML');
                                const symbolName = await div.findElement(By.className('js-symbol-link')).getText();
								// Ignore the div if empty
                                if (symbolName.length === 0) {
                                    continue;
                                }
								
								let logo, logo1, logo2;
								try {
									// Find the <img> element within the div using class name
									const imgElement = await div.findElement(By.className('tv-circle-logo-PsAlMQQF'));
									logo = await imgElement.getAttribute('src');
								}
								catch {
									const imgElements = await div.findElements(By.className('tv-circle-logo-pair__logo-ocURKVwI'));
									logo1 = await imgElements[0].getAttribute('src');
									logo2 = await imgElements[1].getAttribute('src');
									twoImages = true;
									
								}

                                // Find the <div> element with class 'js-symbol-last' within the div
                                const price = await div.findElement(By.className('js-symbol-last')).getText();


                                // Add data to object
                                if (!data[marketName]) {
                                    data[marketName] = [];
                                }
								if(twoImages) {
									data[marketName].push({
										logo1: logo1,
										logo12: logo2,
										symbolName: symbolName,
										price: price
									});
								} else {
									data[marketName].push({
										logo: logo,
										symbolName: symbolName,
										price: price
									});
								}
                            } catch (innerError) {
                                console.error('An error occurred while processing a div:', innerError);
                            }
                        }

                } else {
                    console.log('Button is not clickable.');
                }
            } catch (error) {
                console.error('An error occurred while interacting with the button:', error);
            }
        }

    } catch (error) {
        console.error('An error occurred:', error);
    } finally {
        // Close the WebDriver instance
        await driver.quit();

        // Write data to JSON file
        fs.writeFileSync('data.json', JSON.stringify(data, null, 2));
    }
}

// Call the writeButtonsAndDivsToJSON function
writeButtonsAndDivsToJSON();
