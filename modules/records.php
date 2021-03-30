<?php
/**
 * Рейтинг
 *
 * @author Sergej Rufov <rufov@freeun.ru>
 * @author Bezdelnik12 <nik.shmelev.24@gmail.com>
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/** @var $method string */
/** @var $form_data array */
/** @var $pdo PDO */

$user_id = valid_token();

if ($method == 'GET') {
    $sql = <<<_SQL
SELECT * FROM records ORDER BY record_count DESC LIMIT 0, 10
_SQL;

    $st = $pdo->query($sql);
    $records = [];
    $i = 0;
    if ( !$st ) ok();
    while ($row = $st->fetch()) {
        $records[$i]['id'] = $row['id'];
        $records[$i]['user_id'] = $row['user_id'];

        $sql = <<<_SQL
SELECT * FROM users WHERE id = '{$records[$i]['user_id']}'
_SQL;

        $st = $pdo->query($sql);
        $user = $st->fetch();

        $records[$i]['login'] = $user['login'];

        $records[$i]['record_count'] = $row['record_count'];
        $i++;
    }
    ok($records);
} elseif ($method == 'POST') {
    $record_count = isset($_POST['count']) ? trim(htmlspecialchars($_POST['count'])): null;

    $sql = <<<_SQL
SELECT * FROM records WHERE user_id = {$user_id} ORDER BY record_count DESC
_SQL;

    $st = $pdo->query($sql);
    $records = $st->fetch();
    $records['count'] = isset($records['count']) ? $records['count']: null;
    if ($record_count > $records['count']) {
        $sql = <<<_SQL
INSERT INTO `records`(
    `user_id`,
    `record_count`
) VALUES (
    '{$user_id}',
    '{$record_count}'
);
_SQL;

        $pdo->exec($sql);
        create();
    }
}
