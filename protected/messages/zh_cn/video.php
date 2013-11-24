<?php
return array(

    'video' => '录像',

        'all' => '全部',
        'beg' => '初级',
        'int' => '中级',
        'exp' => '高级',
        'sum' => '总计',

        'time' => '时间记录',
        '3bvs' => '3BV/s记录',

        'status.' . VideoConfig::STATUS_NORMAL   => '待审核',
        'status.' . VideoConfig::STATUS_REVIEWED => '已通过',
        'status.' . VideoConfig::STATUS_BANNED   => '已屏蔽',

    'upload' => '上传',
        'old_browser' => '本浏览器不支持上传，请更换浏览器！',
        'upload_success' => '上传成功',

    'review' => '审核',
        'review_success_' . VideoConfig::STATUS_REVIEWED => '通过成功',
        'review_success_' . VideoConfig::STATUS_BANNED   => '屏蔽成功',
        'review_failed'  => '审核失败',
        'review_blind'   => '拒绝盲审，从我做起！',
        
    'whats_noflag'     => '仅用左键点击完成游戏，全程不用右键标雷',
    'why_uncount_3bvs' => '因为3BV太小，运气成分过高，该3BV/s成绩不被承认',
    'why_null_record'  => '在此级别没有被承认成绩的录像，因此没有记录',

    'not_exist' => '该录像不存在',
    'invalid_status' => '非法的状态',
);