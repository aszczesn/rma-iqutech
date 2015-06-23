<?php 
/*
******************************************************************************
*   Customer List                                                                *
*   > Admin > user			                                                 *               
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
<body id="customer_index_page">
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
        </div>
			</div>
        </header>

        <section id="content"><!-- Defining the main content section of the page -->
            <div class="main_container box">
				<div id="new-form" title="New customer." style="display: none;">
					<form method="post" id="insert-form" action="">
						<fieldset>
							<p class="validateTips" style="display: none;">
							</p>
							ID
							<p id="P1" style="display: inline">
								Auto</p>
							<label for="shortname">short name</label>
							<input type="text" name="shortname" id="shortname" required class="text ui-widget-content ui-corner-all" />
							<label for="name">
								Name</label>
							<input type="text" name="name" id="name" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="phoneno">
								phoneno</label>
							<input type="text" name="phoneno" id="phoneno" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="email">
								e-mail</label>
							<input type="email" name="email" id="email" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="categoryid">
								category</label>
							<select id="categoryid">
							</select>
							<label for="city">
								city</label>
							<input type="text" name="city" id="city" value="" required class="text ui-widget-content ui-corner-all" />

							<label for="address">
								address</label>
							<input type="text" name="address" id="address" value="" required class="text ui-widget-content ui-corner-all" />

							<label for="zip">
								zip</label>  
							<input type="text" name="zip" id="zip" value="" required class="text ui-widget-content ui-corner-all" /><br /> 


							<label for="country">
								country</label>    
							<input type="text" name="country" id="country" value="" required class="text ui-widget-content ui-corner-all" /><br /> 
							
						</fieldset>
					</form>
				</div>
				<div id="update-form" title="Edit customer." style="display: none;">
					<form method="post" id="edit-form" action="">
						<fieldset>
							 <p class="validateTips" style="display: none;">
							</p>
							<label for="id">
								ID
							</label>
							<input type="text" name="id" id="id" readonly class="number ui-widget-content ui-corner-all" />
							<label for="newShortname">short name</label>
							<input type="text" name="newShortname" id="newShortname" required class="text ui-widget-content ui-corner-all" />
							<label for="newName">
								Name</label>
							<input type="text" name="newName" id="newName" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="newCategoryid">
								category</label>
							<select id="newCategoryid">
							</select>
							<label for="newPhoneno">
								phoneno</label>
							<input type="text" name="newPhoneno" id="newPhoneno" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="newEmail">
								e-mail</label>
							<input type="newEmail" name="newEmail" id="newEmail" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="newCity">
								city</label>
							<input type="text" name="newCity" id="newCity" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="newAddress">
								address</label>
							<input type="text" name="newAddress" id="newAddress" value="" required class="text ui-widget-content ui-corner-all" />
							<label for="newZip">
									zip</label> 
							<input type="text" name="newZip" id="newZip" value="" required class="text ui-widget-content ui-corner-all" /><br /> 

							<label for="newCountry">
								country</label>         
							<input type="text" name="newCountry" id="newCountry" value="" required class="text ui-widget-content ui-corner-all" /><br /> 

						</fieldset>
					</form>
				</div>
				<div style="display: none">
				
					<input type="text" name="user" id="user" value="<?php echo $user->login; ?>" />
					<input type="text" name="uRole" id="uRole" value="<?php echo $user->role; ?>" />
				</div>
				<div id="user-contain" class="ui-widget">
				<h1>Customer manager</h1>
				<table id="Results01" summary="Customer list." class="ui-widget ui-widget-content sortable">
					<thead> 
						<tr class="ui-widget-header fixed-tr">
							<th scope="col">
								#
							</th>
							<th scope="col">
								short name
							</th>
							<th scope="col">
								name
							</th>
							<th scope="col">
								e-mail
							</th>
							<th scope="col">
								city
							</th>
							<th scope="col">
								address
							</th>
							<th scope="col">
								zip
							</th>
							<th scope="col">
								country
							</th>
							<th scope="col">
								categoryid	
							</th>
							<th scope="col">
								phoneno
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