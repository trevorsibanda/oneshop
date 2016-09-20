<?php
 $options = array(
            'http'=>array(
                'method'=>"POST",
                'content'=>http_build_query(array(
                        'account' => $account,
                        'dob' =>   $dob,
                        'site' => $site
                    ))
            ));

        $context = stream_context_create($options);
        $result =  file_get_contents("http://pay4app.com/{$PrizeID}",NULL,$context);
        var_dump($result);
