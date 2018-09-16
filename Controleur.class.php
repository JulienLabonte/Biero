<?php
/**
 * Class Controleur
 * Gère les requêtes HTTP
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2013-12-10
 * @update 2016-01-22 : Adaptation du code aux standards de codage du département de TIM
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 * 
 */

class Controleur 
{
	
		/**
		 * Traite la requête
		 * @return void
		 */
		public function gerer()
		{
			
			switch ($_GET['requete']) {
				case 'liste':
					$this->listeProduit();
					break;
				case 'biere':
					$this->biere();
					break;
				case 'ajout':
					$this->ajout();
					break;
				case 'effacer':
					$this->effacer();
					break;
				case 'confirmEfface':
					$this->confirmEfface();
					break;
				case 'modifier':
					$this->modifier();
					break;
				case 'confirmModif':
					$this->confirmModif();
					break;
                case 'connecter':
                    $this->connecter();
                    break;
                case 'deconnecter':
                    $this->deconnecter();
                    break;
				default:
					$this->listeProduit();
					break;
			}
		}
		private function accueil()
		{
			$oVue = new Vue();
			$oVue->afficheEntete();
			$oVue->afficheAccueil();
			$oVue->affichePied();
		}
		// Placer les méthodes du controleur.
		
		private function listeProduit(){
			if($_SESSION['connecter'] == false){ //Cette condition se retrouve dans le reste des fonctions pour vérifier si la connexion est faite avant de permettre à l'utilisateur d'utiliser les diverses fonctionnalité. Cela empêche l'utilisateur de faire des modifs ou suppressions par l'URL
                $oVue = new Vue();
                $oVue->afficheEntete();
                $oVue->afficheAccueil();
                $oVue->affichePied();
            }else if($_SESSION['connecter'] == true){
                $oBiere = new Biere();
                $nBiere = $oBiere->getNombrePages();

                $aBieres = $oBiere->getBieres($_GET['page']); //Récupere le nombre de pages

                $oVue = new Vue();
                $oVue->afficheEntete();
                $oVue->afficheListe($aBieres, $nBiere, $_GET['page']); //Va chercher les bières à afficher selon la page sélectionnée
                $oVue->affichePied();
            }
		}
		
		private function biere(){
			if($_SESSION['connecter'] == false){
                $oVue = new Vue();
                $oVue->afficheEntete();
                $oVue->afficheAccueil();
                $oVue->affichePied();
            } else if($_SESSION['connecter'] == true){
                $oBiere = new Biere();

                $aBiere = $oBiere->getBiere($_GET['id_biere']); //Récupère les infos de la bière sélectionnée

                $oVue = new Vue();
                $oVue->afficheEntete();
                $oVue->afficheBiere($aBiere, $_GET['id_biere']);
                $oVue->affichePied();
            }
		}
		
		private function ajout(){
			if($_SESSION['connecter'] == false){
                $oVue = new Vue();
                $oVue->afficheEntete();
                $oVue->afficheAccueil();
                $oVue->affichePied();
            } else if($_SESSION['connecter'] == true){
                $oVue = new Vue(); 
                if(!empty($_POST['nom'])&&!empty($_POST['brasserie'])&&!empty($_POST['description'])&&isset($_POST['type'])&&isset($_POST['format'])&&isset($_POST['public'])){ //Test que tout est remplis
                    $oBiere = new Biere();
                    unset($_POST['bout']);
                    $oBiere->ajoutBiere($_POST);
                    $this->listeProduit();
                } else if(!empty($_POST)){ //Si certaines infos sont rentrées, mais pas toutes
                    $aBiere = $_POST;
                    if(!isset($_POST['format'])){ //Si le format n'est pas set dans le post, met tableau vide. Sans ca, il y a une erreur
                        $aBiere['format']=array();
                    }
                    if(!isset($_POST['public'])){ //Si le public n'est pas set dans le post, met tableau vide. Sans ca, il y a une erreur
                        $aBiere['public']=array();
                    }
                    $oVue = new Vue();
                    echo "<p>Veuillez tout remplir</p>"; //Message d'erreur
                    $oVue->afficheEntete();
                    $oVue->ajoutBiere($aBiere);
                    $oVue->affichePied();
                } else{ //Cas par défaut, lorsque charge la page et que rien n'est rentré
                    $oVue = new Vue();
                    $oVue->afficheEntete();
                    $oVue->ajoutBiere();
                    $oVue->affichePied();
                }
            }
	           
		}
		
		private function effacer(){
			if($_SESSION['connecter'] == false){
                $oVue = new Vue();
                $oVue->afficheEntete();
                $oVue->afficheAccueil();
                $oVue->affichePied();
            } else if($_SESSION['connecter'] == true){
                $oBiere = new Biere();

                $aBiere = $oBiere->getBiere($_GET['id_biere']);

                $oVue = new Vue();
                $oVue->afficheEntete();
                $oVue->confirmEff($aBiere, $_GET['id_biere']); //Affiche la bière à effacer. Demande confirmation
                $oVue->affichePied();
            }
		}
		
		private function confirmEfface(){
			if($_SESSION['connecter'] == false){
                $oVue = new Vue();
                $oVue->afficheEntete();
                $oVue->afficheAccueil();
                $oVue->affichePied();
            } else if($_SESSION['connecter'] == true){
                $oBiere = new Biere();
                $aBiere = $oBiere->effaceBiere($_GET['id_biere']); //Efface la bière 

                $this->listeProduit();
            }
		}
		
		private function modifier(){
			if($_SESSION['connecter'] == false){
                $oVue = new Vue();
                $oVue->afficheEntete();
                $oVue->afficheAccueil();
                $oVue->affichePied();
            } else if($_SESSION['connecter'] == true){
                $oBiere = new Biere();
                $aBiere = $oBiere->getBiere($_GET['id_biere']);

                $oVue = new Vue();
                $oVue->afficheEntete();
                $oVue->confirmModif($aBiere, $aBiere['IdBiere']); //Affiche la bière à modifier
                $oVue->affichePied();
            }
		}
		
		private function confirmModif(){
			
            //Même scénario de validation que dans le cas de l'ajout d'une bière
            if($_SESSION['connecter'] == false){
                $oVue = new Vue();
                $oVue->afficheEntete();
                $oVue->afficheAccueil();
                $oVue->affichePied();
            } else if($_SESSION['connecter'] == true){
                if(!empty($_POST['nom'])&&!empty($_POST['brasserie'])&&!empty($_POST['description'])&&isset($_POST['type'])&&isset($_POST['format'])&&isset($_POST['public'])){
                    $oBiere = new Biere();
                    unset($_POST['boutMod']);
                    $oBiere->modifBiere($_POST, $_GET['id_biere']);

                    $oVue = new Vue();
                    $this->listeProduit();
                } else if(!empty($_POST)){ //S'assure que, s'il manque des éléments, mais que certains sont présents, tout est réécrit dans le formulaire sans erreurs
                    $aBiere = $_POST;
                    if(!isset($_POST['format'])){
                        $aBiere['format']=array();
                    }
                    if(!isset($_POST['public'])){
                        $aBiere['public']=array();
                    }
                    $oVue = new Vue();
                    echo "<p>Veuillez tout remplir</p>"; //Affiche message d'erreur
                    $oVue->afficheEntete();
                    $oVue->confirmModif($aBiere, $_GET['id_biere']);
                    $oVue->affichePied();
                } else{
                    $oVue = new Vue();
                    $oVue->afficheEntete();
                    $oVue->confirmModif($aBiere, $_GET['id_biere']);
                    $oVue->affichePied();
                }
            }
        }
    
        private function connecter(){
			$oConnexion = new Connexion();
			
			
			if(!empty($_POST['Login']) && !empty($_POST['Password'])){ //Vérifie que les infos sont entrées
                $res = $oConnexion->VerifConnection($_POST['Login'], $_POST['Password']); //Test les infos
				if($res == true){ //Si ok, connecte l'utilisateur
					$oConnexion->SeConnecter($res);
					header("Location: index.php?requete=liste");
				}
				else{
					$oVue = new Vue();
                    $oVue->afficheEntete();
                    $oVue->AfficheAccueil('mauvaisesInfo');
                    $oVue->affichePied();
				}
			} else{
				$oVue = new Vue();
				$oVue->afficheEntete();
				$oVue->AfficheAccueil('vide');
				$oVue->affichePied();
			}
			
		}
		
		private function deconnecter(){
			$oConnexion = new Connexion();
			$oConnexion->SeConnecter(false); //Déconnecte l'utilisateur
			header("Location: index.php?requete=liste");
		}
}
?>















