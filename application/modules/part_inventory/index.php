<?php 
/*
******************************************************************************
*   Part_inventory List                                                      *
*   > Inventory > Part inventory		                                     *               
*       inventory/part_inventory/index.php									 *
*       inventory/part_inventory/ajax/part_inventory_process.php			 *
*       javaScript/ots-core.js                                               *
*       inventory/part_inventory/app/app.js                                  *
*                                                                            *
*   Displays list of psrts. Allows to setup new part or modify existing one. *
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
<body id="part_inventory_index_page">
    <div class="main">
        <header><!-- Defining the header section of the page -->
            <div class="container">
			<div id="h1">
            Misc module
            <hr style='color: #00CCFF; background-color: #00CCFF; height: 1px; width: 100%; padding: 0px 0px 0px 0px;' />
			</div>
			<div class="ui-widget ui-widget-header ui-corner-all">
            <button id="new" style="display: none;">
            </button>
			<button id="import">
            </button>
        </div>
			</div>
        </header>

        <section id="content"><!-- Defining the main content section of the page -->
            <div class="main_container box">
				<div id="update-form" title="Edit remarks" style="display: none;">
					<form method="post" id="edit-form" action="">
						<fieldset>
							 <p class="validateTips" style="display: none;">
							</p>
							<label for="id">
								ID
							</label>
							<input type="text" name="id" id="id" readonly class="number ui-widget-content ui-corner-all" />
							<label for="new_remarks">Remarks</label>
							<input type="text" name="new_remarks" id="new_remarks" required class="text ui-widget-content ui-corner-all" />
						</fieldset>
					</form>
				</div>
				<div style="display: none">
				
					<input type="text" name="user" id="user" value="<?php echo $user->login; ?>" />
					<input type="text" name="uRole" id="uRole" value="<?php echo $user->role; ?>" />
				</div>
				<div id="user-contain" class="ui-widget">
				<h1>Inventory manager</h1>
				<table id="Results01" summary="Inventory list." class="ui-widget ui-widget-content sortable">
					<thead> 
						<tr class="ui-widget-header fixed-tr">
							<th scope="col">
								#
							</th>
							<th scope="col">
								Parent P/N
							</th>
							<th scope="col">
								P/N
							</th>
							<th scope="col">
								Description
							</th>
							<th scope="col">
								ship from Pegatron A
							</th>
							<th scope="col">
								Arrived damaged B
							</th>
							<th scope="col">
								usage C
							</th>
							<th scope="col">
								Good Stock D
							</th>
							<th scope="col">
								NG stock E
							</th>
							<th scope="col">
								Remark
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