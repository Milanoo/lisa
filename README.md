Sure, here’s the revised README file:

---

# LISA Web Application

This is a web application for analyzing and producing comparative reports using the open API available at lisa.mofaga.gov.np. The project is dockerized for easy deployment.

## Features

- Analyze and produce comparative reports.
- Interactive radar chart for visualizing data.
- PHP backend with JSON data handling.
- Dockerized for easy deployment.

## Project Structure

```
my-php-project/
├── data/
│   ├── LISA_summary_fiscal_year_4.json
│   └── ...
├── docker-compose.yml
├── Dockerfile
├── index.php
├── script.js
└── style.css
```

## Requirements

- Docker Desktop
- Web browser

## Getting Started

### Clone the Repository

```sh
git clone https://github.com/Milanoo/lisa.git
cd lisa
```

### Using Docker

#### Pull the Docker Image

For easy deployment, you can pull the pre-built Docker image from Docker Hub:

```sh
docker pull milanosth/lisa-web:latest
```

#### Run the Docker Container

```sh
docker run -d -p 8080:80 milanosth/lisa-web:latest
```

This will start the web application on `http://localhost:8080`.

### Building the Docker Image Locally

If you prefer to build the Docker image locally, follow these steps:

#### Build the Docker Image

```sh
docker-compose build
```

#### Run the Docker Container

```sh
docker-compose up
```

This will start the web application on `http://localhost:8080`.

### Accessing the Application

Open your web browser and navigate to `http://localhost:8080` to access the LISA web application.

## Contributing

Contributions are welcome! Please fork the repository and create a pull request with your changes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

If you have any questions or issues, please feel free to contact me at [milanosth@gmail.com](mailto:milanosth@gmail.com).
