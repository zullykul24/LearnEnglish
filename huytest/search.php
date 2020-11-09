
<?php
require 'db.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #navigation{
            display:fixed;
            
            text-align: center;
            background-color: gray;    
            min-height: 50px;    
        }
    </style>

    

</head>
<body>
<?php   include('../huytest/index.php'); ?>
    <div id="navigation">
        <form action="result.php" method="get">
        <input type="text" id="ahihi" name="search">
        <input type="submit" value="Tim kiem">
    </div>

 
</body>
</html>