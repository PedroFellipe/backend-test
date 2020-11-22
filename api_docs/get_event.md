# Events

Returns a  event

**URL** : `api/event/{id_event}`

**Method** : `GET`

**Auth required** : NO

**Params** : You can filter by page, place and date using that as URL PARAM

    -page
    -place
    -date
## Success Response

**Code** : `200 OK`

**Content example**

```json
{
    "id": 1,
    "user": {
        "id": 3,
        "name": "test",
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
}
```


## Error Response

**Condition** : If the event does not exist

**Code** : `404 Not Found`

**Example Content** :

```json
{
    "message": "event not found!"
}
```
