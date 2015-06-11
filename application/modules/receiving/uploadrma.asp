<%@  language="javascript" %>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
   "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
    <title>PIE Screening On-Line Tracking System</title>
    <link rel="stylesheet" href="../css/library.css" type="text/css" />
    <script type="text/javascript" language="javascript">
    <!--

        function changeFieldSize(elem) {
            fieldSize = document.all[elem.id].value.length + 10;
            document.all[elem.id].size = fieldSize;
        }

        function check_rma_csv() {
            var valid = true;
            valid = CheckUpload();

            if (valid != false) {
                checkRmaWebSer();
            }
            else {
                reportSec.innerHTML = "<p><table border=0><tr><td colspan=2><table border=0 width=100%><tr><td valign=bottom width='55'><img src='../images/warn.bmp' heigth='50' width='50' border=0></td><td valign=bottom align=left style='font-size:2.0em;'><b>Warning</b></td></tr></table></td></tr><tr><td valign=center colspan=2>Sorry please choose a correct file type or file.</td></tr></table>"
            }
        }
        
        function checkRmaWebSer() {

            filename = document.checkrma.csvrma.value

            // create ADO-stream Object
            var ado_stream = new ActiveXObject("ADODB.Stream");

            // create XML document with default header and primary node
            var xml_dom = new ActiveXObject("MSXML2.DOMDocument");
            xml_dom.loadXML('<?xml version="1.0" ?> <root/>');
            // specify namespaces datatypes
            xml_dom.documentElement.setAttribute("xmlns:dt", "urn:schemas-microsoft-com:datatypes");

            // create a new node and set binary content
            var l_node1 = xml_dom.createElement("file1");
            l_node1.dataType = "bin.base64";
            // open stream object and read source file
            ado_stream.Type = 1;  // 1=adTypeBinary 
            ado_stream.Open();
            ado_stream.LoadFromFile(filename);
            // store file content into XML node
            l_node1.nodeTypedValue = ado_stream.Read(-1); // -1=adReadAll
            ado_stream.Close();
            xml_dom.documentElement.appendChild(l_node1);

            // we can create more XML nodes for multiple file upload

            // send XML documento to Web server
            var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            xmlhttp.open("POST", "uploadRmaXML.asp", false);
            xmlhttp.send(xml_dom);

            // show server message in message-area
            //reportSec.innerHTML = xmlhttp.ResponseText; 

            //dis.innerHTML = xmlhttp.ResponseText
            stattxt = xmlhttp.ResponseText;

            var xmlDoc = new ActiveXObject("Microsoft.XMLDOM")
            xmlDoc.async = "false";
            xmlDoc.loadXML(stattxt);

            x = xmlDoc.getElementsByTagName("NoErrors")

            alert(stattxt);

            noerror2 = x.item(0).text;
            erMessage = "<table>";


            if (noerror2 != "0") {
                erMessage += "<tr bgcolor='red'><td>Error</td></tr>";

                //We have errors
                e = xmlDoc.getElementsByTagName("error");
                nodelen = e.length;

                for (j = 0; j < nodelen; j++) {
                    erMessage += "<tr><td>" + e.item(j).text + "</td></tr>";
                }

            }


            w = xmlDoc.getElementsByTagName("nowarning");

            nowarning = w.item(0).text;

            if (nowarning != "0") {
                erMessage += "<tr bgcolor='orange'><td>Warning</td></tr>";

                //We have errors
                e = xmlDoc.getElementsByTagName("warning");
                nodelen = e.length;

                for (j = 0; j < nodelen; j++) {
                    erMessage += "<tr><td>" + e.item(j).text + "</td></tr>";
                }

            }

            erMessage += "</table>";

            dup = xmlDoc.getElementsByTagName("duplicate");
            exists = dup.item(0).text;

            if (noerror2 != "0") {
                //There are errors get confirmation from the user
                erMessage += "<form name='upRMA'>"

                erMessage += "<input type=button value=UPLOAD onClick=uploadRMADB('" + exists + "') />"
                erMessage += "</form>"

            }
            else {
                //write the javascript to auto-submit. There are no problems continue.
                if (exists == "no")
                    uploadRMADB('no')
                else
                    uploadRMADB('yes')
            }

            document.checkrma.csvrma.disabled = true;

            reportSec.innerHTML = erMessage;
        }
        
        function uploadRMADB(exists)
        {        
  
        document.checkrma.csvrma.disabled = false;

        filename = document.checkrma.csvrma.value;
    
        // create ADO-stream Object
        var ado_stream = new ActiveXObject("ADODB.Stream");
 
        // create XML document with default header and primary node
        var xml_dom = new ActiveXObject("MSXML2.DOMDocument");
        xml_dom.loadXML('<?xml version="1.0" ?> <root/>');
        // specify namespaces datatypes
        xml_dom.documentElement.setAttribute("xmlns:dt", "urn:schemas-microsoft-com:datatypes");

        // create a new node and set binary content
        var l_node1 = xml_dom.createElement("file1");
        l_node1.dataType = "bin.base64";
        // open stream object and read source file
        ado_stream.Type = 1;  // 1=adTypeBinary 
        ado_stream.Open(); 
        ado_stream.LoadFromFile(filename);
        // store file content into XML node
        l_node1.nodeTypedValue = ado_stream.Read(-1); // -1=adReadAll
        ado_stream.Close();
        xml_dom.documentElement.appendChild(l_node1);

        if(exists == 'yes'){
        var x=window.confirm("RMA Already exists. Do you wish to overwrite entry?");
        if(x){
    
        // send XML documento to Web server
        var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        xmlhttp.open("POST","uploadRmaDBXML.asp",false);
        xmlhttp.send(xml_dom);
        //Delete once confirm
        
        stattxt = xmlhttp.ResponseText;

        //alert(stattxt);

        var xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
        xmlDoc.async="false";
        xmlDoc.loadXML(stattxt);

        e=xmlDoc.getElementsByTagName("NoErrors");
        err = parseInt(e.item(0).text);

        //alert(e)

        if(err != 0) 
        {
        alert("Insertion Into Database Failed");
        }
        else
        {
        reportSec.innerHTML = "<br><b>RMA successfully inserted into the database";
        }
        }
        else
        {
        alert("Operation has been cancelled. Please contact admininstrator for any queries.");
        }
        }
        else
        {
        // send XML documento to Web server
        var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        xmlhttp.open("POST","uploadRmaDBXML.asp",false);
        xmlhttp.send(xml_dom);

        //Delete once confirm
        stattxt = xmlhttp.ResponseText

        var xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
        xmlDoc.async="false";
        xmlDoc.loadXML(stattxt);

      //  alert(stattxt);

        e=xmlDoc.getElementsByTagName("NoErrors");
        err = parseInt(e.item(0).text);
        if(err != 0) 
        {
        alert("Insertion Into Database Failed");
        }
        else
        {
        reportSec.innerHTML = "<br><b>RMA successfully inserted into the database";
        }

        }
        }
          
        function CheckUpload() {
            var filename = document.checkrma.csvrma.value;
            var extension;
            var valid = true
            var Img1 = new Image()
            if (navigator.appName == "Netscape") {
                alert("This upload function cannot be used with Netscape.");
                valid = false;
            }
            else if (filename == '') {
                valid = false;
                alert("Please include a file.");
            }
            else {
                extension = filename.substring(filename.length - 3, filename.length);
                if (extension.toUpperCase() != 'CSV') {
                    valid = false;
                    alert("The file must be a CSV");
                }
            }

            return valid
        }    
        
    -->
    </script>
    <style type="text/css">
<!--



.borTable {
border-top: 1px solid black;
border-left: 1px solid black;
border-right: 1px solid black;
border-bottom: 1px solid black;
background-color:#d7dfe4;
}


#upTable {position:absolute;top:0;}

input.btn{
   color:#050;
   font-family:'trebuchet ms',helvetica,sans-serif;
   font-size:84%;
   font-weight:bold;
   background-color:#fed;
   border:1px solid;
   border-top-color:#696;
   border-left-color:#696;
   border-right-color:#363;
   border-bottom-color:#363;
   filter:progid:DXImageTransform.Microsoft.Gradient
      (GradientType=0,StartColorStr='#ffffffff',EndColorStr='#ffeeddaa');}

-->
</style>
</head>
<body bgcolor="">
    <table border="0" align="center" id="checkTable" width="100%">

        <tr>
            <td>
                <table border="0" class="borTable" width="100%">
                    <tr>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <img src="../images/tick.jpg" width="20" heigth="20" border="0">&nbsp;&nbsp;<span style="font-family: verdana;
                                font-size: 12pt; font-weight: bold; padding: 10px;">Upload RMA CSV File</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <form name="checkrma">
                            <input id="btn_send" name="csvrma" type="file" size="40" onchange="changeFieldSize(this);">
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="button" class="btn" value='Verify RMA' onclick="check_rma_csv()">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div id="reportSec">
    </div>
</body>
</html>
