# adsbTelegramNotifier
Notifies via Telegram when an aircraft passes over a certain area.  
The script gets the data from an existing readsb or dump1090 installation (`aircraft.json`).

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
1. Create a bot with the [BotFather](https://t.me/BotFather).
2. Decide if you want to get notifications via private chat or in a group chat.
  * Private: Send a message to the [Chat ID Bot](https://t.me/rb_chatId_bot).
  * Group: Invite the [Chat ID Bot](https://t.me/rb_chatId_bot) to your group (can be kicked immediately after that).
3. Enter the chat ID in the `config.php`.

## Cron / automatic notifications
1. Edit the crontab with `crontab -e`
2. Enter a new line with the following contents:  
`* * * * * /usr/bin/php /path/to/your/notifier.php >/dev/null 2>&1`  
Don't forget to edit the path and to add a new line at the end of the crontab. Make sure, that you have the `>/dev/null 2>&1` at the end of the line, or the script will spam your systemlog.
3. If you don't want to get notifications every minute around the clock, you can simply edit the crontab times with the [crontab generator](https://crontab-generator.org/). If you only want notifications between 5 and 16 o'clock (5am to 4pm) and only Monday to Friday, you can use `* 5-16 * * 1-5`
