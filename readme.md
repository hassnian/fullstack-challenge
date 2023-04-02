

<!-- GETTING STARTED -->
## Getting Started



#### I had some issues with setting up docker on my M1 mac, i dont have another pc to test other enviorments. I have tested the application on a windows machine and it works fine. I have also tested the application on a linux machine and it works fine. I have not tested the application on a mac with intel chip. 

### Prerequisites

You will need to have docker installed on your machine.

### Installation

1. The following API keys are required to run the application:
    1. News API: https://newsapi.org/
    2. New York Times: https://developer.nytimes.com/
    3. The Guardian: https://open-platform.theguardian.com/access/
2. Copy paste the /server/.env.example to /server/.env
    ```sh
    cp ./server/.env.example ./server/.env
    ```
3. Update the /server/.env  with the API keys
    ```sh
    NEWS_API_KEY=ENTER YOUR API
    NY_TIMES_API_KEY=ENTER YOUR API
    THE_GUARDIAN_API_KEY=ENTER YOUR API
    ```
   
4. Copy paste the /client/.env.example to /client/.env
    ```sh
    cp ./client/.env.example ./client/.env
    ```
5. Run the following command to build the docker image:
    ```sh
    docker-compose up --build
    ```
6. Install composer dependencies
    ```sh
    docker-compose run composer install
    ```
7. Run the following command to setup the application:
   1. Generate JWT Secret key
      ```sh
      docker-compose run artisan jwt:secret
      ```
   2. Migrate the db
       ```sh
        docker-compose run artisan migrate
       ```
   3. Seed the db
        ```sh
         docker-compose run artisan db:seed
        ```
   4. Index models in elasticsearch
        ```sh
         docker-compose run artisan app:index-models
        ```
   5. You will need to manually run the data sources run at least once to populate the database
        ```sh
        docker-compose run artisan app:feed-news-data-source
        ```
8. The front end should be available at the following URL in your browser:
    ```sh
    http://localhost:3000/
    ```
   
