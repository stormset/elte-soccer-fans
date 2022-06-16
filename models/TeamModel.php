<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php

include_once('lib/_init.php');

class TeamModel {
    public static function getTeams(): ?array {
        return TeamStorage::getContext()->findAll();
    }

    public static function getTeamById($teamId): ?array {
        return TeamStorage::getContext()->findOne(["id" => $teamId]);
    }

    public static function getFollowers($teamId): ?array {
        return FavoriteStorage::getContext()->findAll(["teamId" => $teamId]);
    }
}
