# Backend Code Challenge

## Getting Started

1. Run `make build` to build fresh images
2. Run `make up` (detached mode without logs)
3. Run `make down` to stop the Docker containers

## Testing

Simply run `make test` to run the tests.

If fixtures failed to load (for whatever reason):
- Run `make sh` to enter the PHP container and then run:
- ```
  php bin/console doctrine:database:create --env=test
  php bin/console doctrine:schema:create --env=test
  php bin/console doctrine:fixtures:load --env=test
  ```

## Authentication

1. Download the [Postman Collection](https://github.com/jack-the-creator/vehicle-api-docker/blob/main/vehicle-api.postman_collection.json) and goto the `POST /api/login_check` endpoint to get a JWT token. 
   1. For User Access: `"username": "user", "password": "userpass"`
   2. For Admin Access: `"username": "admin", "password": "adminpass"` 
2. Copy the token, goto the `Vehicle API` collection and add the token to the `Authorization` header as `Bearer Token`.
3. You can now use the other endpoints to make requests.


## API Documentation
You can download the Postman Collection [here](https://github.com/jack-the-creator/vehicle-api-docker/blob/main/vehicle-api.postman_collection.json).

Click for more details 👇

<details>
<summary>1. Endpoint for retrieving all the vehicle makers which are manufacturing a specific type of vehicle.  </summary>

**GET** `/api/make`

**SECURITY**: `ROLE_USER`

Query parameter:
- type

Accepted vehicle types: `[ "car", "motorbike", "truck" ]`

Example:
```
/api/make?type=car
```
Expected response:
```
[
    {
        "id": 1,
        "name": "ford"
    },
    {
        "id": 2,
        "name": "honda"
    },
    {
        "id": 3,
        "name": "toyota"
    }
]
```
</details>

<details>
<summary>2. Endpoint for retrieving all the technical details of a specific vehicle. </summary>

**GET** `/api/vehicle/{id}`

**SECURITY**: `ROLE_USER`

Route parameter:
- `id` where this is the Id of the Vehicle whose details you want to see

Example:
```
/api/vehicle/1
```
Expected response:
```
{
    "id": 1,
    "name": "Mustang GT",
    "year": 2020,
    "make": {
        "id": 1,
        "name": "ford"
    },
    "type": {
        "id": 1,
        "name": "car"
    },
    "vehicleSpecs": [
        {
            "id": 31,
            "value": "155",
            "specParameter": {
                "id": 31,
                "name": "top_speed",
                "unit": "mph"
            }
        },
        // etc
    ]
}
```
</details>

<details>
<summary>3. Endpoint for updating a specific technical parameter of a vehicle. </summary>

**PATCH** `/api/vehicle/{id}/specs/{parameterName}`

**SECURITY**: `ROLE_ADMIN`

Route parameter:
- `id` Id of the Vehicle
- `parameterName` Name of the technical parameter to be updated

Accepted parameter names: `[ 
'top_speed',
'horsepower',
'torque',
'engine_capacity',
'fuel_type',
'transmission',
'weight',
'length',
'width',
'height' ]`



Request body:
- `value` New value of the technical parameter

Example:
```
/api/vehicle/1/specs/top_speed

{
    "value": "150"
}
```
Expected response:
```
{
    "id": 1,
    "value": "150",
    "vehicle": {
        "id": 1
    },
    "specParameter": {
        "id": 1,
        "name": "top_speed",
        "unit": "mph",
        "datatype": "int"
    }
}
```
</details>

<details>
<summary>Bonus: Get all Vehicles.  </summary>

**GET** `/api/vehicles`

**SECURITY**: `ROLE_USER`

Created this endpoint super quickly to get all vehicles for testing purposes.

Example:
```
/api/vehicles
```
Expected response:
```
[
    {
        "id": 1,
        "name": "Mustang GT",
        "year": 2020,
        "make": {
            "id": 1,
            "name": "ford"
        },
        "type": {
            "id": 1,
            "name": "car"
        }
    },
    // etc
]
```
</details>

**Other endpoints to consider:**
- Create (POST) a new Vehicle
- Update (PUT) a Vehicle (may want to update multiple or all fields at once)
- Delete Vehicle
- Create a new Vehicle Make
- Create a new Vehicle Type
- Get all Vehicle Spec Parameters (client-side application may want a list to choose to add to their new vehicle)
- Create a new Vehicle Spec (client-side might want to manually add each detail for a vehicle)
- Delete Vehicle Spec
- Create a new Vehicle Spec Parameter (e.g. number of doors)

## Entities
<details>
<summary>User</summary>

The `Vehicle` class is a simple class used for authentication.

- id (int)
- username (string)
- password (string)
- roles (`ROLE_USER` and `ROLE_ADMIN`)
</details>

<details>
<summary>Vehicle</summary>

The `Vehicle` class holds some basic details of a vehicle; linking to the `VehicleMake` and `VehicleType` classes.

- id (int)
- name (string)
- year (int)
- make (ManyToOne with VehicleMake)
- type (ManyToOne with VehicleType)
- vehicleSpecs (OneToMany with VehicleSpec)
</details>

<details>
<summary>Vehicle Make</summary>

The `VehicleMake` class simply contains the name of the vehicle manufacturer.

- id (int)
- name (string)
- vehicles (OneToMany with Vehicle)
</details>

<details>
<summary>Vehicle Spec</summary>

The `VehicleSpec` class holds the value of a technical parameter for a vehicle, 
linked to one specific parameter (unit of measure) from the `VehicleSpecParameter` class.

- id (int)
- value (string)
- vehicle (ManyToOne with Vehicle)
- specParameter (ManyToOne with VehicleSpecParameter)
</details>

<details>
<summary>Vehicle Spec Parameter</summary>

The `VehicleSpecParameter` class is to be treated as a lookup table for technical parameters and their corresponding units and data types. 

All types of technical parameters that a vehicle can have are stored in this table. For example, top speed, horsepower, torque etc. When a new technical parameter is needed, it should be added to this table.

The data type is used to validate the value of the technical parameter (stored in the `VehicleSpec` class) to ensure the data is of the correct type. With top speed for example, we want `100` not `one hundred`!

You can see this custom validation in action in the `ValidVehicleSpecValue` constraint and `VehicleSpecValueValidator` validator.

- id (int)
- name (string)
- unit (string)
- datatype (string)
- vehicleSpecs (OneToMany with VehicleSpec)
</details>

**Further considerations:**
- We many want to create an association between `VehicleSpecParameter` and `VehicleType` to make sure that certain technical parameters are only available for certain types of vehicles. For example, *number of doors* would not be applicable for motorbikes.
- For different locales, we would need to consider adjusting the unit and value of the technical parameters. For example, the top speed may be required in kilometres per hour, not miles per hour.
- Following from this, we may want to create a lookup table for the different unit types too.
- When creating new `VehicleSpecParameter`s, we may want to include some validation for actual data types OR create a new lookup table with each data type inside it.

## Services and Event Listeners
<details>
<summary>VehicleService</summary>

The VehicleService class is responsible for handling and updating Vehicle data. 
It does not handle responses but throws exceptions when necessary (and these are caught by the ApiExceptionListener to
return the relevant status code and message).

This class only depends on what is needed (EntityManagerInterface and ValidatorInterface) and makes it very easy to test.
See VehicleServiceTest for examples.
</details>

<details>
<summary>ApiExceptionListener</summary>

This is something new I tried during this challenge. 
Typically, when validating route/query parameters and the request body, I would do `instanceof` checks throughout the 
endpoint method itself or validate and throw exceptions in the service classes; further handling them with a try/catch 
in the endpoint method and returning the relevant status code and message.

Due to there being lots of similar checks for valid and existing entities, I decided to create a custom event listener 
that would handle all thrown exceptions and would return the relevant status code and message.

This follows SOLID principles, making it easy to expand upon and maintain when throwing new exceptions.
It also helped with reducing the complexity and amount of code in the endpoint methods and removing the need for 
try/catch blocks altogether!

</details>

## Continuous Integration (CI)

This project uses GitHub Actions to run automated tests and checks on every push to main or on pull requests.

Workflow Overview:

1. Build Docker images for the PHP/Symfony environment.
2. Start services (PHP, database, Mercure, etc.) using Docker Compose.
3. Check service reachability to ensure the containers are running.
4. Create a test database and run migrations to sync the schema.
5. Load fixtures with sample data for tests.
6. Run PHPUnit for unit and functional tests.
7. Validate Doctrine schema to make sure entities match the database.

Benefits

1. Detects failing tests before merging code.
2. Ensures consistent environment between developers and CI.
3. Catches database schema mismatches early (this actually caught me when I pushed haha!)
4. Guarantees that the application works inside Docker.

## Credits

[Base Symfony Docker Template](https://github.com/dunglas/symfony-docker) (Ref: [Using Docker with Symfony](https://symfony.com/doc/current/setup/docker.html))
