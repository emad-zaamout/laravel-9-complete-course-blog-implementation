const mix = require("laravel-mix");

mix.styles(
    [
        "node_modules/bootstrap/dist/css/bootstrap.min.css",
        "resources/css/app.css",
    ],
    "public/css/app.css"
).version();

mix.js(
    [
        "resources/js/app.js"
    ],
    "public/js/app.js"
).version();

mix.js(
    [
        "resources/js/app.js",
        "resources/js/dashboard.js"
    ],
    "public/js/dashboard.js"
).version();