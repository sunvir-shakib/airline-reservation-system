<?php 
include 'admin/db_connect.php'; 

// collect search values (if POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($_POST as $k => $v) $$k = $v;
}
?>

<section class="py-12 bg-slate-50 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- PAGE TITLE -->
    <div class="text-center mb-10">
      <h2 class="text-3xl md:text-4xl font-bold">Find Flights</h2>
      <p class="text-slate-600 mt-2">Search and book available flights easily</p>
    </div>

    <!-- SEARCH FORM -->
    <div class="bg-white border rounded-2xl shadow-sm p-6 mb-10">
      <form id="manage-filter" action="index.php?page=flights" method="POST" class="grid md:grid-cols-5 gap-4">
        <!-- From -->
        <div>
          <label class="text-sm font-semibold text-slate-600">From</label>
          <select name="departure_airport_id" id="departure_location"
            class="mt-1 w-full border rounded-md p-2 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand">
            <option value="">Select</option>
            <?php
            $airport = $conn->query("SELECT * FROM airport_list ORDER BY airport ASC");
            while ($row = $airport->fetch_assoc()):
            ?>
              <option value="<?php echo $row['id']; ?>" <?php echo isset($departure_airport_id) && $departure_airport_id == $row['id'] ? "selected" : ""; ?>>
                <?php echo $row['location'] . ', ' . $row['airport']; ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- To -->
        <div>
          <label class="text-sm font-semibold text-slate-600">To</label>
          <select name="arrival_airport_id" id="arrival_airport_id"
            class="mt-1 w-full border rounded-md p-2 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand">
            <option value="">Select</option>
            <?php
            $airport = $conn->query("SELECT * FROM airport_list ORDER BY airport ASC");
            while ($row = $airport->fetch_assoc()):
            ?>
              <option value="<?php echo $row['id']; ?>" <?php echo isset($arrival_airport_id) && $arrival_airport_id == $row['id'] ? "selected" : ""; ?>>
                <?php echo $row['location'] . ', ' . $row['airport']; ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Departure -->
        <div>
          <label class="text-sm font-semibold text-slate-600">Departure</label>
          <input type="date" name="date"
            value="<?php echo isset($date) ? htmlspecialchars($date) : ''; ?>"
            min="<?php echo date('Y-m-d'); ?>"
            class="mt-1 w-full border rounded-md p-2 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand" />
        </div>

        <!-- Return -->
        <div id="rdate" <?php if (isset($trip) && $trip == 1) echo 'class="hidden"'; ?>>
          <label class="text-sm font-semibold text-slate-600">Return</label>
          <input type="date" name="date_return"
            value="<?php echo isset($date_return) ? htmlspecialchars($date_return) : ''; ?>"
            min="<?php echo date('Y-m-d'); ?>"
            class="mt-1 w-full border rounded-md p-2 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand" />
        </div>

        <!-- Trip type + submit -->
        <div class="flex flex-col justify-end">
          <label class="text-sm font-semibold text-slate-600 mb-2">Trip</label>
          <div class="flex items-center justify-between gap-2 bg-slate-100 rounded-md px-3 py-2">
            <label class="inline-flex items-center gap-1 text-sm">
              <input type="radio" name="trip" value="1" class="accent-brand" <?php echo (!isset($trip) || $trip == 1) ? 'checked' : ''; ?>>
              One-way
            </label>
            <label class="inline-flex items-center gap-1 text-sm">
              <input type="radio" name="trip" value="2" class="accent-brand" <?php echo (isset($trip) && $trip == 2) ? 'checked' : ''; ?>>
              Round trip
            </label>
          </div>
          <button class="mt-4 bg-brand hover:bg-brandDark text-white font-semibold py-2.5 rounded-md">
            Find Flights
          </button>
        </div>
      </form>
    </div>

    <!-- AVAILABLE FLIGHTS -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php
      $airport = $conn->query("SELECT * FROM airport_list");
      while ($row = $airport->fetch_assoc()) {
        $aname[$row['id']] = ucwords($row['airport'] . ', ' . $row['location']);
      }

      $where = " WHERE date(f.departure_datetime) >= CURDATE() ";
      if ($_SERVER['REQUEST_METHOD'] === "POST" && !empty($departure_airport_id) && !empty($arrival_airport_id) && !empty($date)) {
        $where .= " AND f.departure_airport_id = '$departure_airport_id' AND f.arrival_airport_id = '$arrival_airport_id' AND DATE(f.departure_datetime) = '" . date('Y-m-d', strtotime($date)) . "'";
      }

      $qry = $conn->query("SELECT f.*, a.airlines, a.logo_path 
                            FROM flight_list f 
                            INNER JOIN airlines_list a ON f.airline_id = a.id
                            $where 
                            ORDER BY f.departure_datetime ASC");

      if ($qry && $qry->num_rows > 0):
        while ($f = $qry->fetch_assoc()):
          $booked = $conn->query("SELECT id FROM booked_flight WHERE flight_id = " . $f['id'])->num_rows;
          $available = (int)$f['seats'] - (int)$booked;
      ?>
      <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        <div class="p-4">
          <div class="flex items-center justify-between">
            <img src="assets/img/<?php echo $f['logo_path']; ?>" alt="Airline" class="h-10 object-contain">
            <div class="text-right">
              <div class="text-xs text-slate-500">From</div>
              <div class="text-lg font-bold">$<?php echo number_format($f['price'],2); ?></div>
            </div>
          </div>
          <div class="mt-3 text-slate-800 font-semibold">
            <?php echo $aname[$f['departure_airport_id']]; ?> → <?php echo $aname[$f['arrival_airport_id']]; ?>
          </div>
          <div class="text-xs text-slate-500 mt-1">
            🛫 <?php echo date('M d, Y h:i A', strtotime($f['departure_datetime'])); ?><br>
            🛬 <?php echo date('M d, Y h:i A', strtotime($f['arrival_datetime'])); ?>
          </div>
          <div class="mt-3 text-sm <?php echo $available>0 ? 'text-green-600' : 'text-red-600'; ?>">
            <?php echo $available>0 ? $available.' seats left' : 'Sold out'; ?>
          </div>
          <a href="index.php?page=book&id=<?php echo $f['id']; ?>"
             class="inline-block mt-4 w-full bg-brand hover:bg-brandDark text-white font-semibold py-2 rounded-md text-center">
            Book
          </a>
        </div>
      </div>
      <?php endwhile; else: ?>
        <div class="col-span-3 text-center text-slate-600">
          No flights found matching your search.
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
document.addEventListener('change', e=>{
  if(e.target && e.target.name==='trip'){
    document.getElementById('rdate').classList.toggle('hidden', e.target.value !== '2');
  }
});
</script>
