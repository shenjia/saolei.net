<a href="<?= isset( $url ) ? $url : ( '/user/' . $user->id ) ?>" class="avatar size<?=$size?> <?=$class?>" title="<?=$user->chinese_name?>"><?if ( $user->avatar ):?><img src="<?= AVATAR_ROOT . $user->id . '.jpg' ?>" onerror="this.parentNode.removeChild(this);"/><?endif;?></a>