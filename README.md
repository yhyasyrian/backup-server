
# backup-server
backup files and databse in your server and send that in telegram 
* [install](#install)
* [config](#config)
* [start](#start)
# install
Use this command in your terminal:
```bash
git clone https://github.com/yhyasyrian/backup-server.git
cd backup-server
composer update
```
# config
edit file `index.php` in line `115` for add user and database for backup data then in line `123` add pathes for backup files 
```php
$database = [
    'USER' => [ // Here User 1
        'pass' => 'PASS', // Here PassWord 1
        'db' => [
            'NameDatabse', // name databse 1
            'NameDatabse2' // name databse 2
        ]
    ],
    'USER_tow' => [ // Here User 2
        'pass' => 'PASS', // Here PassWord 2
        'db' => [
            'NameDatabse', // name databse 1
            'NameDatabse2' // name databse 2
        ]
    ],
];
$pathes = [
    '/var/www/html', // Path one
    '/var/www/your/path', // Path tow
];
```
and in line `127` add your id in telegram you can get that in [here](https://t.me/SR6BOT)
and  some infromation aboud your acount:
```php
// from https://my.telegram.org
$api_id = '275****';
$api_hash = '9f28ef9d03*****';
// You can get token from BotFather
$Token = '143291***:********';
```
# start
You can start the file for test with command: 
```bash
php index.php
```
and if you wnat start this for send backup every one hour, you should next command:
```bash
screen -S backUp php index.php
```
if you stop that, use the next command:
```bash
screen -X -S backUp kill
```
if you need suppor, you can send me for that [here](https://t.me/kkykkn)