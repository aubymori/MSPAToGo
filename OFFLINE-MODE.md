# Offline Mode
If you want to use MSPA To Go without reliance on the MSPA server, then you can create the `config.json` file with the
following contents:

```json
{
    "offline_mode": true
}
```

You will also need to run the `scripts/dump.js` script with Node.

You will also need to provide the Collide video (https://www.youtube.com/watch?v=Y5wYN6rB_Rg)
in WEBM format at `mspa_local/collide.webm`.

You will also need to provide the ACT 7 video (https://www.youtube.com/watch?v=FevMNMwvdPw)
in WEBM format at `mspa_local/ACT7.webm`.