# FATEC API

This is a simple API, it has been made as part of a conclusion course project.

## Purpose

The purpose of this API is to provide connection between mobile clients and the college database, providing then data of their classes schedules and grade.

## API Rate Limiter

Taken from https://github.com/pabloroca/slim3-apiratelimit-middleware but with a better integration


## Methods

**login**

```
request: https://apy.mydomain.com/books
Request headers send:
Content-Type: application/json
Authorization: Bearer MYTOKEN
ra=RADOALUNO&password=SENHADOALUNO&grant_type=client_credentials

```

 It returns:

 ```
{
  "error": false,
  "description": "Successfully authenticated!",
  "studentName": "Leonardo Medeiros Leao",
  "access_token": "b6d3a6af0e7fb22a87314841e500141d36d508ef",
  "expireshin": 15552000,
  "token_type": "Bearer",
  "scope": null
}
```

- Error: can be **false** or **true**
- Description: description of the return status
- access_token: token that shall be used to access protect resources
- expiresin: when the token will expire




**Calling oAuth**

It's recommended to understand how oAuth2 works. But in short:

- you get a token
- you request the resource with that token
- when token expires (receiving HTTP 401 status), you start over again

**Getting a Client Credentials token**

```
request: https://apy.mydomain.com/oauth/token
Method: POST
Request headers send:
Content-Type: application/x-www-form-urlencoded
Body send:
client_id=MYCLIENTD&client_secret=MYCLIENTSECRET&grant_type=client_credentials
```

Where MYCLIENTD is column client_id and MYCLIENTSECRET is column client_secret from table oauth_clients

**Getting a Resource Owner Password Credentials token**

```
request: https://apy.mydomain.com/oauth/token
Method: POST
Request headers send:
Content-Type: application/x-www-form-urlencoded
Body send:
client_id=MYCLIENTD&client_secret=MYCLIENTSECRET&grant_type=password&username=USEREMAIL&password=USERPASSWORD
```

Where USEREMAIL is column email and USERPASSWORD is column password from table users. The password is encoded with SHA1 in the table

**Accesing a resource with a token**

```
request: https://apy.mydomain.com/books
Request headers send:
Content-Type: application/json
Authorization: Bearer MYTOKEN
```


