<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

//POS & PENGAMAT
$app->group('/pos', function() use ($app) {
	//POS
	$app->get('', function (Request $request, Response $response) {
		$data = $this->db->query("select b.nama as nama_tenant,a.id,a.nama,a.lonlat,a.desa,a.kec,a.kab,a.pengamat from pos a join tenant b on a.tenant_id = b.id;")->fetchAll();
		$params = array('data' => $data, 'title' => 'Form Pos');
		return $this->view->render($response, '/pos/list.html', $params);
	});
	//INSERT POS
	$app->group('/add', function() use ($app) {
		$app->get('', function (Request $request, Response $response) {
			$data = $this->db->query("select id,nama from tenant;")->fetchAll();
			$params = array('data' => $data, 'title' => 'Add Pos');
			return $this->view->render($response, '/pos/add.html', $params);
		});
		$app->post('', function (Request $request, Response $response) {
			$nm = $request->getParsedBody()['nama'];
			$tn = $request->getParsedBody()['tenant_id'];
			$ln = $request->getParsedBody()['lonlat'];
			$ds = $request->getParsedBody()['desa'];
			$kc = $request->getParsedBody()['kec'];
			$kb = $request->getParsedBody()['kab'];
			$png = $request->getParsedBody()['pengamat'];
			$this->db->exec("insert into pos (nama,tenant_id,lonlat,desa,kec,kab,pengamat) values('".$nm."','".$tn."','".$ln."','".$ds."','".$kc."','".$kb."','".$png."');");
			return $response->withRedirect("/pos");
		});
	});
	$app->group('/{id}', function() use ($app) {
		//EDIT POS
		$app->group('/edit', function() use ($app) {
			$app->get('', function (Request $request, Response $response, array $args) {
				$data = $this->db->query("select b.id as tenant_id,b.nama as nama_tenant,a.id,a.nama,a.lonlat,a.desa,a.kec,a.kab,a.pengamat from pos a join tenant b on a.tenant_id = b.id where a.id = '".$args['id']."';")->fetch();
				$data2 = $this->db->query("select id,nama from tenant;")->fetchAll();
				$params = array('data' => $data, 'data2' => $data2, 'title' => 'Edit Pos');
				return $this->view->render($response, '/pos/edit.html', $params);
			});
			$app->post('', function (Request $request, Response $response, array $args) {
				$nm = $request->getParsedBody()['nama'];
				$tn = $request->getParsedBody()['tenant_id'];
				$ln = $request->getParsedBody()['lonlat'];
				$ds = $request->getParsedBody()['desa'];
				$kc = $request->getParsedBody()['kec'];
				$kb = $request->getParsedBody()['kab'];
				$png = $request->getParsedBody()['pengamat'];
				$this->db->exec("update pos set nama = '".$nm."',tenant_id = '".$tn."',lonlat = '".$ln."',desa = '".$ds."',kec = '".$kc."',kab = '".$kb."',pengamat = '".$png."' where id = '".$args['id']."';");
				return $response->withRedirect("/pos");
			});
		});
		//DELETE POS
		$app->get('/delete', function (Request $request, Response $response, array $args) {
			$this->db->exec("delete from pos where id = '".$args['id']."';");
			return $response->withRedirect("/pos");
		});
	});
	
	//PENGAMAT
	$app->group('/{id}', function() use ($app) {
		$app->get('', function (Request $request, Response $response, array $args) {
			$data = $this->db->query("select b.nama as nama_pos,a.id,a.nama,a.alamat,a.desa,a.kec,a.kab,a.noktp from pengamat a join pos b on a.pos_id=b.id where b.id = '".$args['id']."'")->fetchAll();
			$params = array('data' => $data, 'data2' => $args['id'], 'title' => 'Form Pengamat');
			return $this->view->render($response, '/pengamat/list.html', $params);
		});
		$app->group('/pengamat', function() use ($app) {
			//INSERT PENGAMAT
			$app->group('/add', function() use ($app) {
				$app->get('', function (Request $request, Response $response, array $args) {
					$data2 = $this->db->query("select nama from pos where id = '".$args['id']."'")->fetch();
					$params = array('data' => $args['id'], 'data2' => $data2, 'title' => 'Add Pengamat');
					return $this->view->render($response, '/pengamat/add.html', $params);
				});
				$app->post('', function (Request $request, Response $response, array $args) {
					$nm = $request->getParsedBody()['nama'];
					$ps = $request->getParsedBody()['pos_id'];
					$al = $request->getParsedBody()['alamat'];
					$ds = $request->getParsedBody()['desa'];
					$kc = $request->getParsedBody()['kec'];
					$kb = $request->getParsedBody()['kab'];
					$nk = $request->getParsedBody()['noktp'];
					$this->db->exec("insert into pengamat (nama,pos_id,alamat,desa,kec,kab,noktp) values('".$nm."','".$ps."','".$al."','".$ds."','".$kc."','".$kb."','".$nk."');");
					return $response->withRedirect("/pos/".$args['id']."");
				});
			});
			$app->group('/{pengamat_id}', function() use ($app) {
				//UPDATE PENGAMAT
				$app->group('/edit', function() use ($app) {
					$app->get('', function (Request $request, Response $response, array $args) {
						$data = $this->db->query("select b.nama as nama_pos,a.id,a.nama,a.alamat,a.desa,a.kec,a.kab,a.noktp from pengamat a join pos b on a.pos_id=b.id where a.id = '".$args['pengamat_id']."';")->fetch();
						$params = array('data' => $data, 'data2' => $args['id'], 'title' => 'Edit Pengamat');
						return $this->view->render($response, '/pengamat/edit.html', $params);
					});
					$app->post('', function (Request $request, Response $response, array $args) {
						$nm = $request->getParsedBody()['nama'];
						$ps = $request->getParsedBody()['pos_id'];
						$al = $request->getParsedBody()['alamat'];
						$ds = $request->getParsedBody()['desa'];
						$kc = $request->getParsedBody()['kec'];
						$kb = $request->getParsedBody()['kab'];
						$nk = $request->getParsedBody()['noktp'];
						$this->db->exec("update pengamat set nama = '".$nm."',pos_id = '".$ps."',alamat = '".$al."',desa = '".$ds."',kec = '".$kc."',kab = '".$kb."',noktp = '".$nk."' where id = '".$args['pengamat_id']."';");
						return $response->withRedirect("/pos/".$args['id']."");
					});
				});
				//DELETE PENGAMAT
				$app->get('/delete', function (Request $request, Response $response, array $args) {
					$this->db->exec("delete from pengamat where id = '".$args['pengamat_id']."';");
					return $response->withRedirect("/pos/".$args['id']."");
				});
			});
		});
	});
})->add($adminMiddleware);