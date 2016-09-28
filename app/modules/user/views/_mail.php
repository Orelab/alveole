
<h1>Mails envoyés</h1>


<nav>
	<table id="datatable">
		<colgroup>
			<col style="width:30%;">
			<col style="width:30%">
			<col style="width:10%">
			<col style="width:30%">
		</colgroup>
		
		<thead>
			<tr>
				<th>Nom</th>	
				<th>Entreprise</th>
				<th title="Le site est différent selon le groupe dans lequel vous êtes classé">Groupe</th>
				<th title="La liste des rôles attribués dans les projets">Rôles</th>
			</tr>
		</thead>
		
		<?php foreach( $user as $u ): 
		
			$rname = $u->usurname. ' ' . $u->uname;
			$name = $u->uname. ' ' . $u->usurname;
		?>
	
		<tr>
			<td>
				<a href="user/index/<?=$u->id ?>" class="visit" rel="<?=$name ?>"><?=$rname ?></a>
			</td>	
			<td><?=$u->business ?></td>
			<td><?=$u->group ?></td>
			<td><?=str_replace('|', ', ', $u->role) ?></td>
		</tr>
		
		<?php endforeach ?>
	</table>

</nav>
