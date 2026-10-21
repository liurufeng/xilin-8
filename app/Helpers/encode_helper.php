<?php
function sendJson($data)
{
    // Send past expiration and cache control to minimize the chance of
    // browsers (IE) caching AJAX requests
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Fri, 24 Nov 2000 06:00:00 GMT');
    header('Content-type: application/json');
    header('X-Content-Type-Options: nosniff');
    // CI4 sends its own (text/html) Content-Type from the shared response
    // object after the controller returns, so set it there as well.
    if (function_exists('service')) {
        service('response')->setContentType('application/json');
    }
    echo json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

function html_entity_fix($input, $options = array())
{
    $fixed_string = htmlspecialchars_decode(htmlentities($input, ENT_COMPAT, "ISO-8859-1", false));
    if (!empty($options['remove_html']) && $options['remove_html']) {
        $input = strip_tags($input);
    } else {
        $patterns = array('/(?!\<sup\>\s*)(&reg;)(?!\s*\<\/sup\>)/i', '/(?!\<sup\>\s*)(&trade;)(?!\s*\<\/sup\>)/i');
        $replacements = array('<sup>&reg;</sup>', '<sup>&trade;</sup>');
        $fixed_string = preg_replace($patterns, $replacements, $fixed_string);
    }

    return $fixed_string;
}