<?php
/**
 * Основные функции
 *
 * @author Sergej Rufov <rufov@freeun.ru>
 * @author Bezdelnik12 <nik.shmelev.24@gmail.com>
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Получение данных из тела запроса
 *
 * @param string $method Метод запроса
 * @return array
 */
function getFormData($method) {

    // GET или POST: данные возвращаем как есть
    if ($method === 'GET') return $_GET;
    if ($method === 'POST') return $_POST;

    // PUT, PATCH или DELETE
    $data = array();
    $exploded = explode('&', file_get_contents('php://input'));

    foreach ($exploded as $pair) {
        $item = explode('=', $pair);
        if (count($item) == 2) {
            $data[urldecode($item[0])] = urldecode($item[1]);
        }
    }

    return $data;
}

/**
 * Успешный запрос
 *
 * @param array $data Тело ответа
 * @return void
 */
function ok($data)
{
    response(200, [
        'status' => true,
        'result' => $data
    ]);
}

/**
 * Успешный запрос создания записи
 *
 * @param array $data Тело ответа
 * @return void
 */
function create($data)
{
    response(201, [
        'status' => true,
        'result' => $data
    ]);
}

/**
 * Метод запроса не поддерживается
 *
 * @return void
 */
function allowed()
{
    response(405, [
        'status' => false,
        'error' => [
            'code' => 405,
            'msg' => 'Method Not Allowed',
        ]
    ]);
}

/**
 * Обработка ошибок
 *
 * @param int $code Код ошибки
 * @param string $m Сообщение ошибки
 * @return void
 */
function error($code, $m)
{
    response($code, [
        'status' => false,
        'error' => [
            'code' => $code,
            'msg' => $m,
        ]
    ]);
}

/**
 * Вывод запроса
 *
 * @param int $code Код запроса
 * @param array $data Тело запроса
 * @return void
 */
function response($code, $data)
{
    http_response_code($code);
    echo json_encode($data);
    exit();
}

/**
 * Получить токен
 *
 * @param int $id Идентификатор пользователя
 * @return string
 */
function get_token($id)
{
    $time = time() + 3600;
    return base64_encode(json_encode(array(
        't' => $time,
        'id' => $id
    )));
}

/**
 * Проверка пользователя
 *
 * @return mixed
 */
function valid_token()
{
    $access_token = isset($_REQUEST['access_token']) ? $_REQUEST['access_token'] : null;
    $access_token = base64_decode($access_token);
    $access_token = json_decode($access_token, true);
    if (is_null($access_token) || time() >= $access_token['t']) error(401, 'Unauthorized');
    return $access_token['id'];
}
