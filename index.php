<?php
/**
 * Точка входа
 *
 * @author Sergej Rufov <rufov@freeun.ru>
 * @author Bezdelnik12 <nik.shmelev.24@gmail.com>
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

// region CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Content-Type: application/json; charset=utf-8");
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS");
    header("Access-Control-Allow-Headers: Authorization, Content-Type");
    header("Access-Control-Max-Age: 1728000");
    header("Content-Length: 0");
    die();
}
// endregion

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/functions.php';

// Определяем метод запроса
$method = $_SERVER['REQUEST_METHOD'];

// Получаем данные из тела запроса
$form_data = getFormData($method);

$q = isset($_GET['q']) ? trim(htmlspecialchars($_GET['q'])): 'main';
if (isset($_GET['q']) && $_GET['q'] == 'main') $q = '404';

if (is_file(__DIR__.'/modules/' . $q . '.php')) {
    require_once __DIR__.'/modules/' . $q . '.php';
} else {
    require_once __DIR__.'/modules/404.php';
}
