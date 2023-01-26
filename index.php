<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP</title>
</head>

    <body>
        <?php 
            include 'array.php'; 
            include 'function.php';
            $i = rand (0, count($example_persons_array) - 1);
            list ($fullname_arr['surname'], $fullname_arr['name'], $fullname_arr['patronymic']) = explode(' ', $example_persons_array[$i]['fullname']);

            $fullname_str = getFullnameFromParts($fullname_arr['surname'], $fullname_arr['name'], $fullname_arr['patronymic']); 
            $fullname = getPartsFromFullname($fullname_str);

            getShortName($fullname_str);
            $gender = getGenderFromName($fullname_str);
        ?>
        <hr>
        <div align='center'>
            <?php
                getGenderDescription($example_persons_array);
            ?>
            <hr>
            <?php
                getPerfectPartner($fullname['surname'], $fullname['name'], $fullname['patronymic'], $example_persons_array);
            ?>
        </div>
    </body>
</html>