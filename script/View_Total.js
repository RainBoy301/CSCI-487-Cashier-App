/////////When owner hit view total from other////////
function view_other(){
  if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
  } else {
      // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          document.getElementById("display_total").innerHTML = this.responseText;
      }
  };
  xmlhttp.open("GET","View_Total.php" ,true);
  xmlhttp.send();
}

//////////When owner select user from droplist//////////////
function view_individual(){
  var userID = document.getElementById("ID").value;
  var query = "?id="+ userID;

  if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
  } else {
      // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          document.getElementById("order-summary").innerHTML = this.responseText;
      }
  };
  xmlhttp.open("GET","View_Total.php"+query,true);
  xmlhttp.send();
}
