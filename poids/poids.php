<?
define('PUN_ROOT', '../../forum/');
require PUN_ROOT.'include/common.php';

include 'connect_bdd.php';

//nettoyage des signes dangeureux

$motif='`[][<>{}!\$?\*\|\"\^=/:\`;&#%]`';

foreach($_POST as $key => $value)
  {
$_POST[$key]= preg_replace($motif,"", $value);
$_POST[$key]= addslashes($value);
}

//Liste des poids des Produits

if ($_POST['index']=="liste" | $_GET['index']=="liste" )
{
if(isset($_GET['class']))
{$_POST['class']=$_GET['class'];}

if(isset($_GET['categories']))
{$_POST['categories']=$_GET['categories'];}

if(isset($_GET['marques']))
{$_POST['marques']=$_GET['marques'];}

$html='
<table class="inline">
   <tr>
    <th class="leftalign" ><a href="index.php?index=liste&class=categorie&categories='.$_POST['categories'].'&marques='.$_POST['marques'].'">Catégories</a></th>
    <th class="leftalign" ><a href="index.php?index=liste&class=marque&categories='.$_POST['categories'].'&marques='.$_POST['marques'].'">Marques</a></th>
    <th class="leftalign" ><a href="index.php?index=liste&class=nom&categories='.$_POST['categories'].'&marques='.$_POST['marques'].'">Modèles</a></th>
    <th class="leftalign" ><a href="index.php?index=liste&class=poids&categories='.$_POST['categories'].'&marques='.$_POST['marques'].'">Poids en g.</a></th>
    <th class="leftalign" ><a href="index.php?index=liste&class=utilisateur&categories='.$_POST['categories'].'&marques='.$_POST['marques'].'">Utilisateurs</a></th>
    <th class="leftalign" >Remarques</td>
    <th class="leftalign" >Modifier</td>
   </tr>';

if($_POST['categories']=="Toutes" & $_POST['marques']!="Toutes")
{
$req = "
SELECT *
FROM `poids_mat`
WHERE `marque` LIKE '".$_POST['marques']."'";
}
if($_POST['categories']!="Toutes" & $_POST['marques']=="Toutes")
{
$req = "
SELECT *
FROM `poids_mat`
WHERE `categorie` LIKE '".$_POST['categories']."'";
}
if($_POST['categories']=="Toutes" & $_POST['marques']=="Toutes")
{
$req = "
SELECT *
FROM `poids_mat`";
}
if($_POST['categories']!="Toutes" & $_POST['marques']!="Toutes")
{
$req = "
SELECT *
FROM `poids_mat`
WHERE
`categorie` LIKE '".$_POST['categories']."'
AND `marque` LIKE '".$_POST['marques']."'";
}
if(isset($_POST['class']))
{
$req.="ORDER BY `".$_POST['class']."` ASC";
}
else
{
$req.="ORDER BY `categorie` ASC";
}

$result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());

while ($row = mysql_fetch_assoc($result))
{
$html.='
<tr><td class="leftalign">'.$row["categorie"].'</td><td class="leftalign">'.
$row["marque"].'</td><td class="leftalign">'.
$row["nom"].'</td><td class="leftalign">'.
$row["poids"].'</td><td class="leftalign">'.
$row["utilisateur"].'</td><td class="leftalign">'.
$row["rq"].'</td>
<td style="text-align:center;"><a href="index.php?index=modif&ligne='.$row["num"].'"><img border="0" src="button_edit.png"></a></td>
</tr>
';
}
$html.='</table>
<br />
<a href=index.php>Retour</a>';

mysql_free_result($result);

echo $html;
}

//Ajout de poids de Produit

elseif ($_POST['index']=="ajout")
{
if(pun_htmlspecialchars($pun_user['username'])=="Guest")
{
echo '
<H2>Ajout d\'un produit :</H2>
Vous ne pouvez inscrire de données dans cette base en tant qu\'invité,
 veuillez tout d\'abord vous connecter sur le forum.<br />
 Merci de votre compréhension.
 <br /><br />
<a href=index.php>Retour</a>';
}
else
{
$html='<H2>Ajout d\'un produit :</H2>
<p>Les informations suivantes ont été ajoutées à la base de données :</p>';

// cas de la virgule

  if (ereg(',', $_POST['poids']))
  {
  $_POST['poids']=str_replace(",",".",$_POST['poids']);
  }
  $_POST['poids'] = (float) preg_replace("'[^\d]^.'","", $_POST['poids']);

  $req ="
  INSERT INTO `poids_mat` ( `num` , `categorie` , `marque` , `nom` , `utilisateur` , `poids` , `rq`, `date` )
  VALUES (
  NULL , '".$_POST['categories']."', '".$_POST['marques']."', '".ucfirst($_POST['modele'])."', '".pun_htmlspecialchars($pun_user['username'])."', '".$_POST['poids']."', '".ucfirst($_POST['rq'])."', '".time()."')";

$result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());

$html.='
<table class="inline">
   <tr>
    <th class="leftalign" >Catégorie</th>
    <th class="leftalign" >Marque</th>
    <th class="leftalign" >Modèle</th>
    <th class="leftalign" >Poids en g.</th>
    <th class="leftalign" >Utilisateur</th>
    <th class="leftalign" >Remarques</th>
   </tr>
   <tr>
    <td class="leftalign" >'.$_POST['categories'].'</td>
    <td class="leftalign" >'.$_POST['marques'].'</td>
    <td class="leftalign" >'.stripslashes($_POST['modele']).'</td>
    <td class="leftalign" >'.stripslashes($_POST['poids']).'</td>
    <td class="leftalign" >'.pun_htmlspecialchars($pun_user['username']).'</td>
    <td class="leftalign" >'.stripslashes($_POST['rq']).'</td>	
   </tr>
</table>
<br />
<a href=index.php>Retour</a>';

echo $html;
}
}

//Ajout de catégorie ou de marque des poids des Produit

elseif ($_POST['index']=="ajcatmrk")
{

$html='
<H2>Ajout d\'une catégorie ou d\'une marque :</H2>
<p>';

//nouvelle categorie

if($_POST['newcat']!="")
{
$_POST['newcat']=ucfirst($_POST['newcat']);

$req="
SELECT 'NomCat'
FROM `categories`
WHERE 1
AND `Nomcat` LIKE '".$_POST['newcat']."'";

$result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());

    if (mysql_num_rows($result)==0)
    {
$req="
INSERT INTO `categories` ( `NumCat` , `NomCat` )
VALUES (
NULL , '".$_POST['newcat']."'
)";
$result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());

$html.='La catégorie '.$_POST['newcat'].' a été ajouée à la liste.<br /><br />';
    }
else
    {
    $html.='La categorie '.$_POST['newcat'].' existe déja<br /><br />';
    }
}

//nouvelle marque

elseif($_POST['newmarq']!="")
{
$_POST['newmarq']=ucfirst($_POST['newmarq']);

$req="
SELECT 'Nommarq'
FROM `marques`
WHERE 1
AND `Nommarq` LIKE '".$_POST['newmarq']."'";

$result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());

    if (mysql_num_rows($result)==0)
    {
$req="
INSERT INTO `marques` ( `Nummarq` , `Nommarq` )
VALUES (
NULL , '".$_POST['newmarq']."'
)";
$result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());


$html.='La marque '.$_POST['newmarq'].' a été ajouée à la liste.<br />';
    }
    else
    {
    $html.='La marque '.$_POST['newmarq'].' existe déja';
    }
}
$html.='
</p>
<a href=index.php>Retour</a>';

echo $html;
}

//modification d'une donnée

elseif ($_GET['index']=="modif")
{

$_GET['ligne'] = preg_replace($motif ,"", $_GET['ligne']);
$_GET['ligne'] = (float) $_GET['ligne'];


$req="SELECT *
FROM `poids_mat`
WHERE 1 AND `num` =".$_GET['ligne'];

$result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());

$lg=mysql_fetch_row($result);

if(pun_htmlspecialchars($pun_user['username'])!=$lg[4])
{
$html='
<H2>Modifier un produit :</H2>
<p>
Vous n\'êtes pas l\'auteur de ces données, vous ne pouvez les modifier.<br />
<br />
<a href=index.php>Retour</a>';
echo $html;
}
elseif(time()-$lg[7]<60)
{
$html='
<H2>Modifier un produit :</H2>
<p>
Veuillez attendre quelques minutes avant de modifier à nouveau vos données.<br />
<br />
<a href=index.php>Retour</a>';
echo $html;
}
else
{
$html='
<H2>Modifier un produit :</H2>
<p>
<form name="f2" method="POST" action="index.php" onSubmit="return verif_formulaire()">
<table class="inline">
   <tr>
    <th class="leftalign" >Catégorie</th>
    <th class="leftalign" >Marque</th>
    <th class="leftalign" >Modèle</th>
    <th class="leftalign" >Poids en g.</th>
    <th class="leftalign" >Remarques</th>
   </tr>
   <tr>
	<td class="leftalign">
<select size="1" name="categories">
<option>-Choisissez-</option>';

$req="SELECT *
FROM `categories`
ORDER BY `NomCat` ASC";

$result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());

while ($row = mysql_fetch_assoc($result))
{
$html.= "<option>".$row["NomCat"]."</option>";
}
mysql_free_result($result);

$html.='
</select>
	</td>
	<td class="leftalign">
<select size="1" name="marques">
<option>-Choisissez-</option>';

$req="SELECT *
FROM `marques`
ORDER BY `NomMarq` ASC ";

$result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());

while ($row = mysql_fetch_assoc($result))
{
$html.= "<option>".$row["NomMarq"]."</option>";
}

mysql_free_result($result);

$html.='
</select>	
	</td>
	<td class="leftalign"><input type="text" name="modele" size="20" value="'.$lg[3].'"></td>
	<td class="leftalign"><input type="text" name="poids" size="10" value="'.$lg[5].'"></td>
	<td class="leftalign"><input type="text" name="rq" size="50" value="'.$lg[6].'"></td>
   </tr>
</table>
<br />
<input type="submit" value="Modifier">
<input name="ligne" type="hidden" value="'.$lg[0].'">
<input name="index" type="hidden" value="valmod">
</form>
<br />
<a href=index.php>Retour</a>
';

echo $html;
}
}

// Validation des modifications d'une donnée

elseif ($_POST['index']=="valmod")
{
$html='<H2>Modifier un produit :</H2>';

// cas de la virgule
if (ereg(',', $_POST['poids']))
{
$_POST['poids']=str_replace(",",".",$_POST['poids']);
}
$_POST['poids'] = (float) preg_replace("'[^\d]^.'","", $_POST['poids']);

$req="
UPDATE `poids_mat` SET `categorie` = '".$_POST['categories']."',
`marque` = '".$_POST['marques']."',
`nom` = '".$_POST['modele']."',
`utilisateur` = '".pun_htmlspecialchars($pun_user['username'])."',
`poids` = '".$_POST['poids']."',
`rq` = '".$_POST['rq']."', `date` = '".time().
"' WHERE `num` = '".$_POST['ligne']."' LIMIT 1";

$req2="
INSERT INTO `backup` ( `num` , `categorie` , `marque` , `nom` , `utilisateur` , `poids` , `rq`, `date` )
VALUES (NULL , '".$_POST['categories']."', '".$_POST['marques']."', '".ucfirst($_POST['modele'])."', '".pun_htmlspecialchars($pun_user['username'])."', '".$_POST['poids']."', '".ucfirst($_POST['rq'])."', '".time()."')";

$result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());
$result=mysql_query ($req2) or die('Erreur SQL req1!<br />'.mysql_error());
$html.='Votre enregistrement été modifié
<br /><br />
<a href=index.php>Retour</a>';

echo $html;

}
else
{
$html='<H2>Rechercher le poids d\'un produit :</H2>
<p><form name="f1" method="POST" action="index.php">
<table class="inline">
   <tr>
    <th class="leftalign" >Catégorie</th>
    <th class="leftalign" >Marque</th>
   </tr>
      <tr>
	<td class="leftalign">
<select size="1" name="categories">
<option>Toutes</option>';

// Liste categories

  $req="SELECT *
  FROM `categories`
  ORDER BY `NomCat` ASC";

  $result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());

  while ($row = mysql_fetch_assoc($result))
      {
      $html.= '<option>'.$row['NomCat'].'</option>';
      }
      mysql_free_result($result);

      $html.='
  </select>
	</td>
	<td class="leftalign">
  <select size="1" name="marques">
  <option>Toutes</option>';

// Liste marques

   $req="SELECT *
   FROM `marques`
   ORDER BY `NomMarq` ASC ";

   $result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());

   while ($row = mysql_fetch_assoc($result))
   {
   $html.='<option>'.$row['NomMarq'].'</option>';
   }
   mysql_free_result($result);

   $html.='
</select>	
	</td>
   </tr>
</table>
<br />
<input name="index" type="hidden" value="liste">
<input type="submit" value="Rechercher">
</form>
</p>';

//Ajouter un produit

$html .='<br />
<H2>Ajouter un produit :</H2>
<p>
<form name="f2" method="POST" action="index.php" onSubmit="return verif_formulaire()">
<table class="inline">
   <tr>
    <th class="leftalign" >Catégorie</th>
    <th class="leftalign" >Marque</th>
    <th class="leftalign" >Modèle</th>
    <th class="leftalign" >Poids en g.</th>
    <th class="leftalign" >Remarques</th>
   </tr>
      <tr>
	<td class="leftalign">
<select size="1" name="categories">
<option>-Choisissez-</option>';

$req="SELECT *
FROM `categories`
ORDER BY `NomCat` ASC";

$result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());

while ($row = mysql_fetch_assoc($result))
{
$html.='<option>'.$row["NomCat"].'</option>';
}
mysql_free_result($result);

$html.='</select>
	</td>
	<td class="leftalign">
<select size="1" name="marques">
<option>-Choisissez-</option>';


$req="SELECT *
FROM `marques`
ORDER BY `NomMarq` ASC ";

$result=mysql_query ($req) or die('Erreur SQL req1!<br />'.mysql_error());

while ($row = mysql_fetch_assoc($result))
{
$html.='<option>'.$row['NomMarq'].'</option>';
}

mysql_free_result($result);

$html.='
</select>	
	</td>
	<td class="leftalign"><input type="text" name="modele" size="20"></td>
	<td class="leftalign"><input type="text" name="poids" size="10"></td>
	<td class="leftalign"><input type="text" name="rq" size="50"></td>
   </tr>
</table>
<br />
<input name="index" type="hidden" value="ajout">
<input type="submit" value="Ajouter"> <input type="reset" value="Annuler">
</form>
</p>
<br />
<H2>Ajouter une catégorie ou une marque :</H2>
<p>
<form name="f3" method="POST" action="index.php" onSubmit="return verif_formulaire2()">

<table class="inline">
<tr>
    <th class="leftalign" >Nouvelle catégorie :</th>
    <td class="leftalign" ><input type="text" name="newcat" size="20"></td>
</tr>
<tr>
    <th class="leftalign" >Nouvelle marque:</th>
    <td class="leftalign" ><input type="text" name="newmarq" size="20"></td>
</tr>
</table>
<br />
<input name="index" type="hidden" value="ajcatmrk">
<input type="submit" value="Ajouter"> <input type="reset" value="Annuler">

</form>';
//ecrit la page
Echo $html;
}

mysql_close($db);
?>
