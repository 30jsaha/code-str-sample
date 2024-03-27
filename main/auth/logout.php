<?php
unset($_SESSION[CLIENT_ID]);
unset($_SESSION[LOGGEDIN]);
unset($_SESSION[USER_ID]);
unset($_SESSION[RID]);
unset($_SESSION[USERNAME]);
unset($_SESSION[USER_TYPE]);
unset($_SESSION[EMPLOYEE_ID]);

header('location:'.HOST_URL);
?>