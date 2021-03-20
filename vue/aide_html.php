<?php
session_start();
include ('../functions/functions.php');
$connect=new functions();
if(!$connect->is_connected())
{
	header('Location: connexion.php');
	exit;
}
if(!$connect->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))
{
	header('Location: index.php');
	exit;
}
include ('header.php');
?>
<div class="row text">	
	<div class="col-12">
		<div class="row justify-content-center">
			<div class="col-auto">
				<h2 class="mt-5 mb-3">Lexique HTML</h2>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-auto mt-3">
				<p>Voici quelques balises HTML qui vous serivront à customiser l'affichage du site.<br><a target="_blank" href="https://developer.mozilla.org/fr/docs/Web/HTML/Element">Voir la documentation complète des balises HTML</a></p>
			</div>
			<div class="col-auto mb-5 mt-3">
				<small class="text-justify">
					HTML est un langage de <u>balisage</u> c'est à dire qu'il utilise des balises. Chaque balise symbolise quelque chose, par exemple la balise <code>&lt;u&gt;&lt;/u&gt;</code> représente un texte souligné, comme pour le mot <i>"balisage"</i> au dessus.<br><br>

					Chaque balise peut contenir des attributs, il en existe énormément, certains sont exclusifs à une balise alors que d'autres peuvent être utilisés partout. Un attribut se place comme ceci : <code>&lt;balise attribut=""&gt;contenu&lt;/balise&gt;</code>. Par exemple, si je veux colorer un texte souligné : <code>&lt;u style="color: #ff9000;"&gt;texte coloré souligné&lt;/u&gt;</code> <u style="color: #ff9000;">texte coloré souligné</u>. HTML comporte aussi des couleurs par défaut, <code>&lt;u style="color: green"&gt;vert HTML&lt;/u&gt;</code> <u style="color: green;">vert HTML</u>.<br>
					Pour qu'un lien fonctionne, il est impératif d'utiliser l'attribut <code>href=""</code> qui contient la cible du lien, ainsi : <code>&lt;a href="http://wikipedia.fr"&gt;Wikipédia&lt;/a&gt;</code>. De même, une balise peut avoir plusieurs attributs : <code>&lt;a href="http://wikipedia.fr" style="color: #ff9000;"&gt;Wikipédia&lt;/a&gt;</code> qui donne <a href="http://wikipedia.fr" style="color: #ff9000;">Wikipédia</a>.<br>
				</small>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<table class="table table-striped">
					<tr>
						<td class="text-justify"><code>&lt;h5&gt;Titre de niveau 5&lt;/h5&gt;</code></td>
						<td class="text-justify"><h5>Titre de niveau 5</h5></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;h5&gt;&lt;u&gt;Titre de niveau 5 souligné&lt;/u&gt;&lt;/h5&gt;</code></td>
						<td class="text-justify"><h5><u>Titre de niveau 5 souligné</u></h5></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;u&gt;Texte souligné&lt;/u&gt;</code></td>
						<td class="text-justify"><u>Texte souligné</u></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;strong&gt;Texte en gras&lt;/strong&gt;</code></td>
						<td class="text-justify"><strong>Texte en gras</strong></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;i&gt;Texte en italique&lt;/i&gt;</code></td>
						<td class="text-justify"><i>Texte en italique</i></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;s&gt;Texte barré&lt;/s&gt;</code></td>
						<td class="text-justify"><s>Texte barré</s></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;span style="color: blue;"&gt;Texte en bleu&lt;/span&gt;</code></td>
						<td class="text-justify"><span style="color: blue;">Texte en bleu</span></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;span style="color: #ff9000;"&gt;Couleur perso&lt;/span&gt;</code></td>
						<td class="text-justify"><span style="color: #ff9000;">Couleur perso</span></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;a href="http://wikipedia.fr"&gt;Lien&lt;/span&gt;</code></td>
						<td class="text-justify"><a href="http://wikipedia.fr">Lien</a></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;a href="http://wikipedia.fr" style="color: #ff9000;"&gt;Lien coloré&lt;/span&gt;</code></td>
						<td class="text-justify"><a href="http://wikipedia.fr" style="color: #ff9000;">Lien coloré</a></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;a href="http://wikipedia.fr" target="_blank"&gt;Lien qui ouvre dans un nouvel onglet&lt;/span&gt;</code></td>
						<td class="text-justify"><a href="http://wikipedia.fr" target="_blank">Lien qui ouvre dans un nouvel onglet</a></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;ul&gt;Liste non ordonnée<br>&lt;li&gt;item&lt;/li&gt;<br>&lt;li&gt;item&lt;/li&gt;&lt;/ul&gt;</code><br><small>Pour cacher les puces, ajoutez l'attribut <code>style="list-style-type: none;"</code> dans la balise <code>&lt;ul&gt;</code></small></td>
						<td class="text-justify"><ul>Liste non ordonnée<li>item</li><li>item</li></ul></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;ol&gt;Liste ordonnée<br>&lt;li&gt;premier&lt;/li&gt;<br>&lt;li&gt;second&lt;/li&gt;&lt;/ol&gt;</code><br><small>Pour cacher les itérations, ajoutez l'attribut <code>style="list-style-type: none;"</code> dans les balises <code>&lt;li&gt;</code></small></td>
						<td class="text-justify"><ol>Liste ordonnée<li>premier</li><li>second</li></ol></td>
					</tr>
					<tr>
						<td class="text-justify"><code>&lt;br&gt;</code><br>
							<small>Attention : utiliser cette balise dans le but de produire une marge est déconseillé.</small></td>
							<td class="text-justify">Sauter<br>une ligne</td>
						</tr>
						<tr>
							<td class="text-justify"><code>&lt;div class="mt-5"&gt;Produire une marge en haut&lt;/div&gt;</code><br>
								<small>Attention : La balise div <u>n'a pas</u> pour fonction de produire une marge par défaut, c'est seulement ce qui se trouve dans l'attribut class="".<br>Note : il existe mt = margin top, mb (margin bottom), ml (margin left) et mr (margin right). Le chiffre doit être compris entre 1 et 5 inclu, 5 étant la marge la plus importante.</small></td>
								<td class="text-justify"><div class="mt-5">Produire une marge en haut</div></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-auto">
				<h2 class="mt-5 mb-3">Documentation Material Icon</h2>
			</div>
			<div class="col-auto mt-3">
				<p>Voici tous les mots-clefs Material Icon qui vous serivront à customiser l'affichage du site.<br><a target="_blank" href="https://material.io/resources/icons/?style=baseline">Voir la documentation complète</a></p>
			</div>
			<div class="col-auto mb-5 mt-3">
				<small class="text-justify">
					Material Icon est une biliothèque d'icônes standardisés par Google. En effet, ce sont les mêmes icônes que l'on retrouve dans l'interface Android ainsi que dans la plupart des applications mobiles. Elle permet de disposer d'une bibliothèque d'éléments qui sont familiers à la plupart des internautes.<br>Cette bibliothèque est intégrée au site et permet d'intégrer tous ses icônes. Comme pour le HTML, il y a une syntaxe bien précise qu'il convient de respecter.<br><br>
					D'ailleurs cette bibliothèque se base sur du HTML pour afficher les icônes, ça tombe bien puisqu'avec la partie précédente vous connaissez les bases de HTML (c'est bien fait hein ?).<br><br>
					On affiche un icône comme suis : <br>
					<code>&lt;i class="material-icons"&gt;nom_de_l_icon&lt;/i&gt;</code><br>
					Prenons les choses dans l'ordre :<br>
					Il n'est pas obligatoire d'utiliser la balise "i", d'autres peuvent convenir. Cependant, la documentation de Material Icon nous montre des exemples avec la balise "i". Cela parce que cette balise est dite "obsolète" en HTML5, ça veut dire que normalement on ne doit plus l'utiliser. Elle ne sert donc plus à rien puisqu'il existe d'autres moyens de créer du texte en italique (on se souvient que la balise i sert à mettre du texte en italique). Donc on utilise une balise qui ne sert à rien d'autre qu'à mettre nos icônes, et cette méthode peut permettre un gain de temps énorme si jamais un développeur veut travailler là-dessus (pensez simple, pensez aux développeurs).<br><br>
					La class "material-icons" sert à signifier au site qu'à cet endroit précis on veut utiliser des ressources de Material Icon. Cette class est <u>obligatoire</u>, il en existe d'autres qui ne le sont pas mais je détaillerai ça plus tard.<br><br>
					Le nom de l'icône est ... le nom de l'icône. Ils sont classés par nom et leurs noms sont assez explicites, quelques exemples :
					<ul class="list-group list-group-flush">
						<li class="list-group-item"><i class="material-icons">android</i>android</li>
						<li class="list-group-item"><i class="material-icons">alarm</i>alarm</li>
						<li class="list-group-item"><i class="material-icons">build_circle</i>build_circle</li>
						<li class="list-group-item"><i class="material-icons">explore</i>explore</li>
					</ul>
					Cela signifie que si j'écris <code>&lt;i class="material-icons"&gt;android&lt;/i&gt;</code>
					 j'obtiens <i class="material-icons">android</i>
					 <br>Autre exemple : <code>&lt;i class="material-icons"&gt;extension&lt;/i&gt;</code> j'obtiens <i class="material-icons">extension</i><br>Material Icon compte plus de 900 icônes différents, il y a de quoi faire ! Je ne vais pas donner plus de nom ici, ils sont tous disponibles en cliquant sur le lien plus haut.<br><br>
					 Donc là, normalement, vous vous dites que ça y est je sais mettre des icônes ! Et vous aurez raison, vous savez le faire. Mais Material Icon ne s'arrête pas là ... Vous vous souvenez quand je vous disais qu'il existait d'autres class qui n'étaient pas obligatoires et qu'on en reparlerai plus tard ? Ce plus tard, c'est maintenant.<br>
					 En effet, vous aurez peut-être remarqué que des icônes blanches et plus grosses sont présentes dans le menu (sauf si le super-administrateur les a enlevé et remplacé par du texte, ce que je trouverai franchement dommage). Pas de magie, j'ai utilisé pour cela d'autres class.<br>
					 Je vous mets ici une petite sélection des variantes que j'ai implanté sur le site.
					</small>
					 <table class="table table-striped">
					 	<tr>
					 		<td class="text-justify"><code>&lt;i class="material-icons md-light"&gt;android&lt;/i&gt;</code></td>
							<td class="text-justify"><i class="material-icons md-light">android</i></td>
					 	</tr>
					 	<tr>
					 		<td class="text-justify"><code>&lt;i class="material-icons md-36"&gt;android&lt;/i&gt;</code><br>
					 			<small>Note : fonctionne avec md-18, md-24, md-36, md-48 pour modifier la taille.</small></td>
							<td class="text-justify"><i class="material-icons md-36">android</i></td>
					 	</tr>
					 	<tr>
					 		<td class="text-justify"><code>&lt;i class="material-icons md-light md-inactive"&gt;android&lt;/i&gt;</code><br>
					 			<small>Note : il est préférable de respecter l'ordre des class.</small></td>
							<td class="text-justify"><i class="material-icons md-light md-inactive">android</i></td>
					 	</tr>
					 	<tr>
					 		<td class="text-justify"><code>&lt;i class="material-icons md-dark md-inactive"&gt;android&lt;/i&gt;</code><br>
					 			<small>Note : il est préférable de respecter l'ordre des class.</small></td>
							<td class="text-justify"><i class="material-icons md-dark md-inactive">android</i></td>
					 	</tr>
					 </table>
					<small class="text-justify">Il existe encore pleins d'autres class pour les couleurs. Cependant, il faut les intégrer au site en CSS avant de pouvoir les utiliser. Pour cela, vous pouvez utiliser <a href="ajouter_css.php" target="_blank">l'éditeur de CSS</a> présent sur le site.<br>Pour ajouter une nouvelle class de couleur, il faut l'ajouter au CSS, pour cela il faut ajouter ce code dans l'éditeur :
					<code>.material-icons.nom_de_la_class { color: code_couleur; }</code>.
					Ensuite, il ne vous reste qu'à l'utiliser dans votre HTML :
					<code>&lt;i class="material-icons nom_de_la_class"&gt;android&lt;/i&gt;</code>.
					<br>Par exemple si j'ajoute dans l'éditeur CSS
					<code>.material-icons.orange600 { color: #FB8C00; }</code>.
					<br>Et que je l'utilise ensuite dans mon HTML
					<code>&lt;i class="material-icons orange600"&gt;android&lt;/i&gt;</code>.
					<br>J'obtiens
					<i class="material-icons orange600">android</i>
					<br>Il est ensuite possible de mixer les couleurs avec la taille, les possibilités sont infinies. Vous êtes maintenant formés pour utiliser pleinement Material Icon !
			</div>
		</div>
	</div>
</div>
<?php include ('footer.php');