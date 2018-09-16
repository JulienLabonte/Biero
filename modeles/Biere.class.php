<?php
/**
 * Class Biere
 * 
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2017-08-16
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 * 
 */
class Biere {
	const NOMBRE_PAR_PAGE =10;
	private $_bieres;
    private $_conn;
    private $_resultat;
    
	function __construct ()
	{
        $this->_conn = mysqli_connect("localhost", "root", "", "bieres"); //Connection à la database
        $this->_conn->set_charset("utf-8");
        $sql = "SELECT * FROM bieresListe"; //Requete sql
        $resultat = mysqli_query($this->_conn, $sql); //Effectue la requete dans la db
        $this->_bieres = mysqli_fetch_all($resultat, MYSQLI_ASSOC); //Recupere un tableau contenant toutes les bières
	}
	
	function __destruct ()
	{
		
	}
	
		
	/**
	 * @access public
	 * @return Array
	 */
	public function getDonnees() 
	{
		return $this->_bieres;
	}
	
	/**
	 * Retourne les produits d'une page
	 * @access public
	 * @param 
	 * @return Array
	 */
	public function getBieres($nPage=1) 
	{
		$nMaxPage =ceil(count($this->_bieres)/self::NOMBRE_PAR_PAGE); //Calcule du nombre de pages à afficher
		 
		if($nPage>$nMaxPage)
		{
			$nPage = $nMaxPage;
		}
		$aBiere = array_chunk($this->_bieres, 10); //Retourne les 10 bières correspondant à la page sur laquelle nous sommes
		
		return $aBiere[$nPage-1];
	}
	
	/**
	 * Retourne les informations d'une bière
	 * @access public
	 * @param 
	 * @return Array
	 */
	public function getBiere($id_biere) 
	{
        $res = array();
		if(isset($this->_bieres[$id_biere])){ //Récupère la bière correspondant à celle sélectionné dans la liste par l'utilisateur
			$res = $this->_bieres[$id_biere];
            $format = $res['format'];
            $aFormat = json_decode($format, true); //Decode le json des formats pour en faire un array php
            $res['format'] = $aFormat; //Remplace le json de format par le tableau decodé
		}
		return $res;
	}
	
	public function ajoutBiere($aBiere){
        if(!empty($aBiere) && is_array($aBiere))
		{
			$id = count($this->_bieres); //Prend l'id qui vient à la fin du tableau
            $aBiere['format'] = json_encode($aBiere['format']);
            $requete = "INSERT INTO bieresListe (nom, brasserie, description, type, format, public) VALUES ('".mysqli_real_escape_string($this->_conn, $aBiere['nom'])."', '".mysqli_real_escape_string($this->_conn, $aBiere['brasserie'])."', '".mysqli_real_escape_string($this->_conn, $aBiere['description'])."', '".mysqli_real_escape_string($this->_conn, $aBiere['type'])."', '".mysqli_real_escape_string($this->_conn, $aBiere['format'])."', '".mysqli_real_escape_string($this->_conn, $aBiere['public'])."')"; //Requete insérant la nouvelle bière dans la db
            $this->_resultat = mysqli_query($this->_conn, $requete);
		}
        return $id;
	}
	
	public function effaceBiere($id){
		$requete = "DELETE FROM bieresListe WHERE IdBiere=".mysqli_real_escape_string($this->_conn, $id); //Requete supprimant la biere du tableau. L'Id est celui de la position de la bière dans le tableau des bière et non l'Id auto incrementé dans la db
        $this->_resultat = mysqli_query($this->_conn, $requete);
	}
	
	public function modifBiere($aBiere, $id){
        if(!empty($aBiere) && is_array($aBiere))
		{
            $aBiere['format'] = json_encode($aBiere['format']);
            $requete = "UPDATE bieresListe SET nom='".mysqli_real_escape_string($this->_conn, $aBiere['nom'])."', brasserie='".mysqli_real_escape_string($this->_conn, $aBiere['brasserie'])."', description='".mysqli_real_escape_string($this->_conn, $aBiere['description'])."', type='".mysqli_real_escape_string($this->_conn, $aBiere['type'])."', format='".mysqli_real_escape_string($this->_conn, $aBiere['format'])."', public='".$aBiere['public']."'WHERE IdBiere=".mysqli_real_escape_string($this->_conn, $id); //Requete mettant à jour les informations du post dans la database. L'id utilisé ici n'est pas la position du tableau comme dans efface, mais bien l'Id, dans la database, de la bière pour s'assurer de supprimer la bonne bière.
            $this->_resultat = mysqli_query($this->_conn, $requete);
		}
	}
	
	
	//array_replace
	
	/**
	 * Retourne les produits d'une page
	 * @access public
	 * @param 
	 * @return Array
	 */
	public function getNombrePages() 
	{
		return ceil(count($this->_bieres)/self::NOMBRE_PAR_PAGE);
	}
}




?>