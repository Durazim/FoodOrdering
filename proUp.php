if($_FILES['profile']['size'] == 0 && $_FILES['profile']['error'] == 0){
  $profile = 'profile.png';
}
else {
  //type problem
  if (($_FILES["profile"]["size"] < 1024000)) {
    if ($_FILES["profile"]["error"] > 0) {
      $flag = false;
      $error = "Error file";
    }
    else {
        $_FILES["profile"]["name"] = $uname;
        move_uploaded_file($_FILES["profile"]["tmp_name"], "img/" . $_FILES["profile"]["name"]);
      }
    }
  else {
    $flag = false;
  }
}
