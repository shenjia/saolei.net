Saolei.net
==========

1. 安装Mysql，将/protected/migrations/testdb_2019-5-19.sql还原为saolei数据库
2. 在/protected/config/main.php中配置mysql数据库用户名和密码
3. 创建/protected/runtime目录，确保有web用户有写权限
4. 编辑本地host，加入 127.0.0.1 lo.saolei.net
5. 用nginx或apache配置web容器，根目录指向index.php所在目录，域名为lo.saolei.net
6. 在浏览器中访问lo.saolei.net

部署过程中有任何问题，可联系站长张砷镓。