<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php

include_once('lib/_init.php');

class CommentModel {

    // descending by default
    private static function sortCommentsByDate(&$array, $inverse = false) {
        usort($array, function($a, $b) use ($inverse){
            if( $a['date'] == $b['date'] )
                return 0;
            else if($a['date'] > $b['date'])
                return $inverse ? 1 : -1;
            else
                return $inverse ? -1 : 1;
        });
    }

    public static function getCommentById($commentId) {
        return CommentStorage::getContext()->findById($commentId);
    }

    public static function addComment($userId, $teamId, $comment, $date) {
        CommentStorage::getContext()->add([
            "userId" => $userId,
            "teamId" => $teamId,
            "comment" => $comment,
            "date" => $date
        ]);
    }

    public static function updateCommentById($commentId, $comment) {
        $prev = CommentStorage::getContext()->findById($commentId);
        $prev["comment"] = $comment;
        CommentStorage::getContext()->update($commentId, $prev);
    }

    public static function deleteCommentById($commentId) {
        CommentStorage::getContext()->delete($commentId);
    }

    // sorted by date descending
    public static function getCommentsByTeamId($teamId, $inverse = false) {
        $comments =  CommentStorage::getContext()->findAll(["teamId" => $teamId]);

        self::sortCommentsByDate($comments, $inverse);

        return $comments;
    }

}
