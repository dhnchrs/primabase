<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/home', function (Request $request, Response $response) {
	return $this->view->render($response, '/home/admin.html', array('title' => 'Home'));
})->add($adminMiddleware);
$app->get('/homeUser', function (Request $request, Response $response) {
	return $this->view->render($response, '/home/user.html', array('title' => 'Home'));
})->add($userMiddleware);