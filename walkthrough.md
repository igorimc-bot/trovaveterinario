# Deployment and Verification Instructions

Since the environment is on a dedicated server, please follow these steps to finalize the installation.

## 1. Database Setup
1.  Access your hosting control panel (e.g., cPanel, phpMyAdmin).
2.  Open or create the database `trovaveterinario_db`.
3.  **Import the original dump**: Import `full_dump_v2.sql` (or `v1`) first to set up the structure and location data.
4.  **Run the update script**:
    *   Upload all project files to the server.
    *   **CRITICAL**: Run `composer install` on the server to install dependencies. If you cannot run composer, upload the `vendor` folder from your local machine.
    *   Visit `https://trovaveterinario.com/install_db.php` in your browser.
    *   This script will truncate the old `servizi` table and insert the new Veterinarian services.
    *   **Security Note**: Delete `install_db.php` and `setup_trovaveterinario.sql` after successful execution.

## 2. Configuration Check
- Verify `.env` on the server has the correct credentials (already updated in the local file):
  ```
  DB_HOST=localhost
  DB_NAME=trovaveterinario_db
  DB_USER=trovaveterinario_us3r
  DB_PASS=3mP0?k^yWx9Wrekv
  ```

## 3. Manual Verification
- [ ] **Homepage**: Check that the title says "Trova Veterinario".
- [ ] **Search**: Try searching for a location (e.g., "Milano") and verify the Services dropdown shows "Veterinario per Cani e Gatti", etc.
- [ ] **Legal Pages**: Check the footer links for Privacy Policy and Terms to ensure "Aste Giudiziarie" is replaced.

## 4. Troubleshooting
- If you see "Database Connection Errors", double-check the `.env` permissions and credentials.
- If the services list is empty, re-run the `install_db.php` script or manually import `setup_trovaveterinario.sql`.
