<?php
$adminMiddleware = (function ($request, $response, $next) {
	$loggedIn = $_SESSION['isLoggedIn'];
	if ($loggedIn != 'admin') {
		return $response->withRedirect("/login");
	}
	$this->view->offsetSet('flash', $this->flash);
	$response = $next($request, $response);
	return $response;
});
$userMiddleware = (function ($request, $response, $next) {
	$loggedIn = $_SESSION['isLoggedIn'];
	if ($loggedIn != 'user') {
		return $response->withRedirect("/login");
	}
	$this->view->offsetSet('flash', $this->flash);
	$response = $next($request, $response);
	return $response;
});