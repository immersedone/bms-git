        function showMilestones(){
            var xhttp;   
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
            if(xhttp.readyState == 4 && xhttp.status == 200){
                document.getElementById(ProjID).innerHTML = xhttp.responseText;
            }
        };
        xhttp.open("GET", "ProjID", true);
        xhttp.send();               
        }

        function showExpenditures(){
            var xhttp;
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
            if(xhttp.readyState == 4 && xhttp.status == 200){
                document.getElementById().innerHTML = xhttp.responseText;
            }
        };
        xhttp.open("GET", "ExpID", true);
        xhttp.send();        
        } 

        function showReimbursements(){
            var xhttp;
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
            if(xhttp.readyState == 4 && xhttp.status == 200){
                document.getElementById().innerHTML = xhttp.responseText;
            }
        };
        xhttp.open("GET", "ReimID", true);
        xhttp.send();        


        }

        function showFunding(){
            var xhttp;
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
            if(xhttp.readyState == 4 && xhttp.status == 200){
                document.getElementById().innerHTML = xhttp.responseText;
            }
        };
        xhttp.open("GET", "FundID", true);
        xhttp.send();        
        }