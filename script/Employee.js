


/////////when user hit increae quantity//////////////
function incrementValue(serviceID)
{
        /////Create query//////
    id = "quantity"+serviceID;
    price = document.getElementById('price'+serviceID).innerText;
    ticketID = document.getElementById('ticketID').innerText;
    var value = parseInt(document.getElementById(id).value, 10);
    value = isNaN(value) ? 0 : value;
    value++;
    document.getElementById(id).value = value;
    var query = "?serviceID="+ serviceID +"&quantity="+ value +"&price="+ price + "&ticketID="+ ticketID +"&action=increase";
      ///////////////////////

    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("total").innerHTML = this.responseText;
        }
    };

    xmlhttp.open("GET","functionality.php" + query,true);
    xmlhttp.send();
    $("#dec"+serviceID).prop("disabled",false);///unlock decrease quantity button
}

/////////when user hit decrease quantity//////////////
function decrementValue(serviceID)
{
    ///////////////create query/////////////
    id = "quantity"+serviceID;
    price = document.getElementById('price'+serviceID).innerText;
    ticketID = document.getElementById('ticketID').innerText;
    var value = parseInt(document.getElementById(id).value, 10);
    value = isNaN(value) ? 0 : value;
    value--;
    document.getElementById(id).value = value;
    ////////////////////////////////////////////

    var query = "?serviceID="+ serviceID +"&quantity="+ value+"&price="+ price +"&ticketID="+ ticketID +"&action=decrease";
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("total").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET","functionality.php" + query,true);
    xmlhttp.send();

    ///Disable decrease button when quantity reach 1
    if(value<=1){
      $("#dec"+serviceID).prop("disabled",true);
    }
}

/////////////Delete service from ticket////////////////
function deleteService(serviceID){
    ticketID = document.getElementById('ticketID').innerText;
    location.href = "functionality.php?serviceID="+serviceID+"&ticketID="+ ticketID + "&action=delete";
}

  var modal = document.getElementById("myModal");

// Get the button that opens the modal
  var btn = document.getElementById("btn-add");

// Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
  btn.onclick = function() {
    modal.style.display = "block";
  }
// When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }

//////////////Adding service on ticket/////////////////////
function addService(serviceID){
    price = document.getElementById('price'+serviceID).innerText;
    ticketID = document.getElementById('ticketID').innerText;
    var query = "?serviceID=" + serviceID + "&price="+ price + "&ticketID="+ ticketID + "&action=add";

  if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
  } else {
      // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp.open("GET","functionality.php" + query,true);
  xmlhttp.send();
  alert("item added");
}

/////////Check out ticket//////////
function checkout(){
  total = document.getElementById("inner_total").innerHTML;
  var query = "?total=" + total;
  window.location.replace("https://turing.cs.olemiss.edu/~bqhoang/csci487/functionality.php"+query);
  }

////////Cancel ticket///////////////
function cancel(){
  ticketID = document.getElementById('ticketID').innerText;
  var query = "?cancel= 1" + "&ticketID="+ ticketID ;
  window.location.replace("https://turing.cs.olemiss.edu/~bqhoang/csci487/functionality.php"+query);
}
