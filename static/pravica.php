<?php print ($this->header); ?>

<div class="container">
	<div class="row">
		<div class="span3 offset9">
			DODAJ PREDLOG +
		</div>
	</div>
</div>
<div class="container" id="predlogi">

			<?php foreach ($this->predlogi as $key => $value) { ?>

<div class="row predlog">
	<div class="span4">
		<p class="suggestiontitle"><?php print $value->title; ?></p>
	</div>
	<div class="span1">
		<p class="timestamp"><?php print $value->timestamp; ?></p>
	</div>
	<div class="span4">
		<div class="votebox" data-id="<?php print $value->id; ?>">
			<div class="votefor"><?php print $value->vote_plus; ?></div>
			<div class="voteagainst"><?php print $value->vote_minus; ?></div>
		</div>
	</div>
	<div class="span3">
		<div class="ihaveanargument">imam argument</div>
	</div>
</div>

			<?php } ?>	


	<!-- konec predlogov -->
	<div class="moresuggestions">
		VEČ
	</div>
</div>
<input type="hidden" value="<?php print $this->id; ?>" id="rightid"/>
<div class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Modal header</h3>
	</div>
	<div class="modal-body">
		<p>One fine body…</p>
		<form>
			<input type="text" />
			<input type="textarea" />
		</form>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-primary">Save changes</a>
	</div>
</div>

<?php print ($this->footer); ?>