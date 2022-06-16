<?php

include_once("lib/_init.php");

class LoginController
{

    public static function loggedInUser() {
        return isset($_SESSION["user_id"]) ? UserModel::findUserById($_SESSION["user_id"]) : NULL;
    }

    private static function loginUser($user) {
        $_SESSION["user_id"] = $user["id"];
    }

    private static function signOutCurrentUser() {
        unset($_SESSION["user_id"]);
    }

    public function loginForm() {
        $errors = get_flash_data("errors") ?? [];
        $old = get_flash_data("old") ?? [];

        if ($_POST) {
            function validate($post, &$data, &$errors) : bool
            {
                $username_error = validate_username($post["username"]);
                if ($username_error) { $errors["username"] = $username_error; }

                $password_error = validate_password($post["password"], true);
                if ($password_error) { $errors["password"] = $password_error; }

                if (!$username_error && !$password_error) {
                    $user = UserModel::findUserByUsername($post["username"]);

                    if (!password_verify($post["password"], $user["password"])) {
                        $errors['global'] = 'Hibás felhasználónév/jelszó.';
                    } else {
                        $data["user"] = $user;
                    }
                }

                return count($errors) === 0;
            } ;

            $errors = [];
            $data = [];
            if (validate($_POST, $data, $errors)) {
                self::signOutCurrentUser();
                self::loginUser($data["user"]);
                redirect(url("index"));
            } else {
                set_flash_data('errors', $errors);
                set_flash_data('old', $_POST);
            }
            redirect(url("login"));
        } else {
            $old["logged_in"] = self::loggedInUser();
        }

        include 'views/login.php';
    }

    public function registerForm() {
        $errors = get_flash_data("errors") ?? [];
        $old = get_flash_data("old") ?? [];

        if ($_POST) {
            function validate($post, &$data, &$errors): bool
            {
                $email_error = validate_email($post["email"]) ??
                    (UserModel::findUserByEmail($post["email"]) ? "Email foglalt." : NULL);
                if ($email_error) { $errors["email"] = $email_error; }
                else { $data["email"] = $post["email"]; }

                $username_error = validate_username($post["username"])  ??
                    (UserModel::findUserByUsername($post["username"]) ? "Felhasználónév foglalt." : NULL);
                if ($username_error) { $errors["username"] = $username_error; }
                else { $data["username"] = $post["username"]; }

                $password_error = validate_password($post["password"]);
                if ($password_error) { $errors["password"] = $password_error; }

                if (!$password_error && $post["password"] != $post["password_again"]) {
                    $errors['password_again'] = 'Megerősítő jelszó nem egyezik.';
                } else {
                    $data["password"] = $post["password"];
                }

                return count($errors) === 0;
            };

            $data = [];
            $errors = [];
            if (validate($_POST, $data, $errors)) {
                self::signOutCurrentUser();
                UserModel::addUser($data['email'], $data['username'], $data['password']);
                set_flash_data('old', ['username' => $data['username']]);
                redirect(url('login'));
            } else {
                set_flash_data('errors', $errors);
                set_flash_data('old', $_POST);
            }

            redirect(url("register"));
        }

        include 'views/register.php';
    }

    public function signOutAndRedirect() {
        self::signOutCurrentUser();
        redirect(url(''));
    }
}