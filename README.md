# wsChatDemo
websockets Chat app demo

How to run this demo

```
git@github.com:kedlas/wsChatDemo.git

composer install

docker build -t chat_image .

docker rm -f chat

docker run -d -p 8080:8080 --name chat chat_image
```