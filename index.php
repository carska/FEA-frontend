<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <title>FEA</title>
    </head>
    <body onload="start()">
        <div class="container greenhead">
            <h1>Foreign Exchange Analyzer</h1>
            <h3>Compare rates to 30 days prior input date.</h3>
        </div>
        <div class="container">
            <div class="searchfield">
                <input id="datepicker" type="text">
                <img src="http://www.free-icons-download.net/images/search-button-icon-83032.png" onclick="getCurrencies()">
            </div>
            <table id="table"></table>
        </div>
        <script>
            //jQuery datepicker, compatible with all browsers.
            $(function () {
                $("#datepicker").datepicker({
                    firstDay: 1,
                    maxDate: new Date(),
                    dateFormat: 'yy-mm-dd'
                }).val();
            });
            //get current rates when page loads.
            function start() {
                //Get current date.
                var date = new Date();
                var year = date.getFullYear();
                var month = (date.getMonth() + 1);
                var day = date.getDate();
                if (month < 10) {
                    month = "0" + month.toString();
                }
                if (day < 10) {
                    day = "0" + day.toString();
                }
                //set textfield/datepicker with current date
                document.getElementById("datepicker").value = year + "-" + month + "-" + day;
                //get currencies for current date from fixer.io
                getCurrencies();
            }

            //Get selected date from datepicker,
            //use ajax to get data from fixer.io.
            function getCurrencies() {
                loadDoc("http://api.fixer.io/" + $("#datepicker").val(), setCurrencies);
            }
            //Handle return from ajax,
            //Create table and fill with data
            function setCurrencies(xhttp) {
                var json = JSON.parse(xhttp.responseText);
                var rates = json.rates;

                document.getElementById("table").outerHTML = "<table class=\"table\"id=\"table\"></table>";
                var table = document.getElementById("table");
                var tableHead = table.insertRow(0);
                tableHead.insertCell(0).outerHTML = "<th class=\"tableheader\" onclick=\"sortTable(0)\">Currency</th>";
                tableHead.insertCell(1).outerHTML = "<th class=\"tableheader\" onclick=\"sortTable(1)\">Rate</th>";

                for (var key in rates) {
                    var row = table.insertRow(-1);
                    row.insertCell(0).innerHTML = key;
                    row.insertCell(1).innerHTML = rates[key];
                }
            }

            //Copied AJAX function, get json data from fixer.io
            //link: https://www.w3schools.com/js/js_ajax_intro.asp
            function loadDoc(url, cFunction) {
                var xhttp;
                xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        cFunction(this);
                    }
                };
                xhttp.open("GET", url, true);
                xhttp.send();
            }
            //"How TO - Sort a Table" Function from W3schools.com, access date 2018-02-18,
            // link: https://www.w3schools.com/howto/howto_js_sort_table.asp
            function sortTable(n) {
                var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
                table = document.getElementById("table");
                switching = true;
                //Set the sorting direction to ascending:
                dir = "asc";
                /*Make a loop that will continue until
                 no switching has been done:*/
                while (switching) {
                    //start by saying: no switching is done:
                    switching = false;
                    rows = table.getElementsByTagName("TR");
                    /*Loop through all table rows (except the
                     first, which contains table headers):*/
                    for (i = 1; i < (rows.length - 1); i++) {
                        //start by saying there should be no switching:
                        shouldSwitch = false;
                        /*Get the two elements you want to compare,
                         one from current row and one from the next:*/
                        x = rows[i].getElementsByTagName("TD")[n];
                        y = rows[i + 1].getElementsByTagName("TD")[n];
                        /*check if the two rows should switch place,
                         based on the direction, asc or desc:*/
                        if (dir == "asc") {
                            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                //if so, mark as a switch and break the loop:
                                shouldSwitch = true;
                                break;
                            }
                        } else if (dir == "desc") {
                            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                                //if so, mark as a switch and break the loop:
                                shouldSwitch = true;
                                break;
                            }
                        }
                    }
                    if (shouldSwitch) {
                        /*If a switch has been marked, make the switch
                         and mark that a switch has been done:*/
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                        //Each time a switch is done, increase this count by 1:
                        switchcount++;
                    } else {
                        /*If no switching has been done AND the direction is "asc",
                         set the direction to "desc" and run the while loop again.*/
                        if (switchcount == 0 && dir == "asc") {
                            dir = "desc";
                            switching = true;
                        }
                    }
                }
            }
        </script>
    </body>
</html>