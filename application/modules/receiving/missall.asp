<%@  language="javascript" %>
<!-- #include virtual="/library/asp/aspfunctions.inc.asp" -->
<%

    var shipno = Request("shipno")

    var objConn = connectDB(Application("database"), Application("username"), Application("password"));

    mysqlM = "select * from rmapart where status = 'Pre-Alert' order by dpn, shipid";

    rsInv = objConn.execute(mysqlM);

    colorIndex = 0;

    displayInv = "<table><tr><td>Shipid</td><td>PPID</td><td>DPN</td></tr>"

    if (!(rsInv.eof)) {
        while (!(rsInv.eof)) {

            if (colorIndex % 2 == 0)
                displayInv += "<tr style='background:#33CCCC;text-decoration:none; text-align:left;font-weight:bold;'><td>" + rsInv("shipid") + "</td><td>" + rsInv("ppid") + "</td><td>" + rsInv("dpn") + "</td></tr>"
            else
                displayInv += "<tr style='text-decoration:none; text-align:left;font-weight:bold;'><td>" + rsInv("shipid") + "</td><td>" + rsInv("ppid") + "</td><td>" + rsInv("dpn") + "</td></tr>"


            colorIndex++;
            rsInv.move(1);
        }

    }

    objConn.close();

%>
<%= displayInv %>