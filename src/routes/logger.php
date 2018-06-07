<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

//LOGGER & SETTING LOGGER
$app->group('/logger', function() use ($app) {
	//LOGGER
	$app->get('', function (Request $request, Response $response) {
		$data = $this->db->query("select a.id,a.sn,b.nama from logger a join pos b on a.pos_id = b.id;")->fetchAll();
		$params = array('data' => $data, 'title' => 'Form Logger');
		return $this->view->render($response, '/logger/list.html', $params);
	});
	//INSERT LOGGER
	$app->group('/add', function() use ($app) {
		$app->get('', function (Request $request, Response $response) {
			$data = $this->db->query("select id,nama from pos;")->fetchAll();
			$params = array('data' => $data, 'title' => 'Add Logger');
			return $this->view->render($response, '/logger/add.html', $params);
		});
		$app->post('', function (Request $request, Response $response) {
			$sn = $request->getParsedBody()['sn'];
			$ps = $request->getParsedBody()['pos_id'];
			$this->db->exec("insert into logger (sn,pos_id) values('".$sn."','".$ps."');");
			return $response->withRedirect("/logger");
		});
	});
	$app->group('/{id}', function() use ($app) {
		//UPDATE LOGGER
		$app->group('/edit', function() use ($app) {
			$app->get('', function (Request $request, Response $response, array $args) {
				$data = $this->db->query("select a.id,a.sn,b.id as pos_id,b.nama from logger a join pos b on a.pos_id = b.id where a.id = '".$args['id']."';")->fetch();
				$data2 = $this->db->query("select id,nama from pos;")->fetchAll();
				$params = array('data' => $data, 'data2' => $data2, 'title' => 'Edit Logger');
				return $this->view->render($response, '/logger/edit.html', $params);
			});
			$app->post('', function (Request $request, Response $response, array $args) {
				$sn = $request->getParsedBody()['sn'];
				$ps = $request->getParsedBody()['pos_id'];
				$this->db->exec("update logger set sn = '".$sn."',pos_id = '".$ps."' where id = '".$args['id']."';");
				return $response->withRedirect("/logger");
			});
		});
		//DELETE LOGGER
		$app->get('/delete', function (Request $request, Response $response, array $args) {
			$this->db->exec("delete from logger where id = '".$args['id']."';");
			return $response->withRedirect("/logger");
		});
	});


	//SETTING LOGGER
	$app->group('/{id}', function() use ($app) {
		$app->get('', function (Request $request, Response $response, array $args) {
			$data = $this->db->query("select b.sn,a.id,a.tinggisonar,a.tippingfactor,a.wlpressureoffset,a.wlpressurefactor,a.battcorrection from settinglogger a join logger b on a.logger_id=b.id where b.id = '".$args['id']."'")->fetchAll();
			$params = array('data' => $data, 'data2' => $args['id'], 'title' => 'Form Setting Logger');
			return $this->view->render($response, '/settingLogger/list.html', $params);
		});
		$app->group('/setting', function() use ($app) {
			//INSERT SETTING LOGGER
			$app->group('/add', function() use ($app) {
				$app->get('', function (Request $request, Response $response, array $args) {
					$data2 = $this->db->query("select sn from logger where id = '".$args['id']."'")->fetch();
					$params = array('data' => $args['id'], 'data2' => $data2, 'title' => 'Add Setting Logger');
					return $this->view->render($response, '/settingLogger/add.html', $params);
				});
				$app->post('', function (Request $request, Response $response, array $args) {
					$lg = $request->getParsedBody()['logger_id'];
					$ts = $request->getParsedBody()['tinggisonar'];
					$tf = $request->getParsedBody()['tippingfactor'];
					$wo = $request->getParsedBody()['wlpressureoffset'];
					$wf = $request->getParsedBody()['wlpressurefactor'];
					$bc = $request->getParsedBody()['battcorrection'];
					$this->db->exec("insert into settinglogger (logger_id,tinggisonar,tippingfactor,wlpressureoffset,wlpressurefactor,battcorrection) values('".$lg."','".$ts."','".$tf."','".$wo."','".$wf."','".$bc."');");
					return $response->withRedirect("/logger/".$args['id']."");
				});
			});
			$app->group('/{settinglogger_id}', function() use ($app) {
				//UPDATE SETTING LOGGER
				$app->group('/edit', function() use ($app) {
					$app->get('', function (Request $request, Response $response, array $args) {
						$data = $this->db->query("select b.sn,a.id,a.tinggisonar,a.tippingfactor,a.wlpressureoffset,a.wlpressurefactor,a.battcorrection from settinglogger a join logger b on a.logger_id=b.id where a.id = '".$args['settinglogger_id']."';")->fetch();
						$params = array('data' => $data, 'data2' => $args['id'], 'title' => 'Edit Setting Logger');
						return $this->view->render($response, '/settingLogger/edit.html', $params);
					});
					$app->post('', function (Request $request, Response $response, array $args) {
						$ts = $request->getParsedBody()['tinggisonar'];
						$tf = $request->getParsedBody()['tippingfactor'];
						$wo = $request->getParsedBody()['wlpressureoffset'];
						$wf = $request->getParsedBody()['wlpressurefactor'];
						$bc = $request->getParsedBody()['battcorrection'];
						$this->db->exec("update settinglogger set tinggisonar = '".$ts."',tippingfactor = '".$tf."',wlpressureoffset = '".$wo."',wlpressurefactor = '".$wf."',battcorrection = '".$bc."' where id = '".$args['settinglogger_id']."';");
						return $response->withRedirect("/logger/".$args['id']."");
					});
				});
				//DELETE SETTING LOGGER
				$app->get('/delete', function (Request $request, Response $response, array $args) {
					$this->db->exec("delete from settinglogger where id = '".$args['settinglogger_id']."';");
					return $response->withRedirect("/logger/".$args['id']."");
				});
			});
		});
	});
})->add($adminMiddleware);