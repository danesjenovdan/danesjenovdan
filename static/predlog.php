<?php print ($this->header); $id_user = $this->id_user; ?>

<?php
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
?>

<?php if($this->id_right == 1) {?>
	<title><?php print $this->predlog->title; ?></title>
	<meta name="description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!"/>
	<meta property="og:title" content="<?php print $this->predlog->title; ?>"/>
	<meta property="og:description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://danesjenovdan.si/pravica-do-dobrega-zivljenja-in-solidarne-druzbe/<?php print $this->predlog->id; ?>" />
	<meta property="og:image" content="http://zakonopljo.si/Content/img/pravica_1.png" />
	<meta property="og:site_name" content="Danes je nov dan.si" />
	<meta property="fb:app_id" content="301375193309601" />
<?php }?>

<?php if($this->id_right == 2) {?>
	<title><?php print $this->predlog->title; ?></title>
	<meta name="description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!"/>
	<meta property="og:title" content="<?php print $this->predlog->title; ?>"/>
	<meta property="og:description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://danesjenovdan.si/pravica-do-skupnega/<?php print $this->predlog->id; ?>" />
	<meta property="og:image" content="http://zakonopljo.si/Content/img/pravica_2.png" />
	<meta property="og:site_name" content="Danes je nov dan.si" />
	<meta property="fb:app_id" content="301375193309601" />
<?php }?>

<?php if($this->id_right == 4) {?>
	<title><?php print $this->predlog->title; ?></title>
	<meta name="description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!"/>
	<meta property="og:title" content="<?php print $this->predlog->title; ?>"/>
	<meta property="og:description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://danesjenovdan.si/pravica-do-narave/<?php print $this->predlog->id; ?>" />
	<meta property="og:image" content="http://zakonopljo.si/Content/img/pravica_4.png" />
	<meta property="og:site_name" content="Danes je nov dan.si" />
	<meta property="fb:app_id" content="301375193309601" />
<?php }?>

<?php if($this->id_right == 3) {?>
	<title><?php print $this->predlog->title; ?></title>
	<meta name="description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!"/>
	<meta property="og:title" content="<?php print $this->predlog->title; ?>"/>
	<meta property="og:description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://danesjenovdan.si/pravica-do-dobre-politicne-oblasti/<?php print $this->predlog->id; ?>" />
	<meta property="og:image" content="http://zakonopljo.si/Content/img/pravica_3.png" />
	<meta property="og:site_name" content="Danes je nov dan.si" />
	<meta property="fb:app_id" content="301375193309601" />
<?php }?>

<?php if($this->id_right == 5) {?>
	<title><?php print $this->predlog->title; ?></title>
	<meta name="description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!"/>
	<meta property="og:title" content="<?php print $this->predlog->title; ?>"/>
	<meta property="og:description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://danesjenovdan.si/pravica-do-cloveku-prijazne-ekonomije/<?php print $this->predlog->id; ?>" />
	<meta property="og:image" content="http://zakonopljo.si/Content/img/pravica_5.png" />
	<meta property="og:site_name" content="Danes je nov dan.si" />
	<meta property="fb:app_id" content="301375193309601" />
<?php }?>

</head>
<body>

	<!-- facebook crap -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=301375193309601";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<!-- end of facebook crap -->

	<!-- dirty dirty banner hack -->
	<div id="banner" style="width: 100%; background: #1d7373; color: #262626; position: fixed; bottom: 0px; display: block; text-align: center; color: white; line-height: 30px">Zdravo! Ker bi bilo narobe, da bi kdo drug v vašem imenu preoblikoval predloge, <a style="color: #ffffff"; href="http://www.danesjenovdan.si/stara" target="_blank"><strong>tukaj</strong></a> ostaja posnetek stare verzije, vi pa ste vabljeni, da svoje predloge prilagodite novemu formatu.</a></div>
	<!-- end of dirty dirty banner hack -->

	<div class="container">
		<div class="row">
			<div class="span3">
				<a class="nonexistent" href="/"><div class="logo"></div></a>
			</div>
			<div class="span9">
				<div class="navigation">
					<div class="navblock 6">
						<a href="/pravica-do-vkljucenosti">PRAVICA DO VKLJUČENOSTI</a>
					</div>
					<div class="divider">
					</div>
					<div class="navblock 5">
						<a href="/pravica-do-cloveku-prijazne-ekonomije">PRAVICA DO ČLOVEKU PRIJAZNE EKONOMIJE</a>
					</div>
					<div class="divider">
					</div>
					<div class="navblock 4">
						<a href="/pravica-do-narave">PRAVICA (DO) NARAVE</a>
					</div>
					<div class="divider">
					</div>
					<div class="navblock 3">
						<a href="/pravica-do-dobre-politicne-oblasti">PRAVICA DO DOBRE POLITIČNE OBLASTI</a>
					</div>
					<div class="divider">
					</div>
					<div class="navblock 2">
						<a href="/pravica-do-skupnega">PRAVICA DO SKUPNEGA</a>
					</div>
					<div class="divider">
					</div>
					<div class="navblock 1">
						<a href="/pravica-do-dobrega-zivljenja-in-solidarne-druzbe">PRAVICA DO DOBREGA ŽIVLJENJA IN SOLIDARNE DRUŽBE</a>
					</div>
				</div>
			</div>
		</div>
	</div>

<div class="container">
	<div class="row">
		<div class="span3 offset9">
			<div class="suggest"></div>
		</div>
	</div>
</div>
<div class="container blokic">
	<div class="row">
		<div class="span12 toprow">
			<h1 class="title"><?php print $this->predlog->title; ?></h1>
			<div class="temporarybox">
				<div class="suggestionup  <?php print ($this->predlog->vuser_plus>0) ? "marked" : null; ?>" data-id="<?php print $this->predlog->id; ?>"></div>
				<div class="votecount"><span><?php print $this->predlog->vote_plus; ?></span></div>
				<div class="suggestiondown <?php print ($this->predlog->vuser_minus>0) ? "marked" : null; ?>" data-id="<?php print $this->predlog->id; ?>"></div>
				<div class="votecount"><span><?php print $this->predlog->vote_minus; ?></span></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<span class="author"><?php print $this->user->name; ?> <?php print $this->user->surname; ?>, </span>
			<span class="suggestiontimestamp"><?php print $this->predlog->timestamp; ?></span>
			<span class="suggestionsocial">
				<div class="fb-like" data-href="<?php print curPageURL(); ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
				<div class="g-plusone" data-href="<?php print curPageURL(); ?>" data-size="medium" data-annotation="buble" data-width="100"></div>
				<script type="text/javascript">
					(function() {
						var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
						po.src = 'https://apis.google.com/js/plusone.js';
						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
					})();
				</script>
				<a href="https://twitter.com/share" data-url="<?php print curPageURL(); ?>" class="twitter-share-button" data-lang="en" data-hashtags="danesjenovdan" data-text="Pridruži se grajenju skupne prihodnosti: <?php print $this->predlog->title ?>">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></span>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<p class="suggestiontext"><?php print $this->predlog->text; ?></p>
		</div>
	</div>
	<hr class="predlogcrta" />
	<div class="row">
		<div class="span6">
			<h1 class="documenttitle">Prednosti</h1>
<!--			<div class="row">
				<div class="span5"> </div>
				<div class="span1 icsort"> 
					<a href="?sort=f&w=asc" class="<?php print (($_GET['sort']=="f") && ($_GET['w']=="asc"))? "active" : null; ?>">
						<font class="up">&#9650;</font>
					</a> 
					
					<a href="?sort=f&w=desc" class="<?php print (($_GET['sort']=="f") && ($_GET['w']=="desc"))? "active" : null; ?>">
						<font class="down ">&#9660;</font>
					</a>
				</div>
			</div>-->
			<?php
			foreach ($this->argplus as $key => $value) { ?>
				<div class="row">

					<div class="span6">
						<div class="argumentboxfor">
							<p class="authorship"><?php print $value->name; ?>, <?php print $value->timestamp; ?></p>
							<p class="argumenttext"><?php print $value->text; ?></p>
							<div class="argumentup <?php print ($value->vuser_plus>0) ? "marked" : null; ?>" data-id="<?php print $value->id_argument; ?>"></div>
							<div > plus <?php print $value->users_plus; ?> </div>
							<div class="argumentdown  <?php print ($value->vuser_minus>0) ? "marked" : null; ?>" data-id="<?php print $value->id_argument; ?>"></div>
							<div > minus <?php print $value->users_minus; ?> </div>
							<hr class="argumentcrta" />
						</div>
					</div>
						

				</div>
			<?php }?>

			<div class="row">
				<div class="span6">
					<div class="addargumentfor">
						<div class="argumentinputwrap">
							<img src="/static/img/human.png"><!-- INJECT SRC FROM FACEBOOK -->
							<textarea id="argumentinputfor"></textarea>
							<div class="usersignedinargument">objavi kot <span class="signedinname"></span></div>
						</div>
						<div class="submitargumentfor"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="span6">
			<h1 class="documenttitle">Zadržki</h1>
<!--			<div class="row">
				<div class="span5"> </div>
				<div class="span1 icsort"> 
					<a href="?sort=a&w=asc" class="<?php print (($_GET['sort']=="a") && ($_GET['w']=="asc"))? "active" : null; ?>">
						<font class="up">&#9650;</font>
					</a> 
					
					<a href="?sort=a&w=desc" class="<?php print (($_GET['sort']=="a") && ($_GET['w']=="desc"))? "active" : null; ?>">
						<font class="down ">&#9660;</font>
					</a>
				</div>
			</div>-->
			<?php
			foreach ($this->argminus as $key => $value) { ?>
				<div class="row">

					<div class="span6">
						<div class="argumentboxagainst">
							<p class="authorship"><?php print $value->name; ?>, <?php print $value->timestamp; ?></p>
							<p class="argumenttext"><?php print $value->text; ?></p>
							<div class="argumentup  <?php print ($value->vuser_plus>0) ? "marked" : null; ?>" data-id="<?php print $value->id_argument; ?>"></div>
							<div > plus <?php print $value->users_plus; ?> </div>
							<div class="argumentdown  <?php print ($value->vuser_minus>0) ? "marked" : null; ?>" data-id="<?php print $value->id_argument; ?>"></div>
							<div > minus <?php print $value->users_minus; ?> </div>
							<hr class="argumentcrta" />
						</div>
					</div>
						

				</div>
			<?php }?>
			

			<div class="addargumentagainst">
				<div class="argumentinputwrap">
					<img src="/static/img/human.png"><!-- INJECT SRC FROM FACEBOOK -->
					<textarea id="argumentinputagainst"></textarea>
					<div class="usersignedinargument">objavi kot <span class="signedinname"></span></div>
				</div>
				<div class="submitargumentagainst"></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span6">
			<h1 class="documenttitle">Dokumenti</h1>
			<div class="documentbox">
				<div class="adddocument"></div>
				
			<?php foreach ($this->document as $key => $value) { ?>
				<a href="/documents/<?php print $value->path; ?>" target="_blank" class="documentlink"><?php print $value->title; ?> <span class="documenttype">(<?php print strtolower($value->type); ?>, <?php print $value->size; ?>)</span></a><br />
			<?php } ?>	
				<form class="adddocumentbox" id="adddocumentbox" action="/ajax/add_document.php" method="post"> 
					<input type="text" name="documentname" id="documentname" placeholder="ime dokumenta" />
<input type="hidden" name="right_id" value="<?php print $this->id_right; ?>" />
<input type="hidden" id="proposal_id" name="proposal_id" value="<?php print $this->predlog->id; ?>" />

					<label for="documentfile" id="uploadlabel"></label>
					<input type="file" name="documentfile" id="documentfile" />
					<input type="submit" id="submitdocument" value="" />
					<div id="docresp"></div>
				</form>
			</div>
		</div>
		<div class="span6">
			<h1 class="workgrouptitle">Delovna skupina</h1>
			<div class="workgroupbox">
				<p class="workgrouptext"><?php print ($this->wg)? "Skupini si že pridružen/-a." : "Iniciativa mi veliko pomeni in o njej veliko vem."; ?></p>
				<div class="toggleworkgroup <?php print($this->wg); ?>"></div>
				<br />
				<div class="workgrouptext toggleworkgroupt"></div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" value="<?php print $this->id_right; ?>" id="rightid"/>
<div class="modal hide fade suggestionpopup">
	<button type="button" class="closepopup" data-dismiss="modal" aria-hidden="true"></button>
	<div class="modal-body">
		<input type="text" class="addsuggestiontitle" maxlength="100" placeholder="naslov predloga (do 100 znakov)" />
		<textarea class="addsuggestioncontent" placeholder="opis"></textarea>
		<div class="usersignedin">objavi kot <span class="signedinname"></span></div>
		<div class="submitsuggestion postsuggestion"></div>
	</div>
</div>
<div class="modal hide fade loginpopup">
	<button type="button" class="closepopup" data-dismiss="modal" aria-hidden="true"></button>
	<div class="modal-body">
		<div class="usersignedin">objavi kot <span class="signedinname"></span></div>
		<div class="socialconnect">
			<p class="connectwith">Poveži se z</p>
			<div class="fbsignin"></div>
			<img src="/static/img/gumb_twitter.png" class="twsignin"/>
			<div class="googlesign"></div>
		</div>
		<div class="createaccount">
			<p> </p>
			<input type="text" class="accountname" placeholder="ime in priimek"/>
			<input type="email" class="accountemail" placeholder="email"/>
		</div>
		<div class="submitloginpopup"></div>
	</div>
</div>





<?php print ($this->footer); ?>