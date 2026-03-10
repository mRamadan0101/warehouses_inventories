## Warehouse Inventories API Documentation

RESTful API for managing stock across warehouses, listing item quantities, and handling stock transfers with low-stock alerts.

### Base URL

- **Local**: `http://localhost:8000`
- **API Prefix**: all routes are under `api`, e.g. `http://localhost:8000/api/login`

---

## Authentication

- The API uses **Laravel Sanctum**.
- After a successful login, an **access token** is generated and must be sent with every protected request.

### Required Headers

- **Authorization**: `Bearer {token}`
- **Accept**: `application/json`

### Standard Response Format

All responses are wrapped using `ApiResponseHelper` with a consistent structure:

- **Success responses**:

```json
{
  "status": "success",
  "message": "Login successful",
  "code": 200,
  "data": { ... }
}
```

- **Error responses**:

```json
{
  "status": "error",
  "message": "Invalid credentials",
  "code": 401
}
```

---

## Endpoints Summary

- **POST** `/api/login`  
  Authenticate a user and retrieve an access token.

- **GET** `/api/inventory`  
  List inventory for all warehouses with filtering and pagination.

- **GET** `/api/warehouses/{id}/inventory`  
  Get detailed inventory for a single warehouse.

- **POST** `/api/stock-transfers`  
  Create a stock transfer between two warehouses (requires `admin` role).

> Note: all routes except `/login` require `Authorization: Bearer {token}`.

---

## 1. Login

- **URL**: `/api/login`
- **Method**: `POST`
- **Auth**: no token required.

### Request Body

```json
{
  "email": "user@example.com",
  "password": "secret"
}
```

**Validation** (`LoginRequest`):
- `email`: `required|email|exists:users,email`
- `password`: `required|string`

### Successful Response

```json
{
  "status": "success",
  "message": "Login successful",
  "code": 200,
  "data": {
    "id": 1,
    "name": "Admin",
    "email": "admin@example.com",
    "role": {
      "id": 1,
      "name": "admin",
      "permissions": [ ... ]
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxx"
  }
}
```

Use the `data.token` value in the `Authorization` header:

```http
Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxx
```

---

## 2. Get Inventory (all warehouses)

- **URL**: `/api/inventory`
- **Method**: `GET`
- **Auth**: requires token (Sanctum).

### Query Parameters (optional)

- `per_page` (int, default 10): items per page.
- `page` (int, default 1): page number.
- `warehouse_id` (int): filter by a specific warehouse.
- `name` (string): filter by item name.
- `price_from` (float): minimum item price.
- `price_to` (float): maximum item price.

### Example Request

`GET /api/inventory?per_page=10&page=1&name=iphone&price_from=1000&price_to=5000`

### Successful Response (excerpt)

```json
{
  "status": "success",
  "code": 200,
  "data": [
    {
      "id": 1,
      "name": "Main Warehouse",
      "items": [
        {
          "id": 10,
          "name": "Item A",
          "description": "Item description",
          "price": 120.5,
          "sku": "SKU-001",
          "quantity": 50,
          "inventory_details": [
            {
              "id": 100,
              "warehouse_id": 1,
              "item_id": 10,
              "quantity": 50,
              "alert_level": 10
            }
          ]
        }
      ]
    }
  ]
}
```

`data` is a collection of `WarehouseResource` using `WarehouseResourceCollection`.

---

## 3. Get Warehouse Inventory (single warehouse)

- **URL**: `/api/warehouses/{id}/inventory`
- **Method**: `GET`
- **Auth**: requires token.

### URL Parameters

- `id` (int): warehouse ID.

### Query Parameters (optional)

- `name` (string): filter by item name.
- `price_from` (float)
- `price_to` (float)

### Example Request

`GET /api/warehouses/1/inventory?name=keyboard`

### Successful Response (excerpt)

```json
{
  "status": "success",
  "code": 200,
  "data": {
    "id": 1,
    "name": "Main Warehouse",
    "items": [
      {
        "id": 5,
        "name": "Keyboard",
        "description": "Mechanical keyboard",
        "price": 300,
        "sku": "KB-001",
        "quantity": 20,
        "inventory_details": [
          {
            "id": 200,
            "warehouse_id": 1,
            "item_id": 5,
            "quantity": 20,
            "alert_level": 5
          }
        ]
      }
    ]
  }
}
```

---

## 4. Stock Transfer (between warehouses)

- **URL**: `/api/stock-transfers`
- **Method**: `POST`
- **Auth**:
  - requires token.
  - requires `admin` role via `has_role:admin` middleware.

### Request Body

```json
{
  "from_warehouse_id": 1,
  "to_warehouse_id": 2,
  "item_id": 10,
  "quantity": 5
}
```

**Validation** (`StocktransferRequest`):

- `from_warehouse_id`: `required|exists:warehouses,id`
- `to_warehouse_id`: `required|exists:warehouses,id|different:from_warehouse_id`
- `item_id`: `required|exists:items,id`
- `quantity`: `required|integer|min:1`

### Business Rules

- Check that the source warehouse (`from_warehouse_id`) has enough stock in `ItemInventory`.
- Decrement quantity from the source warehouse and increment it in the destination warehouse.
- If the remaining quantity in the source warehouse is less than or equal to `alert_level`, the `LowStockDetected` event is dispatched.

### Successful Response

```json
{
  "status": "success",
  "message": "Transfer completed successfully",
  "code": 201,
  "data": {
    "id": 1,
    "from_warehouse_id": 1,
    "to_warehouse_id": 2,
    "item_id": 10,
    "quantity": 5,
    "user_id": 1,
    "type": "transfer",
    "created_at": "2026-03-11T10:00:00Z",
    "updated_at": "2026-03-11T10:00:00Z"
  }
}
```

### Error Responses

- **Insufficient stock**:

```json
{
  "status": "error",
  "message": "Insufficient stock",
  "code": 400
}
```

---

## Error Handling & Status Codes

- `200` – Successful read operations (GET, login).
- `201` – Resource created successfully (stock transfer).
- `400` – Bad request (e.g. insufficient stock, invalid input).
- `401` – Authentication failed (missing/invalid token or bad credentials).
- `403` – Forbidden (user does not have the required role/permissions).
- `404` – Resource not found (e.g. warehouse not found).

---

## Quick Start (Postman / Insomnia)

1. Call `POST /api/login` to obtain a token.
2. Add headers:  
   - `Authorization: Bearer {token}`  
   - `Accept: application/json`
3. Test:
   - `GET /api/inventory`
   - `GET /api/warehouses/{id}/inventory`
   - `POST /api/stock-transfers` with valid payload.

This `README.md` serves as the main reference for the warehouse inventories API.
