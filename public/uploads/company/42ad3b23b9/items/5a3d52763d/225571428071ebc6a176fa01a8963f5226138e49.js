
console.log('script loaded');


 var img = new Image();
img.onload = function() {
document.body.appendChild(img);  // not needed! 
}

img.src = 'https://refilliate.com/pixel.gif?price=1000&ref_id=abcd1234';

// if we request the image as a javascript object - then Shopify can fire the request.


(new Image()).src = "https://refilliate.com/pixel.gif?price=1000&ref_id=abcd1234";

/*
We also should implement a http / https check.
 */


 (new Image()).src =  (location.protocol == “https:” ? “https:” : “http:”) + '//refilliate.com/pixel.gif?price=1000&ref_id=abcd1234';


// Script that grabs order number from checkout page

document.getElementsByClassName("os-order-number")[0].innerHTML.replace(/[^0-9.]/g, "");

// reading cookie
  var cookiePairs = document.cookie.split(';');
  for(var i = 0; i < cookiePairs.length; i ++){
    var cookie = cookiePairs[i];
    var keyValPair = cookie.split('=');
    if(keyValPair[0].indexOf('refill_hash') !== -1) {
      alert('found refill hash cookie!')
    } else {
      alert('no hash :(')
    }
  }


// TRACKING SCRIPT V_1



  // making sure we are on checkout page

  if (window.location.pathname.indexOf('/checkouts/') !== -1) {

    // we are in checkout!
  
      // Hunting for a hash cookie 

        var cookieJar = document.cookie.split(';');

        // For each cookie we have
        for(var i = 0; i < cookieJar.length; i ++){

          var cookie = cookieJar[i];
          var innerCookie = cookie.split('=');

          // If it's a hash cookie - awesome, do more shit.
          if(innerCookie[0] === 'refill_hash') {

            // get the value
            var cookieVal = innerCookie[1];

            // get order number by scraping HTML
            var orderNum = document.getElementsByClassName("os-order-number")[0].innerHTML.replace(/[^0-9.]/g, "");

            // Send req. to refilliate to record sale
            (new Image()).src = "https://refilliate.com/bag.gif?hash=" + cookieVal + "&order_number=" + orderNum;

          } 
        } 
        
        
    
      // SEND
      
        // request to refilliate 
      
  } else {

    // we are on a regular page
    
      // Check if query string exists
      
        var url = window.location.href;
        var queryString = url.split('?')[1];
        

        if (queryString) {

          var pairs = queryString.split('&');

          // go through each key-value pair in query string
          
            for(i = 0; i < pairs.length ; i++) {
              var item = pairs[i].split('=');
              if (item[0] === 'refill_hash'){

                alert('storing cookie..')

                // store it as a cookie
                  // set expiry
                    var date = new Date();
                    date.setTime(date.getTime()+(10*365*24*60*60*1000));
                  expires = "; expires=" + date.toGMTString();
                    // store the cookie
                  document.cookie = item[0]+"="+item[1]+expires+"; path=/";
                
              }      
              
            }

        }
        
  }



  
    
   


  
    





