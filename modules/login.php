<?php
/**
 * Вход на сайт
 *
 * @author Sergej Rufov <rufov@freeun.ru>
 * @author Bezdelnik12 <nik.shmelev.24@gmail.com>
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/** @var $method string */
/** @var $pdo PDO */

if ( $method != 'POST' ) allowed();

$login = isset($_POST['login']) ? trim(htmlspecialchars($_POST['login'])): null;
$password = isset($_POST['password']) ? trim(htmlspecialchars($_POST['password'])): null;

if (!$login || !$password) error(403, 'Invalid params');

$password = md5($password);

$sql = <<<_SQL
SELECT * FROM users WHERE login = '{$login}' AND password = '{$password}'
_SQL;

$st = $pdo->query($sql);
$user = $st->fetch();

if ( !$user ) error(401, 'Unauthorized');

ok(array(
    'user_id' => $user['id'],
    'login' => $user['login'],
    'access_token' => get_token($user['id'])
));
