<?php

$klein->respond('GET', '/plugin/list', function ($request) {

});

$klein->respond(array('GET', 'POST'), '/plugin/add', function ($request) {

});

$klein->respond(array('GET', 'POST'), '/plugin/[:id]/edit', function ($request) {

});

$klein->respond('GET', '/plugin/[:id]/remove', function ($request) {

});
