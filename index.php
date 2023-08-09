<?php
require 'vendor/autoload.php';

use \YhyaSyrian\Sql\SyDb;
use danog\MadelineProto\API;

/**
 * function copy all files from directory
 * @param string $sourceDirectory
 * @param string $destinationDirectory
 * @param string $childFolder
 * @return void
 * @link https://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php
 */
function recurseCopy(
    string $sourceDirectory,
    string $destinationDirectory,
    string $childFolder = ''
): void {
    $directory = opendir($sourceDirectory);

    if (is_dir($destinationDirectory) === false) {
        mkdir($destinationDirectory);
    }

    if ($childFolder !== '') {
        if (is_dir("$destinationDirectory/$childFolder") === false) {
            mkdir("$destinationDirectory/$childFolder");
        }

        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..' || $file == 'www' || $file == 'bytesyria.com' || $file == 'yhyasyrian.com' || $file == 'gulfbar963.com') {
                continue;
            }

            if (is_dir("$sourceDirectory/$file") === true) {
                recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
            } else {
                copy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
            }
        }

        closedir($directory);

        return;
    }

    while (($file = readdir($directory)) !== false) {
        if ($file === '.' || $file === '..' || $file == 'www' || $file == 'bytesyria.com' || $file == 'yhyasyrian.com' || $file == 'gulfbar963.com') {
            continue;
        }

        if (is_dir("$sourceDirectory/$file") === true) {
            recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$file");
        } else {
            copy("$sourceDirectory/$file", "$destinationDirectory/$file");
        }
    }

    closedir($directory);
}
/**
 * function replace (/) from name path
 * @param string $path
 * @return string
 */
function pathName(string $path)
{
    return str_replace('--', '-', str_replace(['\\', '/', ':'], '-', $path));
}
/**
 * Remove directory
 * @param string $dir
 * @return void
 * @link https://www.php.net/manual/en/function.rmdir.php
 */ 
function delTree($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
     foreach ($files as $file) {
       (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
     }
     return rmdir($dir);
   }
if (!class_exists('ZipArchive')) {
    die("enter sudo apt install php-zip in your command line");
}
/**
 * function for compres files
 * @param string $SAIEDZip1 path files
 * @param string $SAIEDZip2 names file .zip
 * @return void
 * @link https://t.me/SaiedCh
 */
function SAIEDZip($SAIEDZip1, $SAIEDZip2)
{
    $SAIEDZip4 = realpath($SAIEDZip1);
    $SAIEDZip = new ZipArchive();
    $SAIEDZip->open($SAIEDZip2, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $SAIEDZip3 = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($SAIEDZip4),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($SAIEDZip3 as $SAIEDZip5 => $SAIEDZip6) {
        if (!$SAIEDZip6->isDir()) {
            $SAIEDZip7 = $SAIEDZip6->getRealPath();
            $SAIEDZip8 = substr($SAIEDZip7, strlen($SAIEDZip4) + 1);
            $SAIEDZip->addFile($SAIEDZip7, $SAIEDZip8);
        }
    }
    $SAIEDZip->close();
}
/**
 * Start Variable
 */
$database = [
    'USER' => [
        'pass' => 'PASS',
        'db' => [
            'NameDatabse'
        ]
    ],
];
$pathes = [
    '/var/www/html',
    '/var/www/your/path',
];
$chatID = '809064751';
// You can get that from my.telegram.org
$api_id = '275****';
$api_hash = '9f28ef9d03*****';
// You can get token from BotFather
$Token = '143291***:********';
/**
 * Start Script
 */
if (!is_dir(__DIR__ . '/backup')) {
    mkdir(__DIR__ . '/backup');
}
/**
 * Configration MadelineProto
 */
$settings = [
    'app_info' => [
        'api_id' => $api_id,
        'api_hash' => $api_hash,
    ],
    'logger' => [
        'logger_level' => 0,
    ],
    'serialization' => [
        'serialization_interval' => 60,
    ],
];
if (!is_dir(__DIR__.'/session')) {
    mkdir(__DIR__.'/session');
}
$MadelineProto = new API('session/madeline', $settings);
$MadelineProto->botLogin($Token);
$MadelineProto->start();
while (true) {
    try {
        foreach ($database as $user => $databases) {
            // Loop for create backup database
            foreach ($databases['db'] as $db) {
                // create connection with database
                $SyDb = new SyDb('localhost', $user, $databases['pass'], $db);
                if (!is_dir(__DIR__ . "/backup/sql_{$db}")) {
                    mkdir(__DIR__ . "/backup/sql_{$db}");
                }
                // export tables from database
                $SyDb->exportTables(__DIR__ . "/backup/sql_{$db}",false);
            }
            // Loop for create backup files
        }
        foreach ($pathes as $dir) {
            if (!is_dir(__DIR__ . "/backup/" . pathName($dir))) {
                mkdir(__DIR__ . "/backup/" . pathName($dir));
            }
            // copy content directory
            recurseCopy(sourceDirectory: $dir, destinationDirectory: __DIR__ . "/backup/" . pathName($dir));
        }
        $nameFile = date('Y-n-d-H-i') . '-backup.zip';
        SAIEDZip(__DIR__.'/backup', $nameFile);
        // upload file for sendin telegram
        $media = $MadelineProto->upload($nameFile);
        $MadelineProto->messages->sendMedia([
            'peer' => $chatID,
            'media' => [
                '_' => 'inputMediaUploadedDocument',
                'file' => $media,
                'attributes' => [
                    ['_' => 'documentAttributeFilename', 'file_name' => $nameFile]
                    ]
                ],
            'message' => "BackUp in : " . date('Y/n/d h:i a'),
        ]);
        echo "File sent successfully!" . PHP_EOL;
        unlink(date('Y-n-d-H-i') . '-backup.zip');
        delTree(__DIR__.'/backup');
        mkdir(__DIR__.'/backup');
    
    } catch (\danog\MadelineProto\RPCErrorException $e) {
        echo 'Error: ' . $e->getMessage() . PHP_EOL;
    }
    sleep(3600);
}