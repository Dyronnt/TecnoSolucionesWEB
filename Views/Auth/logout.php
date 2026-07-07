<?php
session_unset();
session_destroy();
header('Location: index.php?controller=auth&action=login');
exit;