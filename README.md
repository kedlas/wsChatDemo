# wsChatDemo
websockets Chat app demo

```
docker build -t chatimage .
docker rm -f chat && docker run -d -p 8080:8080 -v `pwd`:/app --name chat chatimage
```