<?php
session_start();
include 'admin/db_connect.php';

$fid = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if(!$fid){
  echo '<div class="max-w-3xl mx-auto p-6 text-red-600">Invalid flight.</div>';
  return;
}

// Load flight + airline
$fq = $conn->query("
  SELECT f.*, a.airlines, a.logo_path,
         (SELECT airport FROM airport_list WHERE id=f.departure_airport_id) AS dep_airport,
         (SELECT location FROM airport_list WHERE id=f.departure_airport_id) AS dep_city,
         (SELECT airport FROM airport_list WHERE id=f.arrival_airport_id) AS arr_airport,
         (SELECT location FROM airport_list WHERE id=f.arrival_airport_id) AS arr_city
  FROM flight_list f
  INNER JOIN airlines_list a ON a.id=f.airline_id
  WHERE f.id = {$fid}
  LIMIT 1
");
if(!$fq || !$fq->num_rows){
  echo '<div class="max-w-3xl mx-auto p-6 text-red-600">Flight not found.</div>';
  return;
}
$flight = $fq->fetch_assoc();

// Compute available seats
$booked = $conn->query("SELECT id FROM booked_flight WHERE flight_id={$fid}")->num_rows;
$available = (int)$flight['seats'] - (int)$booked;
if($available < 0) $available = 0;

?>
<section class="py-12 bg-slate-50 min-h-screen">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white border rounded-2xl shadow-sm p-6 md:p-8">
      <div class="flex items-start gap-4">
        <img src="assets/img/<?php echo $flight['logo_path']; ?>" class="w-14 h-14 object-contain" alt="Airline">
        <div>
          <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($flight['airlines']); ?></h1>
          <div class="text-sm text-slate-600">
            <?php echo $flight['dep_city'].' ('.$flight['dep_airport'].')'; ?> →
            <?php echo $flight['arr_city'].' ('.$flight['arr_airport'].')'; ?>
          </div>
          <div class="text-sm text-slate-600 mt-1">
            🛫 <?php echo date('M d, Y h:i A', strtotime($flight['departure_datetime'])); ?> &nbsp; | &nbsp;
            🛬 <?php echo date('M d, Y h:i A', strtotime($flight['arrival_datetime'])); ?>
          </div>
        </div>
        <div class="ms-auto text-right">
          <div class="text-xs text-slate-500">Price</div>
          <div class="text-xl font-bold">$<?php echo number_format($flight['price'],2); ?></div>
          <div class="text-xs mt-1 <?php echo $available>0?'text-green-600':'text-red-600'; ?>">
            <?php echo $available>0 ? $available.' seats left' : 'Sold out'; ?>
          </div>
        </div>
      </div>

      <?php if(!isset($_SESSION['customer_id'])): ?>
        <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-md">
          <div class="font-semibold text-amber-800">Please log in to book</div>
          <p class="text-amber-800/90 text-sm mt-1">Log in to save your booking and manage it later.</p>
          <a href="index.php?page=login" class="inline-block mt-3 bg-brand text-white px-4 py-2 rounded-md hover:bg-brandDark">Login</a>
          <a href="index.php?page=signup" class="inline-block mt-3 ms-2 border border-brand text-brand px-4 py-2 rounded-md hover:bg-brand/10">Sign up</a>
        </div>
      <?php endif; ?>

      <hr class="my-6">

      <?php if($available > 0): ?>
      <form id="book-flight" class="space-y-6">
        <input type="hidden" name="flight_id" value="<?php echo $fid; ?>">

        <div class="grid sm:grid-cols-3 gap-4 items-end">
          <div>
            <label class="text-sm font-semibold text-slate-700">Persons</label>
            <input type="number" id="count" min="1" max="<?php echo $available; ?>" value="1"
                   class="mt-1 w-full border rounded-md p-2 focus:ring-2 focus:ring-brand text-slate-900">
            <p class="text-xs text-slate-500 mt-1">Max: <?php echo $available; ?></p>
          </div>
          <div class="sm:col-span-2">
            <button type="button" id="go"
              class="w-full sm:w-auto inline-flex items-center bg-brand hover:bg-brandDark text-white font-semibold py-2.5 px-4 rounded-md">
              Add Passenger Details
            </button>
          </div>
        </div>

        <div id="passenger-fields" class="space-y-4 hidden"></div>

        <div id="actions" class="hidden flex items-center gap-3">
          <button class="bg-brand hover:bg-brandDark text-white font-semibold py-2.5 px-4 rounded-md" type="submit">
            Save Booking
          </button>
          <a href="index.php?page=flights" class="border border-slate-300 text-slate-700 font-semibold py-2.5 px-4 rounded-md hover:bg-slate-50">
            Cancel
          </a>
        </div>
      </form>
      <?php else: ?>
        <div class="p-4 text-red-600">No seats available for this flight.</div>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
(function(){
  const goBtn = document.getElementById('go');
  const countEl = document.getElementById('count');
  const fieldsWrap = document.getElementById('passenger-fields');
  const actions = document.getElementById('actions');

  if(goBtn){
    goBtn.addEventListener('click', function(){
      const c = parseInt(countEl.value || '1', 10);
      const max = parseInt(countEl.getAttribute('max'), 10);
      if(c < 1 || c > max){
        alert("The number of persons must be between 1 and " + max + ".");
        return;
      }
      fieldsWrap.innerHTML = '';
      for(let i=0;i<c;i++){
        fieldsWrap.insertAdjacentHTML('beforeend', `
          <div class="grid md:grid-cols-3 gap-4 border rounded-xl p-4">
            <div>
              <label class="text-sm font-semibold text-slate-700">Full Name</label>
              <input type="text" name="name[]" required class="mt-1 w-full border rounded-md p-2 focus:ring-2 focus:ring-brand">
            </div>
            <div>
              <label class="text-sm font-semibold text-slate-700">Address</label>
              <input type="text" name="address[]" class="mt-1 w-full border rounded-md p-2 focus:ring-2 focus:ring-brand">
            </div>
            <div>
              <label class="text-sm font-semibold text-slate-700">Contact</label>
              <input type="text" name="contact[]" class="mt-1 w-full border rounded-md p-2 focus:ring-2 focus:ring-brand">
            </div>
          </div>
        `);
      }
      fieldsWrap.classList.remove('hidden');
      actions.classList.remove('hidden');
    });
  }

  const form = document.getElementById('book-flight');
  if(form){
    form.addEventListener('submit', function(e){
      e.preventDefault();
      const fd = new FormData(form);

      // attach logged in customer id if available
      <?php $cid = isset($_SESSION['customer_id']) ? (int)$_SESSION['customer_id'] : 0; ?>
      fd.append('customer_id', '<?php echo $cid; ?>');

      fetch('admin/ajax.php?action=book_flight', {
        method: 'POST',
        body: fd
      }).then(r => r.text()).then(resp => {
        if(resp == '1'){
          alert("Flight successfully booked.");
          window.location.href = 'index.php?page=my_bookings';
        }else if(resp == '2'){
          alert("Not enough seats available for this flight.");
        }else{
          alert("Booking failed. Please try again.");
        }
      }).catch(() => alert("Network error."));
    });
  }
})();
</script>
