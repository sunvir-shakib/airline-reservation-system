<?php
// session_start();
include 'admin/db_connect.php';

if(!isset($_SESSION['customer_id'])){
    header('Location: index.php?page=login');
    exit;
}

$uid = $_SESSION['customer_id'];
$q = $conn->query("
  SELECT b.*, f.plane_no, f.price, f.departure_datetime, f.arrival_datetime,
         (SELECT airport FROM airport_list WHERE id=f.departure_airport_id) AS dep_airport,
         (SELECT location FROM airport_list WHERE id=f.departure_airport_id) AS dep_city,
         (SELECT airport FROM airport_list WHERE id=f.arrival_airport_id) AS arr_airport,
         (SELECT location FROM airport_list WHERE id=f.arrival_airport_id) AS arr_city
  FROM booked_flight b
  INNER JOIN flight_list f ON f.id = b.flight_id
  WHERE b.customer_id = $uid
  ORDER BY f.departure_datetime DESC
");

?>
<section class="py-16 bg-slate-50 min-h-screen">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold mb-6">My Booked Flights</h2>
    <?php if($q && $q->num_rows): ?>
    <div class="overflow-x-auto bg-white border rounded-xl shadow-sm">
      <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50">
          <tr class="text-left text-xs font-semibold text-slate-600 uppercase">
            <th class="px-4 py-3">Flight</th>
            <th class="px-4 py-3">From</th>
            <th class="px-4 py-3">To</th>
            <th class="px-4 py-3">Departure</th>
            <th class="px-4 py-3">Arrival</th>
            <th class="px-4 py-3 text-right">Price</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
        <?php while($row = $q->fetch_assoc()): ?>
          <tr>
            <td class="px-4 py-3 font-medium"><?php echo $row['plane_no'] ?></td>
            <td class="px-4 py-3"><?php echo $row['dep_city'].' ('.$row['dep_airport'].')' ?></td>
            <td class="px-4 py-3"><?php echo $row['arr_city'].' ('.$row['arr_airport'].')' ?></td>
            <td class="px-4 py-3"><?php echo date('M d, Y h:i A', strtotime($row['departure_datetime'])) ?></td>
            <td class="px-4 py-3"><?php echo date('M d, Y h:i A', strtotime($row['arrival_datetime'])) ?></td>
            <td class="px-4 py-3 text-right font-semibold">$<?php echo number_format($row['price'],2) ?></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
      <p class="text-slate-600 mt-4">You have not booked any flights yet.</p>
    <?php endif; ?>
  </div>
</section>
