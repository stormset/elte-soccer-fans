<?php

include_once("lib/_init.php");

class HomeController
{
    function showHomePage() {
        $loggedInUser = LoginController::loggedInUser();
        $isLoggedIn = $loggedInUser != NULL;
        $teams = TeamModel::getTeams();
        $favorites = $isLoggedIn ? UserModel::getFavoriteTeamsForUser($loggedInUser["id"]) : NULL;

        array_walk($teams, function (&$t) use ($isLoggedIn, $favorites) {
            $t["recently_played"] = MatchModel::getRecentlyPlayedDate($t["id"]) ?? "még nem játszott";

            if ($isLoggedIn) {
                $t["is_favorite"] = isset($favorites[$t["id"]]);
            }
        });

        include 'views/home.php';
    }
}