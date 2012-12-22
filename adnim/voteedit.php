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

$vote_edit = NULL; // Initialize page object first

class cvote_edit extends cvote {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{DE39BAA5-7315-4870-A01D-3D4074A06380}";

	// Table name
	var $TableName = 'vote';

	// Page object name
	var $PageObjName = 'vote_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id_vote"] <> "")
			$this->id_vote->setQueryStringValue($_GET["id_vote"]);

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id_vote->CurrentValue == "")
			$this->Page_Terminate("votelist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("votelist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
		$index = $objForm->Index; // Save form index
		$objForm->Index = -1;
		$confirmPage = (strval($objForm->GetValue("a_confirm")) <> "");
		$objForm->Index = $index; // Restore form index
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_vote->FldIsDetailKey)
			$this->id_vote->setFormValue($objForm->GetValue("x_id_vote"));
		if (!$this->id_argument->FldIsDetailKey) {
			$this->id_argument->setFormValue($objForm->GetValue("x_id_argument"));
		}
		if (!$this->id_proposal->FldIsDetailKey) {
			$this->id_proposal->setFormValue($objForm->GetValue("x_id_proposal"));
		}
		if (!$this->id_user->FldIsDetailKey) {
			$this->id_user->setFormValue($objForm->GetValue("x_id_user"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->vote_plus->FldIsDetailKey) {
			$this->vote_plus->setFormValue($objForm->GetValue("x_vote_plus"));
		}
		if (!$this->vote_minus->FldIsDetailKey) {
			$this->vote_minus->setFormValue($objForm->GetValue("x_vote_minus"));
		}
		if (!$this->timestamp->FldIsDetailKey) {
			$this->timestamp->setFormValue($objForm->GetValue("x_timestamp"));
			$this->timestamp->CurrentValue = ew_UnFormatDateTime($this->timestamp->CurrentValue, 5);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_vote->CurrentValue = $this->id_vote->FormValue;
		$this->id_argument->CurrentValue = $this->id_argument->FormValue;
		$this->id_proposal->CurrentValue = $this->id_proposal->FormValue;
		$this->id_user->CurrentValue = $this->id_user->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->vote_plus->CurrentValue = $this->vote_plus->FormValue;
		$this->vote_minus->CurrentValue = $this->vote_minus->FormValue;
		$this->timestamp->CurrentValue = $this->timestamp->FormValue;
		$this->timestamp->CurrentValue = ew_UnFormatDateTime($this->timestamp->CurrentValue, 5);
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_vote
			$this->id_vote->EditCustomAttributes = "";
			$this->id_vote->EditValue = $this->id_vote->CurrentValue;
			$this->id_vote->ViewCustomAttributes = "";

			// id_argument
			$this->id_argument->EditCustomAttributes = "";
			$this->id_argument->EditValue = ew_HtmlEncode($this->id_argument->CurrentValue);

			// id_proposal
			$this->id_proposal->EditCustomAttributes = "";
			$this->id_proposal->EditValue = ew_HtmlEncode($this->id_proposal->CurrentValue);

			// id_user
			$this->id_user->EditCustomAttributes = "";
			$this->id_user->EditValue = ew_HtmlEncode($this->id_user->CurrentValue);

			// type
			$this->type->EditCustomAttributes = "";
			$this->type->EditValue = ew_HtmlEncode($this->type->CurrentValue);

			// vote_plus
			$this->vote_plus->EditCustomAttributes = "";
			$this->vote_plus->EditValue = ew_HtmlEncode($this->vote_plus->CurrentValue);

			// vote_minus
			$this->vote_minus->EditCustomAttributes = "";
			$this->vote_minus->EditValue = ew_HtmlEncode($this->vote_minus->CurrentValue);

			// timestamp
			$this->timestamp->EditCustomAttributes = "";
			$this->timestamp->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->timestamp->CurrentValue, 5));

			// Edit refer script
			// id_vote

			$this->id_vote->HrefValue = "";

			// id_argument
			$this->id_argument->HrefValue = "";

			// id_proposal
			$this->id_proposal->HrefValue = "";

			// id_user
			$this->id_user->HrefValue = "";

			// type
			$this->type->HrefValue = "";

			// vote_plus
			$this->vote_plus->HrefValue = "";

			// vote_minus
			$this->vote_minus->HrefValue = "";

			// timestamp
			$this->timestamp->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!is_null($this->id_argument->FormValue) && $this->id_argument->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_argument->FldCaption());
		}
		if (!ew_CheckInteger($this->id_argument->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_argument->FldErrMsg());
		}
		if (!is_null($this->id_proposal->FormValue) && $this->id_proposal->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_proposal->FldCaption());
		}
		if (!ew_CheckInteger($this->id_proposal->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_proposal->FldErrMsg());
		}
		if (!is_null($this->id_user->FormValue) && $this->id_user->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_user->FldCaption());
		}
		if (!ew_CheckInteger($this->id_user->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_user->FldErrMsg());
		}
		if (!is_null($this->type->FormValue) && $this->type->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->type->FldCaption());
		}
		if (!ew_CheckInteger($this->type->FormValue)) {
			ew_AddMessage($gsFormError, $this->type->FldErrMsg());
		}
		if (!is_null($this->vote_plus->FormValue) && $this->vote_plus->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vote_plus->FldCaption());
		}
		if (!ew_CheckInteger($this->vote_plus->FormValue)) {
			ew_AddMessage($gsFormError, $this->vote_plus->FldErrMsg());
		}
		if (!is_null($this->vote_minus->FormValue) && $this->vote_minus->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vote_minus->FldCaption());
		}
		if (!ew_CheckInteger($this->vote_minus->FormValue)) {
			ew_AddMessage($gsFormError, $this->vote_minus->FldErrMsg());
		}
		if (!ew_CheckDate($this->timestamp->FormValue)) {
			ew_AddMessage($gsFormError, $this->timestamp->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$rsnew = array();

			// id_argument
			$this->id_argument->SetDbValueDef($rsnew, $this->id_argument->CurrentValue, 0, $this->id_argument->ReadOnly);

			// id_proposal
			$this->id_proposal->SetDbValueDef($rsnew, $this->id_proposal->CurrentValue, 0, $this->id_proposal->ReadOnly);

			// id_user
			$this->id_user->SetDbValueDef($rsnew, $this->id_user->CurrentValue, 0, $this->id_user->ReadOnly);

			// type
			$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, 0, $this->type->ReadOnly);

			// vote_plus
			$this->vote_plus->SetDbValueDef($rsnew, $this->vote_plus->CurrentValue, 0, $this->vote_plus->ReadOnly);

			// vote_minus
			$this->vote_minus->SetDbValueDef($rsnew, $this->vote_minus->CurrentValue, 0, $this->vote_minus->ReadOnly);

			// timestamp
			$this->timestamp->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->timestamp->CurrentValue, 5), NULL, $this->timestamp->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($vote_edit)) $vote_edit = new cvote_edit();

// Page init
$vote_edit->Page_Init();

// Page main
$vote_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var vote_edit = new ew_Page("vote_edit");
vote_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = vote_edit.PageID; // For backward compatibility

// Form object
var fvoteedit = new ew_Form("fvoteedit");

// Validate form
fvoteedit.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = "";
		elm = fobj.elements["x" + infix + "_id_argument"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($vote->id_argument->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_id_argument"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($vote->id_argument->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_id_proposal"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($vote->id_proposal->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_id_proposal"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($vote->id_proposal->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_id_user"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($vote->id_user->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_id_user"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($vote->id_user->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_type"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($vote->type->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_type"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($vote->type->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_vote_plus"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($vote->vote_plus->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_vote_plus"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($vote->vote_plus->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_vote_minus"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($vote->vote_minus->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_vote_minus"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($vote->vote_minus->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_timestamp"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($vote->timestamp->FldErrMsg()) ?>");

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}

	// Process detail page
	if (fobj.detailpage && fobj.detailpage.value && ewForms[fobj.detailpage.value])
		return ewForms[fobj.detailpage.value].Validate(fobj);
	return true;
}

// Form_CustomValidate event
fvoteedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvoteedit.ValidateRequired = true;
<?php } else { ?>
fvoteedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $vote->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $vote->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $vote_edit->ShowPageHeader(); ?>
<?php
$vote_edit->ShowMessage();
?>
<form name="fvoteedit" id="fvoteedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="vote">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_voteedit" class="ewTable">
<?php if ($vote->id_vote->Visible) { // id_vote ?>
	<tr id="r_id_vote"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_id_vote"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->id_vote->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $vote->id_vote->CellAttributes() ?>><span id="el_vote_id_vote">
<span<?php echo $vote->id_vote->ViewAttributes() ?>>
<?php echo $vote->id_vote->EditValue ?></span>
<input type="hidden" name="x_id_vote" id="x_id_vote" value="<?php echo ew_HtmlEncode($vote->id_vote->CurrentValue) ?>">
</span><?php echo $vote->id_vote->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($vote->id_argument->Visible) { // id_argument ?>
	<tr id="r_id_argument"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_id_argument"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->id_argument->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $vote->id_argument->CellAttributes() ?>><span id="el_vote_id_argument">
<input type="text" name="x_id_argument" id="x_id_argument" size="30" value="<?php echo $vote->id_argument->EditValue ?>"<?php echo $vote->id_argument->EditAttributes() ?>>
</span><?php echo $vote->id_argument->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($vote->id_proposal->Visible) { // id_proposal ?>
	<tr id="r_id_proposal"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_id_proposal"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->id_proposal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $vote->id_proposal->CellAttributes() ?>><span id="el_vote_id_proposal">
<input type="text" name="x_id_proposal" id="x_id_proposal" size="30" value="<?php echo $vote->id_proposal->EditValue ?>"<?php echo $vote->id_proposal->EditAttributes() ?>>
</span><?php echo $vote->id_proposal->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($vote->id_user->Visible) { // id_user ?>
	<tr id="r_id_user"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_id_user"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->id_user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $vote->id_user->CellAttributes() ?>><span id="el_vote_id_user">
<input type="text" name="x_id_user" id="x_id_user" size="30" value="<?php echo $vote->id_user->EditValue ?>"<?php echo $vote->id_user->EditAttributes() ?>>
</span><?php echo $vote->id_user->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($vote->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_type"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $vote->type->CellAttributes() ?>><span id="el_vote_type">
<input type="text" name="x_type" id="x_type" size="30" value="<?php echo $vote->type->EditValue ?>"<?php echo $vote->type->EditAttributes() ?>>
</span><?php echo $vote->type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($vote->vote_plus->Visible) { // vote_plus ?>
	<tr id="r_vote_plus"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_vote_plus"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->vote_plus->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $vote->vote_plus->CellAttributes() ?>><span id="el_vote_vote_plus">
<input type="text" name="x_vote_plus" id="x_vote_plus" size="30" value="<?php echo $vote->vote_plus->EditValue ?>"<?php echo $vote->vote_plus->EditAttributes() ?>>
</span><?php echo $vote->vote_plus->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($vote->vote_minus->Visible) { // vote_minus ?>
	<tr id="r_vote_minus"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_vote_minus"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->vote_minus->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $vote->vote_minus->CellAttributes() ?>><span id="el_vote_vote_minus">
<input type="text" name="x_vote_minus" id="x_vote_minus" size="30" value="<?php echo $vote->vote_minus->EditValue ?>"<?php echo $vote->vote_minus->EditAttributes() ?>>
</span><?php echo $vote->vote_minus->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($vote->timestamp->Visible) { // timestamp ?>
	<tr id="r_timestamp"<?php echo $vote->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_vote_timestamp"><table class="ewTableHeaderBtn"><tr><td><?php echo $vote->timestamp->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $vote->timestamp->CellAttributes() ?>><span id="el_vote_timestamp">
<input type="text" name="x_timestamp" id="x_timestamp" value="<?php echo $vote->timestamp->EditValue ?>"<?php echo $vote->timestamp->EditAttributes() ?>>
</span><?php echo $vote->timestamp->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fvoteedit.Init();
</script>
<?php
$vote_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vote_edit->Page_Terminate();
?>
