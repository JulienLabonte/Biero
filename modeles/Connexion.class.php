<?php

class Connexion {
	private $_Conn;
	
	
	
	public function __construct(){
		$this->_Conn = new mysqli("localhost", "root", "", "bieres");
		$this->_Conn->set_charset("utf-8");
	}
	
	
	public function SeConnecter($etat){
		$_SESSION['connecter'] = $etat; //Recoit un état true ou false qui est assigné à connecter dans session
	}
	
	public function VerifConnection($user, $password){
		$connection = false;
		$user = $this->_Conn->real_escape_string($user);
		$password = $this->_Conn->real_escape_string($password);
		
		
		$res = $this->_Conn->query("SELECT * FROM utilisateurs WHERE Login='".$user."' LIMIT 0,1"); //Verifie que le nom d'utilisateur est bien dans la database
		if($res)
		{
			$aUser = $res->fetch_assoc();
			if($password == $aUser['MdeP']) //test le password associé au nom d'utilisateur
			{
				$connection = true; //donne l'état true
			}
		}
									
		return $connection;
	}
	
	
	
}

?>