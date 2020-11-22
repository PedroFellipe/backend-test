# Login

Used to collect a Token for a registered User.

**URL** : `api/auth/login`

**Method** : `POST`

**Auth required** : NO

**Data constraints**

```json
{
    "username": "[valid email address]",
    "password": "[password in plain text]"
}
```

**Data example**

```json
{
    "email": "testA@gmail.com",
    "password": "123456"
}
```

## Success Response

**Code** : `200 OK`

**Content example**

```json
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYwNTk4MjU2NSwiZXhwIjoxNjA1OTg2MTY1LCJuYmYiOjE2MDU5ODI1NjUsImp0aSI6Ijd1ZUNYRVJxTmhPTHhUREYiLCJzdWIiOjMsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.p17v_IesaYCIQMFPIixZx5tCU52u-pEpJyqNGFy5rbc",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
        "id": 3,
        "profile_picture_id": null,
        "name": "test",
        "email": "testA@gmail.com",
        "bio": "A exemple bio",
        "city": "São Luís",
        "state": "Maranhão",
        "created_at": "2020-11-21T18:12:15.000000Z",
        "updated_at": "2020-11-21T18:12:15.000000Z"
    }
}
```

## Error Response

**Condition** : If 'username' and 'password' combination is wrong.

**Code** : `401 UNAUTHORIZED`

**Content** :

```json
{"error":"Unauthorized"}
```
