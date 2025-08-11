# MSPA To Go

MSPA To Go is an application server that serves pages from the MS Paint Adventures server
that is digestible on desktop computers and mobile devices, without any external downloads,
since the original MS Paint Adventures site no longer works, and Homestuck.com is incredibly
broken.

MS Paint Adventures is the work of Andrew Hussie, and I am not associated with MS Paint Adventures,
What Pumpkin, VIZ Media, or the Homestuck Independent Creative Union. MSPA To Go is made out of love
for MSPA and a desire for it to be accessible to those using mobile devices.

## Hosting
To host MSPA To Go, you will need an Apache web server with support for at least PHP 8 (may be 8.1, I'm
not sure) with the cURL plugin.

## Reading offline
See [OFFLINE-MODE.md](OFFLINE-MODE.md).

## Docker
To run it in Docker, you'll just need to clone the repository and do:

```
docker build -t mspa --no-cache=true .
docker-compose up
```

And it should just work!
