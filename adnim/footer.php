<?php if (@!$gbSkipHeaderFooter) { ?>
				<p>&nbsp;</p>			
			<!-- right column (end) -->
			<?php if (isset($gTimer)) $gTimer->Stop() ?>
	    </td>	
		</tr>
	</table>
	<!-- content (end) -->
<?php if (!ew_IsMobile()) { ?>
	<!-- footer (begin) --><!-- *** Note: Only licensed users are allowed to remove or change the following copyright statement. *** -->
	<div class="ewFooterRow">	
		<div class="ewFooterText">&nbsp;<?php echo $Language->ProjectPhrase("FooterText") ?></div>
		<!-- Place other links, for example, disclaimer, here -->		
	</div>
	<!-- footer (end) -->	
<?php } ?>
</div>
<?php } ?>
<?php if (ew_IsMobile()) { ?>
	</div>
	<!-- footer (begin) --><!-- *** Note: Only licensed users are allowed to remove or change the following copyright statement. *** -->
<!-- *** Remove comment lines to show footer for mobile
	<div data-role="footer">
		<h4>&nbsp;<?php echo $Language->ProjectPhrase("FooterText") ?></h4>
	</div>
*** -->
	<!-- footer (end) -->	
</div>
<script type="text/javascript">
$("#ewPageTitle").html($("#ewPageCaption").text());
</script>
<?php } ?>
<div class="yui-tt" id="ewTooltipDiv" style="visibility: hidden; border: 0px;"></div>
<script type="text/javascript">
ew_Select("table." + EW_TABLE_CLASSNAME, document, ew_SetupTable); // Init tables
ew_Select("table." + EW_GRID_CLASSNAME, document, ew_SetupGrid); // Init grids
ew_InitTooltipDiv(); // init tooltip div
</script>
<script type="text/javascript">

// Write your global startup script here
// document.write("page loaded");

</script>
</body>
</html>
