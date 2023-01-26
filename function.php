<?php

function getFullnameFromParts($surname, $name, $patronymic) { // функция склейки полного Имени из 3 вводных
    $fullname_str = $surname . ' ' . $name . ' ' . $patronymic;
    return $fullname_str;
}

function getPartsFromFullname($fullname_str) { // функция возврата массива из 1 строки
    list ($fullname['surname'], $fullname['name'], $fullname['patronymic']) = explode(' ', $fullname_str);
    return $fullname;
 }
 
 function getShortName($fullname_str) { // функция сокращения Имени из 1 строки
    $fullname = getPartsFromFullname($fullname_str);
    $cutSurname = mb_substr($fullname['surname'], 0, 1);
    $cutNS_str = $fullname['name'] . ' ' . $cutSurname . '.';
    return $cutNS_str;
 }

 function getGenderFromName($fullname_str) { // функция опеределения пола из строки
    $fullname = getPartsFromFullname($fullname_str);

    $i = 0;
    $patronymicEnd = mb_substr($fullname['patronymic'], -3, 3);
    $nameEnd = mb_substr($fullname['name'], -1, 1);
    $surnameEndFemale = mb_substr($fullname['surname'], -2, 2);
    $surnameEndMale = mb_substr($fullname['surname'], -1, 1);

    if($patronymicEnd == 'вна') $i--;
    if($patronymicEnd == 'вич') $i++;
    if($nameEnd == 'а') $i--;
    if($nameEnd == 'й' || $nameEnd == 'н') $i++;
    if($surnameEndFemale == 'ва') $i--;
    elseif($surnameEndMale == 'в') $i++;
    
    if($i == 0) return 0;
    elseif($i > 0) return 1;
    else return -1;
}

function getGenderDescription($example_persons_array) { // Функция определения полового состава аудитории
    foreach ($example_persons_array as $names) { 
        $gender[] = getGenderFromName($names['fullname']);
    }

    function undefined ($gender) {
        return $gender == 0;
    }
    function male ($gender) {
        return $gender > 0;
    }
    function female ($gender) {
        return $gender < 0;
    }
    
    $undefined = count(array_filter($gender, 'undefined'));
    $male = count(array_filter($gender, 'male'));
    $female = count(array_filter($gender, 'female'));
    $total = count($example_persons_array);
    
    $malePercent = round($male*100/$total, 1);
    $femalePercent = round($female*100/$total, 1);
    $undefinedPercent = round($undefined*100/$total, 1);


    $genderComposition = <<<SOSTAV
    Гендерный состав аудитории: <br>
    -------------------------------------- <br>
    Мужчины - $malePercent % <br>
    Женщины - $femalePercent % <br>
    Не удалось определить - $undefinedPercent % <br>
SOSTAV;

    echo $genderComposition;
}

// функция для подбора «идеальной» пары

function getPerfectPartner($nameReg, $surnameReg, $patronymicReg, $example_persons_array) {
    $name = mb_convert_case($nameReg, MB_CASE_TITLE);
    $surname = mb_convert_case($surnameReg, MB_CASE_TITLE);
    $patronymic = mb_convert_case($patronymicReg, MB_CASE_TITLE);

    $fullname1 = getFullnameFromParts($name, $surname, $patronymic);
    $gend1 = getGenderFromName($fullname1);
    
    $person1 = getShortName($fullname1);
    if ($gend1 == 0) echo "♡ Для $person1 не удалось подобрать пару! ♡"; 
    else {
    do {
        $k = rand(0, count($example_persons_array) - 1);
        $fullname2 = $example_persons_array[$k]['fullname'];
        $gend2 = getGenderFromName($fullname2);
    } while ($gend1 !== -$gend2);

    $person1 = getShortName($fullname1);
    $person2 = mb_convert_case(getShortName($fullname2), MB_CASE_TITLE);

    $compatibility = round(rand(5000, 10000)/100, 2);
    echo "$person1 + $person2 = <br>
    ♡ Идеально на $compatibility % ♡";
}
}
?>