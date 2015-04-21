Node Zephir
===========

Wrapper around [Zephir](https://github.com/phalcon/zephir) parser for Node.js

Installation
------------

```
npm install zephir
```

Usage
-----

```js
var zephir = require('zephir');

zephir.parse('path/to/file.zep', function(err, ast) {
  console.assert(err === null);
  console.log(ast);
});
```

License
-------
[MIT](LICENSE)
