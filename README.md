# Laravel E-commerce API

## Overview
This Laravel-based E-commerce API provides core functionalities for managing users, categories, products, cart, and orders. The API supports role-based access control, authentication, localization, and structured API responses.

## Features
- **User Authentication** (Register, Login, Logout)
- **Category Management** (CRUD operations, Localization)
- **Product Management** (CRUD operations, Pagination, Localization)
- **Cart Management** (Add, Update, Remove, Clear cart)
- **Order Processing** (Order creation, Payment integration, Order tracking)
- **Localization** (Support for English & Arabic via `Accept-Language` header)

## Installation
### 1. Clone the Repository
```sh
git clone <repository_url>
cd e-commerce-api
```

### 2. Install Dependencies
```sh
composer install
```

### 3. Configure Environment
Rename `.env.example` to `.env` and update the necessary environment variables (database, mail, etc.).
```sh
cp .env.example .env
```

### 4. Generate Application Key
```sh
php artisan key:generate
```

### 5. Set Up the Database
```sh
php artisan migrate --seed
```

### 6. Serve the Application
```sh
php artisan serve
```
Your API will be available at `http://127.0.0.1:8000/api`

## API Documentation
All API endpoints are available in the **Postman Collection** (`E-commerce API.postman_collection.json`). Import this file into Postman for testing.

### Authentication
- **POST** `/api/register` - Register a new user
- **POST** `/api/login` - Authenticate and get a token
- **GET** `/api/user` - Retrieve authenticated user details
- **POST** `/api/logout` - Logout the user

### Category Management
- **POST** `/api/categories` - Create a category
- **GET** `/api/categories` - Retrieve all categories
- **GET** `/api/categories/{id}` - Retrieve a category by ID
- **PUT** `/api/categories/{id}` - Update category details
- **DELETE** `/api/categories/{id}` - Delete a category

### Product Management
- **POST** `/api/products` - Create a product
- **GET** `/api/products` - Retrieve all products (paginated)
- **GET** `/api/products/{id}` - Retrieve a product by ID
- **PUT** `/api/products/{id}` - Update product details
- **DELETE** `/api/products/{id}` - Delete a product

### Cart Management
- **POST** `/api/cart` - Add a product to the cart
- **GET** `/api/cart` - Retrieve all cart items
- **PUT** `/api/cart/{id}` - Update cart item quantity
- **DELETE** `/api/cart/{id}` - Remove a product from the cart
- **POST** `/api/cart/clear` - Clear the cart

### Order Management
- **POST** `/api/orders` - Create an order
- **GET** `/api/orders` - Retrieve all orders
- **GET** `/api/orders/{id}` - Retrieve order details
- **PUT** `/api/orders/{id}/status` - Update order status

## Localization
This API supports localization via the `Accept-Language` header:
- **`Accept-Language: en`** - Returns responses in English
- **`Accept-Language: ar`** - Returns responses in Arabic
- Defaults to English if no header is provided.

## Security & Authorization
- Token-based authentication using Laravel Sanctum.
- RBAC (Role-Based Access Control) for users and admin.
- Secure API responses with proper validation.

## Contribution
Feel free to submit pull requests or issues to improve this project.

## License
This project is licensed under the MIT License.

## Contact
For inquiries, reach out to `dev-submissions@it-trendco.com`.

