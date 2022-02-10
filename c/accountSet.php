<?php error_reporting(0); include('database_connection.php');  session_start();

function checkValid($stringT){
  $checker = false;
  $arrayInvalid = array("\\0","\\'","\\b","\\n","\\r","\\t","\\z","\\","\%","\\_",";","\"");
	  foreach($arrayInvalid as $value){
		  if (strpos($stringT,$value)>(-1)){
			  $checker = true;
		  }
	  }
	  return $checker;
  }


     if(isset($_POST['emailc'])){
		 $email = $_POST['email'];
		 $userID = $_POST['user'];
		 $checkForInvalidChar = checkValid(($email.$userID ));
		 if($checkForInvalidChar){
			 header("Location: ../c/?accountSettings&m=You.Used.an.Invalid.Character!");
		 }
		 else{
			 $q = "SELECT * FROM accountstbl WHERE account_email='$email'";
			 $userCount = mysqli_num_rows( mysqli_query($dbCon, $q));
			 if($userCount>=1){
				 header("Location: ../c/?accountSettings&m=Email.Already.Exist!");
			 }else{
				 $query = "UPDATE accountstbl SET account_email='$email' WHERE account_username='$userID'";
				     mysqli_query($dbCon, $query);
					 header("Location: ../c/?accountSettings&m=Email.Changed.Successfully!");
			}
				 
				 
				 

		 }
	 }
	 else if(isset($_POST['userc'])){
		 $user = $_POST['username'];
		 $userID = $_POST['user'];
		 $checkForInvalidChar = checkValid(($user.$userID ));
		 if($checkForInvalidChar){
			 header("Location: ../c/?accountSettings&m=You.Used.an.Invalid.Character!");
		 }
		 else{
			 $q = "SELECT * FROM accountstbl WHERE account_username='$user'";
			 $userCount = mysqli_num_rows( mysqli_query($dbCon, $q));
			 if($userCount>=1){
				 header("Location: ../c/?accountSettings&m=User.Already.Exist!");
			 }else{
				 $query = "UPDATE accountstbl SET account_username='$user' WHERE account_username='$userID'";
				     mysqli_query($dbCon, $query);
					 $_SESSION['username'] = $user;
					 header("Location: ../c/?accountSettings&m=Username.Changed.Successfully!");
				 }
				 
			 }
		 }

		 
	 
	 else if(isset($_POST['passc'])){
		 $pass = trim($_POST['pass1']);
		 $userID = $_POST['user'];
		 $checkForInvalidChar = checkValid(($pass.$userID ));
		 if($checkForInvalidChar){
			 header("Location: ../c/?accountSettings&m=You.Used.an.Invalid.Character!");
		 }
		 else{
			 $pass = crypt($pass,'$2a$09$qpwoeirutyalskdj3adasd$');
				 $query = "UPDATE accountstbl SET account_password='$pass' WHERE account_username='$userID'";
				     mysqli_query($dbCon, $query);
					 header("Location: ../c/?accountSettings&m=Password.Changed.Successfully!");
				
		 }
	 }
	 else if(isset($_POST['picturec'])){
		 $userID = $_POST['user'];
		 $data = $_POST["photoCROP"];
		 $image_array_1 = explode(";", $data);
		 $image_array_2 = explode(",", $image_array_1[1]);
		 $data = base64_decode($image_array_2[1]);
		 $imageName = time() . '.png';
		 file_put_contents($imageName, $data);
		 $image_file = addslashes(file_get_contents($imageName));
		 $query = "UPDATE accountstbl SET account_photo='$image_file' WHERE account_username='$userID'";
				     mysqli_query($dbCon, $query);
					 header("Location: ../c/?accountSettings&m=Photo.Changed.Successfully!");
		 
	 }
	 else if(isset($_POST['genderc'])){
		 $gender = $_POST['gender'];
		 $userID = $_POST['user'];
		 $checkForInvalidChar = checkValid(($pass.$userID ));
		 if($checkForInvalidChar){
			 header("Location: ../c/?accountSettings&m=You.Used.an.Invalid.Character!");
		 }
		 else if($gender=='male'||$gender=='female'){
				 $query = "UPDATE accountstbl SET account_gender='$gender' WHERE account_username='$userID'";
				     mysqli_query($dbCon, $query);
					 header("Location: ../c/?accountSettings&m=Gender.Changed.Successfully!");
				 
		 }
		 else{
			 header("Location: ../c/?accountSettings&m=Data.Update.Error!.Please.Try.Again");
		 }
	 }
	 else if(isset($_POST['reviewb'])){
		 $review = preg_replace("/[^A-Za-z0-9.,  ]/", '',$_POST['reviewText']);
		 $userID = $_POST['user'];

			$countR = mysqli_num_rows(mysqli_query($dbCon, "SELECT * FROM accountstbl WHERE account_number_ratings IS NULL AND account_username='$userID'"));
			if($countR>=1){
				$countAll = mysqli_num_rows(mysqli_query($dbCon, "SELECT * FROM ratingstbl"))+1;
				$query = "INSERT INTO ratingstbl (rating_description) VALUES ('$review');";
				$query2 = "UPDATE accountstbl SET account_number_ratings='$countAll' WHERE account_username='$userID'";
			    mysqli_query($dbCon, $query);
				mysqli_query($dbCon, $query2);
				header("Location: ../c?rq");
			}
			else{
				
				$query = "UPDATE ratingstbl a JOIN accountstbl b ON a.account_number=b.account_number_ratings SET a.rating_description='$review' WHERE b.account_username='$userID'";
			         mysqli_query($dbCon, $query);
				header("Location: ../c?rs");
			}

	 }
	 else {
		 header("Location: ../c");
	 }
?>