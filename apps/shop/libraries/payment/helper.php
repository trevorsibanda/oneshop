<?php
function rest_helper($url, $params = null, $verb = 'GET', $format = 'json')
{
  $cparams = array(
    'http' => array(
      'method' => $verb,
      'ignore_errors' => true
    )
  );
  if ($params !== null) {
    $params = http_build_query($params);
    if ($verb == 'POST') {
      $cparams['http']['content'] = $params;
    } else {
      $url .= '?' . $params;
    }
  }

  $context = stream_context_create($cparams);
  $fp = @fopen($url, 'rb', false, $context);
  if (!$fp) {
    $res = false;
  } else {
    // If you're trying to troubleshoot problems, try uncommenting the
    // next two lines; it will show you the HTTP response headers across
    // all the redirects:
    // $meta = stream_get_meta_data($fp);
    // var_dump($meta['wrapper_data']);
    $res = stream_get_contents($fp);
  }

  if ($res === false) {
    throw new Exception("$verb $url failed: $php_errormsg");
  }

  switch ($format) {
    case 'json':
      $r = json_decode($res);
      if ($r === null) {
        throw new Exception("failed to decode $res as json");
      }
      return $r;

    case 'xml':
      $r = simplexml_load_string($res);
      if ($r === null) {
        throw new Exception("failed to decode $res as xml");
      }
      return $r;
  }
  return $res;
}


$data = rest_helper('http://localhost:8081/', array('username'=>'null','password'=>'too') , 'POST' ,'' );
die($data);

// This lists projects by Ed Finkler on GitHub:
foreach (
    rest_helper('http://github.com/api/v2/json/repos/show/funkatron')
    ->repositories as $repo) {
  echo $repo->name, "<br>\n";
  echo htmlentities($repo->description), "<br>\n";
  echo "<hr>\n";
}

// This incomplete snippet demonstrates using POST with the Disqus API
var_dump(
  rest_helper(
    "http://disqus.com/api/thread_by_identifier/",
    array(
      'api_version' => '1.1',
      'user_api_key' => $my_disqus_api_key,
      'identifier' => $thread_unique_id,
      'forum_api_key' => $forum_api_key,
      'title' => 'HTTP POST from PHP, without cURL',
    ), 'POST'
  )
);


