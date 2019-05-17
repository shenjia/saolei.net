delete from news where user > 100;

delete from user           where id > 100;
delete from user_info      where id > 100;
delete from user_auth      where id > 100;
delete from user_scores    where id > 100;
delete from user_scores_nf where id > 100;
delete from user_sig       where id > 100;
delete from user_stat      where id > 100;

update user_auth set username = concat('user', id) 

delete from video                where user > 100;
delete from video_info           where id not in (select id from video);
delete from video_scores_beg     where user > 100;
delete from video_scores_beg_nf  where user > 100;
delete from video_scores_exp     where user > 100;
delete from video_scores_exp_nf  where user > 100;
delete from video_scores_int     where user > 100;
delete from video_scores_int_nf  where user > 100;

delete from comment        where user > 100;
