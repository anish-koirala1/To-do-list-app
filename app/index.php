<?php

// If the web server document root is set to the project root, this file
// redirects requests into the app/public front controller.
header("Location: app/public/index.php");
exit;
