<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
if($action == "save_airlines"){
	$save = $crud->save_airlines();
	if($save)
		echo $save;
}
if($action == "delete_airlines"){
	$save = $crud->delete_airlines();
	if($save)
		echo $save;
}
if($action == "save_airports"){
	$save = $crud->save_airports();
	if($save)
		echo $save;
}
if($action == "delete_airports"){
	$save = $crud->delete_airports();
	if($save)
		echo $save;
}
if ($action == "save_flight") {
    // ---- Route banner resolve & upload ----
    $targetDir = __DIR__ . '/../assets/img/routes/';
    if (!is_dir($targetDir)) { @mkdir($targetDir, 0775, true); }

    $finalBanner = null;
    $givenText   = isset($_POST['banner']) ? trim($_POST['banner']) : '';
    $idEdit      = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    // (A) uploaded file
    if (!empty($_FILES['banner_file']['name']) && is_uploaded_file($_FILES['banner_file']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['banner_file']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        if (in_array($ext, $allowed, true)) {
            $newName = 'route_' . date('Ymd_His') . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
            if (move_uploaded_file($_FILES['banner_file']['tmp_name'], $targetDir.$newName)) {
                $finalBanner = $newName; // store filename in DB
            }
        }
    }

    // (B) text field (URL or filename)
    if (!$finalBanner && $givenText !== '') {
        if (preg_match('#^https?://#i', $givenText)) {
            $finalBanner = $givenText; // full URL
        } else {
            $candidate = $targetDir . basename($givenText);
            if (is_file($candidate)) { $finalBanner = basename($givenText); } // filename
        }
    }

    // (C) EDIT mode: preserve old banner if nothing new provided
    if (!$finalBanner && $idEdit > 0) {
        // safe read using the crud instance's db
        $old = $crud->db->query("SELECT banner FROM flight_list WHERE id=".$idEdit);
        if ($old && $old->num_rows) {
            $row = $old->fetch_assoc();
            if (!empty($row['banner'])) { $finalBanner = $row['banner']; }
        }
    }

    // (D) forward to class save method
    $_POST['banner'] = $finalBanner ?? null;

    $save = $crud->save_flight();
    if($save) echo $save;
}



if($action == "delete_flight"){
	$save = $crud->delete_flight();
	if($save)
		echo $save;
}
if($action == "set_appointment"){
	$save = $crud->set_appointment();
	if($save)
		echo $save;
}
if($action == "delete_appointment"){
	$save = $crud->delete_appointment();
	if($save)
		echo $save;
}
if($action == "update_appointment"){
	$save = $crud->update_appointment();
	if($save)
		echo $save;
}
if($action == "book_flight"){
	$save = $crud->book_flight();
	if($save)
		echo $save;
}

if($action == "update_booked"){
	$save = $crud->update_booked();
	if($save)
		echo $save;
}


