<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/periodic', function (Request $request, Response $response) {
	$data = $this->db->query("select * from periodic order by sampling desc;")->fetchAll();
	$wawl = $this->db->query("select sampling from periodic order by sampling asc limit 1;")->fetch();
	$wakhr = $this->db->query("select sampling from periodic order by sampling desc limit 1;")->fetch();
	$params = array('data' => $data, 'wawl' => date('Y-m-d', strtotime($wawl['sampling'])), 'wakhr' => date('Y-m-d', strtotime($wakhr['sampling'])),  'title' => 'View Periodic');
	return $this->view->render($response, '/periodic/list.html', $params);
})->add($adminMiddleware);
$app->post('/periodic', function (Request $request, Response $response) {
	$wawl = $request->getParsedBody()['waktuawal'];
	$wakhr = $request->getParsedBody()['waktuakhir'];
	$data = $this->db->query("select * from periodic where sampling between '".$wawl."' and '".$wakhr."' order by sampling desc;")->fetchAll();
	$params = array('data' => $data, 'wawl' => $wawl, 'wakhr' => $wakhr, 'title' => 'View Periodic');
	return $this->view->render($response, '/periodic/list.html', $params);
})->add($adminMiddleware);