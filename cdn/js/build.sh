cd /Users/zhangshenjia/saolei.net/code/js
rm app.js	
cat source/*.js > app.js
java -jar yc.jar --preserve-semi -o app.js app.js