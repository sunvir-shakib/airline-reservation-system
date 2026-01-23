<?php
// session_start();
include 'admin/db_connect.php';

$msg = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $exists = $conn->query("SELECT id FROM customer_users WHERE email='$email'");
    if($exists->num_rows){
        $msg = "Email already registered.";
    } else {
        $conn->query("INSERT INTO customer_users(name,email,password) VALUES('$name','$email','$password')");
        $msg = "Account created successfully. You can now log in.";
    }
}
?>

<section class="min-h-screen flex items-center justify-center bg-slate-50">
  <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-4">Create an Account</h2>
    <?php if($msg): ?><p class="text-green-600 text-center mb-3"><?php echo $msg; ?></p><?php endif; ?>
    <form method="POST">
      <div class="mb-4">
        <label class="block text-sm font-medium text-slate-600">Full Name</label>
        <input type="text" name="name" required class="mt-1 w-full border rounded-md p-2 focus:ring-2 focus:ring-brand">
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium text-slate-600">Email</label>
        <input type="email" name="email" required class="mt-1 w-full border rounded-md p-2 focus:ring-2 focus:ring-brand">
      </div>
      <div class="mb-6">
        <label class="block text-sm font-medium text-slate-600">Password</label>
        <input type="password" name="password" required class="mt-1 w-full border rounded-md p-2 focus:ring-2 focus:ring-brand">
      </div>
      <button type="submit" class="w-full bg-brand hover:bg-brandDark text-white py-2 rounded-md font-semibold">Sign Up</button>
      <p class="text-sm text-center mt-4">Already have an account? <a href="index.php?page=login" class="text-brand font-semibold hover:underline">Login</a></p>
    </form>
  </div>
</section>
