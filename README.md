# Sanova Full-Stack Food Ordering Website

## Included pages
- Home: `index.php`
- Restaurant / Menu: `menu.php`
- Cart: `cart.php`
- Checkout: `checkout.php`
- Order tracking: `track.php`
- Login / Register: `login.php`
- Customer account: `account.php`
- Admin dashboard + Orders + Menu Items + Categories + Customers + Reports + Settings: `admin/`

## XAMPP setup
1. Copy the `Sanova_Fullstack` folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** in XAMPP.
3. Open phpMyAdmin: `http://localhost/phpmyadmin`.
4. Import `sql/sanova.sql`.
5. Open `http://localhost/Sanova_Fullstack/`.

## Demo accounts
- Admin: `admin@sanova.test` / `admin123`
- Customer: `customer@sanova.test` / `user123`

## Payment note
The card / online payment interfaces are simulations for a university project. Card fields do not have `name` attributes and are never submitted or stored. Cash on Delivery is also available.

## Database settings
Default XAMPP values are in `config.php`:
- host: `127.0.0.1`
- database: `sanova`
- user: `root`
- password: blank

Change those constants if your MySQL setup is different.
