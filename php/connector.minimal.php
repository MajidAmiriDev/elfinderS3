<?php

 error_reporting(E_ALL);
 ini_set("display_errors", 1);

// Load composer autoload

// elFinder autoload
require '../vendor/autoload.php';

// Enable FTP connector netmount
elFinder::$netDrivers['ftp'] = 'FTP';

// Include S3 filesystem driver
use League\Flysystem\Filesystem;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use Aws\S3\S3Client;
use elFinderFlysystem\FlysystemVolume;

// Your S3 configuration
// $aws_config = [
//     'key'    => 'fff887a0-7591-44b0-b6c1-69876359484b',
//     'secret' => '5007d09ec40e0d20fb54597a60d98f46a65ae68217f9f0ac9b009dd623a5e74c',
//     'version' => 'latest',
//     'region' => 'Simin',
//     'bucket' => 'classeh-stage',
//     'endpoint' => 'https://s3.ir-thr-at1.arvanstorage.ir/',
// ];
$aws_config = [
    'credentials' => [
        'key'    => 'fff887a0-7591-44b0-b6c1-69876359484b',
        'secret' => '5007d09ec40e0d20fb54597a60d98f46a65ae68217f9f0ac9b009dd623a5e74c',
    ],
    'version' => 'latest',
    'region'  => 'Simin',
    'endpoint' => 'https://s3.ir-thr-at1.arvanstorage.ir', // Correct endpoint URL without trailing slash
    'bucket'  => 'classeh-stage',
];
// Create an S3 client
// $s3 = new S3Client($aws_config);
$s3 = new S3Client([
    'credentials' => $aws_config['credentials'],  // Explicitly provide credentials
    'region'      => $aws_config['region'],
    'version'     => $aws_config['version'],
    'endpoint'    => $aws_config['endpoint'],
    'use_path_style_endpoint' => true,  // Necessary for certain S3-compatible services like ArvanCloud
]);
// Create an adapter
//$adapter = new AwsS3Adapter($s3, $aws_config['bucket'], $aws_config['endpoint']);
$adapter = new AwsS3Adapter($s3, $aws_config['bucket']);
// Create a Filesystem
$filesystem = new Filesystem($adapter);
$aws_url = "https://classeh-stage.s3.ir-thr-at1.arvanstorage.ir/";
// Configure elFinder with the S3 volume
$opts = array(
    'roots' => array(
        // S3 volume
        array(
            'driver'        => 'Flysystem', // Use Flysystem driver
            'filesystem'    => $filesystem,  // Your Filesystem instance
            'URL'           => $aws_url,     // URL to files
            'accessControl' => 'access',      // Optional: access control callback
        ),
        // Existing volumes can remain here
//         array(
//             'driver'        => 'LocalFileSystem',
//             'path'          => '../files/',
//             'URL'           => dirname($_SERVER['PHP_SELF']) . '/../files/',
//             'trashHash'     => 't1_Lw',
//             'winHashFix'    => DIRECTORY_SEPARATOR !== '/',
//             'uploadDeny'    => array('all'),
//             'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', 'text/plain'),
//             'uploadOrder'   => array('deny', 'allow'),
//             'accessControl' => 'access'
//         ),
//         // Trash volume
//         array(
//             'id'            => '1',
//             'driver'        => 'Trash',
//             'path'          => '../files/.trash/',
//             'tmbURL'        => dirname($_SERVER['PHP_SELF']) . '/../files/.trash/.tmb/',
//             'winHashFix'    => DIRECTORY_SEPARATOR !== '/',
//             'uploadDeny'    => array('all'),
//             'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', 'text/plain'),
//             'uploadOrder'   => array('deny', 'allow'),
//             'accessControl' => 'access',
//         ),
    )
);

// Run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();


