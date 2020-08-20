# Pokemon Rest Api

Crud Rest APi in symfony for get pokemons

## Stack
- php
- symfony 5 (jwt, cors, json api)
- docker
- mysql

## Installation
This project require docker and docker-compose

```bash
git clone https://github.com/kevin-schmitt/pokemon.git
cd pokemon
docker-compose up -d
make install
```
Go to [127.0.0.1:9010](http://127.0.0.1:9010)

## Quality tools
- phpstan lvl 2
- cs fixe

just run ``` make quality ```

## Endpoints

POST /api/login_check - get jwt token with username and password
``` json
{
  {"username":"johndoe","password":"test"}
}
```
response
``` json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.
            eyJpYXQiOjE1OTcyMzA1NzEsImV4cCI6MTU5NzIzNDE3MSwicm9sZX
            MiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiam9obmRvZSJ9.IsznvRlpPVMZ
            pYh3ypyMLM4DJYzx3ntOFzOGgfaJrMMgBj7KPFd1pTWzZZ6QhDhCqhgDbCDnm4wuFyUAKImp4l1Z
            sBotnAF4b72vwEkSr1pvhh1fNaG6z_4cnX8XBhXi4zx5NzcJBG1u7mwDj6NMVuw8G4OAwSfu3Y6G1KWxyCC2ZW-Q-Hq
            hTsDo0epkxqJD919xN-1DDsfsrcTZQ7hFG6i0qqjvnydSEFzY_AiyAk-87Wg-TmpqIeT8EG8E791Wxe8CnsXRWcLAav2u4lAiYdNo
            XEXIOiIWZeKCDqr6RAs26bEGZzOtM8NBUMfF-kFfKjZZGlg4COZdbdizLkePfOwNM_5KnCRMx6r-DH5DIcub3qos9i3VNOr8coucMmKqOH
            MRXP_WIiWxuKtqPw1Pn9svkVYrrWHfTRDtoed18Abx2366Kpv8oApsNl5Mab8T2NE6Pv9qKK6hcHHlUtAeH5HVFu8tOnvIYvVLjU3Lm5ey
            DFm4AXP-gmxZiyShh-aBucWWc99hHubxLpFiN_-CKtl0ff2hI692LWP-_4ipTm015tUwDDgFgDcdkPUHUrKyjjmt9cfuzsNMaQfrw_fCC3H
            sWwmzWNR23DeID1PZLemwRCJlbnQPJ05V2ziTIQr21tYZpxfZRQXEl5ySIbjyrqUWoGO6nRXD5iFNC33GGc8"
}
```

PUT /api/pokemons/{id} - update pokemon with id, all attributes are required!
``` json
{
    "name": "Blaziken",
    "types": [
			 "Fire",
       "Fighting"
    ],
    "total": 530,
    "hp": 80,
    "attack": 120,
    "defense": 70,
    "attackSp": 110,
    "defenseSp": 70,
    "generation": 3,
    "speed": 80,
    "legendary": true
}
```
response error
``` json
{

  "errors": "attackSp: Cannot be empty\ndefenseSp: Cannot be empty\ngeneration: Cannot be empty\nspeed: Cannot be empty\nlegendary: Cannot be empty"

}
```

POST /api/pokemons - create a pokemon, all attributes are required!
``` json
{
    "name": "Blaziken",
    "types": [
			 "Fire",
       "Fighting"
    ],
    "total": 530,
    "hp": 80,
    "attack": 120,
    "defense": 70,
    "attackSp": 110,
    "defenseSp": 70,
    "generation": 3,
    "speed": 80,
    "legendary": true
}
```
response error
``` json
{

  "errors": "attackSp: Cannot be empty\ndefenseSp: Cannot be empty\ngeneration: Cannot be empty\nspeed: Cannot be empty\nlegendary: Cannot be empty"

}
```

DELETE /api/pokemons/{id]} delete a pokemon

GET /api/pokemons?page=1&order=asc - get pokemons by pagination
- page: (60 pokemons by page)
- order: asc or desc by name
response
``` json
[
  {
    "id": 12761,
    "name": "Abomasnow",
    "types": [
      {
        "name": "Grass"
      },
      {
        "name": "Ice"
      }
    ],
    "total": 494,
    "hp": 90,
    "attack": 92,
    "defense": 75,
    "attackSp": 92,
    "defenseSp": 85,
    "generation": 4,
    "speed": 60,
    "legendary": true
  },
  {
    "id": 12762,
    "name": "AbomasnowMega Abomasnow",
    "types": [
      {
        "name": "Grass"
      },
      {
        "name": "Ice"
      }
    ],
    "total": 594,
    "hp": 90,
    "attack": 132,
    "defense": 105,
    "attackSp": 132,
    "defenseSp": 105,
    "generation": 4,
    "speed": 30,
    "legendary": true
  }
]
```

## Technical description
File structure back
```
  src/
    Command
      LoadPokemonCsvCommand # insert csv data pokemon in databse (csv is not dynamique and have to be in Data/pokemon.csv)
    Data
      pokemon.csv
    DataFixtures
      PokemonCsvFixtures # run LoadPokemonCsvCommand is slow!
      UserFixtures # insert user for jwt authentification
    Controller
        AbstractApiController # Abstract class with method for all controller like serializer...
        PokemonController # crud rest api
    Entity
      Pokemon
      Type
      User
    Repository
      PokemonRepository # for cerate update pokemon, and handle pagination
      TypeRepository
      UserRepository
    Serializer
      JsonSerializer # use symfony serialize component, here for add custom normalizer
    Service
      PaginatorService # use doctrine paginator
    Utils:
      TimeStam # trait for add timestamp annotation in entity

```

## Todo
- Add functional test with behat and selenium
- Add ci/cd for test
- Add functional test for json (with mock api)
- Not authorize same pokemon name
- Improve error return
- add swagger with nelmio
- Use event subscriber/ listener for return exception and not use try catch