<?php
require('../Controllers/uploadController.class.php');
$uploads = new uploadController();
$uploads->removeFile($_POST['id']);
echo json_encode($uploads->getResult());

