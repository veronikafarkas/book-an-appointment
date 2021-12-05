<?php
include_once('datastorage.php');
include_once('userstorage.php');
include_once('auth.php');

// functions
function validate($post, &$data, &$errors) {
    //date
    if (!isset($post['date'])) {
        $errors['date'] = 'Dátum megadása kötelező!';
    }else if (trim($post['date']) === '') {
        $errors['date'] = 'Dátum megadása kötelező!';
    }else {

        $test_date = $post['date'];
        $test_arr  = explode('-', $test_date);
        if (checkdate($test_arr[1], $test_arr[2], $test_arr[0])) {
            $data['date'] = $post['date'];
        }
        else{
            $errors['date'] = 'Rossz formátumú dátum!';
        }
        $dateim = implode('. ', $test_arr);
        $date = $dateim . ".";
        $data['date'] = $date;
    }

    //time
    if (!isset($post['time'])) {
        $errors['time'] = 'Idő megadása kötelező!';
    }else if (trim($post['time']) === '') {
        $errors['time'] = 'Idő megadása kötelező!';
    }else if(preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $post['time'])) {
        $data['time'] = $post['time'];
    }else{
        $errors['time'] = 'Rossz formátumú idő!';
    }

    //total
    if (!isset($post['total'])) {
        $errors['total'] = 'Helyek számának megadása kötelező!';
    }
    else if (trim($post['total']) === '') {
        $errors['total'] = 'Helyek számának megadása kötelező!';
    }
    else if (!filter_var($post['total'], FILTER_VALIDATE_INT)) {
        $errors['total'] = 'Helyek száma rossz számformátumú!';
    } else{
        $total = (int)$post['total'];
        if ($total < 1 || $total > 20) {
            $errors['total'] = 'A helyek számának 1 és 20 között kell lennie!';
        } else {
            $data['total'] = $total;
        }
    }

    $data['taken'] = 0;
    $data['users'] = [];

  return count($errors) === 0;
}

function redirect($page) {
    header("Location: ${page}");
    exit();
}

$user_storage = new UserStorage();
$auth = new Auth($user_storage);
$dataStorage = new DataStorage();
$errors = [];
$data = [];
if (count($_POST) > 0) {
  if (validate($_POST, $data, $errors)) {

    $dates = $dataStorage->findAll(['date' => $data['date']]);
    $letezik = 0;

    if(!is_null($dates))
    {
        foreach($dates as $date)
        {
            $foundTime = explode(':', $date['time']);
            $dataTime = explode(':', $data['time']);
    
            if(($foundTime[0] == $dataTime[0]) && ($foundTime[1] == $dataTime[1]))
            {
                $errors['global'] = "Ez az időpont már létezik!";
                $letezik = 1;
                break;
            }
        }
        if($letezik == 0)
        {
            $dataStorage->add($data);
            redirect("index.php");
        }
    }else{
        $dataStorage->add($data);
        redirect("index.php");
    }
  }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NemKoViD - Új időpont felvétele</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="all">
    <div class="title"><h1>Nemzeti Koronavírus Depó</h1>
    <h3>NemKoViD - Mondj nemet a koronavírusra!</h3>
    </div>
    <p><h3>Új időpont felvétele</h3></p>

    <?php if (isset($errors['global'])) : ?>
    <p><span class="error"><?= $errors['global'] ?></span></p>
    <?php endif; ?>
    <form action="" method="post" novalidate>
    <div>
        <label for="date">Dátum: </label>
        <span class="redspan">*</span><br>
        <input type="date" name="date" id="date" value="<?= $_POST['date'] ?? "" ?>">
        <?php if (isset($errors['date'])) : ?>
        <span class="error"><?= $errors['date'] ?></span>
        <?php endif; ?>
    </div>

    <div>
        <label for="time">Időpont: </label>
        <span class="redspan">*</span><br>
        <input type="text" name="time" id="time" value="<?= $_POST['time'] ?? "" ?>">
        <?php if (isset($errors['time'])) : ?>
        <span class="error"><?= $errors['time'] ?></span>
        <?php endif; ?>
    </div>

    <div>
        <label for="total">Helyek száma: </label>
        <span class="redspan">*</span><br>
        <input type="number" name="total" id="total" min="1" max="20" value="<?= $_POST['total'] ?? "" ?>">
        <?php if (isset($errors['total'])) : ?>
        <span class="error"><?= $errors['total'] ?></span>
        <?php endif; ?>
    </div>

    <br>
    <small><span class="redspan">*</span>-gal jelölt mezők kitöltése kötelező!</small>
    <br><br>
    <div>
        <button type="submit">Mentés</button><br><br>
    </div>
    </form>
        </div>
</body>
</html>