<?php

use Clue\React\Buzz\Browser;
use React\Filesystem\Filesystem;
use React\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use React\Stream\WritableStreamInterface;

final class Downloader
{
    private $client;
    private $filesystem;
    private $dir;

    public function __construct(Browser $client, Filesystem $filesystem, string $dir)
    {
        $this->dir = $dir;
        $this->client = $client;
        $this->filesystem = $filesystem;
    }

    public function download(string ...$links)
    {
        foreach ($links as $link) {
            $this->openFile($link)
                ->then(function (WritableStreamInterface $file) use ($link) {
                    $this->client->get($link)
                        ->then(function (ResponseInterface $response) use ($file) {
                            echo $response->getBody();
                            echo $file;
                            $response->getBody()->pipe($file);
                        });
                });
        }
    }

    private function openFile(string $url): PromiseInterface
    {
        $path = $this->dir . DIRECTORY_SEPARATOR . basename($url);
        return $this->filesystem->file($path)->open('cw');
    }
}
