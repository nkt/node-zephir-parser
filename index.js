var cp = require('child_process');
var path = require('path');

var parserPath = path.join(__dirname, 'zephir-parser');

function parse(filename, cb) {
  var child = cp.execFile(parserPath, [filename], function(err, stdout, stderr) {
    if (err) {
      return cb(err);
    }

    cb(null, JSON.parse(stdout.toString()));
  });
}

module.exports.parse = parse;
