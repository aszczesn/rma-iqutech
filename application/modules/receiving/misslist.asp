<%@  language="javascript" %>
<!-- #include virtual="/library/asp/aspfunctions.inc.asp" -->
<%

    var shipno = Request("shipno");
	var location = Session("location");

    var objConn = connectDB(Application("database"), Application("username"), Application("password"));

    mysqlM = "select * from rmapart where shipid = '" + shipno + "' and status = 'Pre-Alert' order by dpn";

    rsInv = objConn.execute(mysqlM);

    colorIndex = 0;

    displayInv = "<table><tr style='background-color: silver;'><td>Shipid</td><td>PPID</td><td>DPN</td><td>DPS NO</td></tr>"

    if (!(rsInv.eof)) {
        while (!(rsInv.eof)) {

            if (colorIndex % 2 == 0)
                displayInv += "<tr style='background:#33CCCC;text-decoration:none; text-align:left;font-weight:bold;'><td>" + rsInv("shipid") + "</td><td>" + rsInv("ppid") + "</td><td>" + rsInv("dpn") + "</td><td>" + rsInv("dpsno") + "</td></tr>"
            else
                displayInv += "<tr style='text-decoration:none; text-align:left;font-weight:bold;'><td>" + rsInv("shipid") + "</td><td>" + rsInv("ppid") + "</td><td>" + rsInv("dpn") + "</td><td>" + rsInv("dpsno") + "</td></tr>"


            colorIndex++;
            rsInv.move(1);
        }

    }

    objConn.close();

%>
<%= displayInv %>