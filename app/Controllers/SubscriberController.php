<?php

namespace App\Controllers;

use App\Models\SubscriberModel;

class SubscriberController extends BaseController
{
    public function index()
    {
    }

    public function popularTags()
{
    $db = db_connect();
    $query = $db->query("SELECT tag_name, COUNT(*) AS subscriber_count FROM tags GROUP BY tag_name ORDER BY subscriber_count DESC");
    $data['popularTags'] = $query->getResultArray();

    return view('popular_tags', $data);
}

public function topTagValues()
{
    $db = db_connect();
    $query = $db->query("SELECT tag_name, value AS tag_value, COUNT(*) AS subscriber_count FROM (SELECT t.tag_name, t.subscriber_id, t.value, ROW_NUMBER() OVER (PARTITION BY tag_name ORDER BY COUNT(*) DESC) AS row_num FROM tags t GROUP BY tag_name, subscriber_id, value) top_tags WHERE top_tags.row_num <= 10 GROUP BY tag_name, tag_value ORDER BY tag_name, subscriber_count DESC");
    $data['topTagValues'] = $query->getResultArray();

    return view('top_tag_values', $data);
}
}
