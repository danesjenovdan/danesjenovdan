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

<?php print ($this->header); $id_user = $this->id_user; ?>

<?php if($this->id == 1) {?>
	<title>Pravica do dobrega življenja in solidarne družbe</title>
	<meta name="description" content="Vsakdo ima pravico do kvalitetnega zdravstva, šolstva, oskrbe, do bivališča, pravico do solidarne družbe, ki te podpira, ko padeš in spodbuja, ko rasteš, pravico do prostega časa, pravico do srečnega otroštva, kreativne mladosti, preskrbljene odraslosti in varne starosti. Vsakdo ima pravico do prihodnosti. Predolgo je bilo merilo uspešnosti države njen gospodarski razvoj in merilo posameznika njegov dohodek. Naše merilo je veselje do življenja.">
	<meta property="og:title" content="Pravica do dobrega življenja in solidarne družbe" />
	<meta property="og:description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://danesjenovdan.si/pravica-do-dobrega-zivljenja-in-solidarne-druzbe" />
	<meta property="og:image" content="http://zakonopljo.si/Content/img/pravica_1.png" />
	<meta property="og:site_name" content="Danes je nov dan.si" />
	<meta property="fb:app_id" content="301375193309601" />
<?php }?>

<?php if($this->id == 2) {?>
	<title>Pravica do skupnega</title>
	<meta name="description" content="Naša skupna moč presega seštevek naših posameznih moči, zato je potrebno varovati skupno, z njim upravljati demokratično in v dobrobit vseh. Ne dovolimo privatizacije in uničevanja naravnih virov in javnih prostorov.">
	<meta property="og:title" content="Pravica do skupnega"/>
	<meta property="og:description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://danesjenovdan.si/pravica-do-skupnega" />
	<meta property="og:image" content="http://zakonopljo.si/Content/img/pravica_2.png" />
	<meta property="og:site_name" content="Danes je nov dan.si" />
	<meta property="fb:app_id" content="301375193309601" />
<?php }?>

<?php if($this->id == 4) {?>
	<title>Pravica (do) narave</title>
	<meta name="description" content="Narava je subjekt zaščite, interesi naravnega okolja so skupni interesi, živali imajo pravico do dostojanstva. Napredek, razvoj in gospodarska rast ne smejo biti izgovor za neodgovorno ravnanje z naravo.">
	<meta property="og:title" content="Pravica (do) narave"/>
	<meta property="og:description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://danesjenovdan.si/pravica-do-narave" />
	<meta property="og:image" content="http://zakonopljo.si/Content/img/pravica_4.png" />
	<meta property="og:site_name" content="Danes je nov dan.si" />
	<meta property="fb:app_id" content="301375193309601" />
<?php }?>

<?php if($this->id == 3) {?>
	<title>Pravica do dobre politične oblasti</title>
	<meta name="description" content="Politika ni boj za oblast, ampak je v službi skupnih interesov. Hočemo odprt politični prostor in umik političnih elit. Civilni sferi je treba omogočiti dostop do mehanizmov odločanja.">
	<meta property="og:title" content="Pravica do dobre politične oblasti"/>
	<meta property="og:description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://danesjenovdan.si/pravica-do-dobre-politicne-oblasti" />
	<meta property="og:image" content="http://zakonopljo.si/Content/img/pravica_3.png" />
	<meta property="og:site_name" content="Danes je nov dan.si" />
	<meta property="fb:app_id" content="301375193309601" />
<?php }?>

<?php if($this->id == 5) {?>
	<title>Pravica do človeku prijazne ekonomije</title>
	<meta name="description" content="Javni dolg je rezultat zasebnega delovanja, zato ga ne bo plačala javnost. Hočemo ustvariti okolje, v katerem bodo prosperirala podjetja, ki so prijazna do človeka in narave. Vsakdo ima pravico do dela in dostojnega življenja.">
	<meta property="og:title" content="Pravica do človeku prijazne ekonomije"/>
	<meta property="og:description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://danesjenovdan.si/pravica-do-cloveku-prijazne-ekonomije" />
	<meta property="og:image" content="http://zakonopljo.si/Content/img/pravica_5.png" />
	<meta property="og:site_name" content="Danes je nov dan.si" />
	<meta property="fb:app_id" content="301375193309601" />
<?php }?>

<?php if($this->id == 6) {?>
	<title>Pravica do vključenosti</title>
	<meta name="description" content="Zavračamo delitve na podlagi kakršnih koli osebnih okoliščin. Dovolj je politike izključevanja, ki naključnim družbenim skupinam jemlje dostojno življenje, jih potiska na družbene robove ter jim krade moč, ki jim pripada. Z ustavo zagotovljena pravica do enakosti ne sme biti samo deklarativna. Pravica do vključenosti in človekove pravice veljajo za vse. O tem ne razpravljamo.">
	<meta property="og:title" content="Pravica do vključenosti"/>
	<meta property="og:description" content="Ne čakaj pomladi. Pridruži se grajenju boljše družbe!">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://danesjenovdan.si/pravica-do-vkljucenosti" />
	<meta property="og:image" content="http://zakonopljo.si/Content/img/pravica_6.png" />
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

<?php if ($this->id == 6) {?>
<div class="container predlogi">
	<div class="row">
		<div class="span12">
			<p class="zigatezi">Pravica do vključenosti je pravica, ki se nanaša na preostalih pet pravic in ekplicitno izjavlja, da pravice veljajo za vse. Zavračamo delitve na podlagi kakršnih koli osebnih okoliščin. Dovolj je politike izključevanja, ki naključnim družbenim skupinam jemlje dostojno življenje, jih potiska na družbene robove ter jim krade moč, ki jim pripada. Z ustavo zagotovljena pravica do enakosti ne sme biti samo deklarativna. O tem ne razpravljamo.</p>
			<p class="zigatezi">Pravica do vključenosti zato ne dopušča vnašanja predlogov, glasovanja zanje in dodajanja argumentov. O dejstvu, da smo vsi enaki, se ne glasuje in ne razpravlja. Na teoretski ravni zavračamo že samo dihotomijo vključen - izključen, saj je skregana s pojmom skupnosti. Vendar pa na pragmatični ravni nihče ne more zanikati dejstva, da družbeni robovi obstajajo, da so mnogi ljudje oropani raznih pravic in moči, da so torej izključeni iz skupnosti. Sporno binarno opozicijo vključen-izključen lahko presežemo le tako, da omogočimo vsakomur, da je slišan, in da vsak glas šteje enako. Le tako bomo na podlagi novega družbenega konsenza skupnost, v kateri nihče ni "marginalen" in "izključen", šele zgradili.
			</p>
		</div>
	</div>
</div>
<?php }?>

<?php if ($this->id != 6) {?>

<div class="container predlogi">
	<div class="row">
		<div class="span12">
			<div class="row">
				<div class="span8">
					<h1 class="pravicatext"><?php print $this->pravica->title;?></h1>
				</div>
				<div class="span4">
					<div class="pravicasocial">
						<div class="fb-like" data-href="<?php print curPageURL(); ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
						<div class="g-plusone" data-href="<?php print curPageURL(); ?>" data-size="medium" data-annotation="buble" data-width="100"></div>
						<script type="text/javascript">
							(function() {
								var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
								po.src = 'https://apis.google.com/js/plusone.js';
								var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
							})();
						</script>
						<a href="https://twitter.com/share" data-url="<?php print curPageURL(); ?>" class="twitter-share-button" data-lang="en" data-hashtags="danesjenovdan" data-text="Pridruži se grajenju skupne prihodnosti: <?php print $this->pravica->title ?>">Tweet</a>
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></span>
					</div>
					
				</div>
			</div>
			<p class="zigatezi">
				<?php print $this->pravica->text; ?>
			</p>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="span4 offset5">
		</div>
		<div class="span3 suggest">
		</div>
	</div>
</div>
<div class="container" id="predlogi">
<div class="row sortrow">
	<div class="span4"> </div>
	<div class="span2 timestampwrap icsort"> 
		<a href="?sort=date&w=asc" class="<?php print (($_GET['sort']=="date") && ($_GET['w']=="asc"))? "active" : null; ?>">
			<font class="up">&#9650;</font>
		</a> 
		datum 
		<a href="?sort=date&w=desc" class="<?php print (($_GET['sort']=="date") && ($_GET['w']=="desc"))? "active" : null; ?>">
			<font class="down ">&#9660;</font>
		</a>
	</div>
	<div class="span4 icsort"> 
		<a href="?sort=imp&w=asc" class="<?php print (($_GET['sort']=="imp") && ($_GET['w']=="asc"))? "active" : null; ?>">
			<font class="up ">&#9650;</font> 
		</a>
		pomembnost 
		<a href="?sort=imp&w=desc" class="<?php print (($_GET['sort']=="imp") && ($_GET['w']=="desc"))? "active" : null; ?>">
			<font class="down ">&#9660;</font>
		</a> 
	</div>
</div>
			<?php foreach ($this->predlogi as $key => $value) { ?>

<div class="row predlog">
	<div class="suggestionrow">
		<div class="span4 firstblock">
			<h1 class="suggestiontitle"><a href="<?php print $this->link; ?>/<?php print $value->id; ?>"><?php print $value->title; ?></a></h1>
		</div>
		<div class="span2 timestampwrap">
			<h1 class="timestamp"><?php print $value->timestamp; ?></h1>
		</div>
		<div class="span4">
			<div class="votebox" data-id="<?php print $value->id; ?>">
				<div class="votefor  <?php print ($value->vuser_plus>0) ? "marked" : null; ?>"></div>
				<div class="votecount"><span><?php print $value->vote_plus; ?></span></div>
				<div class="voteagainst  <?php print ($value->vuser_minus>0) ? "marked" : null; ?>"></div>
				<div class="votecount"><span><?php print $value->vote_minus; ?></span></div>
			</div>
		</div>
		<div class="span1 hidden-phone">
		<div class="ihaveanargument" data-id="<?php print $value->id; ?>"></div>
	</div>
	</div>
</div>

			<?php } ?>	

	<!-- konec predlogov -->
	<div class="row predlog">
		<div class="suggestionrow">
		</div>
	</div>
<!--	<div class="m0ar"></div>-->
	<?php }?>
</div>
<input type="hidden" value="<?php print $this->id; ?>" id="rightid"/>
<div class="modal hide fade suggestionpopup">
	<button type="button" class="closepopup" data-dismiss="modal" aria-hidden="true"></button>
	<div class="modal-body">
		<form>
			<input type="text" class="addsuggestiontitle" maxlength="100" placeholder="naslov predloga (do 100 znakov)" />
			<textarea class="addsuggestioncontent" placeholder="opis"></textarea>
			<div class="usersignedin">objavi kot <span class="signedinname"></span></div>
		</form>
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