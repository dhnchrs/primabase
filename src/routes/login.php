<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

//LOGIN
$app->group('/login', function() use ($app) {
	$app->get('', function (Request $request, Response $response) {
		return $this->view->render($response, '/login/login.html', array('title' => 'Login'));
	});
	$app->post('', function (Request $request, Response $response) {
		$username = $request->getParsedBody()['username'];
		$password = $request->getParsedBody()['password'];
		$ps = ($this->db->query("select password from user where username = '".$username."' LIMIT 1")->fetch());
		$ut = ($this->db->query("select usertype from user where username = '".$username."' LIMIT 1")->fetch());
		$db_ps = $ps['password'];
		$db_ut = $ut['usertype'];
		if($password === $db_ps && $db_ut === "admin") {
			$_SESSION['isLoggedIn'] = 'admin';
			session_regenerate_id();
			$response = $response->withRedirect("/home");
			return $response;
		} else if($password === $db_ps && $db_ut === "user") {
			$_SESSION['isLoggedIn'] = 'user';
			session_regenerate_id();
			$params = array('data' => $username, 'title' => 'Home');
			return $this->view->render($response, '/home/user.html', $params);
		} else {
			$this->flash->addMessage('Test', 'Login gagal, silahkan ulangi !');
			$messages = $this->flash->getMessages();
			$rs = isset($messages['Test'][0]) ? $messages['Test'][0] : "";
			return $this->view->render($response, '/login/login.html', array('data' => $rs, 'title' => 'Login'));
			//echo "<script type='text/javascript'>alert('$rs');</script>";
			//return $this->view->render($response, '/login/login.html');
		}
	});
});
//LOGOUT
$app->get('/logout', function (Request $request, Response $response, array $args) {
	unset($_SESSION['isLoggedIn']);
	unset($_SESSION['username']);
	session_regenerate_id();
	$response = $response->withRedirect("/login");
	return $response;
});