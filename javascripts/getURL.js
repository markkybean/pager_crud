var http_request = false;
   function makeRequest(url, parameters) {
   
      http_request = false;
      if (window.XMLHttpRequest) { // Mozilla, Safari,...
         http_request = new XMLHttpRequest();
         if (http_request.overrideMimeType) {
            http_request.overrideMimeType('text/xml');
         }
      } else if (window.ActiveXObject) { // IE
         try {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
         } catch (e) {
            try {
               http_request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
         }
      }
      if (!http_request) {
         alert('Cannot create XMLHTTP instance');
         return false;
      }
      
      //alert("hello2:"+url + parameters);
      http_request.onreadystatechange = alertContents;
      http_request.open('GET', url + parameters, true);
      //alert("hello3:"+url + parameters);
      
      http_request.send(null);
   }

   function alertContents() {
      if (http_request.readyState == 4) {
         if (http_request.status == 200) {
           //alert("hello5:"+http_request.responseText);
           result = http_request.responseText;
           //alert("result1:"+http_request.responseText);
           //document.getElementById('PeriodCovered').innerHTML = result;
           FillSelect(result)
         } else {
            alert('There was a problem with the request.');
         }
      }
   }

   //function get(obj) {
   //   var getstr = "?";
   //   getstr += "PayrollGroup=" + obj.PayrollGroup.value + "&CreditMonth=" + obj.CreditMonth.value + "&CreditYear=" + obj.CreditYear.value;
   //   makeRequest('get.php', getstr);
   //}
