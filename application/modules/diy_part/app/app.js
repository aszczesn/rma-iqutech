var url = "ajax/diy_part_process.php";

function urlProcess() {
	return url;
}

function initializeButtons() {
    $("#new").button({
        icons: {
            primary: 'ui-icon-document'
        },
        label: "New"
    }).click(function () {
        $("#new-form").dialog("open");
    });

    $("#import").button({
        icons: {
            primary: 'ui-icon-print'
        },
        label: "import"
    }).click(function(){
		$(location).attr('href','upload_csv.php');
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
                              "<td width=\"10%;\">" + this["partNumber"] + "</td>" +
                              "<td>" + this["model"] +"</td>" +
                              "<td>" + this["description"] + "</td>" +
                              "<td>" + this["unitPrice"] + "</td>" +
							  "<td>" + this["vendorName"] + "</td>";
							  
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

//function addViewDetailsEvent(obj) {
//	$(obj).dblclick(function() {
//		var element = $(this);
//		var getId = element.attr("id");
//		$("#jobId").val(getId);
//		$("#jobDetails").submit();
//	});
//}

function fillMD(data) {
	$("#id").val(data["id"]);
	$("#new_partNumber").val(data["partNumber"]);
	$("#new_model").val(data["model"]);
	$("#new_description").val(data["description"]);
	$("#new_unitPrice").val(data["unitPrice"]);

	fillSelectTypes(data["vendorId"], 1, $("select#new_vendorId"), "..\/..\/dictionaries\/diy_customer\/ajax\/diy_customer_process.php");
}

function initializeMD(n, u) {
	var oNew = n;
	var oUpdate = u;
	oNew.dialog("destroy");
	oUpdate.dialog("destroy");
	
	var partNumber = $("#partNumber"),
		model = $("#model"),
		description = $("#description"),
		unitPrice = $("#unitPrice"),
		allFields = $([]).add(partNumber).add(model).add(description).add(unitPrice);

	oNew.dialog({
	    autoOpen: false,
	    open: function () {
	        fillSelectTypes(0, 0, $("select#vendorId"), "..\/..\/dictionaries\/diy_customer\/ajax\/diy_customer_process.php");
	        },
	    height: 400,
	    width: 400,
	    modal: true,
	    buttons: {
	        'Add part': function () {
	            var bValid = true;
	            allFields.removeClass('ui-state-error');
//	            bValid = bValid && checkLength(shortname, "partNumber", 5, 32);
//	            bValid = bValid && checkLength(name, "name", 3, 45);
//
//	            bValid = bValid && checkRegexp(shortname, /^[A-Z]([0-9a-z_ &])+$/i, "shortname may consist of a-z, 0-9, &,  underscores, begin with a letter.");
//	            bValid = bValid && checkRegexp(name, /^[A-Z]([0-9a-z_ &])+$/i, "name may consist of A-Z, 0-9, &, underscores, begin with a letter.");
				

	            // send there data to database
	            if (bValid) {
	                var aFields = new Array();
	                aFields["partNumber"] = $("#partNumber").val();
	                aFields["model"] = $("#model").val();
	                aFields["description"] = $("#description").val();
	                aFields["unitPrice"] = $("#unitPrice").val();
	                aFields["vendorId"] = $("#vendorId :selected").val();
            
					console.log(aFields["vendorId"]);
	                submitForm("insert", aFields);
	                $(this).dialog('close');
	            }
	        },
	        Cancel: function () {
	            $(this).dialog('close');
	        }
	    },
	    close: function () {
	        allFields.val('').removeClass('ui-state-error');
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
				var partNumber = $("#new_partNumber"),
				model = $("#new_model"),
				description = $("#new_escription"),
				unitPrice = $("#new_unitPrice"),
				allFields = $([]).add(partNumber).add(model).add(description).add(unitPrice);
				
				var bValid = true;			
				allFields.removeClass('ui-state-error');
				
//				bValid = bValid && checkLength(shortname, "shortname", 5, 32);
//	            bValid = bValid && checkLength(name, "name", 3, 45);
//
//	            bValid = bValid && checkRegexp(shortname, /^[A-Z]([0-9a-z_ &])+$/i, "shortname may consist of a-z, 0-9, &,  underscores, begin with a letter.");
//	            bValid = bValid && checkRegexp(name, /^[A-Z]([0-9a-z_ &])+$/i, "name may consist of A-Z, 0-9, &, underscores, begin with a letter.");
//				
				if (bValid) {
					var aFields = new Array();
					aFields["id"] = $("#id").val();
					
	                aFields["partNumber"] = $("#new_partNumber").val();
	                aFields["model"] = $("#new_model").val();
	                aFields["description"] = $("#new_description").val();
	                aFields["unitPrice"] = $("#new_unitPrice").val();
	                aFields["vendorId"] = $("#new_vendorId :selected").val();
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
		console.log(aFields["vendorId"]);
	$.ajax({
		type: "POST",
		url: url,
		data: ({
			action: action,
			id: id,
			partNumber: aFields["partNumber"],
			model: aFields["model"],
			description: aFields["description"],
			unitPrice: aFields["unitPrice"],
			vendorId: aFields["vendorId"]
		}),
		success: function(response) { getData(data, url) }
	});
}
