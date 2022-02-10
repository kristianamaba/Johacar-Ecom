<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  

<form id="formID">
  <label for="fname">First name:</label><br>
  <input type="text" name="fname" value=""><br>
  <label for="lname">Last name:</label><br>
  <input type="text" name="lname" value=""><br><br>
  <input type="button" onclick="onSubmit()" value="Submit">
</form> 



		<script type='text/javascript'>
			function onSubmit(){
				$.ajax({
					 url:'sample.php',
					 method: 'post',
					 data: $("#formID").serialize(),
					 success: function(response){
						alert(response);
					 },
					 error: function(XMLHttpRequest, textStatus, errorThrown) { 
						alert("Status: " + textStatus); alert("Error: " + errorThrown); 
					}   
				  });
			}
		</script>