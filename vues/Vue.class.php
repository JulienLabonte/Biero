<?php
/**
 * Class Vue
 * Modèle de classe Vue. Dupliquer et modifier pour votre usage.
 * 
 * @author Jonathan Martel
 * @version 1.1
 * @update 2013-12-11
 * @update 2016-01-22 : Adaptation du code aux standards de codage du département de TIM
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 * 
 */


class Vue {

	/**
	 * Produit l'entête html
	 * @access public
	 * @return void
	 */
	public function afficheEntete() {
		?>
		<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Biero</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">
		
		<link rel="stylesheet" href="./css/normalize.css" type="text/css" media="screen">
		<link rel="stylesheet" href="./css/base_h5bp.css" type="text/css" media="screen">
		<link rel="stylesheet" href="./css/main.css" type="text/css" media="screen">
		
		<script src="./js/plugins.js"></script>
		<script src="./js/main.js"></script>
	</head>

	<body>
 		<header class="monEntete">
	        <h1>Bièro : Administration</h1>
	        
    	</header>
    	<main>
    	<nav class="panneauNavigation">
    		<ul>
    			<li><a href="?requete=liste">Liste des bières</a></li>
    			<li><a href="?requete=ajout">Ajouter une bière</a></li>
    		</ul>
    		
    	</nav>
		
		<?php
	}
	
	/**
	 * Contenu de la page d'accueil
	 * @access public
	 * @return void
	 */
	public function afficheAccueil($erreur = "") {
		
		?>
		<div class='accueil'>
		<p>Veuillez vous connecter</p>
        <?php //Selon le type d'erreur recu, affiche un message différent
            if($erreur == 'vide'){
                echo "<p>Veuillez remplir les deux champs</p>";
            } else if($erreur == 'mauvaisesInfo'){
                echo "<p>Mauvais nom d'utilisateur ou mot de passe</p>";
            }
        ?>
		<form method="post" action="?requete=connecter">
			<p>Utilisateur : <input type="text" name="Login" /></p>
			<p>Mot de passe : <input type="password" name="Password" /></p>
		<button type="submit">Se connecter</button>
		</form>
		</div>
		<?php
		
	}
	
	
	/**
	 * Contenu de la page d'accueil
	 * @access public
	 * @return void
	 */
	public function afficheListe($aBieres, $nPage, $nPageActive) {
		
		?>
		<section class="liste">
			<article class='titre'>
			<span class='nom'>Nom</span>
			<span class='brasserie'>Brasserie</span>
			<span class='description'>Description</span>
			<span class='public'>Public?</span>
			<span class='flex_filler'></span>
			<span class='action'>Action</span>
			</article>
			<?php
				foreach ($aBieres as $cle => $aUneBiere) { //Affiche la liste des bières pour chaque ligne dans le tableau, clé par clé
					$index = ($nPageActive-1)*10+$cle;
					echo "<article class='biere'>";
					echo "<span class='nom'><a href='?requete=biere&id_biere=".$index."'>". $aUneBiere['nom'] . "</a></span>";
					echo "<span class='brasserie'>".$aUneBiere['brasserie']."</span>";
					echo "<span class='description'>". $aUneBiere['description']."</span>";
					echo "<span class='public'>". $aUneBiere['public']."</span>";
					echo "<span class='flex_filler'></span>";
					echo "<span class='action'><a href='?requete=modifier&id_biere=".$index."'>[Modif]</a><a href='?requete=effacer&id_biere=".$index."'>[Effacer]</a></span>";
					echo "</article>";
				}
			?>
		
			<section class="pager">
				<ul>
				<?php
					for($i=0;$i<$nPage;$i++)
					{
						echo "<li><a href='?requete=liste&page=". ($i+1) ."'>". ($i+1) ."</a></li>";
					}
				?>
				</ul>
			</section>
            <a href='?requete=deconnecter'>Se deconnecter</a>
		</section>
		<?php
		
	}
	
	
	
	/**
	 * Contenu de la page d'accueil
	 * @access public
	 * @return void
	 */
	public function afficheBiere($aBiere, $id) {
		?>
		<section >
			<p>Nom : <input disabled type='text' name='nom' value='<?php echo $aBiere['nom']?>' >
			<p>Brasserie : <input disabled type='text' name='brasserie' value='<?php echo $aBiere['brasserie']?>' >
			<p>Description : <textarea disabled name='description'><?php echo $aBiere['description']?></textarea>
			<p>Type : <select disabled name='type'><!--Vérifie ce qui est le type sélectionné et affiche le bon type-->
				<option value="ipa" <?php echo ($aBiere['type'] == 'ipa' ? 'selected':'')?> >IPA</option>
				<option value="brune" <?php echo ($aBiere['type'] == 'brune' ? 'selected' :'') ?>>Brune</option>
				<option value="blonde" <?php echo ($aBiere['type'] == 'blonde' ? 'selected' :'') ?>>Blonde</option>
				<option value="rousse" <?php echo ($aBiere['type'] == 'rousse' ? 'selected' :'') ?>>Rousse</option>
				<option value="lager" <?php echo ($aBiere['type'] == 'lager' ? 'selected' :'') ?>>Lager</option>
				<option value="stout" <?php echo ($aBiere['type'] == 'stout' ? 'selected' :'') ?>>Stout</option>
			 </select>
			<p>Formats disponibles : <!--Vérifie ce qui est dans la liste des formats et check ce qui est présent-->
				<p>350ml : <input disabled type='checkbox' <?php echo (in_array('350ml', $aBiere['format']) ? 'checked':'')?>  name='format[]' value='350ml'></p>
				<p>500ml : <input disabled type='checkbox' <?php echo (in_array('500ml', $aBiere['format']) ? 'checked':'')?> name='format[]' value='500ml'></p>
				<p>750ml : <input disabled type='checkbox' <?php echo (in_array('750ml', $aBiere['format']) ? 'checked':'')?> name='format[]' value='750ml'></p>
				<p>1.8l : <input disabled type='checkbox' <?php echo (in_array('1.8l', $aBiere['format']) ? 'checked':'')?> name='format[]' value='1.8l'></p>
				<p>Public : Oui : <input disabled type='radio' name='public' value='Oui' <?php echo ($aBiere['public'] == 'Oui' ? 'checked':'')?> > Non : <input disabled type='radio' name='public' value='Non' <?php echo ($aBiere['public'] == 'Non' ? 'checked':'')?> >	
                </br>
                <?php
                echo "<span class='action'><a href='?requete=modifier&id_biere=".$id."'>[Modif]</a><a href='?requete=effacer&id_biere=".$id."'>[Effacer]</a></span>";?>
	   </section>
		<?php
		
	}

	public function ajoutBiere($aBiere = array('nom'=>'','brasserie'=>'','description'=>'','type'=>'','format'=>array(),'public'=>array())) {
		/*Cas par défaut si il n'y a pas de tableau. Initie le bon tableau, completement vide.*/
		?>
        <section >
			<form method="post" action="?requete=ajout">
            <p>Nom : <input type='text' name='nom' value='<?php echo $aBiere['nom']?>' >
			<p>Brasserie : <input type='text' name='brasserie' value='<?php echo $aBiere['brasserie']?>' >
			<p>Description : <textarea name='description'><?php echo $aBiere['description']?></textarea>
			<p>Type : <select name='type'>
				<option value="ipa" <?php echo ($aBiere['type'] == 'ipa' ? 'selected':'')?> >IPA</option>
				<option value="brune" <?php echo ($aBiere['type'] == 'brune' ? 'selected' :'') ?>>Brune</option>
				<option value="blonde" <?php echo ($aBiere['type'] == 'blonde' ? 'selected' :'') ?>>Blonde</option>
				<option value="rousse" <?php echo ($aBiere['type'] == 'rousse' ? 'selected' :'') ?>>Rousse</option>
				<option value="lager" <?php echo ($aBiere['type'] == 'lager' ? 'selected' :'') ?>>Lager</option>
				<option value="stout" <?php echo ($aBiere['type'] == 'stout' ? 'selected' :'') ?>>Stout</option>
			 </select>
			<p>Formats disponibles : 
				<p>350ml : <input type='checkbox' <?php echo (in_array('350ml', $aBiere['format']) ? 'checked':'')?>  name='format[]' value='350ml'></p>
				<p>500ml : <input type='checkbox' <?php echo (in_array('500ml', $aBiere['format']) ? 'checked':'')?> name='format[]' value='500ml'></p>
				<p>750ml : <input type='checkbox' <?php echo (in_array('750ml', $aBiere['format']) ? 'checked':'')?> name='format[]' value='750ml'></p>
				<p>1.8l : <input type='checkbox' <?php echo (in_array('1.8l', $aBiere['format']) ? 'checked':'')?> name='format[]' value='1.8l'></p>
				<p>Public : Oui : <input  type='radio' name='public' value='Oui' <?php echo ($aBiere['public'] == 'Oui' ? 'checked':'')?> > Non : <input type='radio' name='public' value='Non' <?php echo ($aBiere['public'] == 'Non' ? 'checked':'')?> >	
                <p><input type="submit" name="bout" value="Soumettre"></p>
                </br>
	   </section>
		
		<?php
		
	}
	
	public function confirmEff($aBiere, $id) {
		/*Affiche la page de confirmation de l'effacage. L'idée recu est celui de la position de la bière dans le tableau des bières. Celui qui est dans le span pour confirmer la suppression est celui associé à la bière à effacer dans la database*/
		?>
		<section >
            <form method="post" action="?requete=confirmModif">
			<p>Nom : <input disabled type='text' name='nom' value='<?php echo $aBiere['nom']?>' >
			<p>Brasserie : <input disabled type='text' name='brasserie' value='<?php echo $aBiere['brasserie']?>' >
			<p>Description : <textarea disabled name='description'><?php echo $aBiere['description']?></textarea>
			<p>Type : <select disabled name='type'>
				<option value="ipa" <?php echo ($aBiere['type'] == 'ipa' ? 'selected':'')?> >IPA</option>
				<option value="brune" <?php echo ($aBiere['type'] == 'brune' ? 'selected' :'') ?>>Brune</option>
				<option value="blonde" <?php echo ($aBiere['type'] == 'blonde' ? 'selected' :'') ?>>Blonde</option>
				<option value="rousse" <?php echo ($aBiere['type'] == 'rousse' ? 'selected' :'') ?>>Rousse</option>
				<option value="lager" <?php echo ($aBiere['type'] == 'lager' ? 'selected' :'') ?>>Lager</option>
				<option value="stout" <?php echo ($aBiere['type'] == 'stout' ? 'selected' :'') ?>>Stout</option>
			 </select>
			<p>Formats disponibles : 
				<p>350ml : <input disabled type='checkbox' <?php echo (in_array('350ml', $aBiere['format']) ? 'checked':'')?>  name='format[]' value='350ml'></p>
				<p>500ml : <input disabled type='checkbox' <?php echo (in_array('500ml', $aBiere['format']) ? 'checked':'')?> name='format[]' value='500ml'></p>
				<p>750ml : <input disabled type='checkbox' <?php echo (in_array('750ml', $aBiere['format']) ? 'checked':'')?> name='format[]' value='750ml'></p>
				<p>1.8l : <input disabled type='checkbox' <?php echo (in_array('1.8l', $aBiere['format']) ? 'checked':'')?> name='format[]' value='1.8l'></p>
				<p>Public : Oui : <input disabled type='radio' name='public' value='Oui' <?php echo ($aBiere['public'] == 'Oui' ? 'checked':'')?> > Non : <input disabled type='radio' name='public' value='Non' <?php echo ($aBiere['public'] == 'Non' ? 'checked':'')?> >
				</br>
				<?php
				echo "<span><a href='?requete=confirmEfface&id_biere=". $aBiere['IdBiere'] ."'>[Confirmer]</a><a href='?requete=liste'>[Annuler]</a></span>";
				?>
		</form>
		
	</section>
	
	
	
	<?php
	}

	public function confirmModif($aBiere, $id) {
		/*Recoit la bière à modifier et permet à l'utilisateur de soumettre ses modifications*/
		?>
		<section >
			<form method="post" action="?requete=confirmModif&id_biere=<?php echo $id?>">
			<p>Nom : <input type='text' name='nom' value='<?php echo $aBiere['nom']?>' >
			<p>Brasserie : <input type='text' name='brasserie' value='<?php echo $aBiere['brasserie']?>' >
			<p>Description : <textarea name='description'><?php echo $aBiere['description']?></textarea>
			<p>Type : <select name='type'>
				<option value="ipa" <?php echo ($aBiere['type'] == 'ipa' ? 'selected':'')?> >IPA</option>
				<option value="brune" <?php echo ($aBiere['type'] == 'brune' ? 'selected' :'') ?>>Brune</option>
				<option value="blonde" <?php echo ($aBiere['type'] == 'blonde' ? 'selected' :'') ?>>Blonde</option>
				<option value="rousse" <?php echo ($aBiere['type'] == 'rousse' ? 'selected' :'') ?>>Rousse</option>
				<option value="lager" <?php echo ($aBiere['type'] == 'lager' ? 'selected' :'') ?>>Lager</option>
				<option value="stout" <?php echo ($aBiere['type'] == 'stout' ? 'selected' :'') ?>>Stout</option>
			 </select>
			<p>Formats disponibles : 
				<p>350ml : <input type='checkbox' <?php echo (in_array('350ml', $aBiere['format']) ? 'checked':'')?>  name='format[]' value='350ml'></p>
				<p>500ml : <input type='checkbox' <?php echo (in_array('500ml', $aBiere['format']) ? 'checked':'')?> name='format[]' value='500ml'></p>
				<p>750ml : <input type='checkbox' <?php echo (in_array('750ml', $aBiere['format']) ? 'checked':'')?> name='format[]' value='750ml'></p>
				<p>1.8l : <input type='checkbox' <?php echo (in_array('1.8l', $aBiere['format']) ? 'checked':'')?> name='format[]' value='1.8l'></p>
				<p>Public : Oui : <input type='radio' name='public' value='Oui' <?php echo ($aBiere['public'] == 'Oui' ? 'checked':'')?> > Non : <input type='radio' name='public' value='Non' <?php echo ($aBiere['public'] == 'Non' ? 'checked':'')?> >
				</br>
				<p><input type="submit" name="boutMod" value="Soumettre"></p>
		</form>
		
	</section>
		<?php
		
	}
	
	/**
	 * Produit le html du pied de page
	 * @access public
	 * @return void
	 */
	public function affichePied()
	{
		?>
		</main>
		<div id="footer">
					Certains droits réservés @ Jonathan Martel (2017)<br>
					Sous licence Creative Commons (BY-NC 3.0)
				</div>
			</div>	
		</body>
	</html>
	<?php
	}
	
	
	
}
?>