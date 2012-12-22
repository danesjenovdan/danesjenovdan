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

$vote_list = NULL; // Initialize page object first

class cvote_list extends cvote {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DE39BAA5-7315-4870-A01D-3D4074A06380}";

	// Table name
	var $TableName = 'vote';

	// Page object name
	var $PageObjName = 'vote_list';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "voteadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "votedelete.php";
		$this->MultiUpdateUrl = "voteupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vote', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $DisplayRecs = 50;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Handle reset command
			$this->ResetCmd();

			// Hide all options
			if ($this->Export <> "" ||
				$this->CurrentAction == "gridadd" ||
				$this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ExportOptions->HideAllOptions();
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 50; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id_vote->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_vote->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_vote); // id_vote
			$this->UpdateSort($this->id_argument); // id_argument
			$this->UpdateSort($this->id_proposal); // id_proposal
			$this->UpdateSort($this->id_user); // id_user
			$this->UpdateSort($this->type); // type
			$this->UpdateSort($this->vote_plus); // vote_plus
			$this->UpdateSort($this->vote_minus); // vote_minus
			$this->UpdateSort($this->timestamp); // timestamp
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// cmd=reset (Reset search parameters)
	// cmd=resetall (Reset search and master/detail parameters)
	// cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id_vote->setSort("");
				$this->id_argument->setSort("");
				$this->id_proposal->setSort("");
				$this->id_user->setSort("");
				$this->type->setSort("");
				$this->vote_plus->setSort("");
				$this->vote_minus->setSort("");
				$this->timestamp->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->ViewUrl . "\">" . $Language->Phrase("ViewLink") . "</a>";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->EditUrl . "\">" . $Language->Phrase("EditLink") . "</a>";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->CopyUrl . "\">" . $Language->Phrase("CopyLink") . "</a>";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink\"" . "" . " href=\"" . $this->DeleteUrl . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_vote")) <> "")
			$this->id_vote->CurrentValue = $this->getKey("id_vote"); // id_vote
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($vote_list)) $vote_list = new cvote_list();

// Page init
$vote_list->Page_Init();

// Page main
$vote_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var vote_list = new ew_Page("vote_list");
vote_list.PageID = "list"; // Page ID
var EW_PAGE_ID = vote_list.PageID; // For backward compatibility

// Form object
var fvotelist = new ew_Form("fvotelist");

// Form_CustomValidate event
fvotelist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvotelist.ValidateRequired = true;
<?php } else { ?>
fvotelist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$vote_list->TotalRecs = $vote->SelectRecordCount();
	} else {
		if ($vote_list->Recordset = $vote_list->LoadRecordset())
			$vote_list->TotalRecs = $vote_list->Recordset->RecordCount();
	}
	$vote_list->StartRec = 1;
	if ($vote_list->DisplayRecs <= 0 || ($vote->Export <> "" && $vote->ExportAll)) // Display all records
		$vote_list->DisplayRecs = $vote_list->TotalRecs;
	if (!($vote->Export <> "" && $vote->ExportAll))
		$vote_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$vote_list->Recordset = $vote_list->LoadRecordset($vote_list->StartRec-1, $vote_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $vote->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $vote_list->ExportOptions->Render("body"); ?>
</p>
<?php $vote_list->ShowPageHeader(); ?>
<?php
$vote_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fvotelist" id="fvotelist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="vote">
<div id="gmp_vote" class="ewGridMiddlePanel">
<?php if ($vote_list->TotalRecs > 0) { ?>
<table id="tbl_votelist" class="ewTable ewTableSeparate">
<?php echo $vote->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$vote_list->RenderListOptions();

// Render list options (header, left)
$vote_list->ListOptions->Render("header", "left");
?>
<?php if ($vote->id_vote->Visible) { // id_vote ?>
	<?php if ($vote->SortUrl($vote->id_vote) == "") { ?>
		<td><span id="elh_vote_id_vote" class="vote_id_vote"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $vote->id_vote->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $vote->SortUrl($vote->id_vote) ?>',1);"><span id="elh_vote_id_vote" class="vote_id_vote">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $vote->id_vote->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($vote->id_vote->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($vote->id_vote->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($vote->id_argument->Visible) { // id_argument ?>
	<?php if ($vote->SortUrl($vote->id_argument) == "") { ?>
		<td><span id="elh_vote_id_argument" class="vote_id_argument"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $vote->id_argument->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $vote->SortUrl($vote->id_argument) ?>',1);"><span id="elh_vote_id_argument" class="vote_id_argument">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $vote->id_argument->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($vote->id_argument->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($vote->id_argument->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($vote->id_proposal->Visible) { // id_proposal ?>
	<?php if ($vote->SortUrl($vote->id_proposal) == "") { ?>
		<td><span id="elh_vote_id_proposal" class="vote_id_proposal"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $vote->id_proposal->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $vote->SortUrl($vote->id_proposal) ?>',1);"><span id="elh_vote_id_proposal" class="vote_id_proposal">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $vote->id_proposal->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($vote->id_proposal->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($vote->id_proposal->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($vote->id_user->Visible) { // id_user ?>
	<?php if ($vote->SortUrl($vote->id_user) == "") { ?>
		<td><span id="elh_vote_id_user" class="vote_id_user"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $vote->id_user->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $vote->SortUrl($vote->id_user) ?>',1);"><span id="elh_vote_id_user" class="vote_id_user">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $vote->id_user->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($vote->id_user->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($vote->id_user->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($vote->type->Visible) { // type ?>
	<?php if ($vote->SortUrl($vote->type) == "") { ?>
		<td><span id="elh_vote_type" class="vote_type"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $vote->type->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $vote->SortUrl($vote->type) ?>',1);"><span id="elh_vote_type" class="vote_type">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $vote->type->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($vote->type->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($vote->type->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($vote->vote_plus->Visible) { // vote_plus ?>
	<?php if ($vote->SortUrl($vote->vote_plus) == "") { ?>
		<td><span id="elh_vote_vote_plus" class="vote_vote_plus"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $vote->vote_plus->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $vote->SortUrl($vote->vote_plus) ?>',1);"><span id="elh_vote_vote_plus" class="vote_vote_plus">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $vote->vote_plus->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($vote->vote_plus->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($vote->vote_plus->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($vote->vote_minus->Visible) { // vote_minus ?>
	<?php if ($vote->SortUrl($vote->vote_minus) == "") { ?>
		<td><span id="elh_vote_vote_minus" class="vote_vote_minus"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $vote->vote_minus->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $vote->SortUrl($vote->vote_minus) ?>',1);"><span id="elh_vote_vote_minus" class="vote_vote_minus">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $vote->vote_minus->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($vote->vote_minus->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($vote->vote_minus->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($vote->timestamp->Visible) { // timestamp ?>
	<?php if ($vote->SortUrl($vote->timestamp) == "") { ?>
		<td><span id="elh_vote_timestamp" class="vote_timestamp"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $vote->timestamp->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $vote->SortUrl($vote->timestamp) ?>',1);"><span id="elh_vote_timestamp" class="vote_timestamp">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $vote->timestamp->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($vote->timestamp->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($vote->timestamp->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vote_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($vote->ExportAll && $vote->Export <> "") {
	$vote_list->StopRec = $vote_list->TotalRecs;
} else {

	// Set the last record to display
	if ($vote_list->TotalRecs > $vote_list->StartRec + $vote_list->DisplayRecs - 1)
		$vote_list->StopRec = $vote_list->StartRec + $vote_list->DisplayRecs - 1;
	else
		$vote_list->StopRec = $vote_list->TotalRecs;
}
$vote_list->RecCnt = $vote_list->StartRec - 1;
if ($vote_list->Recordset && !$vote_list->Recordset->EOF) {
	$vote_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $vote_list->StartRec > 1)
		$vote_list->Recordset->Move($vote_list->StartRec - 1);
} elseif (!$vote->AllowAddDeleteRow && $vote_list->StopRec == 0) {
	$vote_list->StopRec = $vote->GridAddRowCount;
}

// Initialize aggregate
$vote->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vote->ResetAttrs();
$vote_list->RenderRow();
while ($vote_list->RecCnt < $vote_list->StopRec) {
	$vote_list->RecCnt++;
	if (intval($vote_list->RecCnt) >= intval($vote_list->StartRec)) {
		$vote_list->RowCnt++;

		// Set up key count
		$vote_list->KeyCount = $vote_list->RowIndex;

		// Init row class and style
		$vote->ResetAttrs();
		$vote->CssClass = "";
		if ($vote->CurrentAction == "gridadd") {
		} else {
			$vote_list->LoadRowValues($vote_list->Recordset); // Load row values
		}
		$vote->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$vote->RowAttrs = array_merge($vote->RowAttrs, array('data-rowindex'=>$vote_list->RowCnt, 'id'=>'r' . $vote_list->RowCnt . '_vote', 'data-rowtype'=>$vote->RowType));

		// Render row
		$vote_list->RenderRow();

		// Render list options
		$vote_list->RenderListOptions();
?>
	<tr<?php echo $vote->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vote_list->ListOptions->Render("body", "left", $vote_list->RowCnt);
?>
	<?php if ($vote->id_vote->Visible) { // id_vote ?>
		<td<?php echo $vote->id_vote->CellAttributes() ?>><span id="el<?php echo $vote_list->RowCnt ?>_vote_id_vote" class="vote_id_vote">
<span<?php echo $vote->id_vote->ViewAttributes() ?>>
<?php echo $vote->id_vote->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<a id="<?php echo $vote_list->PageObjName . "_row_" . $vote_list->RowCnt ?>"></a>
	<?php if ($vote->id_argument->Visible) { // id_argument ?>
		<td<?php echo $vote->id_argument->CellAttributes() ?>><span id="el<?php echo $vote_list->RowCnt ?>_vote_id_argument" class="vote_id_argument">
<span<?php echo $vote->id_argument->ViewAttributes() ?>>
<?php echo $vote->id_argument->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($vote->id_proposal->Visible) { // id_proposal ?>
		<td<?php echo $vote->id_proposal->CellAttributes() ?>><span id="el<?php echo $vote_list->RowCnt ?>_vote_id_proposal" class="vote_id_proposal">
<span<?php echo $vote->id_proposal->ViewAttributes() ?>>
<?php echo $vote->id_proposal->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($vote->id_user->Visible) { // id_user ?>
		<td<?php echo $vote->id_user->CellAttributes() ?>><span id="el<?php echo $vote_list->RowCnt ?>_vote_id_user" class="vote_id_user">
<span<?php echo $vote->id_user->ViewAttributes() ?>>
<?php echo $vote->id_user->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($vote->type->Visible) { // type ?>
		<td<?php echo $vote->type->CellAttributes() ?>><span id="el<?php echo $vote_list->RowCnt ?>_vote_type" class="vote_type">
<span<?php echo $vote->type->ViewAttributes() ?>>
<?php echo $vote->type->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($vote->vote_plus->Visible) { // vote_plus ?>
		<td<?php echo $vote->vote_plus->CellAttributes() ?>><span id="el<?php echo $vote_list->RowCnt ?>_vote_vote_plus" class="vote_vote_plus">
<span<?php echo $vote->vote_plus->ViewAttributes() ?>>
<?php echo $vote->vote_plus->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($vote->vote_minus->Visible) { // vote_minus ?>
		<td<?php echo $vote->vote_minus->CellAttributes() ?>><span id="el<?php echo $vote_list->RowCnt ?>_vote_vote_minus" class="vote_vote_minus">
<span<?php echo $vote->vote_minus->ViewAttributes() ?>>
<?php echo $vote->vote_minus->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($vote->timestamp->Visible) { // timestamp ?>
		<td<?php echo $vote->timestamp->CellAttributes() ?>><span id="el<?php echo $vote_list->RowCnt ?>_vote_timestamp" class="vote_timestamp">
<span<?php echo $vote->timestamp->ViewAttributes() ?>>
<?php echo $vote->timestamp->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$vote_list->ListOptions->Render("body", "right", $vote_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($vote->CurrentAction <> "gridadd")
		$vote_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($vote->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($vote_list->Recordset)
	$vote_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($vote->CurrentAction <> "gridadd" && $vote->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager"><tr><td>
<?php if (!isset($vote_list->Pager)) $vote_list->Pager = new cPrevNextPager($vote_list->StartRec, $vote_list->DisplayRecs, $vote_list->TotalRecs) ?>
<?php if ($vote_list->Pager->RecordCount > 0) { ?>
	<table cellspacing="0" class="ewStdTable"><tbody><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($vote_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $vote_list->PageUrl() ?>start=<?php echo $vote_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($vote_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $vote_list->PageUrl() ?>start=<?php echo $vote_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $vote_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($vote_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $vote_list->PageUrl() ?>start=<?php echo $vote_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($vote_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $vote_list->PageUrl() ?>start=<?php echo $vote_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $vote_list->Pager->PageCount ?></span></td>
	</tr></tbody></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vote_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vote_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vote_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($vote_list->SearchWhere == "0=101") { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
	</td>
</tr></table>
</form>
<?php } ?>
<span class="phpmaker">
<?php if ($vote_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $vote_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
</span>
</div>
</td></tr></table>
<script type="text/javascript">
fvotelist.Init();
</script>
<?php
$vote_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vote_list->Page_Terminate();
?>
