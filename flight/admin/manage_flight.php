<?php include 'db_connect.php' ?>
<?php 
if(isset($_GET['id'])){
  $qry = $conn->query("SELECT * FROM flight_list WHERE id=".(int)$_GET['id']);
  foreach($qry->fetch_array() as $k => $val){ $$k = $val; }
}
$banner_val = isset($banner) ? trim($banner) : '';
if ($banner_val) {
  $bannerSrc = preg_match('#^https?://#i',$banner_val) ? $banner_val : '../assets/img/routes/'.$banner_val;
} else {
  $bannerSrc = '../assets/img/routes/sample.jpg';
}
?>
<div class="container-fluid">
  <div class="col-lg-12">
    <form id="manage-flight" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? (int)$_GET['id'] : '' ?>">

      <div class="row">
        <div class="col-md-8">
          <div class="form-group">
            <label class="control-label">Airline</label>
            <select name="airline_id" id="airline_id" class="custom-select browser-default select2">
              <option></option>
              <?php 
                $airline = $conn->query("SELECT * FROM airlines_list ORDER BY airlines ASC");
                while($row = $airline->fetch_assoc()):
              ?>
              <option value="<?php echo $row['id'] ?>" <?php echo isset($airline_id) && (int)$airline_id === (int)$row['id'] ? "selected" : '' ?>>
                <?php echo $row['airlines'] ?>
              </option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>
      </div>

      <div class="row"><div class="col-md-8">
        <div class="form-group">
          <label>Plane No</label>
          <textarea name="plane_no" cols="30" rows="2" class="form-control"><?php echo isset($plane_no) ? htmlspecialchars($plane_no) : '' ?></textarea>
        </div>
      </div></div>

      <div class="row form-group">
        <div class="col-md-6">
          <label>Departure Location</label>
          <select name="departure_airport_id" id="departure_location" class="custom-select browser-default select2">
            <option value=""></option>
            <?php $airport = $conn->query("SELECT * FROM airport_list ORDER BY airport ASC");
              while($row = $airport->fetch_assoc()): ?>
              <option value="<?php echo $row['id'] ?>" <?php echo isset($departure_airport_id) && (int)$departure_airport_id === (int)$row['id'] ? "selected" : '' ?>>
                <?php echo $row['location'].", ".$row['airport'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label>Arrival Location</label>
          <select name="arrival_airport_id" id="arrival_airport_id" class="custom-select browser-default select2">
            <option value=""></option>
            <?php $airport = $conn->query("SELECT * FROM airport_list ORDER BY airport ASC");
              while($row = $airport->fetch_assoc()): ?>
              <option value="<?php echo $row['id'] ?>" <?php echo isset($arrival_airport_id) && (int)$arrival_airport_id === (int)$row['id'] ? "selected" : '' ?>>
                <?php echo $row['location'].", ".$row['airport'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>

      <div class="row form-group">
        <div class="col-md-6">
          <label>Departure Date/Time</label>
          <input type="text" name="departure_datetime" id="departure_datetime"
                 class="form-control datetimepicker"
                 value="<?php echo isset($departure_datetime) ? date('Y-m-d H:i', strtotime($departure_datetime)) : '' ?>">
        </div>
        <div class="col-md-6">
          <label>Arrival Date/Time</label>
          <input type="text" name="arrival_datetime" id="arrival_datetime"
                 class="form-control datetimepicker"
                 value="<?php echo isset($arrival_datetime) ? date('Y-m-d H:i', strtotime($arrival_datetime)) : '' ?>">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <label>Seats</label>
          <input name="seats" id="seats" type="number" step="any" class="form-control text-right"
                 value="<?php echo isset($seats) ? (float)$seats : '' ?>">
        </div>
        <div class="col-md-6">
          <label>Price</label>
          <input name="price" id="price" type="number" step="any" class="form-control text-right"
                 value="<?php echo isset($price) ? (float)$price : '' ?>">
        </div>
      </div>

      <hr>
      <!-- Route Banner -->
      <div class="row">
        <div class="col-md-8">
          <div class="form-group">
            <label class="font-weight-bold d-block">Route Banner</label>
            <div class="mb-2">
              <img id="bannerPreview" src="<?php echo htmlspecialchars($bannerSrc); ?>"
                   class="img-fluid border rounded" style="max-height:180px;object-fit:cover;">
            </div>
            <input type="file" name="banner_file" accept="image/*" class="form-control-file" onchange="previewBanner(this)">
            <small class="text-muted d-block mt-2">Or enter an existing filename / full image URL:</small>
            <input type="text" name="banner" value="<?php echo htmlspecialchars($banner_val); ?>"
                   class="form-control" placeholder="e.g. dhaka_london.jpg or https://…/image.jpg">
            <small class="text-muted d-block mt-1">* Filenames are read from <code>assets/img/routes/</code>.</small>
          </div>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-8">
          <button type="submit" class="btn btn-primary">Save Flight</button>
          <button type="button" class="btn btn-secondary" onclick="$('.modal').modal('hide')">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  $(document).ready(function(){
    $('.select2').select2({ placeholder:"Please select here", width:"100%" });
    $('.datetimepicker').datetimepicker({ format:'Y-m-d H:i' }).attr('autocomplete','off');
  });
  function previewBanner(input){
    if (input.files && input.files[0]) {
      const r = new FileReader();
      r.onload = e => $('#bannerPreview').attr('src', e.target.result);
      r.readAsDataURL(input.files[0]);
    }
  }
  // Submit via FormData (file-safe)
  $('#manage-flight').on('submit', function(e){
    e.preventDefault();
    start_load();
    const fd = new FormData(this);
    $.ajax({
      url:'ajax.php?action=save_flight',
      method:'POST',
      data:fd,
      contentType:false,
      processData:false,
      success:function(resp){
        if(resp == 1 || resp === '1'){ alert_toast("Flight successfully saved.","success"); setTimeout(()=>location.reload(),1200); }
        else { console.log('save_flight response:', resp); alert_toast("Save failed. Check console/logs.","danger"); end_load(); }
      },
      error:function(xhr){ console.error(xhr.responseText); alert_toast("Server error. Check logs.","danger"); end_load(); }
    });
  });
</script>
