# adsbTelegramNotifier
Notifies via Telegram when an aircraft passes over a certain area.  
The script gets the data from an existing readsb or dump1090 installation (`aircraft.json`).

<img src="/screenshots/telegramMessage.png" alt="Telegram message">  
<sub><a href="https://www.planespotters.net/photo/1304779/9m-mub-malaysia-airlines-airbus-a330-223f" title="Photo Source">Photo Source</a></sub>


When the (`aircraft.json`) don't contain the distance to the ADS-B station but do contain the position of the aircraft the distance can be calculated by providing the base station coordinates in the config file.

## Dependencies
Install the dependencies via:  
`sudo apt install curl php-cli php-json php-curl -y`  
You also need a decoder instance, such as [readsb](https://github.com/wiedehopf/adsb-wiki/wiki/Raspbian-Lite%3A-ADS-B-receiver) or dump1090.

## Install
Go to the directory where you want to install the notifier, clone Repository, rename `config.template.php` to `config.php` and edit the config file.
```shell
cd ~
git clone https://github.com/RundesBalli/adsbTelegramNotifier.git
cd adsbTelegramNotifier
mv includes/config.template.php includes/config.php
nano includes/config.php
```

## Telegram Bot
1. Create a bot with the [BotFather](https://t.me/BotFather) and note the bot auth token.
2. Get the chat ID: Decide if you want to get notifications via private chat or in a group chat.
  * Private: Send a message to the [Chat ID Bot](https://t.me/rb_chatId_bot).
  * Group: Invite the [Chat ID Bot](https://t.me/rb_chatId_bot) to your group (can be kicked immediately after that).
3. Enter the bot auth token and the chat ID in the `includes/config.php`.
4. Initialize the bot:
  * Private: you must first send a message to your bot (e.g. /start or click the start button in the bot) to initialize the chat with the bot. Otherwise it will fail until first contact from you (antispam reasons). Note that the bot will not reply to your message.
  * Group: You have to invite the bot to your group. Thats it.

## Test the Bot / Debug / Troubleshooting
To test the bot, you can simply run it with PHP in the CLI:  
`php notifier.php`  

If you just want to know if the telegram chat ID and bot token is correct, you can run:  
`php notifier.php --test-telegram`  
It will send a test message to the configured chat ID.

The bot has a detailed output in the CLI, which makes debugging extremely easy:  
<img src="/screenshots/output.png" alt="Output">  

## Cron / automatic notifications
1. Edit the crontab with `crontab -e`
2. Enter a new line with the following contents:  
`* * * * * /usr/bin/php /path/to/your/notifier.php >/dev/null 2>&1`  
Don't forget to edit the path and to add a new line at the end of the crontab. Make sure, that you have the `>/dev/null 2>&1` at the end of the line, or the script will spam your systemlog.
3. If you don't want to get notifications every minute around the clock, you can simply edit the crontab times with the [crontab generator](https://crontab-generator.org/). If you only want notifications between 5 and 16 o'clock (5am to 4pm) and only Monday to Friday, you can use `* 5-16 * * 1-5`
4. Note: The update check is only executed every 15 minutes (0, 15, 30, 45)! So if you dont execute the notifier every minute, you have to check for updates yourself!

## More information (readsb/tar1090 only)
The default setup shows the HEX Code (ICAO) and the Flightnumber/Callsign if available.  
If you want to know more information about the aircraft (registration, aircraft-type, dbFlags) you can add more data to the `aircraft.json`.  
As seen in [wiedehopfs tutorial](https://github.com/wiedehopf/tar1090#0800-destroy-sd-card) you can download a `aircraft.csv` file and include it into readsb to get more data.  
```shell
sudo wget -O /usr/local/share/tar1090/aircraft.csv.gz https://github.com/wiedehopf/tar1090-db/raw/csv/aircraft.csv.gz
sudo nano /etc/default/readsb
```
Add the following line in one of the readsb parameter lines:  
`--db-file /usr/local/share/tar1090/aircraft.csv.gz --db-file-lt`  

For example:
```
DECODER_OPTIONS="--max-range 450 --write-json-every 1"
```
becomes:  
```
DECODER_OPTIONS="--max-range 450 --write-json-every 1 --db-file /usr/local/share/tar1090/aircraft.csv.gz --db-file-lt"
```

Restart readsb:  
`sudo systemctl restart readsb`

## Docker
Create docker image

`docker build -t adsbtelegramnotify .`

a Docker compose file is provided as an example of running this as a stack in e.g. Portainer. The example file maps in the config file as well as the decoded ADS-B file location.

## Contribute
If you want to contribute to the project, you are welcome to do the translations into your local language. But no matter what you want to contribute, just send a PR and I'll take a look at it.
