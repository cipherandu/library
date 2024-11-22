# Library API
This is an API Documentation for the Activity Library API written by Lorellie Culbengan Tuyan Section 4D

# API Documentation

## Table of Contents
1. Introduction

As part of the requirements for the subject System Integration and Architecture 2, Lorellie C. Tuyan has developed a simple activity showcasing 10 API endpoints designed specifically for a Library System. These endpoints enable core functionalities such as adding new books and authors to the database, authenticating library users, deleting records of outdated or irrelevant entries, and updating existing information, such as book titles or author details. A key feature of this system is the implementation of token rotation, where each POST request sent via ThunderClient generates a new token for the next transaction. The tokens are designed to remain active for only 5 minutes, enhancing security by limiting their validity. 

2. API Endpoints
   a. /user/registration
   b. /user/authentication
   c. /displayColllection
   d. /addBookAuthor
   e. /displayBook
   f. /displayAuthor
   g. /updateBook
   h. /updateAuthor
   i. /deleteBook
   j. /deleteAuthor




