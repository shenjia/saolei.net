<?php if ($link):?>
<a href="/user/<?= $user->id ?>" class="avatar_link <?= $class ?>" target="_blank"><?= $user->chinese_name ?></a>
<?php else:?>
<span class="avatar_link <?= $class ?>"><?= $user->chinese_name ?></span>
<?php endif;?>
<span class="gender <?= $user->sex ? 'male' : 'female'?> <?= $gender ?>"></span>