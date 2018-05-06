<?php

include __DIR__.'/../../vendor/autoload.php';

use uSIreF\Networking;

function callClient() {
    $protocolFactory = new Networking\Protocol\HTTP\Factory();
    $messageFactory  = new Networking\Message\Factory($protocolFactory);

    $stream = new Networking\Stream\TCP\Client($messageFactory);
    $client = new Networking\Client($stream);

    $request = new Networking\Protocol\HTTP\Request();
    $request->method(Networking\Protocol\HTTP\Request\Method::GET);
    $request->requestUri('http://www.google.cz/');
    $request->data(['message' => 'GET']);

    $message = $client->send($request, 10);
    for ($i = 0; $i < 10 && !$message->isCompleted(); $i++) {
        $message->update();
        usleep(20 * 1000);
    }

    echo 'Request: '.print_r($message->getRequest()->to(), true)."\n";
    echo 'Response: '.print_r($message->getResponse()->to(), true)."\n";
}

callClient();