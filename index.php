<?php
// token to check from where is the page accessed
const TOKEN = 'A secret token';

include_once("lib/_init.php");
include("lib/router.php");

function url($path, $params = []): string
{
    $path = is_empty($path) ? "index" : $path;
    $url = basename($_SERVER['SCRIPT_FILENAME']) .  "?page=" . $path;
    foreach ($params as $k => $v){
        $url .= "&" . $k . "=" . $v;
    }
    return $url;
}

// start session
session_start();
// start routing
$router = new Router();

$router->not_found(function () {
    include 'views/404.php';
});

/* home page */
$router->get("index", "HomeController", "showHomePage");

/* team page */
$router->get("team", "TeamController", "showTeamPage");

/* match edit page */
$router->get("match", "MatchController", "editMatchForm");
$router->post("match", "MatchController", "editMatchForm");
$router->post("delete-match", "MatchController", "deleteMatch");

/* comment edit page */
$router->get("comment", "CommentController", "editCommentForm");
$router->post("comment", "CommentController", "editCommentForm");
$router->post("delete-comment", "CommentController", "deleteComment");

/* auth page */
$router->get("login", "LoginController", "loginForm");
$router->post("login", "LoginController", "loginForm");
$router->get("register", "LoginController", "registerForm");
$router->post("register", "LoginController", "registerForm");
$router->post("logout", "LoginController", "signOutAndRedirect");

/* actions */
$router->post("change-favorite", "ActionController", "change_favorite");
$router->post("followers", "ActionController", "follower_count");
$router->post("latest", "ActionController", "latest_results");
$router->post("add-comment", "ActionController", "add_comment");

$router->start();
