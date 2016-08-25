//(document).ready(function(){
     // $(".submit").click(function(event){
        function showMilestones(){
        var xhttp;   
        xhttp = new XMLHttpRequest();
        xhttp.click = function(){
        if(xhttp.readyState == 4 && xhttp.status == 200){
           document.getElementById().innerHTML = xhttp.responseText;
            }
        };
        xhttp.open("GET", "ProjID", true);
        xhttp.send();               
        }

        function showExpenditures(){

        } 

        function showReimbursements(){

        }

        function showFunding(){

        }
      //});
    //});