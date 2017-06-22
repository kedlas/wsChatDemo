# wsChatDemo
websockets Chat app demo

How to run this demo app:
```
git clone git@github.com:kedlas/wsChatDemo.git

docker build -t chat_image .

docker rm -f chat

docker run -d -p 8080:8080 --name chat chat_image
```

Optional:
```
composer install
```