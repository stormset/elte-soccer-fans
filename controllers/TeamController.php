<?php

include_once("lib/_init.php");

class TeamController
{
    function showTeamPage() {
        $loggedInUser = LoginController::loggedInUser();
        $isLoggedIn = $loggedInUser != NULL;
        $canEdit = $isLoggedIn && $loggedInUser["role"] === "admin";

        if (!isset($_GET["id"])) {
            include 'views/404.php';
            return;
        }

        $teamId = $_GET["id"];
        $team = TeamModel::getTeamById($teamId);

        if ($team == NULL) {
            include 'views/404.php';
            return;
        }

        $isFavorite = $isLoggedIn &&
                      isset(UserModel::getFavoriteTeamsForUser($loggedInUser["id"])[$teamId]);
        $winRatio = 0;
        $followers = count(TeamModel::getFollowers($teamId));
        $upcoming = NULL;
        $matches = MatchModel::getMatchesByTeamId($teamId);
        $played_matches = MatchModel::getPlayedMatches($teamId);
        $upcoming_matches = MatchModel::getUpcomingMatches($teamId);
        $comments = CommentModel::getCommentsByTeamId($teamId);

        $wonCount = 0; $notDrawCount = 0;
        array_walk($played_matches, function (&$m) use ($teamId, &$wonCount, &$notDrawCount) {
            $m["home"] = array_merge($m["home"], TeamModel::getTeamById($m["home"]["id"]));
            $m["away"] = array_merge($m["away"], TeamModel::getTeamById($m["away"]["id"]));
            $m["team_won"] = ($m["home"]["id"] == $teamId) ? ($m["home"]["score"] <=> $m["away"]["score"]) :
                                                             ($m["away"]["score"] <=> $m["home"]["score"]);

            if ($m["team_won"] == 1) {
                $wonCount++; $notDrawCount++;
            }
            else if ($m["team_won"] == -1) {
                $notDrawCount++;
            }
        });
        $winRatio = ($notDrawCount > 0) ? round($wonCount * 100 / $notDrawCount) . " %" : NULL;

        array_walk($upcoming_matches, function (&$m) {
            $m["home"] = TeamModel::getTeamById($m["home"]["id"]);
            $m["away"] = TeamModel::getTeamById($m["away"]["id"]);
        });
        $upcoming = count($upcoming_matches) > 0 ? $upcoming_matches[count($upcoming_matches) - 1] : NULL;

        array_walk($comments, function (&$c) {
            $user = UserModel::findUserById($c["userId"]);
            $c["username"] = ($user != NULL ? $user["username"] : "unknown");
        });

        include 'views/team.php';
    }
}