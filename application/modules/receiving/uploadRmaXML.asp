<%@  language="javascript" %>
<!-- #include virtual="/library/asp/aspfunctions.inc.asp" -->
<%
    Response.Expires = 0;

    // define variables and COM objects
    var ado_stream;
    var xml_dom;
    var xml_file1;
    var file_location;
    var xml_filename;
    var serverfolder;
    var resXML = "<parterr>";

    //Array to store the parts
    partArr = new Array();

    fsObj = new ActiveXObject("Scripting.FileSystemObject");


    // create Stream Object
    ado_stream = new ActiveXObject("ADODB.Stream");
    // create XMLDOM object and load it from request ASP object
    xml_dom = new ActiveXObject("MSXML2.DOMDocument");
    xml_dom.load(Request);
    // retrieve XML node with binary content
    xml_file1 = xml_dom.selectSingleNode("root/file1");


    //open stream object and store XML node content into it
    ado_stream.Type = 1;  // 1=adTypeBinary
    ado_stream.open;
    ado_stream.Write(xml_file1.nodeTypedValue);
    // save uploaded file

    serverfolder = Server.MapPath("/roermond/rma/tmp.csv");

    ado_stream.SaveToFile(serverfolder, 2);   // 2=adSaveCreateOverWrite

    fileexists = fsObj.FileExists(serverfolder);


    var objConn = connectDB(Application("database"), Application("username"), Application("password"));

    var curPTR = "";
    var prevPTR = "";
    var index = 0;
    error = 0;
    warning = 0;

    if (fileexists) {
        f = fsObj.OpenTextFile(serverfolder, 1);

        f.SkipLine();
        f.SkipLine();
        f.SkipLine();
        f.SkipLine();
        f.SkipLine();

        stringLine = f.ReadLine();
        string = new String(stringLine);
        stringTmp = string.split(",");
        upsshipmentno = stringTmp[2];

        //f.SkipLine()



        f.SkipLine();
        f.SkipLine();

        while (!(f.AtEndOfStream)) {
            //resXML += f.ReadLine() + "<br>";

            stringLine = f.ReadLine()

            string = new String(stringLine)

            stringTmp = string.split(",")

            prevPTR = curPTR;
            curPTR = stringTmp[1];

            if (curPTR != prevPTR) {
                partArr[index] = curPTR;
                index++;
            }
        }
    }
    else {
        resXML = "<error>File has not been uploaded. Check file for corruption and try again.</error>";
        error++;
    }

    ado_stream.close;
    f.close();
    fsObj.close;


    for (i = 0; i < partArr.length; i++) {
        sqlVer = "select count(partno) as partCount, description as de from partnumbers where partno = '" + partArr[i] + "' and company = 'Foxconn' group by partno, description";

        rsPart = objConn.execute(sqlVer);

        if (!(rsPart.eof)) {
            if (rsPart("partCount") == 0) {
                resXML += "<error>The RMA contains part number: " + partArr[i] + " which is not in the Foxconn master list.</error>";
                error++;
            }
            else {

                partdesc = new String(rsPart("de"));

                if (partdesc.substring(0, 7).toUpperCase() == "NON-RFE") {
                    resXML += "<warning>Non-RFE part detected: " + partArr[i] + "</warning>";
                    warning++;
                }
            }
        }
        else {
            resXML += "<error>The RMA contains part number: " + partArr[i] + " which is not in the Foxconn master list</error>";
            error++;
        }
    }

    //-----------------------------------------------------------------------------------------------------------------------
    //Check to see if RMA has already been uploaded
    sqlVer = "select count(shipno) as shipCount , ushipment.id from ushipment where shipno = '" + upsshipmentno + "' group by shipno, id";
    rsRMA = objConn.execute(sqlVer)

    if (!(rsRMA.eof)) {

        shipInt = parseInt(rsRMA("shipCount"))

        if (shipInt != 0) {
            resXML += "<duplicate>yes</duplicate>"
            shipid = rsRMA("id")
            sqlPro = "select count(rmapart.dpn) as rmaCount from rmapart where shipid = " + shipid + " and status = 'shipped' group by dpn";
            rsPro = objConn.execute(sqlPro)
            if (!(rsPro.eof)) {
                rmaInt = parseInt(rsPro("rmaCount"))
                if (rmaInt != 0) {
                    resXML += "<processed>yes</processed>"
                    resXML += "<error>The Pre-Alert has begun to be processed. Uploading a new RMA with interfere witht the integrity of the data</error>";
                }
                else {
                    resXML += "<processed>no</processed>"
                }
            }
            else {
                resXML += "<processed>no</processed>"
            }
        }
        else {
            resXML += "<duplicate>no</duplicate>"
            resXML += "<processed>no</processed>"
        }
    }
    else {
        resXML += "<duplicate>no</duplicate>"
        resXML += "<processed>no</processed>"
    }

    objConn.close();



    resXML += "<NoErrors>" + error + "</NoErrors>";
    resXML += "<nowarning>" + warning + "</nowarning></parterr>";


    Response.Write(resXML)

%>
