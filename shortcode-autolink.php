<?php
/*
Plugin Name: Shortcode Autolink
Version: 0.1
Plugin URI: http://www.webinventif.fr/shortcode-autolink-creation-automatique-de-liens-internes-en-1-clic/
Description: Lets you create links automatically on the basis of internal research results, and everything through Shortcode! / Permet de creer des liens automatiquement en se basant sur des résultats de recherche interne, et tout ça grâce aux ShortCode !
Author: Julien Chauvin
Author URI: http://www.webinventif.fr/

License: GNU General Public License
*/

/* 
 * Attributs possibles pour "order_by" ([shal order_by="%value"]key word[/shal])
 * -------------------------------------------------------------------
 * rand : recherche aléatoire
 * ID : recherche classée par ID
 * post_date : recherche classée par date (valeur par défaut)
 * post_title : recherche classée par titre
 * post_modified : recherche classée par date de modification
 * comment_count : recherche par nombre de commentaires
 * 
 * Attributs possibles pour "order" ([shal order="%value"]key word[/shal])
 * -------------------------------------------------------------
 * DESC (valeur par défaut)
 * ASC
 * 
 *  Attributs possibles pour "only_title" ([shal only_title="%value"]key word[/shal])
 *  ------------------------------------------------------------------------
 *  0 (ou false) : exécute la recherche dans les titres ET le contenu
 *  1 (ou true) : exécute la recherche uniquement dans les titres (valeur par défaut)
 *  
 *  Ressources
 *  ----------
 *  http://codex.wordpress.org/Displaying_Posts_Using_a_Custom_Select_Query
 *  http://codex.wordpress.org/Function_Reference/wpdb_Class
 */

########################################################
#      Prise en charge du Shortcode [shal]
########################################################
function sc_shal($atts, $content = null) {
	//C'est ici que vous pouvez modifier les attributs par défaut
	extract(shortcode_atts(array(
		"order" => 'DESC',
		"only_title" => 1,
		"order_by" => 'post_date'
	), $atts));
	
	//Et a partir d'ici, on évite de toucher pour ne pas tout casser ;)
	global $wpdb, $post;
	if($order_by == 'rand') {
		$order_by = 'rand()';
	}else{
		$order_by = 'wposts.'.$order_by.' '.$order.'';
	}
	if ( !empty($content) ) {
		$content = stripslashes($content);
		if ($sentence) {
			$search_terms = array($content);
		} else {
			preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $content, $matches);
			$search_terms = array_map(create_function('$a', 'return trim($a, "\\"\'\\n\\r ");'), $matches[0]);
		}
		$n = ($exact) ? '' : '%';
		$searchand = '';
		foreach((array)$search_terms as $term) {
			$term = addslashes_gpc($term);
			$search .= "{$searchand}((wposts.post_title LIKE '{$n}{$term}{$n}')";
			if(!$only_title) $search .= " OR (wposts.post_content LIKE '{$n}{$term}{$n}')";
			$search .= ")";
			$searchand = ' AND ';
		}
		$term = $wpdb->escape($content);
		if (!$sentence && count($search_terms) > 1 && $search_terms[0] != $content )
			$search .= " OR (wposts.post_title LIKE '{$n}{$term}{$n}')";
			if(!$only_title) $search .= " OR (wposts.post_content LIKE '{$n}{$term}{$n}')";

		if ( !empty($search) )
			$search = " AND ({$search}) ";
	}
	$request = " SELECT wposts.* FROM $wpdb->posts wposts WHERE wposts.post_status = 'publish' AND wposts.post_type = 'post' And wposts.ID != $post->ID $search ORDER BY $order_by LIMIT 1";
	$mybucle = $wpdb->get_results($request, OBJECT);
	//Debugage
	/*echo '<br />--------------------------------- '.$content.' ---------------------------------<br />';
	foreach($mybucle as $post) {
		echo '<b>Result =</b> '.$post->post_title.'<br />';
	}
	print_r ($mybucle);*/
	if($mybucle){
		return '<a class="shal" href=" '.get_permalink($mybucle[0]->ID).'" title=" '.$mybucle[0]->post_title.'">'.$content.'</a>';
	}else{
		return $content;
	}
	
}
add_shortcode("shal", "sc_shal");
# Fin de la prise en charge du Shortcode [shal] 


########################################################
#      Ajout du bouton du Shortcode [shal]
########################################################
function sc_inject_shal() {
	$stock='<script type="text/javascript">';
	$stock.='eval(function(p,a,c,k,e,r){e=function(c){return(c<a?\'\':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!\'\'.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return\'\\\\w+\'};c=1};while(c--)if(k[c])p=p.replace(new RegExp(\'\\\\b\'+e(c)+\'\\\\b\',\'g\'),k[c]);return p}(\'p 3(h,a,b){o c=9.s?9.s:9.M;1(!c)c=E;2=c.2;1(A 2!=\\\'1e\\\'&&(u=2.11(\\\'R\\\'))&&!u.P()){2.O.L().J();1(!a){2.k(\\\'r\\\',7,h)}4{1(2.D.t.z({y:\\\'w\\\'})){2.k(\\\'18\\\',7,a+\\\'{$t}\\\'+b)}4{2.k(\\\'r\\\',7,a)}}}4{1(!a){c.15(c.j,h)}4{c.Z(c.j,h)}}}p x(a,b,c,d,e,f){1(!d&&!f){3(\\\'[\\\'+c+\\\']\\\')}1(!d&&f){3(b,\\\'[\\\'+c+\\\']\\\',\\\'[/\\\'+c+\\\']\\\')}1(d){o g=\\\'[\\\'+c;1(!N(b)){1(d.6){K(i=0;i<d.6;i++){g+=\\\' \\\'+d[i]+\\\'="\\\'+I(d[i],e[i])+\\\'"\\\'}}g+=\\\']\\\';8[b].H=g}1(f){3(b,g,\\\'[/\\\'+c+\\\']\\\')}4{8[b].G="";3(g)}}}p F(a,b,c,d){o e=8.6+l(\\\'#n-m a.q\\\').6+10;l("#n-m").C(\\\'<a B="q" U="\\\'+a+\\\'" V="W-X: Y;w-1c: 1a; 16-v: #14; v: 13" 12="17 [\\\'+a+\\\']" T="#" 19="x(j, \\\'+e+\\\', \\\\\\\'\\\'+a+\\\'\\\\\\\', \\\'+b+\\\', \\\'+c+\\\', \\\'+d+\\\'); S 7;">\\\'+a+\\\'</a>\\\');l(\\\'#n-m a#\\\'+a+\\\'\\\').1b();8[e]=Q 1d(a+e,a,\\\'[\\\'+a+\\\']\\\',\\\'[/\\\'+a+\\\']\\\',\\\'5\\\')}\',62,77,\'|if|tinyMCE|sc_send_to_editor|else||length|false|edButtons|window||||||||||edCanvas|execCommand|jQuery|buttons|media|var|function|sc_btn|mceInsertRawHTML|opener|selection|ed|color|text|sc_insertall|format|getContent|typeof|class|append|activeEditor|top|gotoload|tagEnd|tagStart|prompt|focus|for|getWin|dialogArguments|edCheckOpenTags|selectedInstance|isHidden|new|content|return|href|id|style|margin|left|5px|edInsertTag||getInstanceById|title|white|999999|edInsertContent|background|Balise|mceReplaceContent|onclick|none|tTips|decoration|edButton|undefined\'.split(\'|\'),0,{}));';
	$stock.="\njQuery(document).ready(function(){";
	$stock.="\n\tif(jQuery('#media-buttons').length){";
	$stock.="\n\t\tgotoload('shal', 'false', 'false', 'true');";
	$stock.="\n\t}";
	$stock.="\n});";
	$stock.="\n</script>";
	echo $stock;
}
add_action("admin_head", "sc_inject_shal");
# Fin d'ajout du bouton du Shortcode [shal] 


?>