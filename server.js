const http = require('http');

const hostname = process.env.port;
const port = 3000;

const server = http.createServer((req, res) => {
  res.sendFile(__dirname + '/public/index.html');
});

server.listen(process.env.PORT);