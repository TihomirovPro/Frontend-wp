<?php

$recepient = "email";
$sitename = "Sitename";

$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];

$message = "Имя: $name \nТелефон: $phone \nEmail: $email";

$pagetitle = "Новая заявка с сайта \"$sitename\"";
mail($recepient, $pagetitle, $message, "Content-type: text/plain; charset=\"utf-8\"\n From: $recepient");

// header('Location: thanks.html');