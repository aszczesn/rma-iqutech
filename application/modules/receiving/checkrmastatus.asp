<%@ LANGUAGE=javascript%>
<!-- #include virtual="/library/asp/aspfunctions.inc.asp" -->
<html>
<head>
<title>
</title>
</head>
<body>
<%

    Server.ScriptTimeout = 100000;
    var rowsPerPage = 20;
    var pageNumber = 1;
	var location = 	1;
    
    if(Request.QueryString("page").Count != 0)
    {
      pageNumber = parseInt(Request("page"));
    }
    
    var offset = (pageNumber - 1)*rowsPerPage;
    

    var outStr = "<table><tr style='background:silver;text-decoration:none; text-align:left;font-weight:bold;'><td>Shipment #</td><td>RMA #</td><td>Total</td><td>Date</td></tr>";
    var objConn = connectDB(Application("database"), Application("username"), Application("password"));
    
    var sQuery01 = "SELECT count(distinct(us.id)) AS numrows FROM rmapart rp " + 
	               "LEFT OUTER JOIN ushipment us ON " +
		           "rp.shipid =  us.id " +
		           "WHERE rp.status = 'Pre-Alert' and us.vendorid = 6 and us.location = "+ location +"";
		           
    var rQuery01 = objConn.execute(sQuery01);
    
    var numrows = rQuery01("numrows");
    
    var maxPage = parseInt(numrows/rowsPerPage)+1; 
    
    var self = "checkrmastatus.asp";
    var nav = "";
    
    var mysqlRma = "SELECT us.shipno , us.rmano, count(rp.shipid) AS unitCount , Date_Format(us.date,'%d-%m-%Y') AS newdate , rp.shipid " +
            "FROM rmapart rp " +
	        " LEFT OUTER JOIN ushipment us ON " +
		    " rp.shipid =  us.id " +
		    "WHERE rp.status = 'Pre-Alert' and us.vendorid = 6 and us.location = "+ location +" GROUP BY us.id ORDER BY us.date DESC LIMIT " + offset + ", " + rowsPerPage + ";";

    var rsRma = objConn.execute(mysqlRma);

    var colorIndex = 0;
    if (!(rsRma.eof)) {
        while (!(rsRma.eof)) {
            if (colorIndex % 2 == 0)
                outStr += "<tr style='background:#33CCCC;text-decoration:none; text-align:left;font-weight:bold;'><td><a href='misslist.asp?shipno=" + rsRma("shipid") + "'>" + rsRma("shipno") + "</a></td><td>" + rsRma("rmano") + "</td><td align='right'>" + rsRma("unitCount") + "</td><td>" + rsRma("newdate") + "</td></tr>";
            else
                outStr += "<tr style='text-decoration:none; text-align:left;font-weight:bold;'><td><a href='misslist.asp?shipno=" + rsRma("shipid") + "'>" + rsRma("shipno") + "</a></td><td>" + rsRma("rmano") + "</td><td align='right'>" + rsRma("unitCount") + "</td><td>" + rsRma("newdate") + "</td></tr>";
            colorIndex++;
            rsRma.move(1);
        }
    }
    
    for (page = 1; page <= maxPage; page++)
    { 
        if(page == pageNumber)
        {
            nav =  page;
        }
       else
       {
            nav = "<a href=" + self + "?page=" + page + ">" + page + "</a>";
       }
    }
    
    if(pageNumber > 1)
    {
        page = pageNumber - 1;
        prev = "<a href="+ self +"?page="+page+ ">[Prev]</a>";
        first = "<a href="+ self +"?page=1>[First]</a>";
    } else {
        prev = "&nbsp;";
        first = "&nbsp;";
    }
    
    if(pageNumber < maxPage){
        page = pageNumber + 1;
        next = "<a href="+ self +"?page="+page+ ">[Next]</a>";
        last = "<a href="+ self +"?page="+maxPage+ ">[Last]</a>";
    } else {
        next = "&nbsp;";
        last = "&nbsp;";
    }
    
    
    outStr += "<tr><td>Total</td><td>" + numrows + "</td><td></td></tr><tr><td colspan=3><a href=missall.asp>Show All Records</td></tr></table>";
    outStr += first + prev + " Showing page " + pageNumber + " of " + maxPage + " pages " + next + last;
    objConn.close();
%>

<%= outStr %>
</body>
</html>