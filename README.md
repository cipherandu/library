# LITERA
This is an API Documentation for the Activity Library I personally named `LITERA` API written by Lorellie Culbengan Tuyan, Bachelor of Information Technology - Section 4D

# API Documentation


## Introduction

      As part of the requirements for the subject System Integration and Architecture 2, Lorellie C. Tuyan has developed a simple activity showcasing 10 API endpoints designed specifically for a Library System. These endpoints enable core functionalities such as adding new books and authors to the database, authenticating library users, deleting records of outdated or irrelevant entries, and updating existing information, such as book titles or author details. A key feature of this system is the implementation of token rotation, where each POST request sent via ThunderClient generates a new token for the next transaction. The tokens are designed to remain active for only 5 minutes, enhancing security by limiting their validity. 

## API Endpoints
  <pre>
      - /user/registration
      - /user/authentication
      - /displayColllection
      - /addBookAuthor
      - /displayBook
      - /displayAuthor
      - /updateBook
      - /updateAuthor
      - /deleteBook
      - /deleteAuthor
  </pre>

# Endpoint: User Registration

#### URL
`POST /user/registration`

### Description
This endpoint allows a new user to register by providing a `username` and `password`. Upon successful registration, a new user account will be created.

### Request Body

**JSON**
  <pre>
    {
      "username": "admin",
      "password": "me"
    }
  </pre>

**RESULT**
  <pre>
    {
      "status": "success",
      "data": null
    }
  </pre>

# Endpoint: User Authentication

#### URL
`POST /user/authentication`

### Description
This endpoint is used to authenticate a user. You must provide a valid `username` and `password` in the request body to receive a successful response and will provide a token to be used in the next transaction

### Request Body

**JSON**
  <pre>
    {
      "username": "admin",
      "password": "me"
    }
  </pre>

**RESULT**
  <pre>
    {
      "status": "success",
      "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI3OTgyNSwiZXhwIjoxNzMyMjgwMTI1LCJkYXRhIjp7InVzZXJfaWQiOjZ9fQ.4h2xWTTLV3VyQZBN-MRLyXgYe6BEXMBKPsn04V3G3Kc"
      }
    }
  </pre>

# Endpoint: Display Collection

#### URL
`POST /displayCollection`

### Description
This endpoint retrieves a collection of books along with their corresponding authors. It provides an overview of each book, including the book's title and the bok's author.

### Request Body

**JSON**
  <pre>
    {
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MDI1MywiZXhwIjoxNzMyMjgwNTUzLCJkYXRhIjp7InVzZXJfaWQiOjZ9fQ._fs1NMExlHvXxN9391YzRTAdeOK30UhwxNGOM6rvfZc"
    }
  </pre>

**RESULT**
  <pre>
    {
      "status": "success",
      "data": {
        "collections": [
          {
            "collection_id": 6,
            "book_title": "DMMMSU-GOOD",
            "author_name": "Loleley"
          },
          {
            "collection_id": 8,
            "book_title": "System Integ",
            "author_name": "Einstein"
          }
        ],
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MDI1MywiZXhwIjoxNzMyMjgwNTUzLCJkYXRhIjp7InVzZXJfaWQiOjZ9fQ._fs1NMExlHvXxN9391YzRTAdeOK30UhwxNGOM6rvfZc"
      }
    }
  </pre>

# Endpoint: Adding of Book Author

#### URL
`POST /addBookAuthor`

### Description
This endpoint allows you to add a new book along with its author to the collection. Upon successful creation, a token will be returned that can be used for subsequent transactions

### Request Body

**JSON**
  <pre>
    {
      "title_of_book":"API Documentation",
      "name_of_author":"Tuyan",
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MDk5OCwiZXhwIjoxNzMyMjgxMjk4LCJkYXRhIjp7InVzZXJfaWQiOjZ9fQ.MMbFM5shXZ2hdsXtvHmAAnqRMNUE-Wvo2xiYgbYarbQ"
    }
  </pre>

**RESULT**
  <pre>
    {
      "status": "success",
      "data": {
        "message": "very good",
        "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MTAwOSwiZXhwIjoxNzMyMjgxMzA5LCJkYXRhIjp7InN0YXR1cyI6ImFjdGl2ZSJ9fQ.FDgEIK30_Udtj46Z6MUWhhRB4Om76-mHqktoyGXGanI"
      }
    }
  </pre>

# Endpoint: Display Book

#### URL
`POST /displayBook`

### Description
This endpoint retrieves a list of books in the collection, a token will be returned that can be used for subsequent transactions

### Request Body

**JSON**
  <pre>
    {
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMDk0NjUyNiwiZXhwIjoxNzMwOTQ2ODI2LCJkYXRhIjp7InVzZXJfaWQiOjN9fQ.g1yOCLyxCwzYKa-TBWwMgPRFt8A0O0nwZ4bHijHTrjM"
    }
  </pre>

**RESULT**
  <pre>
    {
      "status": "success",
      "data": {
        "books": [
          {
            "book_id": 9,
            "book_title": "CIT"
          },
          {
            "book_id": 10,
            "book_title": "DMMMSU-GOOD"
          },
          {
            "book_id": 11,
            "book_title": "DMMMSU"
          },
          {
            "book_id": 12,
            "book_title": "System Integ"
          },
          {
            "book_id": 13,
            "book_title": "API Documentation"
          }
        ],
        "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MTgxOCwiZXhwIjoxNzMyMjgyMTE4LCJkYXRhIjp7InN0YXR1cyI6ImFjdGl2ZSJ9fQ.nKHwOllQwoLNB7YHtXn632UCoTpYcjalEKVyqLPj0Fg"
      }
    }
  </pre>

# Endpoint: Display Author

#### URL
`POST /displayAuthor`

### Description
This endpoint retrieves details about a specific author, a token will be returned that can be used for subsequent transactions

### Request Body

**JSON**
  <pre>
    {
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MjI1NCwiZXhwIjoxNzMyMjgyNTU0LCJkYXRhIjp7InVzZXJfaWQiOjZ9fQ.WYtmdeiPScEY2dppc30q3fRS7da7dKfYfrj2FY_Cw_c"
    }
  </pre>

**RESULT**
  <pre>
    {
      "status": "success",
      "data": {
        "authors": [
          {
            "author_id": 5,
            "author_name": "MADELLA"
          },
          {
            "author_id": 6,
            "author_name": "Loleley"
          },
          {
            "author_id": 8,
            "author_name": "Einstein"
          },
          {
            "author_id": 9,
            "author_name": "Tuyan"
          }
        ],
        "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MjI3MCwiZXhwIjoxNzMyMjgyNTcwLCJkYXRhIjp7InN0YXR1cyI6ImFjdGl2ZSJ9fQ.2T4d4tufor2N9m4iyUSH8ghd7N4x-Sd9aGI3NlQ9-sA"
      }
    }
  </pre>

# Endpoint: Update Book

#### URL
`POST /updateBook`

### Description
This endpoint updates the details of a specific book in the collection. Need to check the Collection Id of the book to be change and just enter the New Book Title together with the new token.

### Request Body

**JSON**
  <pre>
    {
      "book_id": "5",
      "new_book_title": "APPLICATION PROGRAMMING INTERFACE",
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MjUxOSwiZXhwIjoxNzMyMjgyODE5LCJkYXRhIjp7InVzZXJfaWQiOjZ9fQ.hyH5U6rT-DEyzhgDq8gF7Hqk51Zgwz3LIWBwiK0gsMU"
    }
  </pre>

**RESULT**
  <pre>
    {
      "status": "success",
      "data": {
        "message": "Successfully updated an author from records.",
        "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MjU3NywiZXhwIjoxNzMyMjgyODc3LCJkYXRhIjp7InN0YXR1cyI6ImFjdGl2ZSJ9fQ.wO0xTdgDMg5FGIElrvQokVX90IgiggzSEmY_dM7NYQE"
      }
    }
  </pre>

# Endpoint: Update Author

#### URL
`POST /updateAuthor`

### Description
This endpoint updates the details of a specific author in the collection. Need to check the Collection Id of the book to be change and just enter the New book author together with the new token.

### Request Body

**JSON**
  <pre>
    {
      "author_id": "5",
      "new_author_name": "SLIM",
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MjkxNSwiZXhwIjoxNzMyMjgzMjE1LCJkYXRhIjp7InVzZXJfaWQiOjZ9fQ.1_IuK2ErebVMejgGCcmircOKbdS5K_dhmjQku8Ogu6w"
    }
  </pre>

**RESULT**
  <pre>
    {
      "status": "success",
      "data": {
        "message": "Successfully updated an author from records.",
        "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4Mjk2MCwiZXhwIjoxNzMyMjgzMjYwLCJkYXRhIjp7InN0YXR1cyI6ImFjdGl2ZSJ9fQ.R-HKAXRLYpAOQbYbUhm2OS-uXOJu4VpOyE1Wp9yRz1s"
      }
    }
  </pre>

# Endpoint: Delete Book

#### URL
`POST /deleteBook`

### Description
This endpoint deletes a specific book from the collection. Need to check the Collection Id of the book to be deleted

### Request Body

**JSON**
  <pre>
    {
      "book_id": "5",
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MzE3MCwiZXhwIjoxNzMyMjgzNDcwLCJkYXRhIjp7InVzZXJfaWQiOjZ9fQ.2RNem2NzgbNPuZ30fXOSbq1O_TBVFCwy-Fu8awslef8"
    }
  </pre>

**RESULT**
  <pre>
    {
      "status": "success",
      "data": {
        "message": "Successfully deleted a book from records.",
        "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MzE5NSwiZXhwIjoxNzMyMjgzNDk1LCJkYXRhIjp7InN0YXR1cyI6ImFjdGl2ZSJ9fQ.OwX741ttlZHTy1lTNtuqcdYDr1ZXWytkPAkpROLPeQQ"
      }
    }
  </pre>

# Endpoint: Delete Author

#### URL
`POST /deleteAuthor`

### Description
This endpoint deletes a specific author from the collection. Need to check the Collection Id of the author to be deleted

### Request Body

**JSON**
  <pre>
    {
      "author_id": "5",
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MzMxNSwiZXhwIjoxNzMyMjgzNjE1LCJkYXRhIjp7InVzZXJfaWQiOjZ9fQ.5S1XD-ruGPurSrTal4Msu0K7qNX-PhiBoMKLZJKznUo"
    }
  </pre>

**RESULT**
  <pre>
    {
      "status": "success",
      "data": {
        "message": "Successfully deleted an author from records.",
        "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vc2VjdXJpdHkub3JnIiwiYXVkIjoiaHR0cDovL3NlY3VyaXR5LmNvbSIsImlhdCI6MTczMjI4MzMzNSwiZXhwIjoxNzMyMjgzNjM1LCJkYXRhIjp7InN0YXR1cyI6ImFjdGl2ZSJ9fQ.HfiAQxFTavQDjStwq0pXG3np52TuMd08GDcUeYmHODU"
      }
    }
  </pre>

***Token Usage***
The token is for one-time use only. This message will appear if a used token is submitted.

  <pre>
    {
      "status": "fail",
      "data": {
        "title": "Token already used."
      }
    }
  </pre>