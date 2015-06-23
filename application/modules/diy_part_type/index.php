<?php 
/*
******************************************************************************
*   Part category List                                                                *
*   > Admin > role			                                                 *               
*       Jobs/jobList.asp                                                     *
*       Jobs/jobList_process.asp                                             *
*       JavaScript/ots-core.js                                               *
*       JavaScript/jobList.js                                                *
*                                                                            *
*   Displays list of jobs. Allows to setup new job or modify existing one.   *
*   Launches jobDetails.asp (jobs fields).                                   *
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
<body id="part_category_index_page">
    <div class="demo">
        <header><!-- Defining the header section of the page -->
            <div class="container">
			<div id="h1">
            Administrator module
            <hr style='color: #00CCFF; background-color: #00CCFF; height: 1px; width: 100%; padding: 0px 0px 0px 0px;' />
			</div>
			<div class="ui-widget ui-widget-header ui-corner-all">
            <button id="new">
            </button>

        </div>
			</div>
        </header>

        <section id="content"><!-- Defining the main content section of the page -->
            <div class="main_container box">
				<div id="new-form" title="New category." style="display: none;">
					<form method="post" id="insert-form" action="">
						<fieldset>
							ID
							<p id="P1" style="display: inline">
								Auto</p>
							<label for="name">Category name</label>
							<input type="text" name="name" id="name" required class="text ui-widget-content ui-corner-all" />
						</fieldset>
					</form>
				</div>
				<div id="update-form" title="Edit category." style="display: none;">
					<p class="validateTips">
						Change category name.</p>
					<form method="post" id="edit-form" action="">
						<fieldset>
							<label for="newName">
								Category name</label>
							<input type="text" name="newName" id="newName" class="text ui-widget-content ui-corner-all" />
						</fieldset>
					</form>
				</div>
				<div style="display: none">
				
				<input type="text" id="id" value="" name="id" />
				<input type="text" name="user" id="user" value="<?php echo $user->login; ?>" />
				<input type="text" name="uRole" id="uRole" value="<?php echo $user->role; ?>" />
				</div>
            <div id="user-contain" class="ui-widget">
            <h1>
                Customer category manager</h1>
            <table id="Results01" summary="Category list." class="ui-widget ui-widget-content sortable">
                <thead>
                    <tr class="ui-widget-header fixed-tr">
                        <th scope="col">
                            #
                        </th>
                        <th scope="col">
                            name
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
        <div class="main-description">
        </div>
        <div id="message">
        </div>	

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