<?php if(!isset($_SESSION['customer_id'])): ?>
<div class="text-center py-6">
  <p class="text-slate-700 text-sm mb-3">
    Please log in to book a flight and manage your bookings.
  </p>
  <a href="index.php?page=login" class="bg-brand text-white px-4 py-2 rounded-md hover:bg-brandDark">
    Login Now
  </a>
</div>
<?php return; endif; ?>

<div class="container-fluid">
	<div class="col-lg-12">
	<form action="" id="book-flight">
		<input type="hidden" name="flight_id" value="<?php echo $_GET['id'] ?>">
		<div class="form-group row" id="qty">
			<div class="col-md-3">
			<label for="" class="control-label">Person/s</label>
			<input type="number" class="form-control text-right" min='1' value="1" id="count" max="<?php echo $_GET['max'] ?>">
			</div>
			<div class="col-md-2">
			<label for="" class="control-label">&nbsp;</label>
			<button class="btn btn-primary btn-block" type="button" id="go">Go</button>
			</div>
			<div class="col-md-2">
			<label for="" class="control-label">&nbsp;</label>
			<button class="btn btn-secondary btn-block" type="button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
		<div id="row-field" style="display: none">
			<div class="row ">
				<div class="col-md-12 text-center">
					<button class="btn btn-primary btn-sm " >Save</button>
					<button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
		
	</form>
	</div>
</div>
<script>
	$('#go').click(function(){
		start_load()
		if('<?php echo $_GET['max'] ?>' < $('#count').val()){
			alert("The number of person can't be greater than the available flight seats.")
					end_load()
			return false;
		}
		$.ajax({
			url:"get_fields.php?count="+$('#count').val(),
			success:function(resp){
				if(resp){
					$('#row-field').prepend(resp)
					$('#qty').hide()
					$('#row-field').show()
					end_load()
				}
			}

		})
	})
	$('#book-flight').submit(function(e){
  e.preventDefault();
  start_load();
  $.ajax({
    url:'admin/ajax.php?action=book_flight',
    method:"POST",
    data: $(this).serialize() + '&customer_id=<?php echo $_SESSION['customer_id'] ?? 0; ?>',
    success:function(resp){
      if(resp == 1){
        $('.modal').modal('hide');
        end_load();
        alert_toast("Flight successfully booked.","success");
      } else if (resp == 2){
        end_load();
        alert_toast("Not enough seats available for this flight.", "danger");
      } else {
        end_load();
        alert_toast("Booking failed. Please try again.", "danger");
      }
    }
  })
});

</script>
<style>
	#uni_modal .modal-footer{
		display: none
	}
</style>