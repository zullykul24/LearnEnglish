<?php
$password = password_hash("12345678",PASSWORD_DEFAULT);
if( password_verify('$2y$10$wmHPIvx73Pjexd5TiSBBquRnUh79SBmfIGboS.sI6N/O3BRU93uSW',$password))
echo 'sadas';
else echo'sdsdadsa';
?>