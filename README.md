# Minaria

Minaria is a medieval-themed web management and exploration game built with PHP, HTML and CSS. Players manage resources, construct buildings and discover new regions in a responsive interface with three visual modes (visual, simple and ultrasimple).

---

## Description

- Resource management (stone, metal, wood, food, gold, mana).
- Building construction with level and resource requirements.
- Region exploration with exponentially increasing times.
- Adaptable interface: visual, simple and ultrasimple modes (ultrasimple has no padding in tables and compact buttons, reminiscent of an MS-DOS screen).
- Modular code: UI functions in `funciones-div.php`, business logic in `funciones.php`, and database configuration in `config.php`.

---

## Installation

1. Clone the repository
   ```bash
   git clone https://github.com/dvdMonedero/minaria.git
   cd minaria
   ```
2. Install dependencies (the `/vendor/` directory is ignored if you use Composer in the future).
3. Create an environment file
   ```bash
   cp .env.example .env   # or create one manually
   ```
   The `.env` file should contain:
   ```text
   DB_HOST=your_host
   DB_NAME=your_database
   DB_USER=your_user
   DB_PASS=your_password
   ```
4. Import the database
   ```bash
   mysql -u your_user -p your_database < minaria.sql
   ```
5. Configure the web server
   - Place the folder in the document root of Apache/Nginx.
   - Ensure PHP 8.4+ is enabled.

---

## Usage

- Access `reino.php` for the kingdom overview.
- From there, navigate to `region.php?idr=XX` to manage a specific region.
- Change the visual mode using the buttons in the footer (Visual, Simple, Ultrasimple). The selected mode is stored in the session and a cookie.

---

## Configuration

- **Database:** Managed in `config.php` via environment variables.
- **Visual mode:** Stored in `$_SESSION['modo_estilo']` and can be overridden with the `?modo=ultrasimple` URL parameter.
- **Ignored files:** `.env`, `.htaccess`, `/vendor/`, logs and IDE files are listed in `.gitignore`.

---

## Contributing

1. Fork the repository.
2. Create a branch for your feature or bugfix:
   ```bash
   git checkout -b feature/new-feature
   ```
3. Make changes and ensure everything works.
4. Open a Pull Request describing your changes.

---

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.

---

## Contact

Author: David Monedero
GitHub: [dvdMonedero](https://github.com/dvdMonedero)
