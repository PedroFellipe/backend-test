# Friendship Invitation

Accepts a friendship invitation.

**URL** : `api/friendship_invitation/{friendship_invitation_id}`

**Method** : `DELETE`

**Auth required** : YES

## Success Response

**Code** : `200 Ok`

**Content example**

```json
{
   "message": "Friendship deleted"
}
```

## Error Response
**Condition** : Data not found

**Code** : `404 Not Found`

**Example Content** :

```json
{
   "message": "Friendship request not found!!"
}
```

**Condition** : If you have already accepted this request

**Code** : `400 Bad Request`

**Example Content** :

```json
{
   "message": "This friend request has already been accepted !"
}
```

**Condition** : If the friend request is not for you

**Code** : `400 Bad Request`

**Example Content** :

```json
{
   "message": "This friend request is not for you"
}
```
