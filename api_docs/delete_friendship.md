# Friendship Invitation

Accepts a friendship invitation.

**URL** : `api/friendship/{friendship_invitation_id}`

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
   "message": "Friendship not found!"
}
```

