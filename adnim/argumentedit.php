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

$argument_edit = NULL; // Initialize page object first

class cargument_edit extends cargument {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{DE39BAA5-7315-4870-A01D-3D4074A06380}";

	// Table name
	var $TableName = 'argument';

	// Page object name
	var $PageObjName = 'argument_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id_argument"] <> "")
			$this->id_argument->setQueryStringValue($_GET["id_argument"]);

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id_argument->CurrentValue == "")
			$this->Page_Terminate("argumentlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("argumentlist.php"); // No matching record, return to list
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
		if (!$this->id_argument->FldIsDetailKey)
			$this->id_argument->setFormValue($objForm->GetValue("x_id_argument"));
		if (!$this->id_proposal->FldIsDetailKey) {
			$this->id_proposal->setFormValue($objForm->GetValue("x_id_proposal"));
		}
		if (!$this->id_user->FldIsDetailKey) {
			$this->id_user->setFormValue($objForm->GetValue("x_id_user"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->approved->FldIsDetailKey) {
			$this->approved->setFormValue($objForm->GetValue("x_approved"));
		}
		if (!$this->text->FldIsDetailKey) {
			$this->text->setFormValue($objForm->GetValue("x_text"));
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
		$this->id_argument->CurrentValue = $this->id_argument->FormValue;
		$this->id_proposal->CurrentValue = $this->id_proposal->FormValue;
		$this->id_user->CurrentValue = $this->id_user->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->approved->CurrentValue = $this->approved->FormValue;
		$this->text->CurrentValue = $this->text->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_argument
			$this->id_argument->EditCustomAttributes = "";
			$this->id_argument->EditValue = $this->id_argument->CurrentValue;
			$this->id_argument->ViewCustomAttributes = "";

			// id_proposal
			$this->id_proposal->EditCustomAttributes = "";
			$this->id_proposal->EditValue = ew_HtmlEncode($this->id_proposal->CurrentValue);

			// id_user
			$this->id_user->EditCustomAttributes = "";
			$this->id_user->EditValue = ew_HtmlEncode($this->id_user->CurrentValue);

			// type
			$this->type->EditCustomAttributes = "";
			$this->type->EditValue = ew_HtmlEncode($this->type->CurrentValue);

			// approved
			$this->approved->EditCustomAttributes = "";
			$this->approved->EditValue = ew_HtmlEncode($this->approved->CurrentValue);

			// text
			$this->text->EditCustomAttributes = "";
			$this->text->EditValue = ew_HtmlEncode($this->text->CurrentValue);

			// timestamp
			$this->timestamp->EditCustomAttributes = "";
			$this->timestamp->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->timestamp->CurrentValue, 5));

			// Edit refer script
			// id_argument

			$this->id_argument->HrefValue = "";

			// id_proposal
			$this->id_proposal->HrefValue = "";

			// id_user
			$this->id_user->HrefValue = "";

			// type
			$this->type->HrefValue = "";

			// approved
			$this->approved->HrefValue = "";

			// text
			$this->text->HrefValue = "";

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
		if (!ew_CheckInteger($this->approved->FormValue)) {
			ew_AddMessage($gsFormError, $this->approved->FldErrMsg());
		}
		if (!is_null($this->text->FormValue) && $this->text->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->text->FldCaption());
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

			// id_proposal
			$this->id_proposal->SetDbValueDef($rsnew, $this->id_proposal->CurrentValue, 0, $this->id_proposal->ReadOnly);

			// id_user
			$this->id_user->SetDbValueDef($rsnew, $this->id_user->CurrentValue, 0, $this->id_user->ReadOnly);

			// type
			$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, 0, $this->type->ReadOnly);

			// approved
			$this->approved->SetDbValueDef($rsnew, $this->approved->CurrentValue, NULL, $this->approved->ReadOnly);

			// text
			$this->text->SetDbValueDef($rsnew, $this->text->CurrentValue, "", $this->text->ReadOnly);

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
if (!isset($argument_edit)) $argument_edit = new cargument_edit();

// Page init
$argument_edit->Page_Init();

// Page main
$argument_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var argument_edit = new ew_Page("argument_edit");
argument_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = argument_edit.PageID; // For backward compatibility

// Form object
var fargumentedit = new ew_Form("fargumentedit");

// Validate form
fargumentedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_id_proposal"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($argument->id_proposal->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_id_proposal"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($argument->id_proposal->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_id_user"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($argument->id_user->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_id_user"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($argument->id_user->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_type"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($argument->type->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_type"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($argument->type->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_approved"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($argument->approved->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_text"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($argument->text->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_timestamp"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($argument->timestamp->FldErrMsg()) ?>");

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
fargumentedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fargumentedit.ValidateRequired = true;
<?php } else { ?>
fargumentedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $argument->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $argument->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $argument_edit->ShowPageHeader(); ?>
<?php
$argument_edit->ShowMessage();
?>
<form name="fargumentedit" id="fargumentedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="argument">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_argumentedit" class="ewTable">
<?php if ($argument->id_argument->Visible) { // id_argument ?>
	<tr id="r_id_argument"<?php echo $argument->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_argument_id_argument"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->id_argument->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $argument->id_argument->CellAttributes() ?>><span id="el_argument_id_argument">
<span<?php echo $argument->id_argument->ViewAttributes() ?>>
<?php echo $argument->id_argument->EditValue ?></span>
<input type="hidden" name="x_id_argument" id="x_id_argument" value="<?php echo ew_HtmlEncode($argument->id_argument->CurrentValue) ?>">
</span><?php echo $argument->id_argument->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($argument->id_proposal->Visible) { // id_proposal ?>
	<tr id="r_id_proposal"<?php echo $argument->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_argument_id_proposal"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->id_proposal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $argument->id_proposal->CellAttributes() ?>><span id="el_argument_id_proposal">
<input type="text" name="x_id_proposal" id="x_id_proposal" size="30" value="<?php echo $argument->id_proposal->EditValue ?>"<?php echo $argument->id_proposal->EditAttributes() ?>>
</span><?php echo $argument->id_proposal->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($argument->id_user->Visible) { // id_user ?>
	<tr id="r_id_user"<?php echo $argument->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_argument_id_user"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->id_user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $argument->id_user->CellAttributes() ?>><span id="el_argument_id_user">
<input type="text" name="x_id_user" id="x_id_user" size="30" value="<?php echo $argument->id_user->EditValue ?>"<?php echo $argument->id_user->EditAttributes() ?>>
</span><?php echo $argument->id_user->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($argument->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $argument->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_argument_type"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $argument->type->CellAttributes() ?>><span id="el_argument_type">
<input type="text" name="x_type" id="x_type" size="30" value="<?php echo $argument->type->EditValue ?>"<?php echo $argument->type->EditAttributes() ?>>
</span><?php echo $argument->type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($argument->approved->Visible) { // approved ?>
	<tr id="r_approved"<?php echo $argument->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_argument_approved"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->approved->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $argument->approved->CellAttributes() ?>><span id="el_argument_approved">
<input type="text" name="x_approved" id="x_approved" size="30" value="<?php echo $argument->approved->EditValue ?>"<?php echo $argument->approved->EditAttributes() ?>>
</span><?php echo $argument->approved->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($argument->text->Visible) { // text ?>
	<tr id="r_text"<?php echo $argument->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_argument_text"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->text->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $argument->text->CellAttributes() ?>><span id="el_argument_text">
<textarea name="x_text" id="x_text" cols="35" rows="4"<?php echo $argument->text->EditAttributes() ?>><?php echo $argument->text->EditValue ?></textarea>
</span><?php echo $argument->text->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($argument->timestamp->Visible) { // timestamp ?>
	<tr id="r_timestamp"<?php echo $argument->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_argument_timestamp"><table class="ewTableHeaderBtn"><tr><td><?php echo $argument->timestamp->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $argument->timestamp->CellAttributes() ?>><span id="el_argument_timestamp">
<input type="text" name="x_timestamp" id="x_timestamp" value="<?php echo $argument->timestamp->EditValue ?>"<?php echo $argument->timestamp->EditAttributes() ?>>
</span><?php echo $argument->timestamp->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fargumentedit.Init();
</script>
<?php
$argument_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$argument_edit->Page_Terminate();
?>
