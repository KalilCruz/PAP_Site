<?php
// Encripta a senha fornecida com SHA-1
$hashed_password = sha1("111");
$hashed_password1 = sha1("abc");
$hashed_password2 = sha1("Mm.12345");
echo $hashed_password;
?>