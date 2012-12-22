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

$vote_delete = NULL; // Initialize page object first

class cvote_delete extends cvote {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{DE39BAA5-7315-4870-A01D-3D4074A06380}";

	// Table name
	var $TableName = 'vote';

	// Page object name
	var $PageObjName = 'vote_delete';

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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vote', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("votelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in vote class, voteinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		} else {
			$this->LoadRowValues($rs); // Load row values
		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id_vote'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
if (!isset($vote_delete)) $vote_delete = new cvote_delete();

// Page init
$vote_delete->Page_Init();

// Page main
$vote_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var vote_delete = new ew_Page("vote_delete");
vote_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = vote_delete.PageID; // For backward compatibility

// Form object
var fvotedelete = new ew_Form("fvotedelete");

// Form_CustomValidate event
fvotedelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvotedelete.ValidateRequired = true;
<?php } else { ?>
fvotedelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($vote_delete->Recordset = $vote_delete->LoadRecordset())
	$vote_deleteTotalRecs = $vote_delete->Recordset->RecordCount(); // Get record count
if ($vote_deleteTotalRecs <= 0) { // No record found, exit
	if ($vote_delete->Recordset)
		$vote_delete->Recordset->Close();
	$vote_delete->Page_Terminate("votelist.php"); // Return to list
}
?>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $vote->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $vote->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $vote_delete->ShowPageHeader(); ?>
<?php
$vote_delete->ShowMessage();
?>
<form name="fvotedelete" id="fvotedelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<br>
<input type="hidden" name="t" value="vote">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($vote_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_votedelete" class="ewTable ewTableSeparate">
<?php echo $vote->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_vote_id_vote" class="vote_id_vote"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->id_vote->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_vote_id_argument" class="vote_id_argument"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->id_argument->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_vote_id_proposal" class="vote_id_proposal"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->id_proposal->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_vote_id_user" class="vote_id_user"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->id_user->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_vote_type" class="vote_type"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->type->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_vote_vote_plus" class="vote_vote_plus"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->vote_plus->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_vote_vote_minus" class="vote_vote_minus"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->vote_minus->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_vote_timestamp" class="vote_timestamp"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->timestamp->FldCaption() ?></td></tr></table></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$vote_delete->RecCnt = 0;
$i = 0;
while (!$vote_delete->Recordset->EOF) {
	$vote_delete->RecCnt++;
	$vote_delete->RowCnt++;

	// Set row properties
	$vote->ResetAttrs();
	$vote->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$vote_delete->LoadRowValues($vote_delete->Recordset);

	// Render row
	$vote_delete->RenderRow();
?>
	<tr<?php echo $vote->RowAttributes() ?>>
		<td<?php echo $vote->id_vote->CellAttributes() ?>><span id="el<?php echo $vote_delete->RowCnt ?>_vote_id_vote" class="vote_id_vote">
<span<?php echo $vote->id_vote->ViewAttributes() ?>>
<?php echo $vote->id_vote->ListViewValue() ?></span>
</span></td>
		<td<?php echo $vote->id_argument->CellAttributes() ?>><span id="el<?php echo $vote_delete->RowCnt ?>_vote_id_argument" class="vote_id_argument">
<span<?php echo $vote->id_argument->ViewAttributes() ?>>
<?php echo $vote->id_argument->ListViewValue() ?></span>
</span></td>
		<td<?php echo $vote->id_proposal->CellAttributes() ?>><span id="el<?php echo $vote_delete->RowCnt ?>_vote_id_proposal" class="vote_id_proposal">
<span<?php echo $vote->id_proposal->ViewAttributes() ?>>
<?php echo $vote->id_proposal->ListViewValue() ?></span>
</span></td>
		<td<?php echo $vote->id_user->CellAttributes() ?>><span id="el<?php echo $vote_delete->RowCnt ?>_vote_id_user" class="vote_id_user">
<span<?php echo $vote->id_user->ViewAttributes() ?>>
<?php echo $vote->id_user->ListViewValue() ?></span>
</span></td>
		<td<?php echo $vote->type->CellAttributes() ?>><span id="el<?php echo $vote_delete->RowCnt ?>_vote_type" class="vote_type">
<span<?php echo $vote->type->ViewAttributes() ?>>
<?php echo $vote->type->ListViewValue() ?></span>
</span></td>
		<td<?php echo $vote->vote_plus->CellAttributes() ?>><span id="el<?php echo $vote_delete->RowCnt ?>_vote_vote_plus" class="vote_vote_plus">
<span<?php echo $vote->vote_plus->ViewAttributes() ?>>
<?php echo $vote->vote_plus->ListViewValue() ?></span>
</span></td>
		<td<?php echo $vote->vote_minus->CellAttributes() ?>><span id="el<?php echo $vote_delete->RowCnt ?>_vote_vote_minus" class="vote_vote_minus">
<span<?php echo $vote->vote_minus->ViewAttributes() ?>>
<?php echo $vote->vote_minus->ListViewValue() ?></span>
</span></td>
		<td<?php echo $vote->timestamp->CellAttributes() ?>><span id="el<?php echo $vote_delete->RowCnt ?>_vote_timestamp" class="vote_timestamp">
<span<?php echo $vote->timestamp->ViewAttributes() ?>>
<?php echo $vote->timestamp->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$vote_delete->Recordset->MoveNext();
}
$vote_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
fvotedelete.Init();
</script>
<?php
$vote_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vote_delete->Page_Terminate();
?>
