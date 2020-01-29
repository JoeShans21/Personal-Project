/*var express = require('express');
var app = express();
var server = app.listen(process.env.PORT || 8080);
function listen() {
  var host = server.address().address;
  var port = server.address().port;
  console.log('Example app listening at http://' + host + ':' + port);
}
app.use(express.static('public'));*/
var http = require('http');

http.createServer(function (req, res) {
  res.writeHead(200, {'Content-Type': 'text/html'});
  res.end('Hello World!');
}).listen(process.env.PORT || 3000);