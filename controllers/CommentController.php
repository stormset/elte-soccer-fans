<?php

include_once("lib/_init.php");

class CommentController
{
    function editCommentForm() {
        $loggedInUser = LoginController::loggedInUser();
        $isLoggedIn = $loggedInUser != NULL;
        $canEdit = $isLoggedIn && $loggedInUser["role"] === "admin";

        if (!isset($_GET["id"]) || !$canEdit) {
            include 'views/404.php';
            return;
        }

        $commentId = $_GET["id"];
        $comment = CommentModel::getCommentById($commentId);

        if ($comment == NULL) {
            include 'views/404.php';
            return;
        }

        $author = UserModel::findUserById($comment["id"]);

        $prevTeamId = $_GET["ref"] ?? $flashRef ?? NULL;
        $prevUrl = (isset($_GET["ref"])) ? url("team", ["id" => $_GET["ref"]])  : url("");

        $errors = get_flash_data("errors") ?? [];
        $old = get_flash_data("old") ?? [];

        if ($_POST) {
            function validate($post, &$data, &$errors) : bool
            {
                $comment_error = validate_comment($post["comment"],8, 200);
                if ($comment_error) { $errors["comment"] = $comment_error; }
                else { $data["comment"] = $post["comment"]; }

                return count($errors) === 0;
            }

            $errors = [];
            $data = [];
            if (validate($_POST, $data, $errors)) {
                CommentModel::updateCommentById($commentId, $data["comment"]);
                redirect($prevUrl);
            } else {
                set_flash_data('errors', $errors);
                set_flash_data('old', $_POST);
            }

            if (isset($prevTeamId))
                redirect(url("comment", ["id" => $commentId, "ref" => $prevTeamId]));
            else
                redirect(url("comment", ["id" => $commentId]));
        }

        include 'views/edit_comment.php';
    }

    function deleteComment() {
        $loggedInUser = LoginController::loggedInUser();
        $isLoggedIn = $loggedInUser != NULL;
        $canEdit = $isLoggedIn && $loggedInUser["role"] === "admin";

        if (!$_POST || !$canEdit) {
            echo "Invalid request.";
            return;
        }

        $commentId = $_POST["id"];
        $comment = CommentModel::getCommentById($commentId);

        if ($comment == NULL) {
            echo "No match found by specified id.";
            return;
        }

        $prevUrl = (isset($_POST["ref"])) ? url("team", ["id" => $_POST["ref"]]) : url("");

        CommentModel::deleteCommentById($commentId);
        redirect($prevUrl);
    }
}