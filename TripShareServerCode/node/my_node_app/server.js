// Node Get ICE STUN and TURN list
let o = {
      format: "urls"
};

let bodyString = JSON.stringify(o);
let https = require("https");
let options = {
      host: "global.xirsys.net",
      path: "/_turn/MyFirstApp",
      method: "PUT",
      headers: {
          "Authorization": "Basic " + Buffer.from("seung:6a1928b2-822a-11e9-b1d0-0242ac110003").toString("base64"),
          "Content-Type": "application/json",
          "Content-Length": bodyString.length
      }
};
console.log(bodyString.length.toString());
let httpreq = https.request(options, function(httpres) {
      let str = "";
      httpres.on("data", function(data){ str += data; });
      httpres.on("error", function(e){ console.log("error: ",e); });
      httpres.on("end", function(){
          console.log("ICE List: ", str);
      });
});
httpreq.on("error", function(e){ console.log("request error: ",e); });
httpreq.end();
