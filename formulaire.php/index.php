<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// $tab=[];
// $index=[];
// $obj=[];
//include("formulaire.php");
//include("contenu.php");
//$tache=$_POST['tache'];
//$afaire=$_POST['afaire'];

//$tache=['test', 'coucou'];
//$tachejson= json_encode($tache);

//echo $tachejson;

//file_put_contents("todo.json", "bonjour");

?>
<?php
function ecrireJSON($tache, $terminer)
{
/*appel de la fonction "tableauJSON", $tabjson reçoit un tableau d'objet JSON*/
$tabjson = tableauJSON();
/*CREATION JSON*/
/*Création d'une table ($tab) qui deviendra un objet JSON*/
//global $tab;
$tab = array("Nom" => $tache, "Terminer" => $terminer );
/*ajout de l'objet JSON dans la table qui reçois les objets JSON*/
$tabjson[] = $tab;
sauvegardeJSON($tabjson);
}
/*##################################################################*/
/*fonction qui ouvre le fichier (todo.json) et le transforme en tableau d'objets JSON*/
/*retourne le tableau d'objets JSON*/
function tableauJSON()
{
/*nom du fichier*/
$filename = "todo.json";
/*récupère la totalité du fichier (todo.json) sous forme de chaîne caractère*/
$file = file_get_contents($filename);
/*crée une variable table qui va recevoir les objets JSON*/
$tabjson;
if(empty($file)) 
/*si le fichier est vide : crée une table*/
$tabjson = json_decode("[]");
else /*sinon : il decode la chaîne de caractère en objets JSON*/
$tabjson = json_decode($file);
return $tabjson;
}
/*##################################################################*/
/*fonction qui sauvegarde un tableau d'objets JSON dans le fichie (todo.json)*/
/*reçoit comme paramètre un tableau d'objets JSON ($tabjson)*/
/*retourne false en cas d'erreur ou le nombre de caractères en cas de réussite*/

/*CONTENU*/
/*fonction affiche json en html*/
function afficheJSON($termin)
{
$tabjson=tableauJSON();

/*Boucle sur le tableau*/
//global $tab;
for($i=0; $i < sizeof($tabjson); $i++)
{
$obj=$tabjson[$i];

if($obj->Terminer == $termin)
{
// var_dump($obj);
//print_r($obj);
/*balise ouvrante <label>*/
$txt = '<label class="';
$txt .= $termin?"tache_terminer":"tache_non_terminer";
$txt .= '" for="">';
/*début : balise <input>*/
$txt .= '<input type="checkbox" name="tacheligne[]" value="';
/*$i représente le numero de la ligne*/
$txt .= $i.'" ';
/*si la valeur $termin est vraie ajouter l'attribut "checked" */
$txt .= $termin?"checked":"";
$txt .= ">";
//$obj['Terminer'] = true;
/*fin : balise <input>*/
/*balise fermante <label>*/
$txt .= $obj->Nom.'</label>';
$txt .= "<br/>";
echo $txt;
}
}
}

function enregistreJSON($index)
{
/*appel de la fonction "tableauJSON" $tabjson reçoit un tableau d'objet JSON*/
$tabjson = tableauJSON();
// var_dump($tabjson);
/*casting de la variable $index en INT*/
$index = (int)$index;
/*place l'objet JSON à l'index ($index) du tableau ($tabjson) dans la variable ($obj)*/
$obj = $tabjson[$index];

 // var_dump($obj);
 /*modifie la valeur "Terminer" de l'objet JSON $obj en son inverse (true <-> false)*/
 $obj->Terminer = !$obj->Terminer;
 /*utilise la fonction "sauvegardeJSON" en lui envoyant un tablreau d'objets JSON ($tabjson)*/
 sauvegardeJSON($tabjson);

}
function sauvegardeJSON($tabjson)
{
/*nom du fichier JSON*/
$filename = "todo.json";
/*encode la table d'objets JSON en format chaîne de caractères*/
$str_json = json_encode($tabjson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
/*inscript la chaîne de caractère dans le fichier (todo.json)*/
$resultat = file_put_contents($filename, $str_json);
/*retourne le résultat ($resultat)*/
return $resultat;
}

/*FORMULAIRE*/
/*Sanitisation*/
$options = array(
'tache' => FILTER_SANITIZE_STRING,
'tacheligne' => FILTER_SANITIZE_STRING
);
$result = filter_input_array(INPUT_POST, $options);
/*fin Sanitisation*/
//Requête POST:
//vérification des valeurs après la Sanitisation
if($result != null && $result != FALSE && $_SERVER['REQUEST_METHOD']=='POST')
{

if(isset($_POST["submit"])){
$tache=$_POST["tache"];
ecrireJSON($tache, false);
}

if(isset($_POST["ajouter"])) {

 $tache_ligne = $_POST["tacheligne"];
 // print_r($tache_ligne);
 for($i = 0; $i < sizeof($tache_ligne); $i++){
  enregistreJSON($tache_ligne[$i]);
  // enregistreJSON($tache_ligne);

 }

}
/*nom de la tache contenu dans le "TextBox"*/
/*$tache=$_POST["tache"];
/utilisation de la fonction ecrireJSON/
/ecrireJSON($tache, false);*/
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <title>To-do list</title>
  </head>
  <body>
    <fieldset class="afaire">
      <form action="index.php" method="POST">
        <h5>
          A FAIRE
        </h5>
        <?php afficheJSON(false); ?>
        <input class="button" type="submit" name="ajouter" value="Fini">
      </form>
    </fieldset>
    <fieldset class="archive">
      <h5>
        ARCHIVE
      </h5>
      <?php afficheJSON(true); ?>

    </fieldset>
    <form method="POST" action="index.php">
      <fieldset class="task">
        <label for="tache">Ajouter une tâche</label>
        <p><span>Liste des tâches a effectuer</span></p>
        <input type="text" name="tache" value="">
        <input class="button" type="submit" name="submit" value="Valider">
      </fieldset>
    </form>
  </body>
</html>



