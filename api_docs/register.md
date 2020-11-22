# Register

Used to collect a Token for a registered User.

**URL** : `api/auth/register`

**Method** : `POST`

**Auth required** : NO

**Data constraints**

```json
{
    "username": "[valid email address]",
    "password": "[password in plain text]",
    "password_confirmation": "[password_confirmation]",
    "bio": "[string]",
    "city": "[string]",
    "state": "[string]",
    "profile_picture": "[some uploaded picture in png or jpeg]" 
}
```

**Data example**

```json
{
    "name": "test",
    "email": "testA@gmail.com",
    "password": 123456,
    "password_confirmation": 123456,
    "bio": "A bio test",
    "city": "São Luís",
    "state": "Maranhão",
    "profile_picture": "[some uploades picture in png or jpeg]" 
}
```

## Success Response

**Code** : `200 OK`

**Content example**

```json
{
    "id": 3,
    "name": "test",
    "email": "testA@gmail.com",
    "bio": "A bio test",
    "city": "São Luís",
    "state": "Maranhão"
    "profile_picture": {
        "id": 3,
        "name": "Captura de tela de 2020-09-10 15-13-59.png",
        "link": "http://127.0.0.1:8000/api/attachments/3",
        "extension": "png",
        "mine": "image/png"
    }
}
```

## Error Response

**Condition** : If you data request don't pass of the validation 

**Code** : `422 Unprocessable Entity
`

**Example Content** :

```json
{
    "errors": {
        "name": [
            "The name field is required."
        ],
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ],
        "bio": [
            "The bio field is required."
        ],
        "city": [
            "The city field is required."
        ],
        "state": [
            "The state field is required."
        ]
    }
}
```
