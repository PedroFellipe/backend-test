# Event

Update a event.

**URL** : `api/event/{id_event}`

**Method** : `PUT`

**Auth required** : YES

**Data constraints**

```json
{
    "name": "[a valid string name]",
    "description": "[a valid description]",
    "date": "[a date in format Y-m-d]",
    "time": "[a time in forma h:i]",
    "place": "[valid place]"
}
```

**Data example**
```json
{
    "name": "PHP na brasa",
    "description": "evento anual do PHP na brasa",
    "date": "2020-12-10",
    "time": "12:00",
    "place": "AABB"
}
```
## Success Response

**Code** : `201 Created`

**Content example**

```json
{
    "id": 3,
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

**Condition** : Data Not found

**Code** : `404 Not Found`

**Example Content** :

```json
{
   "message": "Event not found!"
}
```

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
        "description": [
            "The description field is required."
        ],
        "date": [
            "The date field is required."
        ],
        "time": [
            "The time field is required."
        ],
        "place": [
            "The place field is required."
        ]
    }
}
```
