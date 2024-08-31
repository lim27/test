<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Loader;
use CIBlockSection;
use CIBlockElement;

Loader::includeModule('iblock');

// Включение вывода ошибок для отладки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

function fetchData($url) {
    $response = file_get_contents($url);
    if ($response === FALSE) {
        throw new Exception("Error fetching data from $url");
    }
    return json_decode($response, true);
}

try {
    
    $urls = [
        'https://reqres.in/api/users?page=1',
        'https://reqres.in/api/users?page=2'
    ];

    foreach ($urls as $index => $url) {
        
        $data = fetchData($url);

        if (isset($data['data']) && is_array($data['data'])) {
            // Создание раздела
            $section = new CIBlockSection;
            $sectionFields = [
                "NAME" => "Page " . ($index + 1),
                "IBLOCK_ID" => 14, 
                "ACTIVE" => "Y"
            ];
            $sectionID = $section->Add($sectionFields);
            //var_dump($sectionID);
            if ($sectionID) {
                echo "Раздел успешно создан. ID: " . $sectionID;
                foreach ($data['data'] as $user) {
                    var_dump($user['first_name']);
                    $PROPS = array();
	                $PROPS['Email'] = $user['email'];
                    $PROPS['FirstName'] = $user['first_name'];
                    $PROPS['LastName'] = $user['last_name'];
                    $PROPS['Avatar'] = $user['avatar'];
                    var_dump($PROPS);
                    // Создание элемента
                    $el = new CIBlockElement;
                    $elementFields = [
                        "NAME" => $user['first_name'] . ' ' . $user['last_name'],
                        "CODE" => $user['id'],
                        "IBLOCK_ID" => 14, 
                        "IBLOCK_SECTION" => $sectionID,
                        "PROPERTY_VALUES" => $PROPS,
                    ]; 
                    $result = $el->Add($elementFields);

                    if (!$result) {
                        throw new Exception("Error adding element: " . $el->LAST_ERROR);
                    }
                }
            } else {
                echo "Ошибка создания раздела: " . $section->LAST_ERROR;
            }
            
        }
    }

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>