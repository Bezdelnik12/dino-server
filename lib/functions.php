<?php

// Получение данных из тела запроса
function getFormData($method) {

    // GET или POST: данные возвращаем как есть
    if ($method === 'GET') return $_GET;
    if ($method === 'POST') return $_POST;

    // PUT, PATCH или DELETE
    $data = array();
    $exploded = explode('&', file_get_contents('php://input'));

    foreach($exploded as $pair) {
        $item = explode('=', $pair);
        if (count($item) == 2) {
            $data[urldecode($item[0])] = urldecode($item[1]);
        }
    }

    return $data;
}

function ok($data) {
    return response(200, [
        'status' => true,
        'result' => $data
    ]);
}

function create($data) {
    return response(201, [
        'status' => true,
        'result' => $data
    ]);
}

function allowed() {
    return response(405, [
        'status' => false,
        'error' => [
            'code' => 405,
            'msg' => 'Method Not Allowed',
        ]
    ]);
}

function error($code, $m) {
    return response($code, [
        'status' => false,
        'error' => [
            'code' => $code,
            'msg' => $m,
        ]
    ]);
}

function response($code, $data) {
    http_response_code($code);
    echo json_encode($data);
    exit();
}
