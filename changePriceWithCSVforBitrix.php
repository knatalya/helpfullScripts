<?
use Bitrix\Main\Loader;
use Bitrix\Catalog\Model\Price;

Loader::includeModule('catalog');
Loader::includeModule('iblock');

$filePath = $_SERVER["DOCUMENT_ROOT"]."/price.csv";
if (!file_exists($filePath)) {
    die("CSV файл не найден");
}

$handle = fopen($filePath, "r");
if ($handle === false) {
    die("Ошибка при открытии CSV файла");
}

// Чтение CSV файла построчно
while (($data = fgetcsv($handle, 200000, ",")) !== false) {
    $article = $data[0];
    $price = $data[1];

    // Удаление пробелов и замена запятой на точку
    $price = str_replace([' ', ','], ['', '.'], $price);

    // Удаление любых оставшихся нечисловых символов
    $price = preg_replace('/[^0-9.]/', '', $price);

    // Проверка, что цена является числом
    if (!is_numeric($price)) {
        echo 'Неверный формат цены для продукта с CML2_ARTICLE: ' . $article . '. Пропуск.<br>';
        continue;
    }

    // Преобразование цены в float
    $price = floatval($price);

    // Поиск продукта по CML2_ARTICLE
    $productRes = CIBlockElement::GetList(
        [],
        ['IBLOCK_ID' => IBLOCK_ID, 'PROPERTY_CML2_ARTICLE' => $article],
        false,
        false,
        ['ID']
    );

    if ($product = $productRes->Fetch()) {
        // Проверка, существует ли цена
        $priceRes = CPrice::GetList(
            [],
            [
                'PRODUCT_ID' => $product['ID'],
                'CATALOG_GROUP_ID' => 1 // ID группы цен, обычно 1 - базовая цена
            ]
        );

        if ($existingPrice = $priceRes->Fetch()) {
            // Обновление существующей цены
            $result = Price::update($existingPrice['ID'], ['PRICE' => $price]);
        } else {
            // Добавление новой цены
            $result = Price::add([
                'PRODUCT_ID' => $product['ID'],
                'CATALOG_GROUP_ID' => 1,
                'PRICE' => $price,
                'CURRENCY' => 'RUB'
            ]);
        }

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            echo 'Ошибка при обновлении цены для продукта с CML2_ARTICLE: ' . $article . '. Ошибки: ' . implode(', ', $errors) . '<br>';
        } else {
            echo 'Цена обновлена/добавлена для продукта с CML2_ARTICLE: ' . $article . '<br>';
        }
    } else {
        echo 'Продукт с CML2_ARTICLE: ' . $article . ' не найден<br>';
    }
}

fclose($handle);
