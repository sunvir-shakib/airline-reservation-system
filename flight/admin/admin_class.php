<?php
session_start();
ini_set('display_errors', 1);

class Action {
  public $db; // make public so ajax.php can read old banner safely

  public function __construct() {
    ob_start();
    include 'db_connect.php';
    $this->db = $conn;
  }
  function __destruct() {
    $this->db->close();
    ob_end_flush();
  }

  /* AUTH */
  function login(){
    extract($_POST);
    $u = $this->db->real_escape_string($username);
    $p = $this->db->real_escape_string($password);
    $qry = $this->db->query("SELECT * FROM users WHERE username='$u' AND password='$p' ");
    if($qry && $qry->num_rows > 0){
      $row = $qry->fetch_array();
      foreach ($row as $key => $value) if($key !== 'password' && !is_numeric($key)) $_SESSION['login_'.$key] = $value;
      return 1;
    } return 3;
  }
  function login2(){
    extract($_POST);
    $u = $this->db->real_escape_string($email);
    $p = md5($password);
    $qry = $this->db->query("SELECT * FROM users WHERE username='$u' AND password='$p' ");
    if($qry && $qry->num_rows > 0){
      $row = $qry->fetch_array();
      foreach ($row as $key => $value) if($key !== 'password' && !is_numeric($key)) $_SESSION['login_'.$key] = $value;
      return 1;
    } return 3;
  }
  function logout(){ session_destroy(); foreach ($_SESSION as $k=>$v) unset($_SESSION[$k]); header("location:login.php"); }
  function logout2(){ session_destroy(); foreach ($_SESSION as $k=>$v) unset($_SESSION[$k]); header("location:../index.php"); }

  /* USERS */
  function save_user(){
    extract($_POST);
    $name=$this->db->real_escape_string($name);
    $username=$this->db->real_escape_string($username);
    $password=$this->db->real_escape_string($password);
    $type=(int)$type;
    $data=" name='$name', username='$username', password='$password', type='$type' ";
    if(empty($id)){ $save=$this->db->query("INSERT INTO users SET $data"); }
    else { $id=(int)$id; $save=$this->db->query("UPDATE users SET $data WHERE id=$id"); }
    if($save) return 1;
  }
  function signup(){
    extract($_POST);
    $name=$this->db->real_escape_string($name);
    $contact=$this->db->real_escape_string($contact);
    $address=$this->db->real_escape_string($address);
    $email=$this->db->real_escape_string($email);
    $password=md5($password);
    $chk=$this->db->query("SELECT * FROM users WHERE username='$email' ")->num_rows;
    if($chk>0) return 2;
    $data=" name='$name', contact='$contact', address='$address', username='$email', password='$password', type=3 ";
    $save=$this->db->query("INSERT INTO users SET $data");
    if($save){
      $qry=$this->db->query("SELECT * FROM users WHERE username='$email' AND password='$password' ");
      if($qry && $qry->num_rows>0){
        $row=$qry->fetch_array();
        foreach($row as $k=>$v) if($k!=='password' && !is_numeric($k)) $_SESSION['login_'.$k]=$v;
      }
      return 1;
    }
  }

  /* SETTINGS */
  function save_settings(){
    extract($_POST);
    $name=$this->db->real_escape_string(str_replace("'","&#x2019;",$name));
    $email=$this->db->real_escape_string($email);
    $contact=$this->db->real_escape_string($contact);
    $about=$this->db->real_escape_string(htmlentities(str_replace("'","&#x2019;",$about)));
    $data=" name='$name', email='$email', contact='$contact', about_content='$about' ";
    if(!empty($_FILES['img']['tmp_name'])){
      $fname=strtotime(date('y-m-d H:i')).'_'.basename($_FILES['img']['name']);
      if(move_uploaded_file($_FILES['img']['tmp_name'],'../assets/img/'.$fname)){
        $data.=", cover_img='$fname' ";
      }
    }
    $chk=$this->db->query("SELECT * FROM system_settings");
    if($chk && $chk->num_rows>0) $save=$this->db->query("UPDATE system_settings SET $data");
    else $save=$this->db->query("INSERT INTO system_settings SET $data");
    if($save){
      $q=$this->db->query("SELECT * FROM system_settings LIMIT 1")->fetch_array();
      foreach($q as $k=>$v) if(!is_numeric($k)) $_SESSION['setting_'.$k]=$v;
      return 1;
    }
  }

  /* AIRLINES / AIRPORTS */
  function save_airlines(){
    extract($_POST);
    $airlines=$this->db->real_escape_string($airlines);
    $data=" airlines='$airlines' ";
    if(!empty($_FILES['img']['tmp_name'])){
      $fname=strtotime(date("Y-m-d H:i"))."_".basename($_FILES['img']['name']);
      if(move_uploaded_file($_FILES['img']['tmp_name'], '../assets/img/'.$fname)){
        $data.=", logo_path='$fname' ";
      }
    }
    if(empty($id)) $save=$this->db->query("INSERT INTO airlines_list SET $data");
    else { $id=(int)$id; $save=$this->db->query("UPDATE airlines_list SET $data WHERE id=$id"); }
    if($save) return 1;
  }
  function delete_airlines(){ extract($_POST); $id=(int)$id; if($this->db->query("DELETE FROM airlines_list WHERE id=$id")) return 1; }
  function save_airports(){
    extract($_POST);
    $airport=$this->db->real_escape_string($airport);
    $location=$this->db->real_escape_string($location);
    $data=" airport='$airport', location='$location' ";
    if(empty($id)) $save=$this->db->query("INSERT INTO airport_list SET $data");
    else { $id=(int)$id; $save=$this->db->query("UPDATE airport_list SET $data WHERE id=$id"); }
    if($save) return 1;
  }
  function delete_airports(){ extract($_POST); $id=(int)$id; if($this->db->query("DELETE FROM airport_list WHERE id=$id")) return 1; }

  /* FLIGHTS */
  function save_flight(){
    // Allow both airline_id (new) and airline (old)
    if (isset($_POST['airline_id']) && !isset($_POST['airline'])) $_POST['airline']=$_POST['airline_id'];
    extract($_POST);

    $airline_id=(int)($airline ?? 0);
    $plane_no = isset($plane_no)?$this->db->real_escape_string($plane_no):'';
    $dep_id   =(int)($departure_airport_id ?? 0);
    $arr_id   =(int)($arrival_airport_id ?? 0);
    $dep_dt   = isset($departure_datetime)?$this->db->real_escape_string($departure_datetime):'';
    $arr_dt   = isset($arrival_datetime)?$this->db->real_escape_string($arrival_datetime):'';
    $seats    =(float)($seats ?? 0);
    $price    =(float)($price ?? 0);
    $banner   = isset($banner)?trim($banner):null;

    $fields=[];
    $fields[]="airline_id='$airline_id'";
    $fields[]="plane_no='$plane_no'";
    $fields[]="departure_airport_id='$dep_id'";
    $fields[]="arrival_airport_id='$arr_id'";
    $fields[]="departure_datetime='$dep_dt'";
    $fields[]="arrival_datetime='$arr_dt'";
    $fields[]="seats='$seats'";
    $fields[]="price='$price'";
    if ($banner !== null && $banner !== '') $fields[]="banner='".$this->db->real_escape_string($banner)."'";

    $data=implode(', ',$fields);

    if(empty($id)) $save=$this->db->query("INSERT INTO flight_list SET $data");
    else { $id=(int)$id; $save=$this->db->query("UPDATE flight_list SET $data WHERE id=$id"); }

    if($save) return 1;
  }

  function delete_flight(){ extract($_POST); $id=(int)$id; if($this->db->query("DELETE FROM flight_list WHERE id=$id")) return 1; }

  /* BOOKINGS */
 function book_flight(){
    // EXPECTS: flight_id, name[], address[], contact[], (optional) customer_id
    if (!isset($_POST['flight_id']) || !isset($_POST['name']) || !is_array($_POST['name'])) {
        return 0;
    }

    $flight_id   = (int)$_POST['flight_id'];
    $customer_id = isset($_POST['customer_id']) && $_POST['customer_id'] ? (int)$_POST['customer_id'] : null;

    // sanitize arrays (keep it compatible with the rest of the codebase)
    $names    = $_POST['name'];
    $addresses= isset($_POST['address']) ? $_POST['address'] : [];
    $contacts = isset($_POST['contact']) ? $_POST['contact'] : [];

    $qty = count($names);

    // start transaction
    $this->db->begin_transaction();

    try {
        // Lock flight row for update to avoid race conditions (overbooking)
        $fres = $this->db->query("SELECT id, seats FROM flight_list WHERE id = {$flight_id} FOR UPDATE");
        if (!$fres || !$fres->num_rows) {
            $this->db->rollback();
            return 0;
        }
        $frow  = $fres->fetch_assoc();
        $seats = (int)$frow['seats'];

        // Current booked count
        $bres = $this->db->query("SELECT COUNT(*) AS c FROM booked_flight WHERE flight_id = {$flight_id}");
        $brow = $bres->fetch_assoc();
        $booked_now = (int)$brow['c'];

        $available = $seats - $booked_now;

        if ($qty > $available) {
            // not enough seats
            $this->db->rollback();
            return 2; // signal "not enough seats"
        }

        // Insert each passenger
        for ($k = 0; $k < $qty; $k++) {
            $n = $this->db->real_escape_string($names[$k]);
            $a = isset($addresses[$k]) ? $this->db->real_escape_string($addresses[$k]) : '';
            $c = isset($contacts[$k])  ? $this->db->real_escape_string($contacts[$k])  : '';

        if ($customer_id) {
  $sql = "INSERT INTO booked_flight SET 
          flight_id = {$flight_id},
          customer_id = {$customer_id},
          name = '{$n}',
          address = '{$a}',
          contact = '{$c}'";
} else {
  $sql = "INSERT INTO booked_flight SET 
          flight_id = {$flight_id},
          name = '{$n}',
          address = '{$a}',
          contact = '{$c}'";
}


            if (!$this->db->query($sql)) {
                // any failure -> rollback
                $this->db->rollback();
                return 0;
            }
        }

        // all good
        $this->db->commit();
        return 1;

    } catch (Throwable $e) {
        // Safety rollback on any exception/error
        $this->db->rollback();
        return 0;
    }
}

}
