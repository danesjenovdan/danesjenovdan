<?php print ($this->header); $id_user = $this->id_user; ?>


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
			<p class="zigatezi">
				<?php print $this->pravica->text; ?>
			</p>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="span3 offset9 suggest">
		</div>
	</div>
</div>
<div class="container" id="predlogi">
<div class="row sortrow">
<!--	<div class="span4 toggle"><img src="/static/img/toggle_datum_active.png" /></div>
	<div class="span2 timestampwrap"></div>-->
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
		<div class="socialconnect">
			<p class="connectwith">Poveži se z</p>
			<img src="/static/img/gumb_fb.png" class="fbsignin"/>
			<img src="/static/img/gumb_twitter.png" class="twsignin"/>
			<img src="/static/img/gumb_g+.png" class="googlesign"/>
		</div>
		<div class="createaccount">
			<p>ali si ustvari račun</p>
			<input type="text" class="accountname" placeholder="ime in priimek"/>
			<input type="email" class="accountemail" placeholder="email"/>
		</div>
		<div class="submitsuggestion postsuggestion"></div>
	</div>
</div>

<?php print ($this->footer); ?>