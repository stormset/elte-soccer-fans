<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php

include_once 'utils/is_empty.php';

function validate_int($value, $min, $max = NULL): ?string
{
    if (is_empty($value) || $value == "") {
        return 'Hiányzó érték.';
    }
    if (filter_var($value, FILTER_VALIDATE_INT) === false) {
        return 'Nem egész szám.';
    }
    if (((int)$value) < $min) {
        return 'Alsó határ: ' . $min . ". A szám túl kicsi.";
    }
    if ($max !== NULL && $max > ((int)$value)) {
        return 'Felső határ: ' . $max . ". A szám túl nagy.";
    }

    return NULL;
}

function validate_username($value, $for_login = false): ?string
{
    if (!isset($value) || $value == "") {
        return 'Hiányzó felhasználónév.';
    }
    if (preg_match('/[ \'^£$%&*()}{@#~?><>,|=_+¬-]/', $value))
    {
        return 'A felhasználónév nem tartalmazhat szóközt és speciális karaktereket.';
    }

    return NULL;
}

function validate_email($value, $for_login = false): ?string
{
    if (!isset($value) || $value == "") {
        return 'Hiányzó email.';
    }
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return 'Érvénytelen email cím.';
    }

    return NULL;
}

function validate_password($value, $for_login = false): ?string
{
    if (!isset($value) || $value == "") {
        return 'Hiányzó jelszó.';
    }
    if (!$for_login && strlen($value) < 5) {
        return 'A jelszó legalább 6 karakterből kell, hogy álljon.';
    }

    return NULL;
}

function validate_comment($value, $minLength, $maxLength): ?string
{
    if (!isset($value)) {
        return 'Hiányzó komment mező.';
    }
    if (strlen($value) < $minLength) {
        return 'Túl rövid hozzászólás. Leaglább ' . $minLength . " karakter megadása szükséges." ;
    }
    if (strlen($value) > $maxLength) {
        return 'Túl hosszú hozzászólás. Legfeljebb ' . $maxLength . " karakter megadása lehetséges." ;
    }

    return NULL;
}

// date expected in format: xxxx-xx-xx
function validate_date($value): ?string
{
    $parts  = explode('-', $value);
    if (!isset($value) || $value == "" || count($parts) !== 3) {
        return 'Hiányzó dátum.';
    }

    if (!checkdate($parts[1], $parts[2], $parts[0])) {
        return 'Érvénytelen dátum.';
    }

    if ((int)$parts[0] < 2018) {
        return 'Év legalább 2018 (alapítás).';
    }

    return NULL;
}
