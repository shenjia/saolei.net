<?php if ($link):?>
<a href="/user/<?= $user->id ?>" class="name_link <?= $class ?>" target="_blank"><?= $user->chinese_name ?></a>
<?php else:?>
<span class="name_link <?= $class ?>"><?= $user->chinese_name ?></span>
<?php endif;?>
