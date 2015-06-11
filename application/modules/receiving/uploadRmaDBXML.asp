<%@  language="javascript" %>
<!-- #include virtual="/library/asp/aspfunctions.inc.asp" -->
<%
    Response.Expires = 0

    // define variables and COM objects
    var ado_stream
    var xml_dom
    var xml_file1
    var file_location
    var xml_filename
    var serverfolder
    var resXML = "<parterr>"

    //Array to store the parts
    partArr = new Array();

    fsObj = new ActiveXObject("Scripting.FileSystemObject")

    var todaysDate = new Date();
    var day = todaysDate.getDate();
    var month = todaysDate.getMonth() + 1;
    var year = todaysDate.getYear();

    var hour = todaysDate.getHours();
    var minutes = todaysDate.getMinutes();
    var seconds = todaysDate.getSeconds();

    // var dateVal = new Date();   // year + "-" + month + "-" + day;


    // create Stream Object
    ado_stream = new ActiveXObject("ADODB.Stream")
    // create XMLDOM object and load it from request ASP object
    xml_dom = new ActiveXObject("MSXML2.DOMDocument")
    xml_dom.load(Request)
    // retrieve XML node with binary content
    xml_file1 = xml_dom.selectSingleNode("root/file1")


    //open stream object and store XML node content into it   
    ado_stream.Type = 1  // 1=adTypeBinary 
    ado_stream.open
    ado_stream.Write(xml_file1.nodeTypedValue)
    // save uploaded file

    serverfolder = Server.MapPath("/roermond/rma/tmpup.csv");

    ado_stream.SaveToFile(serverfolder, 2)  // 2=adSaveCreateOverWrite

    fileexists = fsObj.FileExists(serverfolder);


    var objConn = connectDB(Application("database"), Application("username"), Application("password"));

    var curPTR = "";
    var prevPTR = "";
    var index = 0;
    error = 0;
    warning = 0;

    if (fileexists) {
        f = fsObj.OpenTextFile(serverfolder, 1)

        //Ship to ID
        stringLine = f.ReadLine()
        string = new String(stringLine)
        stringTmp = string.split(",")

        shiptoid = stringTmp[2];

        //Ship to Name
        stringLine = f.ReadLine()
        string = new String(stringLine)
        stringTmp = string.split(",")

        shiptoname = stringTmp[2];

        f.SkipLine()

        //RMA No
        stringLine = f.ReadLine()
        string = new String(stringLine)
        stringTmp = string.split(",")

        RMANo = stringTmp[2];

        //DELL PO No
        stringLine = f.ReadLine()
        string = new String(stringLine)
        stringTmp = string.split(",")

        DellPoNo = stringTmp[2];

        stringLine = f.ReadLine()
        string = new String(stringLine)
        stringTmp = string.split(",")

        upsshipmentno = stringTmp[2];

        //f.SkipLine()

        f.SkipLine()
        f.SkipLine()

        while (!(f.AtEndOfStream)) {

            stringLine = f.ReadLine()

            string = new String(stringLine)

            stringTmp = string.split(",")

            partObj = new Object();
            partObj.part = stringTmp[1];
            partObj.dpsno = stringTmp[2];
	    partObj.ppid = stringTmp[3];
            partObj.qty = stringTmp[4];
            partObj.price = stringTmp[5];

            partArr[index] = partObj;

            index++;

        }

    }
    else {
        resXML = "<error>There has been an error in uploading the file. Please Try again</error>";
        error++;
    }

    ado_stream.close
    f.close()
    fsObj.close

    if (error == 0) {
        //Check to see if RMA has already been uploaded
        sqlVer = "select count(shipno) as shipCount, id as shipUNQ from ushipment where shipno = '" + upsshipmentno + "' group by shipno, id";
        rsRMA = objConn.execute(sqlVer)

        if (!(rsRMA.eof)) {

            shipInt = parseInt(rsRMA("shipCount"))

            if (shipInt != 0) {
                exists = "yes"
                shipUnqID = parseInt(rsRMA("shipUNQ"))
            }
            else {
                exists = "no"
            }
        }
        else {
            exists = "no"
        }


        if (exists == "yes") {
            mysqlDelShip = "delete from ushipment where id = " + shipUnqID;
            objConn.execute(mysqlDelShip)
            mysqlDelRMA = "delete from rma where shipid = " + shipUnqID;
            objConn.execute(mysqlDelRMA)
            mysqlDelShip = "delete from rmapart where shipid = " + shipUnqID;
            objConn.execute(mysqlDelShip)
        }

        // create rma Date 111110
        var rmadate = "20"+RMANo.substring(8, 10)+"-"+RMANo.substring(6, 8) + "-" +RMANo.substring(4, 6);
        
        //Insert Records Into the Database
        mysqlInsShip = "INSERT INTO ushipment (location,vendorid,shipno,carrier,shiptoid,shiptoname,dellpo,rmano,date) VALUES(1,6,'" + upsshipmentno + "','N/A','" + shiptoid + "','" + shiptoname + "','" + DellPoNo + "','" + RMANo + "', '" + rmadate + "')";
        objConn.execute(mysqlInsShip)

        sqlVer = "select id from ushipment where shipno = '" + upsshipmentno + "'";
        rsRMA = objConn.execute(sqlVer)

        var shipid = parseInt(rsRMA("id"));

        mysqlInsRMA = "insert into rma values('" + shipid + "','" + RMANo + "')";
        objConn.execute(mysqlInsRMA);


        for (i = 0; i < partArr.length; i++) {

            var pObj = new Object();
            pObj = partArr[i];

            var qty = parseInt(pObj.qty);
            var price = parseFloat(pObj.price);		

            mysqlInsRMAPart = "insert into rmapart (shipid, dpn, ppid, qty, price, status, dpsno, id_board) values('" + shipid + "','" + pObj.part + "','" + pObj.ppid + "','" + qty + "','" + price + "','Pre-Alert', '"+pObj.dpsno+"', 0)";

            objConn.execute(mysqlInsRMAPart)

        }
    }

    objConn.close();

    resXML += "<NoErrors>" + error + "</NoErrors></parterr>";

    Response.Write(resXML)

%>
