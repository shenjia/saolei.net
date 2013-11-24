<ul id="account_center">
    <li class="main">
        <div class="box">
            <?php $this->renderPartial('/account/_header', array('user' => $user));?>
            <table class="form" cellpadding="0" cellspacing="0">
                <tr>
                    <th><?php echo Yii::t('user', 'chinese_name');?></th>
                    <td><?= CHtml::encode($user->chinese_name);?></td>    
                </tr>
                <tr>
                    <th><?php echo Yii::t('user', 'english_name');?></th>
                    <td><?= CHtml::encode( $user->english_name );?></td>            
                </tr>
                <tr>
                    <th><?php echo Yii::t('user', 'self_intro');?></th>
                    <td><?= CHtml::encode( $user->info->self_intro );?></td>            
                </tr>
                <tr>
                    <th><?php echo Yii::t('user', 'interest');?></th>
                    <td><?= CHtml::encode( $user->info->interest );?></td>          
                </tr>
                <tr>
                    <th><?php echo Yii::t('user', 'qq');?></th>
                    <td><?= CHtml::encode( $user->info->qq );?></td>            
                </tr>
                <tr>
                    <th><?php echo Yii::t('user', 'nickname');?></th>
                    <td><?= CHtml::encode( $user->info->nickname );?></td>          
                </tr>
                <tr>
                    <th><?php echo Yii::t('user', 'mouse');?></th>
                    <td><?= CHtml::encode( $user->info->mouse );?></td>         
                </tr>
                <tr>
                    <th><?php echo Yii::t('user', 'pad');?></th>
                    <td><?= CHtml::encode( $user->info->pad );?></td>           
                </tr>
            </table>
        </div>
    </li>
    <li class="sidebar">
        <?php $this->renderPartial('/user/_infoCell', array('user' => $user))?>
    </li>
</ul>
