<?php

if ($c=OCILogon("ora_w1d8", "a35861111", "ug")) {
  echo "Successfully connected to Oracle.\n";
  OCILogoff($c);
} else {
  $err = OCIError();
  echo "Oracle Connect Error " . $err['message'];
}

?>
<html>
</html>

