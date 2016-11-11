# FATEC API

This is a simple API, it has been made as part of a conclusion course project.

## Purpose

The purpose of this API is to provide connection between mobile clients and the college database, providing then data of their classes schedules and grade.

## API Rate Limiter

Taken from https://github.com/pabloroca/slim3-apiratelimit-middleware but with a better integration


## Methods

All methods can return:

```json
{
  "error": false,
  "description": "I'm a description of what just happened!"
}
```

- Error: can be **false** or **true**
- Description: description of the return status which can be: 
  * Invalid request, must give user_id!
  * This token belongs to another user, admin was reported!
  * This token is invalid or no longer exists.


###login###

```
request: POST http://fatecapi.tk/public/alunos
Request headers send:
Content-Type: application/json
Authorization: Bearer MYTOKEN
ra=RADOALUNO&password=SENHADOALUNO

```

data can also be sended in JSON format:

```json
{
"ra":"RADOALUNO","
password":"SENHADOALUNO"
}
```
SENHA must be in sha256*

 It returns:

```json
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

- access_token: token that shall be used to access protect resources
- expiresin: when the token will expire

###grade###

```
request: POST http://fatecapi.tk/public/grade
Request headers send:
Content-Type: application/json
Authorization: Bearer MYTOKEN
ra=RADOALUNO

```

data can also be sended in JSON format:

```json
{"ra":"141b22"}
```

 It returns an JSON array of all the student's disciplines **in the current semester/year**:

```json
[
  {
    "ra": "141b22",
    "coddisciplina": "1",
    "semestre": "2",
    "ano": "2016",
    "faltastot": "0",
    "notas1": null,
    "notas2": null,
    "media": null,
    "disciplina": "Estágio Supervisionado"
  },
  {
    "ra": "141b22",
    "coddisciplina": "2",
    "semestre": "2",
    "ano": "2016",
    "faltastot": "4",
    "notas1": "5.0",
    "notas2": null,
    "media": null,
    "disciplina": "Gestão e Governança de Tecnologia da Informação"
  },
  {
    "ra": "141b22",
    "coddisciplina": "3",
    "semestre": "2",
    "ano": "2016",
    "faltastot": "8",
    "notas1": null,
    "notas2": null,
    "media": "6.0",
    "disciplina": "Inteligência Artificial"
  },
  {
    "ra": "141b22",
    "coddisciplina": "4",
    "semestre": "2",
    "ano": "2016",
    "faltastot": "0",
    "notas1": null,
    "notas2": null,
    "media": "0",
    "disciplina": "Trabalho de Graduação II"
  }
]


```

###gradeSchedule###

```
request: POST http://fatecapi.tk/public/gradeSchedule
Request headers send:
Content-Type: application/json
Authorization: Bearer MYTOKEN
ra=RADOALUNO

```

data can also be sended in JSON format:

```json
{"ra":"141b22"}
```

It returns all the discipline's schedule of the student **in the current semester/year**:

```json
[
  {
    "disciplina": "Estágio Supervisionado",
    "coddisciplina": "1",
    "horario": "10:10 / 11:00",
    "periododia": "3",
    "diadasemana": "7",
    "ano": "2016",
    "semestre": "2"
  },
  {
    "disciplina": "Gestão e Governança de Tecnologia da Informação",
    "coddisciplina": "2",
    "horario": "19:00 / 19:50",
    "periododia": "3",
    "diadasemana": "6",
    "ano": "2016",
    "semestre": "2"
  },
  {
    "disciplina": "Gestão e Governança de Tecnologia da Informação",
    "coddisciplina": "2",
    "horario": "19:50 / 20:40",
    "periododia": "3",
    "diadasemana": "6",
    "ano": "2016",
    "semestre": "2"
  }
]
```
###files###

```
request: POST http://fatecapi.tk/public/files
Request headers send:
Content-Type: application/json
Authorization: Bearer MYTOKEN
ra=RADOALUNO

```

data can also be sended in JSON format:

```json
{"ra":"141b22"}
```

It returns all the discipline's files of the student **in the current semester/year**:

```json
[
  {
    "disciplina": "Inteligência Artificial",
    "pasta": "http://192.168.15.6:8888/fatecArquivos/pastaIA/",
    "arquivos": [
      "Exercicios_01_IA__.doc",
      "Exercicios_IA_04_Logica_Fuzzy.doc",
      "Exercicios_IA_AG.doc"
    ]
  },
  {
    "disciplina": "Trabalho de Graduação II",
    "pasta": "http://192.168.15.6:8888/fatecArquivos/pastaTCC/",
    "arquivos": [
      "Modelo_TCC_Monografia_FATECJD.doc",
      "Modelo_TCC_Software_FATECJD-v1.2.doc",
      "cronograma-tg-II-ads-2016-2.pdf"
    ]
  }
]
```

###changePassword###

```
request: PUT http://fatecapi.tk/public/changePassword
Request headers send:
Content-Type: application/json
Authorization: Bearer MYTOKEN
ra=RADOALUNO&rg=RGDOALUNO&datanascimento=DATANASCIMENTODOALUNO&password=NOVASENHA&newpassword=CONFNOVASENHA

```

data can also be sended in JSON format:

```json
{
"ra":"RADOALUNO",
"rg":"RDGOALUNO",
"datanascimento":"DATADENASCIMENTODOALUNO",
"password":"NOVASENHA",
"newpassword":"CONFNOVASENHA"
}
```

*datanascimento must be in 'd/m/Y' format

It returns:

```json
{"error":"false","description":"Password updated successfully!"}
```

- Error: can be **false** or **true**
- Description: description of the return status which can be:  
  * Password updated successfully!
  * Password not updated!
  * Password and new password must match!
  * RG and/or birth date doesn\'t match.
  * No RG and/or birth date provided!  


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


