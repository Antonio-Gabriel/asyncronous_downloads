<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/Downloader.php";

$loop = React\EventLoop\Factory::create();
$client = new \Clue\React\Buzz\Browser($loop);

$downloader = new Downloader(
    $client->withOptions(['streaming' => true]),
    \React\Filesystem\Filesystem::create($loop),
    __DIR__ . '/downloads'
);

$downloader->download(...[
    'https://joy.videvo.net/videvo_files/video/free/2020-04/large_watermarked/200401_Microscope%205_05_preview.mp4',
    'https://joy.videvo.net/videvo_files/video/free/2021-04/large_watermarked/210329_06B_Bali_1080p_013_preview.mp4',
    'https://joy.videvo.net/videvo_files/video/free/video0455/large_watermarked/_import_609117fec58f36.06035401_preview.mp4'
]);

$loop->run();