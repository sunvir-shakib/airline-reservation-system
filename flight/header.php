<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title><?php echo $_SESSION['setting_name'] ?? 'Airline'; ?></title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />

  <!-- Fonts & Icons -->
  <script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic&display=swap" rel="stylesheet" />

  <!-- Vendor CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
  <link href="admin/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
  <link href="admin/assets/css/select2.min.css" rel="stylesheet" />

  <!-- Your legacy/theme CSS (keep if you still need it) -->
  <link href="css/styles.css" rel="stylesheet" />

  <!-- Tailwind via CDN (site-wide) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { brand: '#ff5b2e', brandDark: '#e24f28' },
          letterSpacing: { wide2: '.06em' }
        }
      }
    };
  </script>

  <!-- jQuery (must be before Select2/datepicker JS) -->
  <script src="admin/assets/vendor/jquery/jquery.min.js"></script>
  <!-- Vendor JS -->
  <script src="admin/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
  <script src="admin/assets/js/select2.min.js"></script>

  <!-- Small fixes so Select2/datepicker look right with Tailwind -->
  <style>
    /* Ensure form text is readable on white */
    select, input, .select2-container .select2-selection--single,
    .select2-dropdown, .datepicker { color:#0f172a; } /* slate-900 */
    .select2-container--default .select2-selection--single {
      height: calc(1.5em + .75rem + 2px);
      padding: .375rem .75rem;
      border: 1px solid #cbd5e1; /* slate-300 */
      border-radius: .375rem;     /* rounded-md */
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 2.25rem; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 2.25rem; }
    .select2-dropdown { border-color:#cbd5e1; }
    /* Make dropdowns sit above Tailwind cards */
    .select2-container, .select2-dropdown { z-index: 1055; }
  </style>
</head>
