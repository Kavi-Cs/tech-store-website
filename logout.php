<?php
session_start(); // Session එක ආරම්භ කිරීම
session_unset(); // Session විචල්‍යයන් සියල්ල හිස් කිරීම
session_destroy(); // Session එක සම්පූර්ණයෙන්ම විනාශ කිරීම

// මුල් පිටුවට හරවා යැවීම
header("Location: index.php");
exit();
?>