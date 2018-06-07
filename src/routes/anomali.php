<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/anomali', function (Request $request, Response $response) {
	$data = $this->db->query("select * from anomali order by sampling desc;")->fetchAll();
	$params = array('data' => $data, 'title' => 'View Anomali');
	return $this->view->render($response, '/anomali/list.html', $params);
})->add($adminMiddleware);