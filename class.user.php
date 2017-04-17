<?php

require_once 'dbconfig.php';

class USER
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function lasdID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}
	
	public function regSpinera($spineraFbId,$spineraName,$spineraEmotion)
	{
		try
		{					
			//$stmt = $this->conn->prepare("INSERT INTO tbl_users(spinera_fb_id,spinera_name,spinera_emotion) 
			//                                             VALUES(:spinera_fb_id, :spinera_name, :spinera_emotion)");
			$stmt = $this->conn->prepare("INSERT INTO tbl_spinera (spinera_fb_id, spinera_name, spinera_emotion)
					SELECT * FROM (SELECT :spinera_fb_id, :spinera_name, :spinera_emotion) AS tmp
					WHERE NOT EXISTS (
					    SELECT spinera_fb_id FROM tbl_spinera WHERE spinera_fb_id = :spinera_fb_id
					) LIMIT 1;");
			$stmt->bindparam(":spinera_fb_id",$spineraFbId);
			$stmt->bindparam(":spinera_name",$spineraName);
			$stmt->bindparam(":spinera_emotion",$spineraEmotion);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}

	public function register($uname,$email,$upass,$code)
	{
		try
		{							
			$password = md5($upass);
			$stmt = $this->conn->prepare("INSERT INTO tbl_users(userName,userEmail,userPass,tokenCode) 
			                                             VALUES(:user_name, :user_mail, :user_pass, :active_code)");
			$stmt->bindparam(":user_name",$uname);
			$stmt->bindparam(":user_mail",$email);
			$stmt->bindparam(":user_pass",$password);
			$stmt->bindparam(":active_code",$code);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	
	public function rfidRegister($uID,$rfidTag)
	{
		try
		{							
			$stmt = $this->conn->prepare("INSERT INTO tbl_rfidowners(ownerUser,ownerRfid) 
			                                             VALUES(:owner_user, :owner_rfid)");
			$stmt->bindparam(":owner_user",$uID);
			$stmt->bindparam(":owner_rfid",$rfidTag);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	public function checkrfid($rfidOwner,$rfidStatus,$sumaMinutos, $disponiblesMinutos)
	{
		try
		{	
			$stmt = $this->conn->prepare("INSERT INTO tbl_rfid(rfidOwner,rfidStatus, sumaMinutos, disponiblesMinutos) 
			                                             VALUES(:rfid_owner, :rfid_status, :suma_minutos, :disponibles_minutos)");
			$stmt->bindparam(":rfid_owner",$rfidOwner);
			$stmt->bindparam(":rfid_status",$rfidStatus);
			$stmt->bindparam(":suma_minutos",$sumaMinutos);
			$stmt->bindparam(":disponibles_minutos",$disponiblesMinutos);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	public function payment($payUserID, $payPlan, $payMetodo, $payPesos, $payReceptor)
	{
		try
		{	
			$stmt = $this->conn->prepare("INSERT INTO tbl_pay(payUserID, payPlan, payMetodo, payPesos, payReceptor) 
			                                             VALUES(:pay_user_id, :pay_plan, :pay_metodo, :pay_pesos, :pay_receptor)");
			$stmt->bindparam(":pay_user_id",$payUserID);
			$stmt->bindparam(":pay_plan",$payPlan);
			$stmt->bindparam(":pay_metodo",$payMetodo);
			$stmt->bindparam(":pay_pesos",$payPesos);
			$stmt->bindparam(":pay_receptor",$payReceptor);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	public function newplan($uplanUserID, $uplanPlanID, $uplanAdminID)
	{
		try
		{	
			$stmt = $this->conn->prepare("INSERT INTO tbl_userplan(uplanUserID, uplanPlanID, uplanAdminID) 
			                                             VALUES(:uplan_userID, :uplan_planID, :uplan_adminID)");
			$stmt->bindparam(":uplan_userID",$uplanUserID);
			$stmt->bindparam(":uplan_planID",$uplanPlanID);
			$stmt->bindparam(":uplan_adminID",$uplanAdminID);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}

	public function saveinfo($infoUserID,$infoNombre,$infoApellido,$infoNacimiento,$infoRut,$infoEmpresa,$infoRutE,$infoCelular,$infoCalle,$infoNumero,$infoDepto,$infoComuna,$infoRegion,$infoCalleE,$infoNumeroE,$infoOfE,$infoComunaE,$infoRegionE)
	{
		try
		{							
			$stmt = $this->conn->prepare("INSERT INTO tbl_infouser(infoUserID,infoNombre,infoApellido,infoNacimiento,infoRut,infoEmpresa,infoRutE,infoCelular,infoCalle,infoNumero,infoDepto,infoComuna,infoRegion,infoCalleE,infoNumeroE,infoOfE,infoComunaE,infoRegionE) 
			                                             VALUES(:info_userID, :info_nombre, :info_apellido, :info_nacimiento, :info_rut, :info_empresa, :info_rutE, :info_celular, :info_calle, :info_numero, :info_depto, :info_comuna, :info_region, :info_calleE, :info_numeroE, :info_ofE, :info_comunaE, :info_regionE)");
			$stmt->bindparam(":info_userID",$infoUserID);
			$stmt->bindparam(":info_nombre",$infoNombre);
			$stmt->bindparam(":info_apellido",$infoApellido);
			$stmt->bindparam(":info_nacimiento",$infoNacimiento);
			$stmt->bindparam(":info_rut",$infoRut);
			$stmt->bindparam(":info_empresa",$infoEmpresa);
			$stmt->bindparam(":info_rutE",$infoRutE);
			$stmt->bindparam(":info_celular",$infoCelular);
			$stmt->bindparam(":info_calle",$infoCalle);
			$stmt->bindparam(":info_numero",$infoNumero);
			$stmt->bindparam(":info_depto",$infoDepto);
			$stmt->bindparam(":info_comuna",$infoComuna);
			$stmt->bindparam(":info_region",$infoRegion);
			$stmt->bindparam(":info_calleE",$infoCalleE);
			$stmt->bindparam(":info_numeroE",$infoNumeroE);
			$stmt->bindparam(":info_ofE",$infoOfE);
			$stmt->bindparam(":info_comunaE",$infoComunaE);
			$stmt->bindparam(":info_regionE",$infoRegionE);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}

	public function login($email,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM tbl_users WHERE userEmail=:email_id");
			$stmt->execute(array(":email_id"=>$email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($stmt->rowCount() == 1)
			{
				if($userRow['userStatus']=="Y")
				{
					if($userRow['userPass']==md5($upass))
					{
						$_SESSION['userSession'] = $userRow['userID'];
						return true;
					}
					else
					{
						header("Location: index.php?error");
						exit;
					}
				}
				else
				{
					header("Location: index.php?inactive");
					exit;
				}	
			}
			else
			{
				header("Location: index.php?error");
				exit;
			}		
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	
	public function is_logged_in()
	{
		if(isset($_SESSION['userSession']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function logout()
	{
		session_destroy();
		$_SESSION['userSession'] = false;
	}
	
	function send_mail($email,$message,$subject)
	{						
		require_once('mailer/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP(); 
		$mail->SMTPDebug  = 0;                     
		$mail->SMTPAuth   = true;                  
		$mail->SMTPSecure = "ssl";                 
		$mail->Host       = "smtp.gmail.com";      
		$mail->Port       = 465;             
		$mail->AddAddress($email);
		$mail->Username="admin@cecowork.com";  
		$mail->Password="Felopo10";            
		$mail->SetFrom('admin@cecowork.com','Administrador CeCowork');
		$mail->AddReplyTo("admin@cecowork.com","Administrador CeCowork");
		$mail->Subject    = $subject;
		$mail->MsgHTML($message);
		$mail->Send();
	}	
}