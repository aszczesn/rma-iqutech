var url = "ajax/issue_rmano_process.php";

function urlProcess() {
	return url;
}

function initializeButtons() {
    $("#new").button({
        icons: {
            primary: 'ui-icon-document'
        },
        label: "Issue RMA number"
    }).click(function () {
        $("#new-form").dialog("open");
    });
	$("#checkRma").button({
        icons: {
            primary: 'ui-icon-print'
        },
        label: "validate RMA"
    }).click(function(){
		$(location).attr('href','./checkrma/');
	});
};

function fillTable(data) {
	var tbody = $("table.sortable > tbody").html("");
	var tfoot = $("table.sortable > tfoot").html("");
	var index = 1;
	var user = $("#user").val();
	var uRole = $("#uRole").val();
	var count = data.length;

	$.each(data, function(){
		var rowText = "<tr id=\"" + this["id"] + "\" class=\"tr" + this["id"] + "\">" +
                              "<th scope=\"row\">" + index +
                              ".</td>" +
                              "<td>" + this["rmano"] + "</td>" +
                              "<td>" + this["supplierName"] +"</td>" +
                              "<td>" + this["issue_date"].toString().substr(0,10) + "</td>";
							  
		if (uRole === "Administrator") {
		    rowText += "<td width=\"1px;\"><a href=\"#\" id=\"" + this["id"]  + "\" class=\"editbutton" + this["id"]  + "\">Edit</a>&nbsp;" +
                       "<a href=\"#\" id=\"" + this["id"]  + "\" class=\"delbutton" + this["id"]  + "\">Delete</a>&nbsp;</td>";
		}
		rowText += "</tr>";
		
		$(rowText).appendTo(tbody);
		var objDelete = ".delbutton" + this["id"];
		var objEdit = ".editbutton" + this["id"];
		var objView = ".tr" + this["id"];

		if (uRole === "Administrator") {
			addEditEvent(objEdit);
			addDeleteEvent(objDelete);
		};
		
		// addViewDetailsEvent(objView);
		index++;
							  
	});
	
	
	if (count !== 0) {
		$("div.main-description").text("");
	}
	//            var f_row_text = "<tr><th>" + count + "</th></tr>";
	//            $(f_row_text).appendTo(tfoot);


	$("table.sortable tbody tr:odd").addClass("odd-tr");
	$("table.sortable tbody tr:even").addClass("even-tr");
	$("table.sortable tbody tr").mouseover(function() { $(this).addClass("over-tr"); }).mouseout(function() {
		$(this).removeClass("over-tr");
	}).click(function(event) {
		$("table.sortable tbody tr").removeClass("click-tr");
		$(this).toggleClass("click-tr");
		if (event.target.type !== 'checkbox') {
			$(':checkbox', this).attr('checked', function() {
				$(':checkbox').attr('checked', function() {
					return false;
				});
				return !this.checked;
			});
		}
	});
};

function addViewDetailsEvent(obj) {
	$(obj).dblclick(function() {
		var element = $(this);
		var getId = element.attr("id");
		$("#id").val(getId);
		$("#rmaparts").submit();
	});
}

function fillMD(data) {
	$("#id").val(data["id"]);
	$("#new_rmano").val(data["rmano"]);
	fillSelectTypes(data["supplierId"], 1, $("select#new_supplierId"), "..\/..\/dictionaries\/diy_customer\/ajax\/diy_customer_process.php");
	$("#new_issue_date").val(data["issue_date"]);
}

function initializeMD(n, u) {
	var oNew = n;
	var oUpdate = u;
	oNew.dialog("destroy");
	oUpdate.dialog("destroy");
	
	var rmano = $("#rmano"),
		supplierId = $("#supplierId"),
		issue_date = $("#issue_date"),
		allFields = $([]).add(rmano).add(supplierId).add(issue_date);

	oNew.dialog({
	    autoOpen: false,
	    open: function () {
	        fillSelectTypes(0,0, $("select#supplierId"), "..\/..\/dictionaries\/diy_customer\/ajax\/diy_customer_process.php");
	        },
	    height: 400,
	    width: 400,
	    modal: true,
	    buttons: {
	        'Issue RMA NUmber': function () {
	            var bValid = true;
				allFields.removeClass('ui-state-error');
					if(bValid){
					var aFields = new Array();
					aFields["rmano"] = $("#rmano").val();
	                aFields["supplierId"] = $("#supplierId").val();
	                aFields["issue_date"] = $("#issue_date").val();

					submitForm("insert", aFields);
					$(this).dialog('close');
	            }
	        },
	        Cancel: function () {
	            $(this).dialog('close');
				location.reload();
	        }
	    },
	    close: function () {
	        allFields.val('').removeClass('ui-state-error');
			location.reload();
	    }
	});

	oUpdate.dialog({
		autoOpen: false,
		open: function() {
			
		},
		height: 400,
		width: 400,
		modal: true,
		buttons: {
			'Save changes': function() {
				var rmano = $("#new_rmano"),
					issue_date = $("#new_issue_date"),
					allFields = $([]).add(rmano).add(issue_date);
				
				var bValid = true;			
//				allFields.removeClass('ui-state-error');
//				
//				bValid = bValid && checkLength(shortname, "shortname", 5, 32);
//	            bValid = bValid && checkLength(name, "name", 3, 45);
//
//	            bValid = bValid && checkRegexp(shortname, /^[A-Z]([0-9a-z_ &])+$/i, "shortname may consist of a-z, 0-9, &,  underscores, begin with a letter.");
//	            bValid = bValid && checkRegexp(name, /^[A-Z]([0-9a-z_ &])+$/i, "name may consist of A-Z, 0-9, &, underscores, begin with a letter.");
				
				if (bValid) {
					var aFields = new Array();
					aFields["id"] = $("#id").val();
					
	                aFields["rmano"] = $("#new_rmano").val();
	                aFields["supplierId"] = $("#new_supplierId :selected").val();
	                aFields["issue_date"] = $("#new_issue_date").val();

					submitForm("update", aFields);

					$(this).dialog('close');
				}
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		},
		close: function() {
			allFields.val('').removeClass('ui-state-error');
		}
	});
};

function submitForm(action, aFields) {
	var data = "action=show";
	var id = -1;
	if (aFields["id"])
		id = aFields["id"];
	$.ajax({
		type: "POST",
		url: url,
		data: ({
			action: action,
			id: id,
			rmano: aFields["rmano"],
			issue_date: aFields["issue_date"],
			supplierId: aFields["supplierId"]
		}),
		success: function(response) { getData(data, url) }
	});
}
