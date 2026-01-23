<?php
// home.php (partial)
include 'admin/db_connect.php';
?>

<!-- HERO -->
<header class="relative grid place-items-center min-h-[78vh] text-white">
    <div class="absolute inset-0 bg-[url('assets/img/hero_airport.jpg')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/45 to-black/60"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 py-14 text-center">
        <h1 class="text-4xl md:text-6xl font-extrabold tracking-wide2 uppercase leading-tight">
            BOOK YOUR AIR<br class="hidden md:block">TICKET EASY & FAST
        </h1>
        <p class="mt-5 text-base md:text-lg text-slate-100">
            Stop Searching. Start Traveling. Register with us to enjoy amazing discounts on bookings!
        </p>
        <div class="mt-7 flex flex-wrap items-center justify-center gap-4">
            <a href="#about" class="inline-block bg-brand hover:bg-brandDark text-white font-bold py-3 px-6 rounded-md">
                ABOUT US
            </a>
            <a href="index.php?page=contact" class="inline-block border border-brand text-white font-bold py-3 px-6 rounded-md hover:bg-brand hover:border-brand">
                CONTACT US
            </a>
        </div>
    </div>
    <!-- welcome message -->
    <?php if (isset($_SESSION['customer_name'])): ?>
        <p class="mt-4 text-white font-medium text-lg">
            Welcome back, <?php echo htmlspecialchars($_SESSION['customer_name']); ?> 👋
        </p>
    <?php endif; ?>


    <!-- SEARCH CARD floating -->
    <div class="absolute bottom-0 translate-y-1/2 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-xl p-5 md:p-6">
                <form id="manage-filter" action="index.php?page=flights" method="POST"
                    class="grid grid-cols-1 md:grid-cols-5 gap-4" aria-label="Flight search form">
                    <!-- From -->
                    <div>
                        <label class="text-sm font-semibold text-slate-600">From</label>
                        <select name="departure_airport_id" id="departure_location"
                            class="mt-1 w-full border rounded-md p-2 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand">
                            <option value=""></option>
                            <?php
                            $airport = $conn->query("SELECT * FROM airport_list ORDER BY airport ASC");
                            while ($row = $airport->fetch_assoc()):
                            ?>
                                <option value="<?php echo $row['id'] ?>">
                                    <?php echo $row['location'] . ', ' . $row['airport'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- To -->
                    <div>
                        <label class="text-sm font-semibold text-slate-600">To</label>
                        <select name="arrival_airport_id" id="arrival_airport_id"
                            class="mt-1 w-full border rounded-md p-2 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand">
                            <option value=""></option>
                            <?php
                            $airport = $conn->query("SELECT * FROM airport_list ORDER BY airport ASC");
                            while ($row = $airport->fetch_assoc()):
                            ?>
                                <option value="<?php echo $row['id'] ?>">
                                    <?php echo $row['location'] . ', ' . $row['airport'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Departure -->
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Departure</label>
                        <input type="date" name="date"
                            class="mt-1 w-full border rounded-md p-2 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand"
                            min="<?php echo date('Y-m-d'); ?>" />
                    </div>

                    <!-- Return -->
                    <div id="rdate" class="hidden">
                        <label class="text-sm font-semibold text-slate-600">Return</label>
                        <input type="date" name="date_return"
                            class="mt-1 w-full border rounded-md p-2 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand"
                            min="<?php echo date('Y-m-d'); ?>" />
                    </div>

                    <!-- Trip + Button -->
                    <div class="flex flex-col justify-end">
                        <label class="text-sm font-semibold text-slate-600 mb-2">Trip Type</label>
                        <div class="flex items-center justify-between gap-3 bg-slate-100 rounded-lg px-3 py-2">
                            <label class="inline-flex items-center gap-2 text-sm cursor-pointer">
                                <input type="radio" name="trip" value="1" checked class="accent-brand h-4 w-4">
                                <span class="text-slate-700 font-medium">One-way</span>
                            </label>
                            <label class="inline-flex items-center gap-2 text-sm cursor-pointer">
                                <input type="radio" name="trip" value="2" class="accent-brand h-4 w-4">
                                <span class="text-slate-700 font-medium">Round trip</span>
                            </label>
                        </div>
                        <button class="mt-4 inline-flex justify-center items-center bg-brand hover:bg-brandDark text-white font-semibold py-2.5 px-4 rounded-md w-full transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9L2 14v2l8-2.5V19l-2 1v1l3.5-.5L15 21v-1l-2-1v-5.5l8 2.5Z" />
                            </svg>
                            Find Flights
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Spacer under floating search -->
<div class="h-28"></div>

<!-- WHY BOOK WITH US -->
<section id="features" class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold">Why Book With Us</h2>
            <p class="text-slate-600 mt-2">Fast booking, wide coverage, and 24/7 support.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <div class="w-14 h-14 rounded-xl grid place-items-center bg-white border shadow text-xl">⚡</div>
                <h5 class="mt-4 font-semibold">Fast & Easy Booking</h5>
                <p class="text-slate-600 text-sm mt-1">Search in a flash and complete payment seamlessly.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <div class="w-14 h-14 rounded-xl grid place-items-center bg-white border shadow text-xl">🌍</div>
                <h5 class="mt-4 font-semibold">Anytime, Anywhere</h5>
                <p class="text-slate-600 text-sm mt-1">Fly to destinations around the world easily.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <div class="w-14 h-14 rounded-xl grid place-items-center bg-white border shadow text-xl">🎧</div>
                <h5 class="mt-4 font-semibold">24/7 Support</h5>
                <p class="text-slate-600 text-sm mt-1">We’re here for your questions and concerns anytime.</p>
            </div>
        </div>
    </div>
</section>

<!-- ABOUT / WE ARE B AIRWAYS  -->
<section id="about" class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
            <div class="rounded-2xl overflow-hidden bg-white border shadow-sm">
                <img src="assets/img/about_plane.jpg" alt="Airplane at sunset"
                    class="w-full h-[340px] md:h-[380px] object-cover">
            </div>
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">We Are B Airways</h2>
                <div class="mt-3 h-1.5 w-24 bg-brand rounded"></div>
                <p class="mt-6 text-slate-600 leading-relaxed">
                    B Airways is an independent functioning airline, subsidiary of Virgin Airlines.
                    We began flying in 2017 and currently serve more than 10 destinations across the globe.
                    Within the past three years we’ve taken more than 50,000 passengers on journeys of a lifetime.
                    Come see what you’ve been missing—our team goes the extra mile to make your experience the best.
                    Last year B Airways received the “Most Outstanding Service” Award by BIA-Colombo.
                </p>
                <a href="index.php?page=about" class="inline-block mt-8 bg-brand hover:bg-brandDark text-white font-semibold py-3 px-6 rounded-md">
                    DISCOVER MORE
                </a>
            </div>
        </div>
    </div>
</section>

<!-- UPCOMING FLIGHTS -->
<section id="flights" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold">Upcoming Flights</h2>
            <p class="text-slate-600 mt-2">Book early & save more. New routes added often.</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $flights = $conn->query("SELECT f.*,
          (SELECT airport FROM airport_list WHERE id=f.departure_airport_id) AS dep_airport,
          (SELECT location FROM airport_list WHERE id=f.departure_airport_id) AS dep_city,
          (SELECT airport FROM airport_list WHERE id=f.arrival_airport_id) AS arr_airport,
          (SELECT location FROM airport_list WHERE id=f.arrival_airport_id) AS arr_city
        FROM flight_list f ORDER BY departure_datetime ASC LIMIT 6");
            if ($flights && $flights->num_rows):
                while ($f = $flights->fetch_assoc()):
            ?>
                    <div class="rounded-2xl border overflow-hidden shadow-sm">
                        <?php
                        $banner = trim($f['banner'] ?? '');
                        if ($banner && preg_match('#^https?://#i', $banner)) {
                            $img_src = $banner;
                        } else {
                            $img_src = "assets/img/routes/" . ($banner ?: "sample.jpg");
                        }
                        ?>
                        <img src="<?php echo htmlspecialchars($img_src, ENT_QUOTES); ?>" alt="Route" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <div class="text-xs text-slate-500">
                                From <span class="font-bold text-slate-800">$<?php echo number_format($f['price']); ?></span>
                            </div>
                            <h5 class="mt-1 mb-2 font-semibold">
                                <?php echo $f['dep_city'] . ' (' . $f['dep_airport'] . ')'; ?> →
                                <?php echo $f['arr_city'] . ' (' . $f['arr_airport'] . ')'; ?>
                            </h5>
                            <ul class="text-xs text-slate-500 space-y-1 mb-3">
                                <li>🕒 <?php echo date('M d, Y – h:i A', strtotime($f['departure_datetime'])); ?></li>
                            </ul>
                            <a href="index.php?page=book&id=<?php echo $f['id']; ?>" class="inline-flex justify-center items-center w-full bg-brand hover:bg-brandDark text-white font-medium py-2 rounded-md">Book</a>
                        </div>
                    </div>
                <?php endwhile;
            else: ?>
                <div class="col-span-3">
                    <div class="border rounded-md p-4 text-slate-600">No flights to show yet.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- PARTNER AIRLINES -->
<section id="partners" class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold">Partner Airlines</h2>
            <p class="text-slate-600 mt-2">We work with top carriers worldwide.</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            $cats = $conn->query("SELECT * FROM airlines_list ORDER BY RAND() ASC");
            while ($row = $cats->fetch_assoc()):
            ?>
                <div class="bg-white border rounded-2xl p-4 shadow-sm flex flex-col items-center justify-between">
                    <img class="h-14 object-contain" src="assets/img/<?php echo $row['logo_path'] ?>" alt="<?php echo htmlspecialchars($row['airlines']) ?> logo" />
                    <a class="mt-3 inline-flex justify-center items-center w-full bg-brand hover:bg-brandDark text-white text-sm font-medium py-2 rounded-md"
                        href="index.php?page=flights&airline_id=<?php echo $row['id']; ?>">
                        Find Flights
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<!-- HAPPY PASSENGERS – HERO IMAGE -->
<section class="relative">
    <!-- swap the image path to your own -->
    <img
        src="assets/img/runway_banner.jpg"
        alt="Runway with aircraft"
        class="w-full h-[420px] md:h-[520px] object-cover" />
</section>
<!-- HAPPY PASSENGERS – TITLE + BLURB -->
<section id="reviews" class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">
            Our Happy Passengers
        </h2>
        <div class="mt-3 h-1.5 w-20 bg-brand rounded mx-auto"></div>

        <p class="mt-6 text-slate-600 max-w-3xl mx-auto leading-relaxed">
            Are you a registered customer of B Airways? We’re happy to hear your feedback.
            Visit our review page and share your experience with us.
        </p>

        <!-- Optional CTA; change the link if you have a review page -->
        <a href="index.php?page=contact"
            class="inline-block mt-6 bg-brand hover:bg-brandDark text-white font-semibold py-3 px-6 rounded-md">
            Leave a Review
        </a>
    </div>
</section>
<!-- OPTIONAL: SAMPLE TESTIMONIAL CARDS -->
<section class="pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-3 gap-6">
        <div class="bg-slate-50 border rounded-2xl p-6 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand/10 grid place-items-center text-brand text-lg">😊</div>
                <div class="font-semibold">Ayesha K.</div>
            </div>
            <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                Super smooth booking and on-time flight. Will book again!
            </p>
        </div>

        <div class="bg-slate-50 border rounded-2xl p-6 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand/10 grid place-items-center text-brand text-lg">✈️</div>
                <div class="font-semibold">Rahul S.</div>
            </div>
            <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                Great deals and friendly support. Loved the experience.
            </p>
        </div>

        <div class="bg-slate-50 border rounded-2xl p-6 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand/10 grid place-items-center text-brand text-lg">🌍</div>
                <div class="font-semibold">Mina D.</div>
            </div>
            <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                Found flights easily and the process was quick.
            </p>
        </div>
    </div>
</section>


<!-- Toggle Return date (no jQuery) -->
<script>
    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'trip') {
            const r = document.getElementById('rdate');
            if (r) r.classList.toggle('hidden', e.target.value !== '2');
        }
    });
</script>