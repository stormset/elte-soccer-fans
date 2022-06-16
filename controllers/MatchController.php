<?php

include_once("lib/_init.php");

class MatchController
{
    function editMatchForm() {
        $loggedInUser = LoginController::loggedInUser();
        $isLoggedIn = $loggedInUser != NULL;
        $canEdit = $isLoggedIn && $loggedInUser["role"] === "admin";

        if (!isset($_GET["id"]) || !$canEdit) {
            include 'views/404.php';
            return;
        }

        $matchId = $_GET["id"];
        $match = MatchModel::getMatchById($matchId);

        if ($match == NULL) {
            include 'views/404.php';
            return;
        }

        $home = TeamModel::getTeamById($match["home"]["id"]);
        $away = TeamModel::getTeamById($match["away"]["id"]);

        $prevTeamId = $_GET["ref"] ?? $flashRef ?? NULL;
        $prevUrl = (isset($_GET["ref"])) ? url("team", ["id" => $_GET["ref"]])  : url("");

        $errors = get_flash_data("errors") ?? [];
        $old = get_flash_data("old") ?? [];

        if ($_POST) {
            function validate($post, &$data, &$errors) : bool
            {
                $home_error = validate_int($post["home"], 0);
                if ($home_error) { $errors["home"] = $home_error; }
                else { $data["home"] = $post["home"]; }

                $away_error = validate_int($post["away"], 0);
                if ($away_error) { $errors["away"] = $away_error; }
                else { $data["away"] = $post["away"]; }

                $date_error = validate_date($post["date"]);
                if ($date_error) { $errors["date"] = $date_error; }
                else { $data["date"] = $post["date"]; }

                return count($errors) === 0;
            }

            $errors = [];
            $data = [];
            if (validate($_POST, $data, $errors)) {
                MatchModel::updateMatchById($matchId, $data["home"], $data["away"], $data["date"]);
                redirect($prevUrl);
            } else {
                set_flash_data('errors', $errors);
                set_flash_data('old', $_POST);
            }

            if (isset($prevTeamId))
                redirect(url("match", ["id" => $matchId, "ref" => $prevTeamId]));
            else
                redirect(url("match", ["id" => $matchId]));
        }

        include 'views/edit_match.php';
    }

    function deleteMatch() {
        $loggedInUser = LoginController::loggedInUser();
        $isLoggedIn = $loggedInUser != NULL;
        $canEdit = $isLoggedIn && $loggedInUser["role"] === "admin";

        if (!$_POST || !$canEdit) {
            echo "Invalid request.";
            return;
        }

        $matchId = $_POST["id"];
        $match = MatchModel::getMatchById($matchId);

        if ($match == NULL) {
            echo "No match found by specified id.";
            return;
        }

        $prevUrl = (isset($_POST["ref"])) ? url("team", ["id" => $_POST["ref"]]) : url("");

        MatchModel::deleteMatchById($matchId);
        redirect($prevUrl);
    }
}