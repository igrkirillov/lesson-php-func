<?php

declare(strict_types = 1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;

global $items;
global $operationNumber;
global $operations;

$items = [];
$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
];

function clear(): void
{
    system('clear');
//    system('cls'); // windows
}

function requestOperation(): void
{
    global $operations;
    global $operationNumber;

    $userInput = NULL;
    while ($userInput === NULL) {
        echo 'Выберите операцию для выполнения: ' . PHP_EOL;
        // Проверить, есть ли товары в списке? Если нет, то не отображать пункт про удаление товаров
        echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
        $userInput = trim(fgets(STDIN));
        if (!array_key_exists($userInput, $operations)) {
            clear();
            $userInput = NULL;
            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
        }
    }
    $operationNumber = $userInput;
}

function closeProgram(): void
{
    global $operationNumber;

    showBasket();
}

function addItem(): void
{
    global $items;

    echo "Введение название товара для добавления в список: \n> ";
    $itemName = trim(fgets(STDIN));
    $items[] = $itemName;
}

function deleteItem(): void
{
    global $items;

    // Проверить, есть ли товары в списке? Если нет, то сказать об этом и попросить ввести другую операцию
    if (count($items) === 0) {
        echo 'Пустой список товаров. Удаление товара невозможно.' . PHP_EOL;
        return;
    }

    showBasket();

    echo 'Введение название товара для удаления из списка:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));

    if (in_array($itemName, $items, true) !== false) {
        while (($key = array_search($itemName, $items, true)) !== false) {
            unset($items[$key]);
        }
    }
}

function showBasket(): void
{
    global $items;

    if (count($items)) {
        echo 'Ваш список покупок: ' . PHP_EOL;
        echo implode("\n", $items) . "\n";
        echo 'Всего ' . count($items) . ' позиций. '. PHP_EOL;
    } else {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
    }
}


do {
    clear();
    showBasket();
    requestOperation();

    echo 'Выбрана операция: '  . $operations[$operationNumber] . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD:
            addItem();
            break;

        case OPERATION_DELETE:
            deleteItem();
            break;

        case OPERATION_PRINT:
            showBasket();
            echo 'Нажмите enter для продолжения';
            fgets(STDIN);
            break;

        case OPERATION_EXIT:
            closeProgram();
    }
    echo "\n ----- \n";

} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;