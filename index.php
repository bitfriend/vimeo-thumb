<?php

require('./vimeo.php-2.0.3/autoload.php');

$client_id = 'a518bdb40b00ce4dff66c77c8214f32245034470';
$client_secret = '1746E+XjRucFOMOJH1TRqv6jvpj+OdpF0XQuDcRJm8ELXcuaIzSED7YV065uUq+8gvdCyC+Iq77AXn3LrmhTOB3DKwo2umtwMQNQnicBbOaqxTv1mZ9uHQUB68q2XV//';
$access_token = '3407921d5a18f80ef67aaa199538cf76';

$per_page = 25;
$user_id = 'user84734375';

$image_path = "./thumbnail.png";

$videos = array(
    '/videos/292342572',
    '/videos/292343019',
    '/videos/292343381',
    '/videos/292312927',
    '/videos/292313377',
    '/videos/292313658',
    '/videos/289866134',
    '/videos/289864382',
    '/videos/292255120',
    '/videos/292258063',
    '/videos/292137344',
    '/videos/277626306',
    '/videos/292314718',
    '/videos/292315337',
    '/videos/292315753',
    '/videos/289867055',
    '/videos/292109585',
    '/videos/289868924',
    '/videos/289868930',
    '/videos/292110857',
    '/videos/289870654',
    '/videos/292111720',
    '/videos/292260315',
    '/videos/292344438',
    '/videos/292344975',
    '/videos/292345511',
    '/videos/292345972',
    '/videos/292346341',
    '/videos/292346668',
    '/videos/292316292',
    '/videos/292316680',
    '/videos/292317165',
    '/videos/292352949',
    '/videos/292353354',
    '/videos/292353733',
    '/videos/292317920',
    '/videos/292318203',
    '/videos/292318747',
    '/videos/292256208',
    '/videos/292256441',
    '/videos/292257053',
    '/videos/292257203',
    '/videos/292257379',
    '/videos/292257513',
    '/videos/292253198',
    '/videos/292253742',
    '/videos/292253886',
    '/videos/292254279',
    '/videos/292254545',
    '/videos/292254705',
    '/videos/292254849',
    '/videos/292255122',
    '/videos/292255253',
    '/videos/292255417',
    '/videos/292255554',
    '/videos/292255675',
    '/videos/292257660',
    '/videos/292257832',
    '/videos/292340251',
    '/videos/292178441',
    '/videos/292179656',
    '/videos/292186255',
    '/videos/292187363',
    '/videos/292188926',
    '/videos/292190117',
    '/videos/292292893',
    '/videos/292293412',
    '/videos/292293805',
    '/videos/292266037',
    '/videos/292266422',
    '/videos/292266608',
    '/videos/292191702',
    '/videos/292193129',
    '/videos/292193862',
    '/videos/292296351',
    '/videos/292296815',
    '/videos/292297144',
    '/videos/292267278',
    '/videos/292267656',
    '/videos/292268058',
    '/videos/292308841',
    '/videos/292309184',
    '/videos/292309436',
    '/videos/292268357',
    '/videos/292268704',
    '/videos/292268903',
    '/videos/292336952',
    '/videos/292337762',
    '/videos/292338353',
    '/videos/292354186',
    '/videos/292354678',
    '/videos/292355039',
    '/videos/292347043',
    '/videos/292347465',
    '/videos/292347916',
    '/videos/292334809',
    '/videos/292335435',
    '/videos/292336147',
    '/videos/292342567',
    '/videos/292309739',
    '/videos/292310052',
    '/videos/292310315',
    '/videos/292355499',
    '/videos/292355856',
    '/videos/292356253',
    '/videos/292307739',
    '/videos/292308036',
    '/videos/292308298',
    '/videos/292303781',
    '/videos/292305128',
    '/videos/292305432',
    '/videos/292310520',
    '/videos/292310785',
    '/videos/292311012',
    '/videos/292305842',
    '/videos/292306424',
    '/videos/292306749',
    '/videos/292297436',
    '/videos/292297685',
    '/videos/292297942',
    '/videos/292348473',
    '/videos/292348995',
    '/videos/292349453',
    '/videos/292298156',
    '/videos/292299780',
    '/videos/292300046',
    '/videos/292114307',
    '/videos/292112711',
    '/videos/292113460',
    '/videos/292097500',
    '/videos/292115074',
    '/videos/292116225',
    '/videos/292300344',
    '/videos/292300660',
    '/videos/292300869',
    '/videos/289864834',
    '/videos/292290977',
    '/videos/292291993',
    '/videos/292292517',
    '/videos/292258348',
    '/videos/292258866',
    '/videos/292259116',
    '/videos/292117789',
    '/videos/292142087',
    '/videos/292143785',
    '/videos/292301167',
    '/videos/292301427',
    '/videos/292301657',
);

$lib = new \Vimeo\Vimeo($client_id, $client_secret, $access_token);

// $response = $lib->request('/me/videos', array(
//     'per_page' => $per_page,
// ));
// $page_count = intval(($response['body']['total'] + $per_page - 1) / $per_page);

// for ($i = 1; $i <= $page_count; $i++) {
//     $response = $lib->request('/me/videos', array(
//         'per_page' => $per_page,
//         'page' => $i,
//         'direction' => 'asc',
//         'sort' => 'alphabetical',
//     ));
//     foreach ($response['body']['data'] as $video) {
//         $videos[] = $video['uri'];
//         echo $video['uri'] . '<br>';
//     }
// }
// exit;

$link = mysqli_connect('localhost', 'root', '', 'vimeo');
if (mysqli_connect_errno()) {
    die('Could not connect: ' . mysqli_connect_error());
}

$videos = array();
$result = mysqli_query($link, "SELECT * FROM `videos` WHERE `modified` = 'N'");
while ($record = mysqli_fetch_assoc($result)) {
    $videos[] = $record;
}
mysqli_free_result($result);

$count = 0;
foreach ($videos as $video) {

    $video_uri = $video['uri'];

    $resource = $lib->request($video_uri);
    if ($resource['status'] != 200)
        continue;

    if (!empty($resource['body']['metadata']['connections']['pictures']['uri'])) {
        // The third parameter dictates whether the picture should become the default, or just be part of the collection of pictures
        $response = $lib->uploadImage($resource['body']['metadata']['connections']['pictures']['uri'], $image_path, true);

        if ($resource['status'] != 200)
            continue;

        $sql = "UPDATE `videos` SET `modified` = 'Y' WHERE `id` = " . $video['id'];
        mysqli_query($link, $sql);
        $count++;
    }
}

mysqli_close($link);

echo $count . ' of ' . count($videos) . ' succeeded!';
