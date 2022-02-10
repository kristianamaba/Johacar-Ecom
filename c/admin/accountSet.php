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
			 $_SESSION['message'] = "You.Used.an.Invalid.Character!";
			 header("Location: ../admin/?profile");
		 }
		 else{
			 $q = "SELECT * FROM accountstbl WHERE account_email='$email'";
			 $userCount = mysqli_num_rows( mysqli_query($dbCon, $q));
			 if($userCount>=1){
				$_SESSION['message'] = "Email.Already.Exist";
					 header("Location: ../admin/?profile");
			 }else{
				 $query = "UPDATE accountstbl SET account_email='$email' WHERE account_username='$userID'";
				     mysqli_query($dbCon, $query);
					 $_SESSION['message'] = "Email.Changed.Successfully!";
					 header("Location: ../admin/?profile");
			}
				 
				 
				 

		 }
	 }
	 else if(isset($_POST['userc'])){
		 $user = $_POST['username'];
		 $userID = $_POST['user'];
		 $checkForInvalidChar = checkValid(($user.$userID ));
		 if($checkForInvalidChar){
			  $_SESSION['message'] = "You.Used.an.Invalid.Character!";
			 header("Location: ../admin/?profile");
		 }
		 else{
			 $q = "SELECT * FROM accountstbl WHERE account_username='$user'";
			 $userCount = mysqli_num_rows( mysqli_query($dbCon, $q));
			 if($userCount>=1){
					 $_SESSION['message'] = "Username.Already.Exist!";
					 header("Location: ../admin/?profile");
			 }else{
				 $query = "UPDATE accountstbl SET account_username='$user' WHERE account_username='$userID'";
				     mysqli_query($dbCon, $query);
					 $_SESSION['username'] = $user;
					 $_SESSION['message'] = "Username.Changed.Successfully!";
					 header("Location: ../admin/?profile");
				 }
				 
			 }
		 }


				
		 
	 
	 else if(isset($_POST['passc'])){
		 $pass = trim($_POST['pass1']);
		 $userID = $_POST['user'];
		 $checkForInvalidChar = checkValid(($pass.$userID ));
		 if($checkForInvalidChar){
			 $_SESSION['message'] = "You.Used.an.Invalid.Character!";
			 header("Location: ../admin/?profile");
			  
		 }
		 else{
			 $pass = crypt($pass,'$2a$09$qpwoeirutyalskdj3adasd$');
				 $query = "UPDATE accountstbl SET account_password='$pass' WHERE account_username='$userID'";
				     mysqli_query($dbCon, $query);
					  $_SESSION['message'] = "Password.Changed.Successfully!";
					 header("Location: ../admin/?profile");
					 
				
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
					  $_SESSION['message'] = "Photo.Changed.Successfully!";
					 header("Location: ../admin/?profile");
					
		 
	 }
	 else if(isset($_POST['genderc'])){
		 $gender = $_POST['gender'];
		 $userID = $_POST['user'];
		 $checkForInvalidChar = checkValid(($pass.$userID ));
		 if($checkForInvalidChar){
			 $_SESSION['message'] = "You.Used.an.Invalid.Character!";
			 header("Location: ../admin/?profile");
		 }
		 else if($gender=='male'||$gender=='female'){
				 $query = "UPDATE accountstbl SET account_gender='$gender' WHERE account_username='$userID'";
				     mysqli_query($dbCon, $query);
					  $_SESSION['message'] = "Gender.Changed.Successfully!";
					 header("Location: ../admin/?profile");
					
				 
		 }
		 else{
			 $_SESSION['message'] = "Data.Update.Error!.Please.Try.Again";
			 header("Location: ../admin/?profile");
		 }
	 }
	 else {
		 header("Location: ../admin");
	 }
?>