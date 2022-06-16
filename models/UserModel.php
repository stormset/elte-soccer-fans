<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php

include_once('lib/_init.php');

class UserModel {
    public static function findUserById($id) {
        return UserStorage::getContext()->findById($id);
    }

    public static function findUserByUsername($username) {
        return UserStorage::getContext()->findOne(['username' => $username]);
    }

    public static function findUserByEmail($email) {
        return UserStorage::getContext()->findOne(['email' => $email]);
    }

    public static function addUser($email, $username, $password, $role = NULL) {
        $any = UserStorage::getContext()->findOne(['username' => $username, 'email' => $email]);
        if ($any) {
            throw new Error("User already exists.");
        }

        $user = [
            'email' => $email,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role ?? "basic"
        ];

        UserStorage::getContext()->add($user);
    }

    public static function getFavoriteTeamsForUser($userId){
        $favorites =  FavoriteStorage::getContext()->findAll(["userId" => $userId]);

         $result = [];

         foreach ($favorites as $f) {
             $result += [ ($f["teamId"]) => ($f["userId"]) ];
         }

         return $result;
    }

    public static function addFavoriteTeamForUser($userId, $teamId){
        $any = FavoriteStorage::getContext()->findOne(["userId" => $userId, "teamId" => $teamId]);
        if ($any) {
            throw new Error("Already in favorites.");
        }

        FavoriteStorage::getContext()->add(
            [
                "userId" => $userId,
                "teamId" => $teamId
            ]
        );
    }

    public static function removeFavoriteTeamForUser($userId, $teamId){
        $rec = FavoriteStorage::getContext()->findOne(["userId" => $userId, "teamId" => $teamId]);
        if ($rec) {
            FavoriteStorage::getContext()->delete($rec["id"]);
        }
    }
}
