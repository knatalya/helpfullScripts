<?
// ID вашего инфоблока
$IBLOCK_ID = IBLOCK_ID;

// Получение всех разделов инфоблока
$arFilter = [
    'IBLOCK_ID' => $IBLOCK_ID,
    'ACTIVE' => 'Y'
];

$arSelect = ['ID', 'CODE', 'NAME'];
$rsSections = CIBlockSection::GetList([], $arFilter, false, $arSelect);

$emptySections = [];
$baseUrl = "https://test.ru/catalog/";

while ($arSection = $rsSections->Fetch()) {
    $sectionId = $arSection['ID'];

    // Проверка наличия товаров в разделе
    $arElementFilter = [
        'IBLOCK_ID' => $IBLOCK_ID,
        'SECTION_ID' => $sectionId,
        'INCLUDE_SUBSECTIONS' => 'Y',
        'ACTIVE' => 'Y'
    ];

    $rsElements = CIBlockElement::GetList([], $arElementFilter, false, ['nTopCount' => 1], ['ID']);
    if (!$rsElements->SelectedRowsCount()) {
        $emptySections[] = $baseUrl . $arSection['CODE'] . "/";
    }
}

// Вывод списка пустых разделов
foreach ($emptySections as $sectionUrl) {
    echo $sectionUrl . "\n";
}