=== Shortcode Autolink ===
Contributors: k-ny
Tags: posts, related, post-plugins, query, search, link, shortcode
Requires at least: 2.5
Tested up to: 2.5
Stable tag: 0.1

Lets you create links automatically on the basis of internal research results, and everything through Shortcode!

== Description ==

Lets you **create links automatically** on the basis of **internal research results**, and everything through *Shortcode* ! 

Permet de **creer des liens automatiquement** en se basant sur des **résultats de recherche interne**, et tout ça grâce aux *ShortCode* !

[More infos](http://www.webinventif.fr/shortcode-autolink-creation-automatique-de-liens-internes-en-1-clic/).

== Installation ==

1. Upload the plugin folder to your /wp-content/plugins/ folder.
1. Go to the **Plugins** page and activate the plugin.
1. Put `[shal]keyword(s)[/shal]` at the place in your posts where you want the autolink appear.

[My web site](http://www.webinventif.fr/) has [full instructions](http://www.webinventif.fr/shortcode-autolink-creation-automatique-de-liens-internes-en-1-clic/)

== Frequently Asked Questions ==

= List of attributes available =

* Attributs possibles pour **order_by** (`[shal order_by="%value"]key word[/shal]`)
	* **rand** : recherche aléatoire
	* **ID** : recherche classée par ID
	* **post_date** : recherche classée par date (valeur par défaut)
	* **post_title** : recherche classée par titre
	* **post_modified** : recherche classée par date de modification
	* **comment_count** : recherche par nombre de commentaires
	
* Attributs possibles pour **order** (`[shal order="%value"]key word[/shal]`)
	* **DESC** (valeur par défaut)
	* **ASC**
	
* Attributs possibles pour **only_title** (`[shal only_title="%value"]key word[/shal]`)
	*  **0** (ou **false**) : exécute la recherche dans les titres ET le contenu
	*  **1** (ou **true**) : exécute la recherche uniquement dans les titres (valeur par défaut)

== Version History ==

* 0.1
	* first public release

