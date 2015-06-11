<?php 
/*
******************************************************************************
*   Issue RMA#                                                               *
*   > Receiving > Issue RMA#		                                         *               
*       receiving/issue_rmano/index.php                                        *
*       receiving/issue_rmano/ajax/rmano_process.asp                           *
*       JavaScript/ots-core.js                                               *
*       receiving/issue_rmano/app/app.js                                       *
*                                                                            *
*																			 *
*                                                                            *
*   by Albert Szczesny 2014                                                  *
******************************************************************************
*/  

require_once('../../../includes/initialize.php');

$session = new WidgetSession();
$session->Impress();

if(!$session->IsLoggedIn()){
    redirect_to(HOME_PAGE);
}
$user = $session->getUserObject();
include_layout_template('tables_header.php');
?>
<body id="customer_index_page">
    <div class="main">
        <header><!-- Defining the header section of the page -->
            <div class="container">
				<div id="h1">
					Reciving module
					<hr style='color: #00CCFF; background-color: #00CCFF; height: 1px; width: 100%; padding: 0px 0px 0px 0px;' />
					</div>
					<div class="ui-widget ui-widget-header ui-corner-all">
					<button id="new">
					</button>
					<button id="checkRma">
					</button>
				</div>
			</div>
        </header>

        <section id="content"><!-- Defining the main content section of the page -->
            <div class="main_container box">
				<div id="new-form" title="New rma." style="display: none;">
					<form method="post" id="insert-form" action="">
						<fieldset>
							<p class="validateTips" style="display: none;">
							</p>
							<label for="rmano">RMA</label>
							<input type="text" name="rmano" id="rmano" value="<?php echo Rma::generateRMANo();?>" required readonly class="text ui-widget-content ui-corner-all" />
							<label for="issue_date">Issue date</label>
							<input type="text" name="issue_date" id="issue_date" required class="text ui-widget-content ui-corner-all" />
							<label for="supplierId">
								Supplier</label>
							<select id="supplierId">
							</select>
						</fieldset>
					</form>
				</div>
				<div id="update-form" title="Edit RMA Number." style="display: none;">
					<form method="post" id="edit-form" action="">
						<fieldset>
							 <p class="validateTips" style="display: none;">
							</p>
							<label for="id">Id</label>
							<input type="text" name="id" id="id" readonly class="text ui-widget-content ui-corner-all" />
							<label for="new_rmano">RMA</label>
							<input type="text" name="new_rmano" id="new_rmano" required class="text ui-widget-content ui-corner-all" />
							<label for="new_issue_date">Issue date</label>
							<input type="text" name="new_issue_date" id="new_issue_date" required class="text ui-widget-content ui-corner-all" />
							<label for="new_supplierId">
								Supplier</label>
							<select id="new_supplierId">
							</select>
						</fieldset>
					</form>
				</div>
				<div style="display: none">
				
					<input type="text" name="user" id="user" value="<?php echo $user->login; ?>" />
					<input type="text" name="uRole" id="uRole" value="<?php echo $user->role; ?>" />
				</div>
				<div id="user-contain" class="ui-widget">
				<h1>RMA manager</h1>
				<table id="Results01" summary="RMA Number list." class="ui-widget ui-widget-content sortable">
					<thead> 
						<tr class="ui-widget-header fixed-tr">
							<th scope="col">
								#
							</th>
							<th scope="col">
								RMA Number
							</th>
							<th scope="col">
								Supplier
							</th>
							<th scope="col">
								Issue date</th>
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