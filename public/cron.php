<?php
// NOTE: Make sure this file is not accessible when deployed to production
//if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
//    die('You are not allowed to access this file.');
//}
$password='wmP80RHB2wQLalqMWL3pyThkx4Wxxyt';

require __DIR__ . '/../vendor/autoload.php'; // require composer dependencies

use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;

// Composer\Factory::getHomeDir() method
// needs COMPOSER_HOME environment variable set
// directory must be writable
putenv('COMPOSER_HOME=' . __DIR__ . '/../cache/composer');
chdir(__DIR__ . '/../');
// Setup composer output formatter

$stream = fopen('php://temp', 'w+');
$output = new StreamOutput($stream);

// Programmatically run `composer install`
$application = new Application();
$application->setAutoExit(false);
$code = $application->run(new ArrayInput(array('command' => 'install')), $output);

$log_file = 'cron.log';

$header = '/**' . PHP_EOL;
$header .= '* Date/Time = ' . date("Y-m-d H:i:s") . PHP_EOL;
$header .= '* $_SERVER[\'REMOTE_ADDR\'] = ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL;
$header .= '*/' . PHP_EOL;

file_put_contents($log_file, $header, FILE_APPEND | LOCK_EX);
// rewind stream to read full contents
rewind($stream);
file_put_contents($log_file, stream_get_contents($stream), FILE_APPEND | LOCK_EX);
