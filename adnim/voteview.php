<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "voteinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$vote_view = NULL; // Initialize page object first

class cvote_view extends cvote {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{DE39BAA5-7315-4870-A01D-3D4074A06380}";

	// Table name
	var $TableName = 'vote';

	// Page object name
	var $PageObjName = 'vote_view';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			$html .= "<p class=\"ewMessage\">" . $sMessage . "</p>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewWarningIcon\"></td><td class=\"ewWarningMessage\">" . $sWarningMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewSuccessIcon\"></td><td class=\"ewSuccessMessage\">" . $sSuccessMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewErrorIcon\"></td><td class=\"ewErrorMessage\">" . $sErrorMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p class=\"phpmaker\">" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<p class=\"phpmaker\">" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (vote)
		if (!isset($GLOBALS["vote"])) {
			$GLOBALS["vote"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vote"];
		}
		$KeyUrl = "";
		if (@$_GET["id_vote"] <> "") {
			$this->RecKey["id_vote"] = $_GET["id_vote"];
			$KeyUrl .= "&id_vote=" . urlencode($this->RecKey["id_vote"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vote', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->id_vote->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id_vote"] <> "") {
				$this->id_vote->setQueryStringValue($_GET["id_vote"]);
				$this->RecKey["id_vote"] = $this->id_vote->QueryStringValue;
			} else {
				$sReturnUrl = "votelist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "votelist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "votelist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id_vote->setDbValue($rs->fields('id_vote'));
		$this->id_argument->setDbValue($rs->fields('id_argument'));
		$this->id_proposal->setDbValue($rs->fields('id_proposal'));
		$this->id_user->setDbValue($rs->fields('id_user'));
		$this->type->setDbValue($rs->fields('type'));
		$this->vote_plus->setDbValue($rs->fields('vote_plus'));
		$this->vote_minus->setDbValue($rs->fields('vote_minus'));
		$this->timestamp->setDbValue($rs->fields('timestamp'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_vote
		// id_argument
		// id_proposal
		// id_user
		// type
		// vote_plus
		// vote_minus
		// timestamp

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_vote
			$this->id_vote->ViewValue = $this->id_vote->CurrentValue;
			$this->id_vote->ViewCustomAttributes = "";

			// id_argument
			$this->id_argument->ViewValue = $this->id_argument->CurrentValue;
			$this->id_argument->ViewCustomAttributes = "";

			// id_proposal
			$this->id_proposal->ViewValue = $this->id_proposal->CurrentValue;
			$this->id_proposal->ViewCustomAttributes = "";

			// id_user
			$this->id_user->ViewValue = $this->id_user->CurrentValue;
			$this->id_user->ViewCustomAttributes = "";

			// type
			$this->type->ViewValue = $this->type->CurrentValue;
			$this->type->ViewCustomAttributes = "";

			// vote_plus
			$this->vote_plus->ViewValue = $this->vote_plus->CurrentValue;
			$this->vote_plus->ViewCustomAttributes = "";

			// vote_minus
			$this->vote_minus->ViewValue = $this->vote_minus->CurrentValue;
			$this->vote_minus->ViewCustomAttributes = "";

			// timestamp
			$this->timestamp->ViewValue = $this->timestamp->CurrentValue;
			$this->timestamp->ViewValue = ew_FormatDateTime($this->timestamp->ViewValue, 5);
			$this->timestamp->ViewCustomAttributes = "";

			// id_vote
			$this->id_vote->LinkCustomAttributes = "";
			$this->id_vote->HrefValue = "";
			$this->id_vote->TooltipValue = "";

			// id_argument
			$this->id_argument->LinkCustomAttributes = "";
			$this->id_argument->HrefValue = "";
			$this->id_argument->TooltipValue = "";

			// id_proposal
			$this->id_proposal->LinkCustomAttributes = "";
			$this->id_proposal->HrefValue = "";
			$this->id_proposal->TooltipValue = "";

			// id_user
			$this->id_user->LinkCustomAttributes = "";
			$this->id_user->HrefValue = "";
			$this->id_user->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// vote_plus
			$this->vote_plus->LinkCustomAttributes = "";
			$this->vote_plus->HrefValue = "";
			$this->vote_plus->TooltipValue = "";

			// vote_minus
			$this->vote_minus->LinkCustomAttributes = "";
			$this->vote_minus->HrefValue = "";
			$this->vote_minus->TooltipValue = "";

			// timestamp
			$this->timestamp->LinkCustomAttributes = "";
			$this->timestamp->HrefValue = "";
			$this->timestamp->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($vote_view)) $vote_view = new cvote_view();

// Page init
$vote_view->Page_Init();

// Page main
$vote_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var vote_view = new ew_Page("vote_view");
vote_view.PageID = "view"; // Page ID
var EW_PAGE_ID = vote_view.PageID; // For backward compatibility

// Form object
var fvoteview = new ew_Form("fvoteview");

// Form_CustomValidate event
fvoteview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvoteview.ValidateRequired = true;
<?php } else { ?>
fvoteview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $vote->TableCaption() ?>&nbsp;&nbsp;</span><?php $vote_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $vote_view->ListUrl ?>" id="a_BackToList" class="ewLink"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($vote_view->AddUrl <> "") { ?>
<a href="<?php echo $vote_view->AddUrl ?>" id="a_AddLink" class="ewLink"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php if ($vote_view->EditUrl <> "") { ?>
<a href="<?php echo $vote_view->EditUrl ?>" id="a_EditLink" class="ewLink"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php if ($vote_view->CopyUrl <> "") { ?>
<a href="<?php echo $vote_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php if ($vote_view->DeleteUrl <> "") { ?>
<a href="<?php echo $vote_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
</p>
<?php $vote_view->ShowPageHeader(); ?>
<?php
$vote_view->ShowMessage();
?>
<form name="fvoteview" id="fvoteview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="vote">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_voteview" class="ewTable">
<?php if ($vote->id_vote->Visible) { // id_vote ?>
	<tr id="r_id_vote"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_id_vote"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->id_vote->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $vote->id_vote->CellAttributes() ?>><span id="el_vote_id_vote">
<span<?php echo $vote->id_vote->ViewAttributes() ?>>
<?php echo $vote->id_vote->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($vote->id_argument->Visible) { // id_argument ?>
	<tr id="r_id_argument"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_id_argument"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->id_argument->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $vote->id_argument->CellAttributes() ?>><span id="el_vote_id_argument">
<span<?php echo $vote->id_argument->ViewAttributes() ?>>
<?php echo $vote->id_argument->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($vote->id_proposal->Visible) { // id_proposal ?>
	<tr id="r_id_proposal"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_id_proposal"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->id_proposal->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $vote->id_proposal->CellAttributes() ?>><span id="el_vote_id_proposal">
<span<?php echo $vote->id_proposal->ViewAttributes() ?>>
<?php echo $vote->id_proposal->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($vote->id_user->Visible) { // id_user ?>
	<tr id="r_id_user"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_id_user"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->id_user->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $vote->id_user->CellAttributes() ?>><span id="el_vote_id_user">
<span<?php echo $vote->id_user->ViewAttributes() ?>>
<?php echo $vote->id_user->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($vote->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_type"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->type->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $vote->type->CellAttributes() ?>><span id="el_vote_type">
<span<?php echo $vote->type->ViewAttributes() ?>>
<?php echo $vote->type->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($vote->vote_plus->Visible) { // vote_plus ?>
	<tr id="r_vote_plus"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_vote_plus"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->vote_plus->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $vote->vote_plus->CellAttributes() ?>><span id="el_vote_vote_plus">
<span<?php echo $vote->vote_plus->ViewAttributes() ?>>
<?php echo $vote->vote_plus->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($vote->vote_minus->Visible) { // vote_minus ?>
	<tr id="r_vote_minus"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_vote_minus"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->vote_minus->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $vote->vote_minus->CellAttributes() ?>><span id="el_vote_vote_minus">
<span<?php echo $vote->vote_minus->ViewAttributes() ?>>
<?php echo $vote->vote_minus->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($vote->timestamp->Visible) { // timestamp ?>
	<tr id="r_timestamp"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_timestamp"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->timestamp->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $vote->timestamp->CellAttributes() ?>><span id="el_vote_timestamp">
<span<?php echo $vote->timestamp->ViewAttributes() ?>>
<?php echo $vote->timestamp->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<br>
<script type="text/javascript">
fvoteview.Init();
</script>
<?php
$vote_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vote_view->Page_Terminate();
?>
