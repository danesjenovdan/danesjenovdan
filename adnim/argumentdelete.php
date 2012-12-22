<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "argumentinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$argument_delete = NULL; // Initialize page object first

class cargument_delete extends cargument {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{DE39BAA5-7315-4870-A01D-3D4074A06380}";

	// Table name
	var $TableName = 'argument';

	// Page object name
	var $PageObjName = 'argument_delete';

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

		// Table object (argument)
		if (!isset($GLOBALS["argument"])) {
			$GLOBALS["argument"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["argument"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'argument', TRUE);

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
		$this->id_argument->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("argumentlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in argument class, argumentinfo.php

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
		$this->id_argument->setDbValue($rs->fields('id_argument'));
		$this->id_proposal->setDbValue($rs->fields('id_proposal'));
		$this->id_user->setDbValue($rs->fields('id_user'));
		$this->type->setDbValue($rs->fields('type'));
		$this->approved->setDbValue($rs->fields('approved'));
		$this->text->setDbValue($rs->fields('text'));
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
		// id_argument
		// id_proposal
		// id_user
		// type
		// approved
		// text
		// timestamp

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// approved
			$this->approved->ViewValue = $this->approved->CurrentValue;
			$this->approved->ViewCustomAttributes = "";

			// text
			$this->text->ViewValue = $this->text->CurrentValue;
			$this->text->ViewCustomAttributes = "";

			// timestamp
			$this->timestamp->ViewValue = $this->timestamp->CurrentValue;
			$this->timestamp->ViewValue = ew_FormatDateTime($this->timestamp->ViewValue, 5);
			$this->timestamp->ViewCustomAttributes = "";

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

			// approved
			$this->approved->LinkCustomAttributes = "";
			$this->approved->HrefValue = "";
			$this->approved->TooltipValue = "";

			// text
			$this->text->LinkCustomAttributes = "";
			$this->text->HrefValue = "";
			$this->text->TooltipValue = "";

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
				$sThisKey .= $row['id_argument'];
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
if (!isset($argument_delete)) $argument_delete = new cargument_delete();

// Page init
$argument_delete->Page_Init();

// Page main
$argument_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var argument_delete = new ew_Page("argument_delete");
argument_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = argument_delete.PageID; // For backward compatibility

// Form object
var fargumentdelete = new ew_Form("fargumentdelete");

// Form_CustomValidate event
fargumentdelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fargumentdelete.ValidateRequired = true;
<?php } else { ?>
fargumentdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($argument_delete->Recordset = $argument_delete->LoadRecordset())
	$argument_deleteTotalRecs = $argument_delete->Recordset->RecordCount(); // Get record count
if ($argument_deleteTotalRecs <= 0) { // No record found, exit
	if ($argument_delete->Recordset)
		$argument_delete->Recordset->Close();
	$argument_delete->Page_Terminate("argumentlist.php"); // Return to list
}
?>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $argument->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $argument->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $argument_delete->ShowPageHeader(); ?>
<?php
$argument_delete->ShowMessage();
?>
<form name="fargumentdelete" id="fargumentdelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<br>
<input type="hidden" name="t" value="argument">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($argument_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_argumentdelete" class="ewTable ewTableSeparate">
<?php echo $argument->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_argument_id_argument" class="argument_id_argument"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->id_argument->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_argument_id_proposal" class="argument_id_proposal"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->id_proposal->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_argument_id_user" class="argument_id_user"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->id_user->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_argument_type" class="argument_type"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->type->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_argument_approved" class="argument_approved"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->approved->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_argument_text" class="argument_text"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->text->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_argument_timestamp" class="argument_timestamp"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->timestamp->FldCaption() ?></td></tr></table></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$argument_delete->RecCnt = 0;
$i = 0;
while (!$argument_delete->Recordset->EOF) {
	$argument_delete->RecCnt++;
	$argument_delete->RowCnt++;

	// Set row properties
	$argument->ResetAttrs();
	$argument->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$argument_delete->LoadRowValues($argument_delete->Recordset);

	// Render row
	$argument_delete->RenderRow();
?>
	<tr<?php echo $argument->RowAttributes() ?>>
		<td<?php echo $argument->id_argument->CellAttributes() ?>><span id="el<?php echo $argument_delete->RowCnt ?>_argument_id_argument" class="argument_id_argument">
<span<?php echo $argument->id_argument->ViewAttributes() ?>>
<?php echo $argument->id_argument->ListViewValue() ?></span>
</span></td>
		<td<?php echo $argument->id_proposal->CellAttributes() ?>><span id="el<?php echo $argument_delete->RowCnt ?>_argument_id_proposal" class="argument_id_proposal">
<span<?php echo $argument->id_proposal->ViewAttributes() ?>>
<?php echo $argument->id_proposal->ListViewValue() ?></span>
</span></td>
		<td<?php echo $argument->id_user->CellAttributes() ?>><span id="el<?php echo $argument_delete->RowCnt ?>_argument_id_user" class="argument_id_user">
<span<?php echo $argument->id_user->ViewAttributes() ?>>
<?php echo $argument->id_user->ListViewValue() ?></span>
</span></td>
		<td<?php echo $argument->type->CellAttributes() ?>><span id="el<?php echo $argument_delete->RowCnt ?>_argument_type" class="argument_type">
<span<?php echo $argument->type->ViewAttributes() ?>>
<?php echo $argument->type->ListViewValue() ?></span>
</span></td>
		<td<?php echo $argument->approved->CellAttributes() ?>><span id="el<?php echo $argument_delete->RowCnt ?>_argument_approved" class="argument_approved">
<span<?php echo $argument->approved->ViewAttributes() ?>>
<?php echo $argument->approved->ListViewValue() ?></span>
</span></td>
		<td<?php echo $argument->text->CellAttributes() ?>><span id="el<?php echo $argument_delete->RowCnt ?>_argument_text" class="argument_text">
<span<?php echo $argument->text->ViewAttributes() ?>>
<?php echo $argument->text->ListViewValue() ?></span>
</span></td>
		<td<?php echo $argument->timestamp->CellAttributes() ?>><span id="el<?php echo $argument_delete->RowCnt ?>_argument_timestamp" class="argument_timestamp">
<span<?php echo $argument->timestamp->ViewAttributes() ?>>
<?php echo $argument->timestamp->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$argument_delete->Recordset->MoveNext();
}
$argument_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
fargumentdelete.Init();
</script>
<?php
$argument_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$argument_delete->Page_Terminate();
?>
