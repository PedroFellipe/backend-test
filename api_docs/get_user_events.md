# Events

Returns a list of all your events, yout can filter by those who you will participate and those who you will create

**URL** : `api/user/event`

**Method** : `GET`

**Auth required** : NO

**Params** : You can filter by events that you created or events that you will participate

    -scope=i_created
    -scope=i_will_participate
## Success Response

**Code** : `200 OK`

**Content example**

```json
[
    {
        "id": 1,
        "user": {
            "id": 3,
            "name": "testA",
            "email": "testA@gmail.com",
            "bio": "testA@gmail.com",
            "city": "testA@gmail.com",
            "state": "testA@gmail.com"
        },
        "name": "PHP na brasa",
        "description": "evento anual do PHP na brasa",
        "date": "2020-12-10",
        "time": "12:00",
        "place": "AABB",
        "canceled": null
    },
    {
        "id": 2,
        "user": {
            "id": 3,
            "name": "testA",
            "email": "testA@gmail.com",
            "bio": "testA@gmail.com",
            "city": "testA@gmail.com",
            "state": "testA@gmail.com"
        },
        "name": "PHP na brasa",
        "description": "evento de teste",
        "date": "2020-12-10",
        "time": "12:00",
        "place": "Lugar de teste",
        "canceled": null
    }
]
```
