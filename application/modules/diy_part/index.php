<?php 
/*
******************************************************************************
*   Part List                                                                *
*   > Misc. > Parts		                                                	 *               
*       Dictionaries/diy_part/index.php										 *
*       Dictionaries/diy_part/ajax/diy_part_process.						 *
*       javaScript/ots-core.js                                               *
*       Dictionaries/diy_part/app/app.js                                     *
*                                                                            *
*   Displays list of psrtd. Allows to setup new part or modify existing one. *
*                                                                            *
*   by Albert Szczesny 2014                                                  *
******************************************************************************
*/  

require_once('../../../includes/initialize.php');

$session = new WidgetSession();
$session->Impress();

if(!$session->IsLoggedIn()){
    redirect_to("login");
}
$user = $session->getUserObject();
include_layout_template('tables_header.php');
?>
<body id="part_index_page">
    <div class="main">
        <header><!-- Defining the header section of the page -->
            <div class="container">
			<div id="h1">
            Misc module
            <hr style='color: #00CCFF; background-color: #00CCFF; height: 1px; width: 100%; padding: 0px 0px 0px 0px;' />
			</div>
			<div class="ui-widget ui-widget-header ui-corner-all">
            <button id="new">
            </button>
			<button id="import">
            </button>
        </div>
			</div>
        </header>

        <section id="content"><!-- Defining the main content section of the page -->
            <div class="main_container box">
				<div id="new-form" title="New part" style="display: none;">
					<form method="post" id="insert-form" action="">
						<fieldset>
							<p class="validateTips" style="display: none;">
							</p>
							ID
							<p id="P1" style="display: inline">
								Auto</p>
							<label for="partNumber">P/N</label>
							<input type="text" name="partNumber" id="partNumber" required class="text ui-widget-content ui-corner-all" />
							<label for="model">
								Model</label>
							<input type="text" name="model" id="model" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="description">
								Description</label>
							<input type="text" name="description" id="description" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="unitPrice">
								unitPrice</label>
							<input type="text" name="unitPrice" id="unitPrice" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="vendorId">
								Vendor</label>
							<select id="vendorId"></select>
						</fieldset>
					</form>
				</div>
				<div id="update-form" title="Edit part" style="display: none;">
					<form method="post" id="edit-form" action="">
						<fieldset>
							 <p class="validateTips" style="display: none;">
							</p>
							<label for="id">
								ID
							</label>
							<input type="text" name="id" id="id" readonly class="number ui-widget-content ui-corner-all" />
							<label for="new_partNumber">P/N</label>
							<input type="text" name="new_partNumber" id="new_partNumber" required class="text ui-widget-content ui-corner-all" />
							<label for="new_model">
								Model</label>
							<input type="text" name="new_model" id="new_model" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="new_description">
								Description</label>
							<input type="text" name="new_description" id="new_description" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="new_unitPrice">
								Unit Price</label>
							<input type="new_number" name="unitPrice" id="new_unitPrice" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="new_vendorId">
								Vendor</label>
							<select id="new_vendorId"></select>
						</fieldset>
					</form>
				</div>
				<div style="display: none">
				
					<input type="text" name="user" id="user" value="<?php echo $user->login; ?>" />
					<input type="text" name="uRole" id="uRole" value="<?php echo $user->role; ?>" />
				</div>
				<div id="user-contain" class="ui-widget">
				<h1>Part manager</h1>
				<table id="Results01" summary="Part list." class="ui-widget ui-widget-content sortable">
					<thead> 
						<tr class="ui-widget-header fixed-tr">
							<th scope="col">
								#
							</th>
							<th scope="col">
								P/N
							</th>
							<th scope="col">
								Model
							</th>
							<th scope="col">
								Description
							</th>
							<th scope="col">
								Unit Price
							</th>
							<th scope="col">
								Vendor
							</th>
							<th scope="col">
							</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
			</div>
				<div class="main-description"></div>
				<div id="message"></div>
			</div>
		</section>
	</div>
	<script src="../../javascript/ots-core.js" type="text/javascript"></script>
	<script src="app/app.js" type="text/javascript"></script>
	<script language="javascript" type="application/javascript">
        //<![CDATA[
        var url = urlProcess();
        $(document).ready(function() {
            var data = "action=show";
            getData(data, url);
            initializeButtons();
            initializeMD($("#new-form"), $("#update-form"));
            radioButtons();
        }).ajaxStart(function() {
            $("#message").append("<span class=\"ui-state-processing\">Loading...</span>")
        })
        .ajaxStop(function() {
            $("#message").hide()
        });                        

        // ]]>     
    </script>
</body>
</html>