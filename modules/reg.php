<?php
/**
 * Регистрация
 *
 * @author Sergej Rufov <rufov@freeun.ru>
 * @author Bezdelnik12 <nik.shmelev.24@gmail.com>
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/** @var $method string */
/** @var $pdo PDO */

if ( $method != 'POST' ) allowed();

$login = isset($_POST['login']) ? trim(htmlspecialchars($_POST['login'])): null;
$email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])): null;
$password = isset($_POST['password']) ? trim(htmlspecialchars($_POST['password'])): null;

if (!$login || !$email || !$password) error(403, 'Invalid params');

$password = md5($password);

$sql = <<<_SQL
INSERT INTO `users`(
    `login`,
    `email`,
    `password`
) VALUES (
    '{$login}',
    '{$email}',
    '{$password}'
);
_SQL;

$pdo->exec($sql);
$id = $pdo->lastInsertId();

if ( $id == 0 ) error(403, 'Login is busy');

ok(array(
    'user_id' => $id,
    'access_token' => get_token($id)
));
