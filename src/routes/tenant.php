<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

//TENANT
$app->group('/tenant', function() use ($app) {
	$app->get('', function (Request $request, Response $response) {
		$data = $this->db->query("select * from tenant;")->fetchAll();
		$params = array('data' => $data, 'title' => 'Form Tenant');
		return $this->view->render($response, '/tenant/list.html', $params);
	});
	//INSERT TENANT
	$app->group('/add', function() use ($app) {
		$app->get('', function (Request $request, Response $response) {
			return $this->view->render($response, '/tenant/add.html', array('title' => 'Add Tenant'));
		});
		$app->post('', function (Request $request, Response $response) {
			$nm = $request->getParsedBody()['nama'];
			$this->db->exec("insert into tenant (nama) values('".$nm."');");
			return $response->withRedirect("/tenant");
		});
	});
	$app->group('/{id}', function() use ($app) {
		//EDIT TENANT
		$app->group('/edit', function() use ($app) {
			$app->get('', function (Request $request, Response $response, array $args) {
				$data = $this->db->query("select * from tenant where id = '".$args['id']."';")->fetch();
				$params = array('data' => $data, 'title' => 'Edit Tenant');
				return $this->view->render($response, '/tenant/edit.html', $params);
			});
			$app->post('', function (Request $request, Response $response, array $args) {
				$nm = $request->getParsedBody()['nama'];
				$this->db->exec("update tenant set nama = '".$nm."' where id = '".$args['id']."';");
				return $response->withRedirect("/tenant");
			});
		});
		//DELETE TENANT
		$app->get('/delete', function (Request $request, Response $response, array $args) {
			$this->db->exec("delete from tenant where id = '".$args['id']."';");
			return $response->withRedirect("/tenant");
		});
	});
})->add($adminMiddleware);