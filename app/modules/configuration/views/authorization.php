<?php

$group = array(
	(object)array('id'=>'*',									'name'=>_('all')										),
	(object)array('id'=>'admin',								'name'=>_('administrators')						),
	(object)array('id'=>'team',								'name'=>_('team')										),
	(object)array('id'=>'customer',							'name'=>_('customers')								)
);


function clean( $str )
{
	return $str;

	$str = str_replace( APPPATH . 'views/', '', $str);
	return str_replace('.php', '', $str);
}

function parseDir( &$arr, $directory )
{
	$arr[] = clean($directory) . '*';

	$dir = dir( $directory );
	
	while( false !== ($entry=$dir->read()) )
	{
		if( pathinfo($directory.$entry, PATHINFO_EXTENSION)=='php' )
	   {
	   	$arr[] = clean( $directory.$entry );
	   }
	   
	   if( is_dir( $directory . $entry ) && $entry!='.' && $entry!='..' )
	   {
	   	parseDir( $arr, $directory . $entry . '/' );
	   }
	}
	$dir->close();
}

$views = array('*','views/*');



//-- parsing main "views" directory

//parseDir( $views, APPPATH . 'views/');



//-- parsing module "views" directories

$dir = dir( APPPATH . 'modules/' );

while( false !== ($entry=$dir->read()) )
{
   if( is_dir(APPPATH . 'modules/' . $entry . '/views/') && $entry!='.' && $entry!='..' )
   {
   	parseDir( $views, APPPATH . 'modules/' . $entry . '/views/' );
   }
}
$dir->close();



//-- clean dirs (removing base modules)

foreach( $views as &$v )
{
	$s = APPPATH . 'modules/';
	$l = strlen( $s );

	if( substr($v,0,$l) == $s )
	{
		$v = substr($v,$l);
	}
}




foreach( $views as $key => $val )
{
	$val = str_replace( APPPATH . '/', '', $val);
	$val = str_replace('.php', '', $val);

	$views[$key] = (object)array(
		'id' => $val,
		'name' => $val
	);
}




?>

<article class="right">
	<h2><?=_('General rules')?></h2>
	
	<p>Par défaut, tout est interdit à tous les groupes d'utilisateurs.
	Il faut ouvrir les droits par groupes. Les règles générales permettent
	de contrôler l'accès aux controleurs et aux vues.</p>
	
	<table>
		<colgroup>
			<col class="col-one">
			<col class="col-two">
			<col class="col-three">
		</colgroup>
		
		<tr>
			<th><?=_('group')?></th>
			<th><?=_('view')?></th>
			<th>&nbsp;</th>
		</tr>
		
		<?php foreach( $right as $r ): ?>

		<tr id="SAVEright<?=$r->id ?>">
			<td>
				<select name="group">
					<?=optionsHtml( $group, $r->group, false ) ?>
				</select>
			</td>
			<td>
				<select name="view">
					<?=optionsHtml( $views, $r->view, false ) ?>
				</select>
			</td>
			<td>
				<input type="hidden" name="id" value="<?=$r->id ?>" />
				<button class="save">right|<?=$r->id ?>|SAVEright<?=$r->id ?></button>
				<button class="delete">right|<?=$r->id ?></button>
			</td>
		</tr>
		
		<?php endforeach ?>
		
		<tr id="SAVErightNew">
			<td>
				<select name="group">
					<?=optionsHtml( $group, null, true ) ?>
				</select>
			</td>
			<td>
				<select name="view">
					<?=optionsHtml( $views, null, true ) ?>
				</select>
			</td>
			<td>
				<button class="save">right|-1|SAVErightNew</button>
			</td>
		</tr>
		
	</table>
	
</article>
	

<!--

<article class="firewall">
	<h2>Règles particulières</h2>
	
	<p>Les règles particulières ne sont pas configurables pour le moment. Elle règlementent
	l'accès aux modèles de données.</p>
	
	<p>Actuellement, le système fonctionne comme suit : les administrateurs ont accès à tout,
	le client n'ont accès qu'à leurs projets, leur fiche utilisateur et les documents qui
	leur sont partagés.</p>
	
</article>

-->

