var url = "ajax/diy_customer_type_process.php";

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

    $("#print").button({
        icons: {
            primary: 'ui-icon-print'
        },
        label: "Print"
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
                              "<td>" + this["name"]  + "</td>";
							  
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
	$.each(data, function(){
		$("#id").val(data["id"]);
		$("#newName").val(data["name"]);
	});
}

function initializeMD(n, u) {
	var oNew = n;
	var oUpdate = u;
	oNew.dialog("destroy");
	oUpdate.dialog("destroy");
	
	var name = $("#name"),
				allFields = $([]).add(name);

	oNew.dialog({
	    autoOpen: false,
	    open: function () {
	        name.val('');
	    },
	    height: 300,
	    width: 400,
	    modal: true,
	    buttons: {
	        'Add role': function () {
	            var bValid = true;
				allFields = $([]).add(name);
	            allFields.removeClass('ui-state-error');
	            bValid = bValid && checkLength(name, "name", 5, 20);
	            bValid = bValid && checkRegexp(name, /^[a-z]([0-9a-z_ &])+$/i, "Category name may consist of a-z, 0-9, &,  underscores, begin with a letter.");

	            // send there data to database
	            if (bValid) {
	                var aFields = new Array();
	                aFields["name"] = $("#name").val();
	                submitForm("insert", aFields);
					console.log("insert " +aFields);
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
		height: 300,
		width: 400,
		modal: true,
		buttons: {
			'Save changes': function() {
				var name = $("#newName"),                            
							allFields = $([]).add(name);
				allFields.removeClass('ui-state-error');
				var bValid = true;
				bValid = bValid && checkLength(name, "newName", 5, 20);
				bValid = bValid && checkRegexp(name, /^[a-z]([0-9a-z_ ])+$/i, "Job name may consist of a-z, 0-9, underscores, begin with a letter.");
				
				if (bValid) {
					var aFields = new Array();
					aFields["id"] = $("#id").val();
					aFields["name"] = $("#newName").val();
					submitForm("update", aFields);
					console.log("update " +aFields);
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
	console.log("submit " + aFields);
	if (aFields["id"])
		id = aFields["id"];
	$.ajax({
		type: "POST",
		url: url,
		data: ({
			action: action,
			id: id,
			name: aFields["name"]
		}),
		success: function(response) { getData(data, url) }
	});
}
