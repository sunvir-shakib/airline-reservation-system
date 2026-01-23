
<?php
// session_start();
include('admin/db_connect.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $qry = $conn->query("SELECT * FROM customer_users WHERE email='$email' LIMIT 1");
    if($qry->num_rows > 0){
        $user = $qry->fetch_assoc();
        if(password_verify($password, $user['password'])){
            $_SESSION['customer_id'] = $user['id'];
            $_SESSION['customer_name'] = $user['name'];
            header('Location: index.php?page=my_bookings');
            exit;
        }
    }
    header('Location: index.php?page=login&err=1');
    exit;
}
?>


<section class="min-h-screen flex items-center justify-center bg-slate-50">
  <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-4">Customer Login</h2>
    <!-- <?php if($msg): ?><p class="text-red-600 text-center mb-3"><?php echo $msg; ?></p><?php endif; ?> -->
    <form method="POST" action="auth.php?do=login">
      <div class="mb-4">
        <label class="block text-sm font-medium text-slate-600">Email</label>
        <input type="email" name="email" required class="mt-1 w-full border rounded-md p-2 focus:ring-2 focus:ring-brand">
      </div>
      <div class="mb-6">
        <label class="block text-sm font-medium text-slate-600">Password</label>
        <input type="password" name="password" required class="mt-1 w-full border rounded-md p-2 focus:ring-2 focus:ring-brand">
      </div>
      <button type="submit" class="w-full bg-brand hover:bg-brandDark text-white py-2 rounded-md font-semibold">Login</button>
      <p class="text-sm text-center mt-4">Don’t have an account? <a href="index.php?page=signup" class="text-brand font-semibold hover:underline">Sign up</a></p>
    </form>
  </div>
</section>
