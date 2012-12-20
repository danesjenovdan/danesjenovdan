<?php print ($this->header); ?>

<div class="container">
	<div class="row">
		<div class="span3 offset9 suggest">
		</div>
	</div>
</div>
<div class="container" id="predlogi">
<div class="row sortrow">
	<div class="span4 toggle"><img src="/static/img/toggle_datum_active.png" /></div>
	<div class="span2 timestampwrap"></div>
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
				<div class="votefor"></div>
				<div class="votecount"><?php print $value->vote_plus; ?></div>
				<div class="voteagainst"></div>
				<div class="votecount"><?php print $value->vote_minus; ?></div>
			</div>
		</div>
		<div class="span1">
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
	<div class="m0ar"></div>
</div>
<input type="hidden" value="<?php print $this->id; ?>" id="rightid"/>
<div class="modal hide fade suggestionpopup">
	<button type="button" class="closepopup" data-dismiss="modal" aria-hidden="true"></button>
	<div class="modal-body">
		<form>
			<input type="text" class="addsuggestiontitle" placeholder="naslov predloga (do 70 znakov)" />
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
			<div class="submitsuggestion postsuggestion"></div>
		</div>
	</div>
</div>

<?php print ($this->footer); ?>