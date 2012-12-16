<?php print ($this->header); ?>


<div class="container">
	<div class="row">
		<div class="span3 offset9">
			<div class="suggest"></div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="span12">
			<span class="title"><?php print $this->predlog->title; ?></span>
			<div class="suggestionup"></div>
			<div class="suggestionvotefor" data-id="<?php print $this->predlog->id; ?>"><?php print $this->predlog->vote_plus; ?></div>
			<div class="suggestiondown"></div>
			<div class="suggestionvoteagainst" data-id="<?php print $this->predlog->id; ?>"><?php print $this->predlog->vote_minus; ?></div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<span class="author"><?php print $this->user->name; ?></span>
			<span class="suggestiontimestamp"><?php print $this->predlog->timestamp; ?></span>
			<span class="suggestionsocial">LAJK BATONS</span>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<p class="suggestiontext"><?php print $this->predlog->text; ?></p>
		</div>
	</div>
	<hr />
	<div class="row">
		<div class="span6">
			<h1>Dokumenti</h1>
			<div class="documentbox">
			<?php foreach ($this->document as $key => $value) { ?>
				<a href="<?php print $value->path; ?>" class="documentlink"><?php print $value->title; ?> <span class="documenttype">(<?php print $value->type; ?>, <?php print $value->size; ?>)</span></a>
			<?php } ?>	
				<div class="adddocument"></div>
				<form class="adddocumentbox">
					<input type="file" name="documentfile" />
					<input type="submit" />
				</form>
			</div>
		</div>
		<div class="span6">
			<h1>Delovna skupina</h1>
			<div class="workgroupbox">
				<p>Iniciativa mi veliko pomeni ...</p>
				<div class="toggleworkgroup">PRIDRUŽI SE BATON</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span6">
			Prednosti
			<?php
			foreach ($this->argplus as $key => $value) { ?>
				<div class="row">

					<div class="span6">
						<div class="argumentbox">
							<p class="authorship"><?php print $value->name; ?>, [<?php print $value->timestamp; ?>]</p>
							<p class="argumenttext"><?php print $value->text; ?></p>
							<div class="argumentup" data-id="<?php print $value->id_argument; ?>"></div>
							<div class="argumentdown" data-id="<?php print $value->id_argument; ?>"></div>
						</div>
					</div>
						

				</div>
			<?php }?>

			<div class="row">
				<div class="span6">
					<div class="addargument">
						<img src="/static/img/human.png"><!-- INJECT SRC FROM FACEBOOK -->
						<input type="textarea" name="argumentinput" id="argumentinput"/>
						<div class="socialconnect">
							<p>Poveži se z</p>
							<img src="/static/img/gumb_fb.png" />
							<img src="/static/img/gumb_twitter.png" />
							<img src="/static/img/gumb_g+.png" />
						</div>
						<div class="createaccount">
							<p>ali si ustvari račun</p>
							<input type="text" />
							<input type="email" />
							<input type="submit" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="span6">
			Zadržki
			<?php
			foreach ($this->argminus as $key => $value) { ?>
				<div class="row">

					<div class="span6">
						<div class="argumentbox">
							<p class="authorship"><?php print $value->name; ?>, [<?php print $value->timestamp; ?>]</p>
							<p class="argumenttext"><?php print $value->text; ?></p>
							<div class="argumentup" data-id="<?php print $value->id_argument; ?>"></div>
							<div class="argumentdown" data-id="<?php print $value->id_argument; ?>"></div>
						</div>
					</div>
						

				</div>
			<?php }?>
			

			<div class="addargument">
				<img src="/static/img/human.png"><!-- INJECT SRC FROM FACEBOOK -->
				<input type="textarea" name="argumentinput" id="argumentinput"/>
				<div class="socialconnect">
					<p>Poveži se z</p>
					<img src="/static/img/gumb_fb.png" />
					<img src="/static/img/gumb_twitter.png" />
					<img src="/static/img/gumb_g+.png" />
				</div>
				<div class="createaccount">
					<p>ali si ustvari račun</p>
					<input type="text" />
					<input type="email" />
					<input type="submit" />
				</div>
			</div>
		</div>
	</div>
</div>



<?php print ($this->footer); ?>