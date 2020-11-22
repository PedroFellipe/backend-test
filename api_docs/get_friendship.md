# Events

Returns the list of all your friends

**URL** : `api/friendship`

**Method** : `GET`

**Auth required** : NO

## Success Response

**Code** : `200 OK`

**Content example**

```json
[
    {
        "id": 2,
        "user": {
            "id": 1,
            "name": "teste3",
            "email": "teste3@gmail.com"
        }
    },
    {
        "id": 3,
        "user": {
            "id": 2,
            "name": "test",
            "email": "test@gmail.com"
        }
    }
]
```
