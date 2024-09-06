<?php

session_start();
$con = mysqli_connect("localhost", "adminpan_lonamt", "Amit@2020#", "adminpan_lonamt");
mysqli_set_charset($con,'utf8');
if($con->connect_error){
	echo "Database Connection Failed:";
}


date_default_timezone_set("Asia/Kolkata");


if($_SERVER["REQUEST_METHOD"] == "POST"){
	$name = $_POST['name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	//$phone1 = "91".$phone;
    $loanamt = $_POST['loanamt'];
	$prefix = "DHAN20200TR92R-";
	
	$checkexists = $con->query("SELECT * FROM applications WHERE email = '$email ' OR phone = '$phone'");
	if($checkexists->num_rows > 0){
		$_SESSION['error'] = "Email gender/Phone Number Already Exists!";
		header("location: https://dhaniinstantservices.github.io/index.html");
		exit();
	}else{
		$getappid = $con->query("SELECT * FROM applications ORDER BY id DESC;");
		if($getappid->num_rows == 0){
			$count = "0538";
			$app_id = $prefix.$count;
		}else{
			$lid = $getappid->fetch_assoc();
			$lastid = $lid['app_id'];
			$lastvalue = explode("-",$lastid);
			$nextvalue = sprintf("%'04d", $lastvalue[1]+1);
			$app_id = $prefix.$nextvalue;
		}
		$sql = "INSERT INTO applications (app_id, name, email, phone, loanamt, created_on) VALUES 	('$app_id', '$name', '$email', '$phone', '$loanamt', NOW())";
		if($con->query($sql) == TRUE){
			$_SESSION['success'] = "Application Submitted Successfully! Your Application Id is: $app_id";
			header("location: https://dhaniinstantservices.github.io/index.html");
			exit();
		}else{
		   // var_dump($con);
			$_SESSION['error'] = "Somethign went Wrong! Contact Admin";
			header("location: index.php");
			exit();
		}
		/*$mail = new PHPMailer(true);
			
			//Server settings
			$mail->SMTPDebug = 0;                                       // Enable verbose debug output
			$mail->isSMTP();                                            // Set mailer to use SMTP
			$mail->Host       = 'mail.pharmamedicalkendra.in';  // Specify main and backup SMTP servers
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		    $mail->Username   = 'noreply@pharmamedicalkendra.in';                     // SMTP username
			$mail->Password   = 'Amit@2020';                      // SMTP password
			$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
			$mail->Port       = 465;                                    // TCP port to connect to

			//Recipients
			$mail->setFrom('noreply@pharmamedicalkendra.in');					########Put the Email same as Above
			$mail->addgender('amitcreations2021@gmail.com');     						// Add a recipient
			
			// Content
			$mail->isHTML(true);                                  		// Set email format to HTML
			$mail->Subject = 'Loan Approval Sucessfully';
			$mail->Body    = '<P>Dear,<br>'.$name.'</p><P>Congratulation…..your Application '.$app_id.' for loan amount INR 
has accepted, your repayment plan XX months for your loan interest will be 5% and monthly EMI Rs. (XXX.25) for your loan, next process is to pay the processing charge. please make the payment of Rs { *2500* } To the account listed below. Note:-your Loan amount Will be credited Within one hrs. after completing the processing fees. </p><p>Your Application Id is '.$app_id.'</p><p>Thanks & Regards<p><p>Team Bajaj Finserv Ltd.</p>
';
			//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
*/
		/*	if($mail->send()){
				if($con->query($sql) == TRUE){
					
					$_SESSION['success'] = "Application Submitted Successfully! Your Application Id is: $app_id";
					//header("location: index.html");
					exit();
				}else{
					$_SESSION['error'] = "Somethign went Wrong! Contact Admin";
					//header("location: error.php");
					exit();
				}
			}else{
				$_SESSION['error'] = 'Please Enter correct email Id or contact Admin';
				//header("location: rahul.php");
				exit();
			}*/
	}
}else{
	$_SESSION['error'] = "Forbidden Access";
	header("location: acess.php");
	exit();
}
?>