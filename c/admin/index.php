<?php
date_default_timezone_set('Asia/Manila');
session_start();  include('database_connection.php');  error_reporting(0);

ob_start();


function deleteAccount($aID, $aNAME){ include('database_connection.php');
$username = $_SESSION['username'];
$datetime = date('Y-m-d H:i:s');
	$q = "DELETE FROM accountstbl WHERE account_id='$aID'";
				
    $query = "INSERT INTO reportstbl(report_text, report_date,report_category) VALUES('".$username." deleted the account of ".$aNAME."','$datetime','logs')";
				mysqli_query($dbCon, $q);
				mysqli_query($dbCon, $query);
				header('Location: ../admin/?ad');
				
}


function getAllReports($type){ include('database_connection.php');
	$data = array();
	$q = "SELECT * FROM reportstbl WHERE report_category='$type'";
	$count = 0;
	$result = mysqli_query($dbCon, $q);
	while($row = mysqli_fetch_array($result)){
		$data[$count][0] = $row['report_text'];
		$data[$count][1] = $row['report_date'];
		$count ++;
						
		}
	return $data;
}

function getAllProductReports($type){ include('database_connection.php');
	$data = array();
	$q = "SELECT * FROM reportstbl WHERE report_category='$type'";
	$count = 0;
	$result = mysqli_query($dbCon, $q);
	while($row = mysqli_fetch_array($result)){
		$data[$count][0] = $row['report_username'];
		$data[$count][1] = $row['report_action'];
		$data[$count][2] = $row['report_product'];
		$data[$count][3] = $row['report_quantity'];
		$data[$count][4] = $row['report_date'];
		$count ++;
						
		}
	return $data;
}

function getAllStaffAccount(){ include('database_connection.php');
	$data = array();
	$q = "SELECT account_id, account_username, account_email FROM accountstbl WHERE account_category='staff'";
	$count = 0;
	$result = mysqli_query($dbCon, $q);
	while($row = mysqli_fetch_array($result)){
		$data[$count][0] = $row['account_id'];
		$data[$count][1] = $row['account_username'];
		$data[$count][2] = $row['account_email'];
		$count ++;
						
		}
	return $data;
}

function getAllProductsBought(){ include('database_connection.php');
	$data = array();
	$q = "SELECT * FROM orderdetailstbl b JOIN accountstbl a ON b.account_id=a.account_id JOIN productstbl c ON b.order_product_id=c.product_id WHERE order_status='pending'";
	$count = 0;
	$result = mysqli_query($dbCon, $q);
	while($row = mysqli_fetch_array($result)){
		$data[$count][0] = $row['order_id'];
		$data[$count][1] = $row['account_username'];
		$data[$count][2] = $row['order_date'];
		$data[$count][3] = $row['product_name'];
		$data[$count][4] = $row['order_quantity'];
		$count ++;
						
		}
	return $data;
}

function setOrderStatus($status, $orID, $return, $pnum, $name){ include('database_connection.php');
$username = $_SESSION['username'];
$datetime = date('Y-m-d H:i:s');
if ($status=="cancelled"){
	
	
	$q = "UPDATE orderdetailstbl a JOIN productstbl b ON a.order_product_id=b.product_id SET a.order_status='$status', b.product_available = b.product_available + '$pnum' WHERE a.order_id='$orID' ";
				
}
else {
	$q = "UPDATE orderdetailstbl SET order_status='$status'  WHERE order_id='$orID'";
}
    $query = "INSERT INTO reportstbl(report_username, report_action, report_product, report_quantity, report_date, report_category) VALUES('$username','$status','$name','$pnum','$datetime','products')";
				mysqli_query($dbCon, $q);
				mysqli_query($dbCon, $query);
				header('Location: ../admin/?pconfirm&'.$return);
				
			}

function getUserPhoto($username){include('database_connection.php');
	     $q = $connect->query("SELECT account_photo FROM accountstbl WHERE account_username='$username'");
		 $f = $q->fetch(); $result = $f[0];
     return 'data:image/png;base64,'.base64_encode($result);
}

function slideshowPicture($key){include('database_connection.php');
     $q = $connect->query("SELECT picture_blob FROM websitepicturestbl WHERE picture_id='".$key."'"); $f = $q->fetch(); $result = $f[0];
     echo 'data:image/png;base64,'.base64_encode($result);
}

function getSlideDetails($id){include('database_connection.php');
	    $data = array();
	
	$q = "SELECT slideTitle,slideDescription from websitepicturestbl WHERE picture_id='".$id."'"; 
	$result=$dbCon->query($q);
	 while($row = $result->fetch_assoc()) {
		$data[0] = $row["slideTitle"];
        $data[1] = $row["slideDescription"];
    } 

	return $data ;
}

function getRawProductPicture($pID){include('database_connection.php');
     $q = $connect->query("SELECT product_picture FROM productstbl WHERE product_id='".$pID."'"); $f = $q->fetch(); $result = $f[0];
     echo 'data:image/png;base64,'.base64_encode($result);
}

function getAllProductsID(){include('database_connection.php');
    $results1 = array();
	$query="SELECT * FROM productstbl";
	
	$results=mysqli_query($dbCon,$query);
	
	$resultNum = mysqli_num_rows($results);
	   for($count = 0; $count<$resultNum; $count++){
		   $q="SELECT * FROM productstbl LIMIT ".$count.", 1";
		   $results=mysqli_query($dbCon,$q);
			while($row = mysqli_fetch_array($results)) {
			$results1[$count] = $row['product_id'];	
		    }
	   }
	   
	return $results1;
}


function getProductDetails($pID){include('database_connection.php');

    $data = array();
	
	$q = "SELECT product_id, product_name, product_description, product_specs, product_tags, product_available, product_price, product_category, product_bought from productstbl where product_id='".$pID."'"; 
	$result=$dbCon->query($q);
	 while($row = $result->fetch_assoc()) {
		$data[0] = $row["product_id"];
        $data[1] = $row["product_name"];
		$data[2] = $row["product_description"];
        $data[3] = $row["product_specs"];
		$data[4] = $row["product_tags"];
        $data[5] = $row["product_available"];
		$data[6] = $row["product_price"];
        $data[7] = $row["product_category"];
		$data[8] = $row["product_bought"];

    } 

	return $data ;
}


function checkValid($stringT){
  $checker = false;
  $arrayInvalid = array("\\0","\\'","\\b","\\n","\\r","\\t","\\z","\\","\%","\\_",";");
	  foreach($arrayInvalid as $value){
		  if (strpos($stringT,$value)>(-1)){
			  $checker = true;
		  }
	  }
	  return $checker;
  }
  
function errorLoginRegister($error){
	
	 
	 
	 $_SESSION['messageSU'] = str_replace(" ",".",$error);
	 $_SESSION['signUPBOOL'] = null;
	 header('Location: ../admin/?ssu&m');
	 

}
		echo ' <script language="javascript" type="text/javascript">
               document.addEventListener("DOMContentLoaded", function() { ';
			   

			 	 
				 if(isset($_GET['m'])&&isset($_SESSION['messageSU'])){
					 echo "alert('".str_replace("."," ", $_SESSION['messageSU'])."');";
					 $_SESSION['messageSU']=null;
				 }
		 
		 if(isset($_GET['eSU'])&&isset($_GET['uSU'])&&isset($_GET['pSU'])){
			$_SESSION['gendSU'] = trim($_GET['gSU']);
			$_SESSION['passSU'] = trim($_GET['pSU']);
			$_SESSION['passSU2'] = trim($_GET['pSU2']);
			$_SESSION['userSU'] = trim($_GET['uSU']);
			$_SESSION['emaiSU'] = trim($_GET['eSU']);
			$_SESSION['signUPBOOL'] = true;
		}
			
		 if($_SESSION['signUPBOOL'] == true){
			$error = "";
			//Sessions to PHP variables
			$gendSU = $_SESSION['gendSU'];
			$passSU = $_SESSION['passSU'];
			$passSU2 = $_SESSION['passSU2'];
			$userSU = $_SESSION['userSU'];
			$emaiSU = $_SESSION['emaiSU'];
			
			//Error Checking 
			try{
			if(empty($userSU)){$error += "Empty Username Field\\n";}
			if ($passSU!=$passSU2){$error .= "Password is not the same!\\n";}
			if (empty($passSU)&&empty($passSU2)){$error .= "Password is Empty Spaces as Password aren't allowed!\\n";}
			else if (strlen($passSU)<=7){$error .= "Your password is too short!\\n";}
			$checkForInvalidChar = checkValid(($passSU.$userSU.$emaiSU.$roboSU.$gendSU));
			if($checkForInvalidChar==true){
				$error .= "Invalid Characters Used!\\n Please do not Use\\n '+invalidCharList+'";
			}

			//If no Error, Procceed
			if(empty($error)){
				$query = "SELECT * FROM accountstbl WHERE account_username='$userSU' OR account_email='$emaiSU'";
		        $results = mysqli_query($dbCon, $query);
				  if (mysqli_num_rows($results) >= 1) {
					  $error .= "Username/Email already exist!\\n";
					  errorLoginRegister($error);
				  }
				  else{
					  $datetime = date('Y-m-d H:i:s');
					  $encryptedPass = crypt($passSU,'$2a$09$qpwoeirutyalskdj3adasd$');
					  
					  $insertQ = "INSERT INTO accountstbl(account_username,account_gender,account_email,account_password,account_category) 
					  VALUES('".$userSU."','".$gendSU."','".$emaiSU."','".$encryptedPass."','staff')";
					  
					   mysqli_query($dbCon, $insertQ );
					   //Picture of username default per Gender
					   mysqli_query($dbCon,"UPDATE accountstbl t1 INNER JOIN websitepicturestbl t2 ON t1.account_gender=t2.gender SET t1.account_photo=t2.picture_blob WHERE t1.account_username='".$userSU."'");
					  unset($_SESSION['signUPBOOL']);
					  
					   $query = "INSERT INTO reportstbl(report_text, report_date,report_category) VALUES('".$_SESSION['username']." created an account named  ".$userSU."','$datetime','logs')";
									mysqli_query($dbCon, $query);
					  errorLoginRegister("Account Created! ");
				  }
				
			}//Shows Error
			else{  errorLoginRegister($error);}
		}catch (Exception $e){
	        echo "alert('".$e."');";
		}
		}
		
		echo '});</script>';


//$_SESSION['user']= "admin"; for testing
 if (($_SESSION['user']!=null&&$_SESSION['loggedIN']== true&&($_SESSION['user']=='admin'))||($_SESSION['user']!=null&&$_SESSION['loggedIN']== true&&($_SESSION['user']=='staff'))){
	 
   if(isset($_GET['logout'])){
		session_destroy();
		header("Location: ../");
	}
	$username = $_SESSION['username'];
	if(""==""){
	print'<!DOCTYPE html><html lang="en"><head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Johancar ADMIN</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  
  
  
 
  <script src="jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
      <script src="js/bootstrap.min.js" rel="Stylesheet"></script>
	  <link rel="stylesheet" href="croppie.css" />
	  <script src="croppie.js"></script>
  
  
  
  
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  
  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  
  
  <style>
  .modal-backdrop {
     background-color: rgba(0,0,0,.5) !important;
}

* {
  box-sizing: border-box;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 33.33%;
  padding: 10px;
  height: auto; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
  .column {
    width: 100%;
  }
}

  </style>
  
  
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href>
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">'.$_SESSION['user'].'</div>
      </a>



      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Interface
      </div>
	  
	   <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link" onclick="location.href=\'?pconfirm\';return false;">
          <i class="fas fa-fw fa-table"></i>
          <span>Product Confirmation</span></a>
      </li>
	  
	  '.(($_SESSION['user']=='admin')? '<!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed"  data-toggle="collapse" data-target="#collapseONE" aria-expanded="true" aria-controls="collapseONE" >
          <i class="fas fa-fw fa-cog"></i>
          <span>Website Settings</span>
        </a>
        <div id="collapseONE" class="collapse" aria-labelledby="headingONE" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Settings</h6>
            <a class="collapse-item" onclick="location.href=\'?ws\';return false;">Slideshow Settings</a>
          </div>
        </div>
      </li>':'').'
	   


      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed"  data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>Product Settings</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Options</h6>
            <a class="collapse-item" onclick="location.href=\'?ap\';return false;">Add Products</a>
            <a class="collapse-item" onclick="location.href=\'?edp\';return false;">Edit/Delete Products</a>
          </div>
        </div>
      </li>
	  
      
	  
	  
      <!-- Nav Item - Utilities Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed"  data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Reports</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Report Type</h6>
            <a class="collapse-item" onclick="location.href=\'?rpb\';return false;">Products Bought</a>
            <a class="collapse-item" onclick="location.href=\'?rpm\';return false;">Logs</a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">'.(($_SESSION['user']=='admin')? ' <!-- Heading -->
      <div class="sidebar-heading">
        Others
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed"  data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Accounts</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Account</h6>
            <a class="collapse-item" onclick="location.href=\'?ssu\';return false;">Staff Acount Sign-up</a>
            <a class="collapse-item" onclick="location.href=\'?ad\';return false;">Account Deletion</a>
          </div>
        </div>
      </li>

     

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">':'').'

     

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>



          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">'.$username.'</span>
                <img class="img-profile rounded-circle" src="'.getUserPhoto($username).'">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" onclick="location.href=\'?profile\';return false;" href>
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">';
	}


		     if (isset($_GET['ws'])&&$_SESSION['user']=='admin'){ //WEBSITE SLIDESHOW
				  $data1 = getSlideDetails('1');
				  $data2 = getSlideDetails('2');
				  $data3 = getSlideDetails('3');
				 echo' <div class="row" style="text-align:center;">
  <div class="column" style="background-color:#aaa;">
     <img src="';  slideshowPicture('1'); echo'" style="width:100%;height:auto;">;
    <form class="form-horizontal" action="logic.php" method="POST" enctype="multipart/form-data">
						<fieldset>
						  <input id="slideT" name="slideT" value="'.$data1[0] .'" placeholder="Title" class="form-control input-md"  type="text">
						  <input id="slideD" name="slideD" value="'.$data1[1] .'" placeholder="Description" class="form-control input-md"  type="text">
						 <!-- File Button --> 
						<div class="form-group">
						  <p style="text-align:left;margin:10px 0px 0px 10px">Change SlideShow Image</p>
						  <div class="col-md-4">
						    <input type="hidden" name="wsID" value="1">
							<input type="text" id="pictures1" name="photoCROP" value="';  slideshowPicture('1'); echo'" style="display:none;" required oninvalid="alert(\'Insert a photo to add a product!\')">
	  
						   <input type="file" name="insert_image" value="" id="insert_image" accept="image/*" onchange="var reader = new FileReader();
						reader.onload = function (event) {
						  $image_crop.croppie(\'bind\', {
							url: event.target.result
						  }).then(function(){
							console.log(\'jQuery bind complete\');
						  });
						}
						reader.readAsDataURL(this.files[0]);
						$(\'#insertimageModal\').modal(\'show\');" />
						  </div>
						</div>


						<!-- Button -->


							<button id="singlebutton" name="slideShowChange" class="btn btn-primary float-center">Submit</button>
						 
						 

						</fieldset>
						</form>
	
						  </div>
						  <div class="column" style="background-color:#bbb;">
							
							
							
							     <img src="';  slideshowPicture('2'); echo'" style="width:100%;height:auto;">;
    <form class="form-horizontal" action="logic.php" method="POST" enctype="multipart/form-data">
						<fieldset>
						  <input id="slideT" name="slideT" value="'.$data2[0] .'" placeholder="Title" class="form-control input-md"  type="text">
						  <input id="slideD" name="slideD" value="'.$data2[1] .'" placeholder="Description" class="form-control input-md"  type="text">
						 <!-- File Button --> 
						<div class="form-group">
						  <p style="text-align:left;margin:10px 0px 0px 10px">Change SlideShow Image</p>
						  <div class="col-md-4">
						    <input type="hidden" name="wsID" value="2">
							<input type="text" id="pictures2" name="photoCROP" value="';  slideshowPicture('2'); echo'" style="display:none;" required oninvalid="alert(\'Insert a photo to add a product!\')">
	  
						   <input type="file" name="insert_image" value="" id="insert_image" accept="image/*" onchange="var reader = new FileReader();
						reader.onload = function (event) {
						  $image_crop.croppie(\'bind\', {
							url: event.target.result
						  }).then(function(){
							console.log(\'jQuery bind complete\');
						  });
						}
						reader.readAsDataURL(this.files[0]);
						$(\'#insertimageModal\').modal(\'show\');" />
						  </div>
						</div>


						<!-- Button -->


							<button id="singlebutton" name="slideShowChange" class="btn btn-primary float-center">Submit</button>
						 
						 

						</fieldset>
						</form>
							
							
							
						  </div>
						  <div class="column" style="background-color:#ccc;">
							
							
							     <img src="';  slideshowPicture('3'); echo'" style="width:100%;height:auto;">;
    <form class="form-horizontal" action="logic.php" method="POST" enctype="multipart/form-data">
						<fieldset>
						  <input id="slideT" name="slideT" value="'.$data3[0] .'" placeholder="Title" class="form-control input-md"  type="text">
						  <input id="slideD" name="slideD" value="'.$data3[1] .'" placeholder="Description" class="form-control input-md"  type="text">
						 <!-- File Button --> 
						<div class="form-group">
						  <p style="text-align:left;margin:10px 0px 0px 10px">Change SlideShow Image</p>
						  <div class="col-md-4">
						    <input type="hidden" name="wsID" value="3">
							<input type="text" id="pictures3" name="photoCROP" value="';  slideshowPicture('3'); echo'" style="display:none;" required oninvalid="alert(\'Insert a photo to add a product!\')">
	  
						   <input type="file" name="insert_image" value="" id="insert_image" accept="image/*" onchange="var reader = new FileReader();
						reader.onload = function (event) {
						  $image_crop.croppie(\'bind\', {
							url: event.target.result
						  }).then(function(){
							console.log(\'jQuery bind complete\');
						  });
						}
						reader.readAsDataURL(this.files[0]);
						$(\'#insertimageModal\').modal(\'show\');" />
						  </div>
						</div>


						<!-- Button -->


							<button id="singlebutton" name="slideShowChange" class="btn btn-primary float-center">Submit</button>
						 
						 

						</fieldset>
						</form>
							
							
							
							
						  </div>
						</div>





						<div id="insertimageModal" class="modal" role="dialog" >
					 <div class="modal-dialog" style="min-width:750px;">
					  <div class="modal-content">
						  <div class="modal-header">
							
							<h4 class="modal-title">Crop & Insert Image</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						  </div>
						  <div  class="modal-body" >
							<div class="row">
							
							  <div class="col-md-8 text-center">
								<div id="image_demo" style=" margin-top:30px;"></div>
							  </div>
								
							</div>
						  </div>
						  <div class="modal-footer">
						  <button class="btn btn-success crop_image float-right">Crop & Insert Image</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						  </div>
						</div>
					  </div>
					</div>

					<script>  
					$(document).ready(function(){

					 $image_crop = $(\'#image_demo\').croppie({
						enableExif: true,
						viewport: {
						  width:720,
						  height:350,
						  type:\'square\' //circle
						},
						boundary:{
						  width:720,
						  height:400
						}    
					  });



					  $(\'.crop_image\').click(function(event){
						  
						$image_crop.croppie(\'result\', {
						  type: \'canvas\',
						  size: \'viewport\'
						}).then(function(response){
							var picture1 = response;
						   $(\'#pictures1\').val(picture1);
						    $(\'#pictures2\').val(picture1);
							 $(\'#pictures3\').val(picture1);
						   $(\'#insertimageModal\').modal(\'hide\');
						   alert(\'Picture Cropped!\');
						});
					  });



					});  
					</script>';
			 }
			 else if (isset($_GET['ap'])){//add products
			     
				 echo'
	<form class="form-horizontal" action="logic.php" method="POST" enctype="multipart/form-data">
						<fieldset>

						<!-- Form Name -->
						<legend>PRODUCTS</legend>



						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="product_name">PRODUCT NAME</label>  
						  <div class="col-md-4">
						  <input id="product_name" name="product_name" placeholder="PRODUCT NAME" class="form-control input-md" required="" type="text">
							
						  </div>
						</div>


						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="product_category">PRODUCT CATEGORY</label>
						  <div class="col-md-4">
							<select id="product_category" name="product_category" class="form-control">
							<option value="wellness">Wellness</option>
							<option value="beauty">Beauty</option>
							<option value="beverage">Beverage</option>
							<option value="beaute">Beaute</option>
							</select>
						  </div>
						</div>


						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="available_quantity">AVAILABLE QUANTITY</label>  
						  <div class="col-md-4">
						  <input id="available_quantity" name="available_quantity" placeholder="AVAILABLE QUANTITY" class="form-control input-md" required="" type="text">
							
						  </div>
						</div>

						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="product_price">PRODUCT PRICE</label>  
						  <div class="col-md-4">
						  <input id="product_price" name="product_price" placeholder="150.00" class="form-control input-md" required="" type="text">
							
						  </div>
						</div>



						<!-- Textarea -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="product_description">PRODUCT DESCRIPTION</label>
						  <div class="col-md-4">                     
							<textarea class="form-control" id="product_description" name="product_description"></textarea>
						  </div>
						</div>

						<!-- Textarea -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="product_specs">PRODUCT SPECIFICATIONS</label>
						  <div class="col-md-4">                     
							<textarea class="form-control" id="product_specs" name="product_specs"></textarea>
						  </div>
						</div>

						<!-- Textarea -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="product_tags">PRODUCT TAGS</label>
						  <div class="col-md-4">                     
							<textarea class="form-control" id="product_tags" name="product_tags" ></textarea>
						  </div>
						</div>

						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="products_bought">PRODUCT BOUGHT</label>
						  <div class="col-md-4">
						  <input id="products_bought" name="products_bought" placeholder="BASIS ON SEARCH ORDER (Optional)" class="form-control input-md" required="" type="text">
						  </div>
						</div>


						 <!-- File Button --> 
						<div class="form-group">
						  <label class="col-md-4 control-label" for="filebutton">main_image</label>
						  <div class="col-md-4">
							<input type="text" id="pictures" name="photoCROP" style="display:none;" required oninvalid="alert(\'Insert a photo to add a product!\')">
	  
						   <input type="file" name="insert_image" value="" id="insert_image" accept="image/*" required onchange="var reader = new FileReader();
						reader.onload = function (event) {
						  $image_crop.croppie(\'bind\', {
							url: event.target.result
						  }).then(function(){
							console.log(\'jQuery bind complete\');
						  });
						}
						reader.readAsDataURL(this.files[0]);
						$(\'#insertimageModal\').modal(\'show\');" />
						  </div>
						</div>


						<!-- Button -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="singlebutton"> </label>
						  <div class="col-md-4">
							<button id="singlebutton" name="addProduct" class="btn btn-primary float-right">Submit</button>
						  </div>
						  </div>

						</fieldset>
						</form>
	
						<div id="insertimageModal" class="modal" role="dialog" >
					 <div class="modal-dialog" style="width:60%;height:50%;">
					  <div class="modal-content">
						  <div class="modal-header">
							
							<h4 class="modal-title">Crop & Insert Image</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						  </div>
						  <div  class="modal-body" >
							<div class="row">
							
							  <div class="col-md-8 text-center">
								<div id="image_demo" style=" margin-top:30px;"></div>
							  </div>
								
							</div>
						  </div>
						  <div class="modal-footer">
						  <button class="btn btn-success crop_image float-right">Crop & Insert Image</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						  </div>
						</div>
					  </div>
					</div>

					<script>  
					$(document).ready(function(){

					 $image_crop = $(\'#image_demo\').croppie({
						enableExif: true,
						viewport: {
						  width:300,
						  height:250,
						  type:\'square\' //circle
						},
						boundary:{
						  width:300,
						  height:300
						}    
					  });



					  $(\'.crop_image\').click(function(event){
						  
						$image_crop.croppie(\'result\', {
						  type: \'canvas\',
						  size: \'viewport\'
						}).then(function(response){
							var picture1 = response;
						   $(\'#pictures\').val(picture1);
						   $(\'#insertimageModal\').modal(\'hide\');
						   alert(\'Picture Cropped!\');
						});
					  });



					});  
					</script>';
						
						if(isset($_GET['s'])){
					 echo '<script>document.addEventListener("DOMContentLoaded", function() {
							alert(\'Product Added Successfully!\');
						   });</script>';
				 }
						
						
						
						
			
			 }
			 else if (isset($_GET['delete'])){//DELETE A PRODUCT
				 $pID =  preg_replace("/[^0-9  ]/", '', $_GET['delete']);
				 $query = "DELETE FROM productstbl WHERE product_id='$pID'";
				 mysqli_query($dbCon, $query);
				 header("Location: ../admin/?edp&sd");
				 ob_end_flush();
			 }
			 else if (isset($_GET['edit'])){//UPDATE A PRODUCT
				 $pID =  preg_replace("/[^0-9  ]/", '', $_GET['edit']);
				 $data = getProductDetails($pID);
				 
				 echo '<form class="form-horizontal" action="logic.php" method="POST" enctype="multipart/form-data">
						<fieldset>

						<!-- Form Name -->
						<legend>PRODUCTS</legend>



						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="product_name">PRODUCT NAME</label>  
						  <div class="col-md-4">
						  <input id="product_name" name="product_name" placeholder="PRODUCT NAME" value="'.$data[1].'" class="form-control input-md" required="" type="text">
							
						  </div>
						</div>


						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="product_category">PRODUCT CATEGORY</label>
						  <div class="col-md-4">
							<select id="product_category" name="product_category" class="form-control">
							<option value="wellness" '.(($data[7]=="wellness") ? "selected" : "").'>Wellness</option>
							<option value="beauty" ' .(($data[7]=="beauty") ? "selected" : "").'>Beauty</option>
							<option value="beverage" '.(($data[7]=="beverage") ? "selected" : "").'>Beverage</option>
							<option value="beaute" '.(($data[7]=="beaute") ? "selected" : "").'>Beaute</option>
							</select>
						  </div>
						</div>


						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="available_quantity">AVAILABLE QUANTITY</label>  
						  <div class="col-md-4">
						  <input id="available_quantity" name="available_quantity" value="'.$data[5].'"  placeholder="AVAILABLE QUANTITY" class="form-control input-md" required="" type="text">
							
						  </div>
						</div>

						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="product_price">PRODUCT PRICE</label>  
						  <div class="col-md-4">
						  <input id="product_price" name="product_price" placeholder="150.00" value="'.$data[6].'"  class="form-control input-md" required="" type="text">
							
						  </div>
						</div>



						<!-- Textarea -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="product_description">PRODUCT DESCRIPTION</label>
						  <div class="col-md-4">                     
							<textarea class="form-control" id="product_description"  name="product_description">'.$data[2].'</textarea>
						  </div>
						</div>

						<!-- Textarea -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="product_specs">PRODUCT SPECIFICATIONS</label>
						  <div class="col-md-4">                     
							<textarea class="form-control" id="product_specs"   name="product_specs">'.$data[3].'</textarea>
						  </div>
						</div>

						<!-- Textarea -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="product_tags">PRODUCT TAGS</label>
						  <div class="col-md-4">                     
							<textarea class="form-control" id="product_tags"   name="product_tags" >'.$data[4].'</textarea>
						  </div>
						</div>

						<!-- Select Basic -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="products_bought">PRODUCT BOUGHT</label>
						  <div class="col-md-4">
						  <input id="products_bought" name="products_bought" value="'.$data[8].'"  placeholder="BASIS ON SEARCH ORDER (Optional)" class="form-control input-md" required="" type="text">
						  </div>
						</div>


						 <!-- File Button --> 
						<div class="form-group">
						  <label class="col-md-4 control-label" for="filebutton">main_image</label>
						  <div class="col-md-4">
						    <input type="hidden" name="pid" value="'.$pID.'">
							<input type="text" id="pictures" name="photoCROP" style="display:none;" value="';  getRawProductPicture($pID); echo '" oninvalid="alert(\'Insert a photo to add a product!\')">
	  
						   <input type="file" name="insert_image" value="" id="insert_image" accept="image/*" onchange="var reader = new FileReader();
						reader.onload = function (event) {
						  $image_crop.croppie(\'bind\', {
							url: event.target.result
						  }).then(function(){
							console.log(\'jQuery bind complete\');
						  });
						}
						reader.readAsDataURL(this.files[0]);
						$(\'#insertimageModal\').modal(\'show\');" />
						  </div>
						</div>


						<!-- Button -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="singlebutton"> </label>
						  <div class="col-md-4">
							<button id="singlebutton" name="editProduct" class="btn btn-primary float-right">Submit</button>
						  </div>
						  </div>

						</fieldset>
						</form>
	
						<div id="insertimageModal" class="modal" role="dialog" >
					 <div class="modal-dialog" style="width:60%;height:50%;">
					  <div class="modal-content">
						  <div class="modal-header">
							
							<h4 class="modal-title">Crop & Insert Image</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						  </div>
						  <div  class="modal-body" >
							<div class="row">
							
							  <div class="col-md-8 text-center">
								<div id="image_demo" style=" margin-top:30px;"></div>
							  </div>
								
							</div>
						  </div>
						  <div class="modal-footer">
						  <button class="btn btn-success crop_image float-right">Crop & Insert Image</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						  </div>
						</div>
					  </div>
					</div>

					<script>  
					$(document).ready(function(){

					 $image_crop = $(\'#image_demo\').croppie({
						enableExif: true,
						viewport: {
						  width:300,
						  height:250,
						  type:\'square\' //circle
						},
						boundary:{
						  width:300,
						  height:300
						}    
					  });



					  $(\'.crop_image\').click(function(event){
						  
						$image_crop.croppie(\'result\', {
						  type: \'canvas\',
						  size: \'viewport\'
						}).then(function(response){
							var picture1 = response;
						   $(\'#pictures\').val(picture1);
						   $(\'#insertimageModal\').modal(\'hide\');
						   alert(\'Picture Cropped!\');
						});
					  });



					});  
					</script>';
				 
				 
				 
				 
			 }
			 else if (isset($_GET['edp'])){//edit delete products
					if(isset($_GET['sd'])){
						echo '<script>document.addEventListener("DOMContentLoaded", function() {
							alert(\'Product Deleted! \');
							location.href=\'?edp\';return false;
						   });</script>';
					}
					if(isset($_GET['se'])){
						echo '<script>document.addEventListener("DOMContentLoaded", function() {
							alert(\'Product Updated Product Details! \');
							location.href=\'?edp\';return false;
						   });</script>';
					}
			 $productsID = getAllProductsID();
			 $productsNumbers = count($productsID);
				 echo'
						  <div class="card shadow mb-4">
							<div class="card-header py-3">
							  <h6 class="m-0 font-weight-bold text-primary">Edit/ Delete Product Details</h6>
							</div>
							<div class="card-body">
							  <div class="table-responsive">
								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
								  <thead>
									<tr>
									  <th>Product Name</th>
									  <th style="width:150px">Product Description</th>
									  <th  style="width:150px;">Product Specs</th>
									  <th>Product Available</th>
									  <th>Product Price</th>
									  <th>Product Category</th>
									  <th>Product Edit Options</th>
									</tr>
								  </thead>
								  <tfoot>
									<tr>
									  <th>Product Name</th>
									  <th>Product Description</th>
									  <th>Product Specs</th>
									  <th>Product Available</th>
									  <th>Product Price</th>
									  <th>Product Category</th>
									  <th>Product Edit Options</th>
									</tr>
								  </tfoot>
								  <tbody>';
									for($x = 0; $x<$productsNumbers; $x++){
										$pID = $productsID[$x];

										$data = getProductDetails($pID);
										echo '<tr>
									  <td>'.$data[1].'</td>
									  <td>'.substr( $data[2],0,30).'...</td>
									  <td>'.substr( $data[3],0,30).'...</td>
									  <td>'.$data[5].'</td>
									  <td>'.$data[6].'</td>
									  <td>'.$data[7].'</td>
									  <td> <button type="button" class="btn btn-primary btn-sm" onClick="editProduct(\''.$data[1].'\', \''.$data[0].'\');">
          <span class="glyphicon glyphicon-edit"></span> Edit Product
        </button>
		<button type="button" class="btn btn-danger btn-sm" onClick="deleteProduct(\''.$data[1].'\', \''.$data[0].'\');">
          <span class="glyphicon glyphicon-floppy-remove" ></span> Delete Product
        </button></td>
									</tr>';
									}
									

								  echo '</tbody>
								</table>
							  </div>
							</div>
						  </div>
						  <script>
							function deleteProduct(name, pid) {
							  var r = confirm("Are you sure you want to delete this product?! \\n" + name + "");
							  if (r == true) {
								location.href=\'?delete=\'+pid;return false;
							  }
							}
							</script>
							
							<script>
							function editProduct(name, pid) {
							  var r = confirm("Are you sure you want to edit this product?! \\n" + name + "");
							  if (r == true) {
								location.href=\'?edit=\'+pid;return false;
							  }
							}
							</script>';
			 }
			 else if (isset($_GET['rpb'])){//report products bought


			     $reports =  getAllProductReports('products');
						 echo'
								  <div class="card shadow mb-4">
									<div class="card-header py-3">
									  <h6 class="m-0 font-weight-bold text-primary">Product Reports</h6>
									</div>
									<div class="card-body">
									  <div class="table-responsive">
										<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
										  <thead>
											<tr>
											  <th>Username</th>
											  <th>Action Taken</th>
											  <th>Product Name</th>
											  <th>Quantity</th>
											  <th>Report Date</th>
											</tr>
										  </thead>
										  <tfoot>
											<tr>
											  <th>Username</th>
											  <th>Action Taken</th>
											  <th>Product Name</th>
											  <th>Quantity</th>
											  <th>Report Date</th>
											</tr>
										  </tfoot>
										  <tbody>';
											for($count= 0; $count<count($reports); $count++){
												echo '<tr>
											  <td>'.$reports[$count][0].'</td>
											  <td>'.$reports[$count][1].'</td>
											  <td>'.$reports[$count][2].'</td>
											  <td>'.$reports[$count][3].'</td>
											  <td>'.$reports[$count][4].'</td>';
											}
											

										  echo '</tbody>
										</table>
									  </div>
									</div>
								  </div>
									<script>
									
									
										$(document).ready(function() {
											$(\'#dataTable\').DataTable( {
												"order": [[ 4, "desc" ]]
											} );
										} );
									
									</script>
									';
			 }
			 else if (isset($_GET['rpm'])){//product logs
				  $reports =  getAllReports('logs');
						 echo'
								  <div class="card shadow mb-4">
									<div class="card-header py-3">
									  <h6 class="m-0 font-weight-bold text-primary">Logs</h6>
									</div>
									<div class="card-body">
									  <div class="table-responsive">
										<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
										  <thead>
											<tr>
											  <th>Report Text</th>
											  <th>Report Date</th>
											</tr>
										  </thead>
										  <tfoot>
											<tr>
											  <th>Report Text</th>
											  <th>Report Date</th>
											</tr>
										  </tfoot>
										  <tbody>';
											for($count= 0; $count<count($reports); $count++){
												echo '<tr>
											  <td>'.$reports[$count][0].'</td>
											  <td>'.$reports[$count][1].'</td>';
											}
											

										  echo '</tbody>
										</table>
									  </div>
									</div>
								  </div>
								  
								  <script>
									
									
										$(document).ready(function() {
											$(\'#dataTable\').DataTable( {
												"order": [[ 1, "desc" ]]
											} );
										} );
									
									
									</script>
									
								';
			 }
			 else if (isset($_GET['ssu'])&&$_SESSION['user']=='admin'){//Staff account signup
				 echo '<div class="col-lg-7" style="  margin-left:auto;margin-right:auto;">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
              </div>
              <form class="user">
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" name="uSU"  placeholder="Username">
                  </div>

                </div>
                <div class="form-group">
                  <input type="email" class="form-control form-control-user"  name="eSU" placeholder="Email Address">
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" name="pSU" class="form-control form-control-user"  placeholder="Password">
                  </div>
                  <div class="col-sm-6">
                    <input type="password" name="pSU2" class="form-control form-control-user"  placeholder="Repeat Password">
                  </div>
                </div>
				<label class="control-label" for="gSU">Gender:</label>
              <div class="controls">
                <label for="gSU-0">
                  <input type="radio" name="gSU" id="gender-0" value="male" required>&nbsp Male</label>
                <label  for="gSU-1">
                  <input type="radio" name="gSU" id="gender-1" value="female">&nbsp Female</label>
              </div>
			  <input type="submit" value="Register Account" class="btn btn-primary btn-user btn-block"> 
                <hr>
                
              </form>
              <hr>

            </div>
          </div>';
		  
		  

			 }
			 else if (isset($_GET['ad'])&&$_SESSION['user']=='admin'){//account deletion
				 
				 $Saccounts =  getAllStaffAccount();
						 echo'
								  <div class="card shadow mb-4">
									<div class="card-header py-3">
									  <h6 class="m-0 font-weight-bold text-primary">Logs</h6>
									</div>
									<div class="card-body">
									  <div class="table-responsive">
										<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
										  <thead>
											<tr>
											  <th>Account Name</th>
											  <th>Account Email</th>
											  <th>Delete Account</th>
											</tr>
										  </thead>
										  <tfoot>
											<tr>
											  <th>Account Name</th>
											  <th>Account Email</th>
											  <th>Delete Account</th>
											</tr>
										  </tfoot>
										  <tbody>';
											for($count= 0; $count<count($Saccounts); $count++){
												echo '<tr>
											  <td>'.$Saccounts[$count][1].'</td>
											  <td>'.$Saccounts[$count][2].'</td>
											  <td> 
				<button type="button" class="btn btn-danger btn-sm" onClick="cancel(\''.$Saccounts[$count][0].'\',\''.$Saccounts[$count][1].'\');">
				  <span class="glyphicon glyphicon-floppy-remove" ></span> Delete Account!
				</button></td>';
											  
											}
											

										  echo '</tbody>
										</table>
									  </div>
									</div>
								  </div>
									
									<script>
									function cancel(aID,aNAME) {
									  var r = confirm("Are you sure you want to delete this account named! " + aNAME  );
									  if (r == true) {
										location.href=\'?aID=\'+aID+\'&aNAME=\'+aNAME;return false;
									  }
									}
									</script>';
				 
				 
			 }
			 
		     else if (isset($_GET['profile'])){
				 echo ' <p>Please be Advised, to Change Settings Respectively.</p>
		  
		  <table class="table table-striped">
			
			<tbody>
			  <tr>
				<td>Change Email</td>
				<td><form id="cemail" style="display: none;" action="accountSet.php" method="POST"><h4>Email Change</h4>
				
				
				
				<div class="row">
					<div class="col-sm-2" style="text-align:right;">Email:
					</div>
					<div class="col-sm" ><input type="hidden" value="'.$_SESSION['username'].'" name="user">
					<input type="email" name="email" required=""><br><input type="submit" name="emailc" value="Change"></form>
					</div>
				</div>
				
				
				</td>
				<td class="text-right" style="padding-right:50px;"><a id="edit1" onclick="showStuff(\'cemail\',  this.id); return false;">Edit</a></td>
			  </tr>
			  <tr>
				<td>Change Username</td>
				<td><form id="cuser" style="display: none;"  action="accountSet.php" method="POST"><h4>Username</h4>
				<div class="row">
					<div class="col-sm-3" style="text-align:right;">Username:
					</div>
					<div class="col-sm"><input type="hidden" value="'.$_SESSION['username'].'" name="user">
					<input type="text" name="username" value="'.$_SESSION['username'].'" required=""><br><input type="submit" name="userc" value="Change"></form>
					</div>
				</div>
				</td>
				<td class="text-right" style="width:10%;padding-right:50px;"><a id="edit2" onclick="showStuff(\'cuser\',  this.id); return false;">Edit</a></td>
			  </tr>
			  <tr>
				<td>Change Password</td>
				<td>
				
				<form id="cpass" style="display: none;"  action="accountSet.php" method="POST">
        <h4>Password Change</h4>
		<div>
		<div class="row">
			<div class="col-sm-5" style="text-align:right;">
			  Password:<br>Re-Type Password:
			</div>
			<div class="col-sm">
			  <input type="hidden" value="'.$_SESSION['username'].'" name="user">
			  <input type="text" id="pass1" name="pass1" min="8" required="">
			  <br><input type="text" id="pass2" name="pass2" min="8" required=""><br>
			  <input type="submit" id="passc" name="passc" value="Change">
			</div>
		  </div>
				
</form>
			</td>
				<td class="text-right" style="width:10%;padding-right:50px;"><a id="edit3" onclick="showStuff(\'cpass\',  this.id); return false;">Edit</a></td>
			  </tr>
			  <tr>
			  
				<td>Change Picture</td>
				<td>
				<form id="cpicture" style="display: none;"  action="accountSet.php" method="POST"> 
				<h4>Picture Change</h4>
				<input type="text" id="pictures" name="photoCROP"  style="display: none;" required="">
				
					Select Photo:
					
					
					<input type="hidden" value="'.$_SESSION['username'].'" name="user">
					<input type="file" name="insert_image" value="" id="insert_image" accept="image/*" required onchange="var reader = new FileReader();
						reader.onload = function (event) {
						  $image_crop.croppie(\'bind\', {
							url: event.target.result
						  }).then(function(){
							console.log(\'jQuery bind complete\');
						  });
						}
						reader.readAsDataURL(this.files[0]);
						$(\'#insertimageModal\').modal(\'show\');" />
						<input type="submit" name="picturec" value="Change" ></form>
					
				
				
				</td>
				<td class="text-right" style="width:10%;padding-right:50px;"><a id="edit4" onclick="showStuff(\'cpicture\',  this.id); return false;">Edit</a></td>
			  </tr>
			  <tr >
				<td>Change Gender</td>
				<td class="text-left" style="width:60%;"><form id="cgender" style="display: none;"  action="accountSet.php" method="POST">
				<h4>Gender Change</h4>
			  <input type="radio" name="gender" value="male" required=""> Male<br>
			  <input type="radio" name="gender" value="female"> female<br>
			  <input type="hidden" value="'.$_SESSION['username'].'" name="user">
			  <input type="submit" name="genderc" value="Change"><br></form></td>
				<td class="text-right" style="width:10%;padding-right:50px;"><a id="edit5" onclick="showStuff(\'cgender\',  this.id); return false;">Edit</a></td>
			  </tr>
			  <tr>
				<td></td>
				<td></td>
				<td></td>
			  </tr>
			</tbody>
		  </table>
		  
		  <div id="insertimageModal" class="modal" role="dialog" >
					 <div class="modal-dialog" style="width:30%;height:50%;">
					  <div class="modal-content">
						  <div class="modal-header">
							
							<h4 class="modal-title">Crop & Insert Image</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						  </div>
						  <div  class="modal-body" >
							<div class="row">
							
							  <div class="col-md-8 text-center">
								<div id="image_demo" style=" margin-top:30px;"></div>
							  </div>
								
							</div>
						  </div>
						  <div class="modal-footer">
						  <button class="btn btn-success crop_image float-right">Crop & Insert Image</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						  </div>
						</div>
					  </div>
					</div>

		  	 <script>
	 					$(document).ready(function(){

					 $image_crop = $(\'#image_demo\').croppie({
						enableExif: true,
						viewport: {
						  width:300,
						  height:300,
						  type:\'square\' //circle
						},
						boundary:{
						  width:300,
						  height:300
						}    
					  });



					  $(\'.crop_image\').click(function(event){
						  
						$image_crop.croppie(\'result\', {
						  type: \'canvas\',
						  size: \'viewport\'
						}).then(function(response){
							var picture1 = response;
						   $(\'#pictures\').val(picture1);
						   $(\'#insertimageModal\').modal(\'hide\');
						   alert(\'Picture Cropped!\');
						});
					  });



					}); 
	 
	    document.addEventListener("DOMContentLoaded", function() {';
		

				if(isset($_GET['profile'])&&isset($_SESSION['message'])){
					echo "alert('".str_replace("."," ",$_SESSION['message'])."');";
					$_SESSION['message'] = null;
				}
			
			  
	    echo'});
		
			const signUpForm = document.getElementById("cpass");
			const pass1 = document.getElementById("pass1");
			const pass2 = document.getElementById("pass2");
			const okButton = document.getElementById("passc");
			okButton.disabled = true;
			pass2.addEventListener("keyup", function (event) {
				var len1 = pass1.value.length;
			  if(pass1.value!=pass2.value||len1<8){
				  okButton.disabled = true;
			  }
			  else{
				  okButton.disabled = false;
			  }
			});
			
			pass1.addEventListener("keyup", function (event) {
			  var len2 = pass1.value.length;
			  if(pass1.value!=pass2.value||len2<8){
				  okButton.disabled = true;
			  }
			  else{
				  okButton.disabled = false;
			  }
			});
			  
			okButton.addEventListener("click", function (event) {
			  signUpForm.submit();
			});


		function showStuff(id, editT) {
			document.getElementById("cpass").style.display = "none";
			document.getElementById("cpicture").style.display = "none";
			document.getElementById("cuser").style.display = "none";
			document.getElementById("cemail").style.display = "none";
			document.getElementById("cgender").style.display = "none";
			document.getElementById("edit1").style.display = "block";
			document.getElementById("edit2").style.display = "block";
			document.getElementById("edit3").style.display = "block";
			document.getElementById("edit4").style.display = "block";
			document.getElementById("edit5").style.display = "block";
			document.getElementById(id).style.display = "block";
			document.getElementById(editT).style.display = "none";
		}
	</script>';
			 }
					 else if(isset($_GET['rc'])){ //recieve
						 $orID = preg_replace('/\D/', '', $_GET['rc']);
						  $orderq = preg_replace('/\D/', '', $_GET['name']);
						 setOrderStatus('recieved', $orID, 'rec',$orderq,$_GET['q']);
					 }
					 else if(isset($_GET['cn'])&&isset($_GET['q'])){ //cancel
					 
							 $orID = preg_replace('/\D/', '',$_GET['cn']);
							 $orderq = preg_replace('/\D/', '',$_GET['q']);
							 setOrderStatus('cancelled', $orID, 'can',$orderq,$_GET['name']);
						 
						 
					 }
					else if (isset($_GET['aID'])&&isset($_GET['aNAME'])){

			        $aID = $_GET['aID'];
					$aNAME = $_GET['aNAME'];
					deleteAccount($aID, $aNAME);
			
					}
             else {   //Product Confirmation
			 
			 
				 
				
					if(isset($_GET['rec'])){
						echo '<script>document.addEventListener("DOMContentLoaded", function() {
							alert(\'Product Changed as Recieved! \');
							location.href=\'?pconfirm\';return false;
						   });</script>';
					}
					if(isset($_GET['can'])){
						echo '<script>document.addEventListener("DOMContentLoaded", function() {
							alert(\'Product Cancelled! \');
							location.href=\'?pconfirm\';return false;
						   });</script>';
					}
				 $pendingR = getAllProductsBought();
						 echo'
								  <div class="card shadow mb-4">
									<div class="card-header py-3">
									  <h6 class="m-0 font-weight-bold text-primary">Product Confirmation</h6>
									</div>
									<div class="card-body">
									  <div class="table-responsive">
										<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
										  <thead>
											<tr>
											  <th>Order ID</th>
											  <th>Account Username</th>
											  <th>Order Date</th>
											  <th>Product Name</th>
											  <th>Order Quantity</th>
											  <th>Action</th>
											</tr>
										  </thead>
										  <tfoot>
											<tr>
											  <th>Order ID</th>
											  <th>Account Username</th>
											  <th>Order Date</th>
											  <th>Product Name</th>
											  <th>Order Quantity</th>
											  <th>Action</th>
											</tr>
										  </tfoot>
										  <tbody>';
											for($count= 0; $count<count($pendingR); $count++){
												echo '<tr>
											  <td>'.$pendingR[$count][0].'</td>
											  <td>'.$pendingR[$count][1].'</td>
											  <td>'.$pendingR[$count][2].'</td>
											  <td>'.$pendingR[$count][3].'</td>
											  <td>'.$pendingR[$count][4].'</td>
											  <td> <button type="button" class="btn btn-primary btn-sm" onClick="location.href=\'?rc='.$pendingR[$count][0].'&q='.$pendingR[$count][3].'&name='.$pendingR[$count][4].'\';return false;">
				  <span class="glyphicon glyphicon-edit"></span> Recieve Product
				</button>
				<button type="button" class="btn btn-danger btn-sm" onClick="cancel(\''.$pendingR[$count][0].'\',\''.$pendingR[$count][4].'\',\''.$pendingR[$count][3].'\');">
				  <span class="glyphicon glyphicon-floppy-remove" ></span> Cancel Order
				</button></td>
											</tr>';
											}
											

										  echo '</tbody>
										</table>
									  </div>
									</div>
								  </div>
									
									<script>
									function cancel(orderid,orderq,ordern) {
									  var r = confirm("Are you sure you want to cancel this order! "  );
									  if (r == true) {
										location.href=\'?cn=\'+orderid+\'&q=\'+orderq+\'&name=\'+ordern;return false;
									  }
									}
									</script>
									
									<script>
									
									
										$(document).ready(function() {
											$(\'#dataTable\').DataTable( {
												"order": [[ 2, "asc" ]]
											} );
										} );
									
									</script>
									';
			 }
	if(""==""){
		
              echo'</div>
				 </div>
				 </div>

				</div>
				<!-- /.container-fluid -->

			  </div>
			  <!-- End of Main Content -->

			  

			</div>
			<!-- End of Content Wrapper -->

		  </div>
		  <!-- End of Page Wrapper -->

		  <!-- Scroll to Top Button-->
		  <a class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		  </a>

		  <!-- Logout Modal-->
		  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
				  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				  </button>
				</div>
				<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
				<div class="modal-footer">
				  <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
				  <a class="btn btn-primary" style="Color:white;" onClick="location.href=\'?logout\';return false;">Logout</a>
				</div>
			  </div>
			</div>
		  </div>
		  
		  
		  
		  
		  


            
		  <!-- Core plugin JavaScript-->
		  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

		  <!-- Custom scripts for all pages-->
		  <script src="js/sb-admin-2.min.js"></script>

		  <!-- Page level plugins -->
		  <script src="vendor/chart.js/Chart.min.js"></script>
		  
		  <!-- Page level plugins -->
          <script src="vendor/datatables/jquery.dataTables.min.js"></script>
          <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
  
          <!-- Page level custom scripts -->
          <script src="js/demo/datatables-demo.js"></script>

		  <!-- Page level custom scripts -->
		  <script src="js/demo/chart-area-demo.js"></script>
		  <script src="js/demo/chart-pie-demo.js"></script>
		  
		  </body>
		</html>
		  ';}

}


else{
	header("Location: ../");
}


$dbCon->close(); 
$con->close(); 
?>