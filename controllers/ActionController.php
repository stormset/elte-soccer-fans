<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php

include_once("lib/_init.php");

class ActionController
{
    function change_favorite() {
        $errors = [];

        $loggedInUser = LoginController::loggedInUser();
        $isLoggedIn = $loggedInUser != NULL;

        if (!$isLoggedIn) {
            $errors[] = "Login required";
        }

        if ($_POST) {
            function validate($post, &$data, &$errors) : bool
            {
                if (is_empty($post["teamId"])) {
                    $errors[] = "Missing parameter teamId.";
                } else {
                    $data["teamId"] = $post["teamId"];
                }

                if (is_empty($post["action"])) {
                    $errors[] = "Missing parameter action.";
                } else if ($post["action"] !== "add" && $post["action"] !== "remove") {
                    $errors[] = "Unknown action '" . $post["action"] .  "'. Supported actions are: 'add', 'remove'.";
                } else {
                    $data["action"] = $post["action"];
                }


                return count($errors) === 0;
            }

            $data = [];
            if ($isLoggedIn && validate($_POST, $data, $errors)) {
                $user = LoginController::loggedInUser();
                switch ($data["action"]) {
                    case "add" :
                        try {
                            UserModel::addFavoriteTeamForUser($user["id"], $data["teamId"]);
                        } catch (Error $e){
                            $errors[] = $e->getMessage();
                        }
                        break;
                    case "remove":
                        UserModel::removeFavoriteTeamForUser($user["id"], $data["teamId"]);
                        break;
                }
            }
        } else {
            $errors[] = "Missing request parameters.";
        }

        if (count($errors) === 0) {
            echo json_encode(["success" => true]);
        } else{
            echo json_encode([
                "success" => count($errors) === 0,
                "errors" => $errors
            ]);
        }
    }

    function add_comment() {
        $errors = [];

        $loggedInUser = LoginController::loggedInUser();
        $isLoggedIn = $loggedInUser != NULL;

        if (!$isLoggedIn) {
            $errors[] = "Login required";
        }

        if ($_POST) {
            function validate($post, &$data, &$errors) : bool
            {
                if (!isset($post["teamId"])) {
                    $errors[] = "Missing parameter teamId.";
                } else if (TeamModel::getTeamById($post["teamId"]) == NULL) {
                    $errors[] = "Invalid teamId.";
                } else {
                    $data["teamId"] = $post["teamId"];
                }

                if (!isset($post["comment"])) {
                    $errors[] = "Missing parameter comment.";
                } else {
                    $comment_error = validate_comment($post["comment"], 8, 200);
                    if ($comment_error) { $errors[] = $comment_error; }
                    else {  $data["comment"] = $post["comment"]; }
                }

                return count($errors) === 0;
            }

            $data = [];
            if ($isLoggedIn && validate($_POST, $data, $errors)) {
                $user = LoginController::loggedInUser();
                $date = date("Y-m-d h:i:s");
                CommentModel::addComment($user["id"], $data["teamId"], $data["comment"], $date);
            }
        } else {
            $errors[] = "Missing request parameters.";
        }

        if (count($errors) === 0) {
            $comments = CommentModel::getCommentsByTeamId($data["teamId"]);
            $result = [];
            array_walk($comments, function ($c) use (&$result) {
                $user = UserModel::findUserById($c["userId"]);
                $result[] = [
                    "username" => ($user != NULL ? $user["username"] : "unknown"),
                    "comment" => $c["comment"],
                    "date" => $c["date"],
                    "id" => $c["id"]
                ];
            });

            echo json_encode([
                "success" => true,
                "comments" => $result
            ]);
        } else{
            echo json_encode([
                "success" => false,
                "errors" => $errors
            ]);
        }
    }

    function follower_count() {
        $errors = [];

        if ($_POST) {
            function validate($post, &$data, &$errors) : bool
            {
                if (is_empty($post["teamId"])) {
                    $errors[] = "Missing parameter teamId.";
                } else {
                    $data["teamId"] = $post["teamId"];
                }

                return count($errors) === 0;
            }

            $data = [];
            if (validate($_POST, $data, $errors)) {
                if (TeamModel::getTeamById($data["teamId"]) == NULL) {
                    $errors[] = "Team doesn't exists";
                }
            }
        } else {
            $errors[] = "Missing request parameters.";
        }

        if (count($errors) === 0) {
            echo json_encode([
                "success" => true,
                "followerCount" => count(TeamModel::getFollowers($_POST["teamId"]))
            ]);
        } else{
            echo json_encode([
                "success" => count($errors) === 0,
                "errors" => $errors
            ]);
        }
    }

    function latest_results() {
        $errors = [];
        $latestResults = [];

        $loggedInUser = LoginController::loggedInUser();
        $isLoggedIn = $loggedInUser != NULL;

        if ($_POST) {
            function validate($post, &$data, &$errors) : bool
            {
                if (is_empty($post["startIndex"])) {
                    $errors[] = "Missing parameter startIndex.";
                } else {
                    $data["startIndex"] = $post["startIndex"];
                }
                if (is_empty($post["count"])) {
                    $errors[] = "Missing parameter count.";
                } else {
                    $data["count"] = $post["count"];
                }

                return count($errors) === 0;
            }

            $data = [];

            if (validate($_POST, $data, $errors)) {
                $matches = MatchModel::getLatestResultsMatchesBySection();

                $favorites = NULL;
                if ($isLoggedIn) {
                    $favorites = UserModel::getFavoriteTeamsForUser($loggedInUser["id"]);

                    stable_usort($matches, function($a, $b) use ($favorites){
                        $isFav_a = ( isset($favorites[$a["home"]["id"]]) || isset($favorites[$a["away"]["id"]]) );
                        $isFav_b = ( isset($favorites[$b["home"]["id"]]) || isset($favorites[$b["away"]["id"]]) );

                        if( $isFav_a && !$isFav_b )
                            return -1;
                        else if( !$isFav_a && $isFav_b )
                            return 1;
                        else
                            return 0;
                    });
                }

                array_walk($matches, function (&$m) use (&$latestResults, $isLoggedIn, $favorites) {
                    $home = TeamModel::getTeamById($m["home"]["id"]);
                    $away = TeamModel::getTeamById($m["away"]["id"]);
                    $rec = [
                        "date" => $m["date"],
                        "home" => [
                            "name" => $home["name"],
                            "score" => $m["home"]["score"],
                            "url" => url("team", ["id" => $home["id"]])
                        ],
                        "away" => [
                            "name" => $away["name"],
                            "score" => $m["away"]["score"],
                            "url" => url("team", ["id" => $away["id"]])
                        ]
                    ];

                    if ($isLoggedIn)
                        $rec["is_favorite"] = (isset($favorites[$home["id"]]) || isset($favorites[$away["id"]]));

                    $latestResults[] = $rec;
                });

                $latestResults =  array_slice($latestResults, $data["startIndex"], $data["count"]);
            }
        } else {
            $errors[] = "Missing request parameters.";
        }

        if (count($errors) === 0) {
            echo json_encode([
                "success" => true,
                "results" => $latestResults
            ]);
        } else{
            echo json_encode([
                "success" => count($errors) === 0,
                "errors" => $errors
            ]);
        }
    }
}