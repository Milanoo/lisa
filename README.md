---
## About

### Project Description

This project is designed to fetch and analyze data from the LISA (Local Institution Strength Assessment) system provided by the Ministry of Federal Affairs and General Administration of Nepal. The data, accessed through the API `https://lisa.mofaga.gov.np/backend/api/reports/summary?fiscal_year_id={index starting at 1}`, is used to produce comparative reports and visualizations of local government performance across various fiscal years.

### Key Features

- **Data Fetching**: Automatically fetches data from the LISA API for different fiscal years.
- **Interactive Visualization**: Provides an interactive radar chart for comparing the performance of different local governments (LGs) based on various categories.
- **Comparison Table**: Displays a comparison table with detailed scores for each category, highlighting differences between selected LGs.
- **Responsive Design**: Ensures a user-friendly interface for analyzing and comparing data on different devices.

### Technology Stack

- **Frontend**: HTML, CSS, JavaScript, Chart.js for visualizations.
- **Backend**: PHP for data processing and API interactions.
- **Containerization**: Docker for easy deployment and setup.

## Project Structure

```
LISA/
├── css/
├── data/
│   ├── LISA_summary_fiscal_year_4.json
│   └── ...
├── vendor/
├── docker-compose.yml
├── Dockerfile
├── index.php
└── script.js
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
