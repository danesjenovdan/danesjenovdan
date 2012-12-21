<?php print ($this->header); $id_user = $this->id_user; ?>

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
				<div class="suggestionup  <?php print ($this->predlog->vote_plus>0) ? "marked" : null; ?>" data-id="<?php print $this->predlog->id; ?>"></div>
				<div class="votecount"><span><?php print $this->predlog->vote_plus; ?></span></div>
				<div class="suggestiondown <?php print ($this->predlog->vote_minus>0) ? "marked" : null; ?>" data-id="<?php print $this->predlog->id; ?>"></div>
				<div class="votecount"><span><?php print $this->predlog->vote_minus; ?></span></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<span class="author"><?php print $this->user->name; ?> <?php print $this->user->surname; ?>, </span>
			<span class="suggestiontimestamp"><?php print $this->predlog->timestamp; ?></span>
			<span class="suggestionsocial">
				<div class="fb-like" data-href="http://danesjenovdan.si" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
				<div class="g-plusone" data-size="medium" data-annotation="buble" data-width="100"></div>
				<script type="text/javascript">
					(function() {
						var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
						po.src = 'https://apis.google.com/js/plusone.js';
						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
					})();
				</script>
				<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a>
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
			<h1 class="documenttitle">Dokumenti</h1>
			<div class="documentbox">
				<div class="adddocument"></div>
				
			<?php foreach ($this->document as $key => $value) { ?>
				<a href="/documents/<?php print $value->path; ?>" target="_blank" class="documentlink"><?php print $value->title; ?> <span class="documenttype">(<?php print strtolower($value->type); ?>, <?php print $value->size; ?>)</span></a><br />
			<?php } ?>	
				<form class="adddocumentbox" id="adddocumentbox" action="/ajax/add_document.php" method="post"> 
					<input type="text" name="documentname" id="documentname" placeholder="ime dokumenta" />
<input type="hidden" name="right_id" value="<?php print $this->id_right; ?>" />
<input type="hidden" name="proposal_id" value="<?php print $this->predlog->id; ?>" />

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
				<p class="workgrouptext">Iniciativa mi veliko pomeni ...</p>
				<div class="toggleworkgroup"></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span6">
			<h1 class="documenttitle">Prednosti</h1>
			<?php
			foreach ($this->argplus as $key => $value) { ?>
				<div class="row">

					<div class="span6">
						<div class="argumentboxfor">
							<p class="authorship"><?php print $value->name; ?>, <?php print $value->timestamp; ?></p>
							<p class="argumenttext"><?php print $value->text; ?></p>
							<div class="argumentup <?php print ($value->vuser_plus>0) ? "marked" : null; ?>" data-id="<?php print $value->id_argument; ?>"></div>
							<div class="argumentdown  <?php print ($value->vuser_minus>0) ? "marked" : null; ?>" data-id="<?php print $value->id_argument; ?>"></div>
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
			<?php
			foreach ($this->argminus as $key => $value) { ?>
				<div class="row">

					<div class="span6">
						<div class="argumentboxagainst">
							<p class="authorship"><?php print $value->name; ?>, <?php print $value->timestamp; ?></p>
							<p class="argumenttext"><?php print $value->text; ?></p>
							<div class="argumentup  <?php print ($value->vuser_plus>0) ? "marked" : null; ?>" data-id="<?php print $value->id_argument; ?>"></div>
							<div class="argumentdown  <?php print ($value->vuser_minus>0) ? "marked" : null; ?>" data-id="<?php print $value->id_argument; ?>"></div>
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
			<p>ali si ustvari račun</p>
			<input type="text" class="accountname" placeholder="ime in priimek"/>
			<input type="email" class="accountemail" placeholder="email"/>
		</div>
		<div class="submitloginpopup"></div>
	</div>
</div>





<?php print ($this->footer); ?>