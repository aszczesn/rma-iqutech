var url = "ajax/diy_customer_process.php";

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
                              "<td width=\"10%;\">" + this["shortname"] + "</td>" +
                              "<td>" + this["name"] +"</td>" +
                              "<td width=\"80px;\">" + this["email"] + "</td>" +
                              "<td>" + this["city"] + "</td>" +
							  "<td>" + this["address"] + "</td>" +
							  "<td>" + this["zip"] + "</td>" +
							  "<td>" + this["country"] + "</td>" +
                              "<td>" + this["categoryName"] + "</td>";
							  
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
	$("#newShortname").val(data["shortname"]);
	$("#newName").val(data["name"]);
	$("#newAddress").val(data["address"]);
	$("#newEmail").val(data["email"]);
	$("#newCity").val(data["city"]);
	$("#newPhoneno").val(data["phoneno"]);
	$("#newZip").val(data["zip"]);
	$("#newCountry").val(data["country"]);

	fillSelectTypes(data["categoryid"], 1, $("select#newCategoryid"), "..\/..\/dictionaries\/diy_customer_type\/ajax\/diy_customer_type_process.php");
}

function initializeMD(n, u) {
	var oNew = n;
	var oUpdate = u;
	oNew.dialog("destroy");
	oUpdate.dialog("destroy");
	
	var shortname = $("#shortname"),
		name = $("#name"),
		address = $("#address"),
		email = $("#email"),
		city = $("#city"),
		phoneno = $("#phoneno"),
		zip = $("#zip"),
		country = $("#country"),
		allFields = $([]).add(shortname).add(name).add(address).add(email).add(city).add(phoneno).add(zip).add(country);

	oNew.dialog({
	    autoOpen: false,
	    open: function () {
	        fillSelectTypes(0, 0, $("select#categoryid"), "..\/..\/dictionaries\/diy_customer_type\/ajax\/diy_customer_type_process.php");
	        },
	    height: 600,
	    width: 400,
	    modal: true,
	    buttons: {
	        'Add user': function () {
	            var bValid = true;
	            allFields.removeClass('ui-state-error');
	            bValid = bValid && checkLength(shortname, "shortname", 5, 32);
	            bValid = bValid && checkLength(name, "name", 3, 45);

	            bValid = bValid && checkRegexp(shortname, /^[A-Z]([0-9a-z_ &])+$/i, "shortname may consist of a-z, 0-9, &,  underscores, begin with a letter.");
	            bValid = bValid && checkRegexp(name, /^[A-Z]([0-9a-z_ &])+$/i, "name may consist of A-Z, 0-9, &, underscores, begin with a letter.");
				

	            // send there data to database
	            if (bValid) {
	                var aFields = new Array();
	                aFields["categoryid"] = $("#categoryid :selected").val();
	                aFields["shortname"] = $("#shortname").val();
	                aFields["name"] = $("#name").val();
	                aFields["address"] = $("#address").val();
	                aFields["city"] = $("#city").val();
					aFields["email"] = $("#email").val();
					aFields["phoneno"] = $("#phoneno").val();	
					aFields["zip"] = $("#zip").val();
					aFields["country"] = $("#country").val();
            
					console.log(aFields);
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
		height: 600,
		width: 400,
		modal: true,
		buttons: {
			'Save changes': function() {
				var shortname = $("#newShortname"),
					name = $("#newName"),
					address = $("#newAddress"),
					email = $("#newEmail"),
					city = $("#newCity"),
					phoneno = $("#newPhoneno"),
					zip = $("#newZip"),
					country = $("#newCountry"),
					allFields = $([]).add(shortname).add(name).add(address).add(email).add(city).add(phoneno).add(zip).add(country);
				
				var bValid = true;			
				allFields.removeClass('ui-state-error');
				
				bValid = bValid && checkLength(shortname, "shortname", 5, 32);
	            bValid = bValid && checkLength(name, "name", 3, 45);

	            bValid = bValid && checkRegexp(shortname, /^[A-Z]([0-9a-z_ &])+$/i, "shortname may consist of a-z, 0-9, &,  underscores, begin with a letter.");
	            bValid = bValid && checkRegexp(name, /^[A-Z]([0-9a-z_ &])+$/i, "name may consist of A-Z, 0-9, &, underscores, begin with a letter.");
				
				if (bValid) {
					var aFields = new Array();
					aFields["id"] = $("#id").val();
					
	                aFields["shortname"] = $("#newShortname").val();
	                aFields["name"] = $("#newName").val();
	                aFields["address"] = $("#newAddress").val();
	                aFields["city"] = $("#newCity").val();
					aFields["email"] = $("#newEmail").val();
					aFields["phoneno"] = $("#newPhoneno").val();	
					aFields["zip"] = $("#newZip").val();
					aFields["country"] = $("#newCountry").val();

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
			categoryid: aFields["categoryid"],
			shortname: aFields["shortname"],
			name: aFields["name"],
			email: aFields["email"],
			address: aFields["address"],
			city: aFields["city"],
			phoneno: aFields["phoneno"],
			zip: aFields["zip"],
			country: aFields["country"]
		}),
		success: function(response) { getData(data, url) }
	});
}
