<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "proposalinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$proposal_add = NULL; // Initialize page object first

class cproposal_add extends cproposal {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{DE39BAA5-7315-4870-A01D-3D4074A06380}";

	// Table name
	var $TableName = 'proposal';

	// Page object name
	var $PageObjName = 'proposal_add';

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

		// Table object (proposal)
		if (!isset($GLOBALS["proposal"])) {
			$GLOBALS["proposal"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["proposal"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'proposal', TRUE);

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id_proposal"] != "") {
				$this->id_proposal->setQueryStringValue($_GET["id_proposal"]);
				$this->setKey("id_proposal", $this->id_proposal->CurrentValue); // Set up key
			} else {
				$this->setKey("id_proposal", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("proposallist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "proposalview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
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

	// Load default values
	function LoadDefaultValues() {
		$this->id_right->CurrentValue = NULL;
		$this->id_right->OldValue = $this->id_right->CurrentValue;
		$this->id_user->CurrentValue = NULL;
		$this->id_user->OldValue = $this->id_user->CurrentValue;
		$this->title->CurrentValue = NULL;
		$this->title->OldValue = $this->title->CurrentValue;
		$this->text->CurrentValue = NULL;
		$this->text->OldValue = $this->text->CurrentValue;
		$this->approved->CurrentValue = 0;
		$this->timestamp->CurrentValue = NULL;
		$this->timestamp->OldValue = $this->timestamp->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_right->FldIsDetailKey) {
			$this->id_right->setFormValue($objForm->GetValue("x_id_right"));
		}
		if (!$this->id_user->FldIsDetailKey) {
			$this->id_user->setFormValue($objForm->GetValue("x_id_user"));
		}
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->text->FldIsDetailKey) {
			$this->text->setFormValue($objForm->GetValue("x_text"));
		}
		if (!$this->approved->FldIsDetailKey) {
			$this->approved->setFormValue($objForm->GetValue("x_approved"));
		}
		if (!$this->timestamp->FldIsDetailKey) {
			$this->timestamp->setFormValue($objForm->GetValue("x_timestamp"));
			$this->timestamp->CurrentValue = ew_UnFormatDateTime($this->timestamp->CurrentValue, 5);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id_right->CurrentValue = $this->id_right->FormValue;
		$this->id_user->CurrentValue = $this->id_user->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->text->CurrentValue = $this->text->FormValue;
		$this->approved->CurrentValue = $this->approved->FormValue;
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
		$this->id_proposal->setDbValue($rs->fields('id_proposal'));
		$this->id_right->setDbValue($rs->fields('id_right'));
		$this->id_user->setDbValue($rs->fields('id_user'));
		$this->title->setDbValue($rs->fields('title'));
		$this->text->setDbValue($rs->fields('text'));
		$this->approved->setDbValue($rs->fields('approved'));
		$this->timestamp->setDbValue($rs->fields('timestamp'));
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_proposal")) <> "")
			$this->id_proposal->CurrentValue = $this->getKey("id_proposal"); // id_proposal
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_proposal
		// id_right
		// id_user
		// title
		// text
		// approved
		// timestamp

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_proposal
			$this->id_proposal->ViewValue = $this->id_proposal->CurrentValue;
			$this->id_proposal->ViewCustomAttributes = "";

			// id_right
			$this->id_right->ViewValue = $this->id_right->CurrentValue;
			$this->id_right->ViewCustomAttributes = "";

			// id_user
			$this->id_user->ViewValue = $this->id_user->CurrentValue;
			$this->id_user->ViewCustomAttributes = "";

			// title
			$this->title->ViewValue = $this->title->CurrentValue;
			$this->title->ViewCustomAttributes = "";

			// text
			$this->text->ViewValue = $this->text->CurrentValue;
			$this->text->ViewCustomAttributes = "";

			// approved
			$this->approved->ViewValue = $this->approved->CurrentValue;
			$this->approved->ViewCustomAttributes = "";

			// timestamp
			$this->timestamp->ViewValue = $this->timestamp->CurrentValue;
			$this->timestamp->ViewValue = ew_FormatDateTime($this->timestamp->ViewValue, 5);
			$this->timestamp->ViewCustomAttributes = "";

			// id_right
			$this->id_right->LinkCustomAttributes = "";
			$this->id_right->HrefValue = "";
			$this->id_right->TooltipValue = "";

			// id_user
			$this->id_user->LinkCustomAttributes = "";
			$this->id_user->HrefValue = "";
			$this->id_user->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// text
			$this->text->LinkCustomAttributes = "";
			$this->text->HrefValue = "";
			$this->text->TooltipValue = "";

			// approved
			$this->approved->LinkCustomAttributes = "";
			$this->approved->HrefValue = "";
			$this->approved->TooltipValue = "";

			// timestamp
			$this->timestamp->LinkCustomAttributes = "";
			$this->timestamp->HrefValue = "";
			$this->timestamp->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_right
			$this->id_right->EditCustomAttributes = "";
			$this->id_right->EditValue = ew_HtmlEncode($this->id_right->CurrentValue);

			// id_user
			$this->id_user->EditCustomAttributes = "";
			$this->id_user->EditValue = ew_HtmlEncode($this->id_user->CurrentValue);

			// title
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);

			// text
			$this->text->EditCustomAttributes = "";
			$this->text->EditValue = ew_HtmlEncode($this->text->CurrentValue);

			// approved
			$this->approved->EditCustomAttributes = "";
			$this->approved->EditValue = ew_HtmlEncode($this->approved->CurrentValue);

			// timestamp
			$this->timestamp->EditCustomAttributes = "";
			$this->timestamp->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->timestamp->CurrentValue, 5));

			// Edit refer script
			// id_right

			$this->id_right->HrefValue = "";

			// id_user
			$this->id_user->HrefValue = "";

			// title
			$this->title->HrefValue = "";

			// text
			$this->text->HrefValue = "";

			// approved
			$this->approved->HrefValue = "";

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
		if (!is_null($this->id_right->FormValue) && $this->id_right->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_right->FldCaption());
		}
		if (!ew_CheckInteger($this->id_right->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_right->FldErrMsg());
		}
		if (!is_null($this->id_user->FormValue) && $this->id_user->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_user->FldCaption());
		}
		if (!ew_CheckInteger($this->id_user->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_user->FldErrMsg());
		}
		if (!is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->title->FldCaption());
		}
		if (!is_null($this->text->FormValue) && $this->text->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->text->FldCaption());
		}
		if (!is_null($this->approved->FormValue) && $this->approved->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->approved->FldCaption());
		}
		if (!ew_CheckInteger($this->approved->FormValue)) {
			ew_AddMessage($gsFormError, $this->approved->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		$rsnew = array();

		// id_right
		$this->id_right->SetDbValueDef($rsnew, $this->id_right->CurrentValue, 0, FALSE);

		// id_user
		$this->id_user->SetDbValueDef($rsnew, $this->id_user->CurrentValue, 0, FALSE);

		// title
		$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", FALSE);

		// text
		$this->text->SetDbValueDef($rsnew, $this->text->CurrentValue, "", FALSE);

		// approved
		$this->approved->SetDbValueDef($rsnew, $this->approved->CurrentValue, 0, strval($this->approved->CurrentValue) == "");

		// timestamp
		$this->timestamp->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->timestamp->CurrentValue, 5), NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->id_proposal->setDbValue($conn->Insert_ID());
			$rsnew['id_proposal'] = $this->id_proposal->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
if (!isset($proposal_add)) $proposal_add = new cproposal_add();

// Page init
$proposal_add->Page_Init();

// Page main
$proposal_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var proposal_add = new ew_Page("proposal_add");
proposal_add.PageID = "add"; // Page ID
var EW_PAGE_ID = proposal_add.PageID; // For backward compatibility

// Form object
var fproposaladd = new ew_Form("fproposaladd");

// Validate form
fproposaladd.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_id_right"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($proposal->id_right->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_id_right"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($proposal->id_right->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_id_user"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($proposal->id_user->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_id_user"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($proposal->id_user->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_title"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($proposal->title->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_text"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($proposal->text->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_approved"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($proposal->approved->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_approved"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($proposal->approved->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_timestamp"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($proposal->timestamp->FldErrMsg()) ?>");

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
fproposaladd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fproposaladd.ValidateRequired = true;
<?php } else { ?>
fproposaladd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $proposal->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $proposal->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $proposal_add->ShowPageHeader(); ?>
<?php
$proposal_add->ShowMessage();
?>
<form name="fproposaladd" id="fproposaladd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="proposal">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_proposaladd" class="ewTable">
<?php if ($proposal->id_right->Visible) { // id_right ?>
	<tr id="r_id_right"<?php echo $proposal->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_proposal_id_right"><table class="ewTableHeaderBtn"><tr><td><?php echo $proposal->id_right->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $proposal->id_right->CellAttributes() ?>><span id="el_proposal_id_right">
<input type="text" name="x_id_right" id="x_id_right" size="30" value="<?php echo $proposal->id_right->EditValue ?>"<?php echo $proposal->id_right->EditAttributes() ?>>
</span><?php echo $proposal->id_right->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($proposal->id_user->Visible) { // id_user ?>
	<tr id="r_id_user"<?php echo $proposal->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_proposal_id_user"><table class="ewTableHeaderBtn"><tr><td><?php echo $proposal->id_user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $proposal->id_user->CellAttributes() ?>><span id="el_proposal_id_user">
<input type="text" name="x_id_user" id="x_id_user" size="30" value="<?php echo $proposal->id_user->EditValue ?>"<?php echo $proposal->id_user->EditAttributes() ?>>
</span><?php echo $proposal->id_user->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($proposal->title->Visible) { // title ?>
	<tr id="r_title"<?php echo $proposal->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_proposal_title"><table class="ewTableHeaderBtn"><tr><td><?php echo $proposal->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $proposal->title->CellAttributes() ?>><span id="el_proposal_title">
<input type="text" name="x_title" id="x_title" size="30" maxlength="140" value="<?php echo $proposal->title->EditValue ?>"<?php echo $proposal->title->EditAttributes() ?>>
</span><?php echo $proposal->title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($proposal->text->Visible) { // text ?>
	<tr id="r_text"<?php echo $proposal->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_proposal_text"><table class="ewTableHeaderBtn"><tr><td><?php echo $proposal->text->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $proposal->text->CellAttributes() ?>><span id="el_proposal_text">
<textarea name="x_text" id="x_text" cols="35" rows="4"<?php echo $proposal->text->EditAttributes() ?>><?php echo $proposal->text->EditValue ?></textarea>
</span><?php echo $proposal->text->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($proposal->approved->Visible) { // approved ?>
	<tr id="r_approved"<?php echo $proposal->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_proposal_approved"><table class="ewTableHeaderBtn"><tr><td><?php echo $proposal->approved->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $proposal->approved->CellAttributes() ?>><span id="el_proposal_approved">
<input type="text" name="x_approved" id="x_approved" size="30" value="<?php echo $proposal->approved->EditValue ?>"<?php echo $proposal->approved->EditAttributes() ?>>
</span><?php echo $proposal->approved->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($proposal->timestamp->Visible) { // timestamp ?>
	<tr id="r_timestamp"<?php echo $proposal->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_proposal_timestamp"><table class="ewTableHeaderBtn"><tr><td><?php echo $proposal->timestamp->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $proposal->timestamp->CellAttributes() ?>><span id="el_proposal_timestamp">
<input type="text" name="x_timestamp" id="x_timestamp" value="<?php echo $proposal->timestamp->EditValue ?>"<?php echo $proposal->timestamp->EditAttributes() ?>>
</span><?php echo $proposal->timestamp->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>">
</form>
<script type="text/javascript">
fproposaladd.Init();
</script>
<?php
$proposal_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$proposal_add->Page_Terminate();
?>
