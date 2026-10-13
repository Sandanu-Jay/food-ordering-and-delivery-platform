<?php
// SANOVA RESTAURANT
// Readable source: formatting/comments only; original logic preserved.

session_start();

const DB_HOST = '127.0.0.1';
const DB_NAME = 'sanova';
const DB_USER = 'root';
const DB_PASS = '';

// PayHere Sandbox configuration (server-side only)
const PAYHERE_MERCHANT_ID = '1238357';
const PAYHERE_MERCHANT_SECRET = 'MjAzOTkyMzk5ODE2MDEyMTIzMzAxNTk1ODg0OTg1MzMwOTY5MTg0MA==';
const PAYHERE_CHECKOUT_URL = 'https://sandbox.payhere.lk/pay/checkout';

function base_url(): string {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    return $scheme . '://' . $host . ($dir ? $dir : '');
}

function payhere_hash(string $orderId, float $amount, string $currency='LKR'): string {
    $formatted = number_format($amount, 2, '.', '');
    return strtoupper(md5(
        PAYHERE_MERCHANT_ID . $orderId . $formatted . $currency . strtoupper(md5(PAYHERE_MERCHANT_SECRET))
    ));
}

function db(): ?PDO {
    static $pdo = null;
    static $attempted = false;
    if ($attempted) return $pdo;
    $attempted = true;
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    } catch (Throwable $e) {
        $pdo = null;
    }
    return $pdo;
}

function e(string $value): string { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function money(float $amount): string { return 'Rs ' . number_format($amount, 2); }
function redirect(string $url): never { header('Location: ' . $url); exit; }
function is_post(): bool { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24));
    return $_SESSION['csrf'];
}
function verify_csrf(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419); exit('Invalid request token. Please go back and try again.');
    }
}
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void { if (!current_user()) redirect('login.php?next=' . urlencode($_SERVER['REQUEST_URI'] ?? 'index.php')); }
function require_admin(): void {
    $u = current_user();
    if (!$u || ($u['role'] ?? '') !== 'admin') redirect('../login.php?admin=1');
}

function seed_menu(): array {
    return [
        ['id'=>1,'name'=>'Margherita Pizza','category'=>'Pizza','description'=>'Classic delight with tomato, mozzarella and fresh basil.','price'=>2000,'rating'=>4.9,'image'=>'https://images.unsplash.com/photo-1579751626657-72bc17010498?auto=format&fit=crop&w=900&q=85'],
        ['id'=>2,'name'=>'Pepperoni Pizza','category'=>'Pizza','description'=>'Loaded with pepperoni slices and melted cheese.','price'=>2450,'rating'=>4.8,'image'=>'https://images.unsplash.com/photo-1628840042765-356cda07504e?auto=format&fit=crop&w=900&q=85'],
        ['id'=>3,'name'=>'Chicken BBQ Pizza','category'=>'Pizza','description'=>'BBQ chicken, onion, capsicum and cheese.','price'=>2650,'rating'=>4.7,'image'=>'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=900&q=85'],
        ['id'=>4,'name'=>'Chicken Burger','category'=>'Burgers','description'=>'Crispy chicken, cheese, lettuce and house sauce.','price'=>950,'rating'=>4.8,'image'=>'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=900&q=85'],
        ['id'=>5,'name'=>'Cheese Burger','category'=>'Burgers','description'=>'Juicy grilled beef patty with cheddar cheese.','price'=>1050,'rating'=>4.6,'image'=>'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=900&q=85'],
        ['id'=>6,'name'=>'Chicken Fried Rice','category'=>'Rice','description'=>'Wok-fried rice with chicken, egg and vegetables.','price'=>1250,'rating'=>4.7,'image'=>'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=900&q=85'],
        ['id'=>7,'name'=>'Thai Basil Rice','category'=>'Rice','description'=>'Fragrant Thai-style rice with basil and vegetables.','price'=>1300,'rating'=>4.5,'image'=>'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=900&q=85'],
        ['id'=>8,'name'=>'Creamy Chicken Pasta','category'=>'Pasta','description'=>'Creamy pasta with grilled chicken and herbs.','price'=>1450,'rating'=>4.7,'image'=>'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?auto=format&fit=crop&w=900&q=85'],
        ['id'=>9,'name'=>'French Fries','category'=>'Sides','description'=>'Golden crispy fries with Sanova seasoning.','price'=>650,'rating'=>4.5,'image'=>'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?auto=format&fit=crop&w=900&q=85'],
        ['id'=>10,'name'=>'Coca Cola','category'=>'Drinks','description'=>'Chilled refreshing soft drink - 330ml.','price'=>300,'rating'=>4.6,'image'=>'https://images.unsplash.com/photo-1629203851122-3726ecdf080e?auto=format&fit=crop&w=900&q=85'],
        ['id'=>11,'name'=>'Fresh Lime','category'=>'Drinks','description'=>'Fresh lime juice served chilled.','price'=>450,'rating'=>4.7,'image'=>'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=900&q=85'],
        ['id'=>12,'name'=>'Fruit Salad','category'=>'Desserts','description'=>'Seasonal fresh fruit with a light honey drizzle.','price'=>600,'rating'=>4.5,'image'=>'https://images.unsplash.com/photo-1568158879083-c42860933ed7?auto=format&fit=crop&w=900&q=85'],
    ];
}
function menu_items(): array {
    $pdo = db();
    if ($pdo) {
        try {
            $rows = $pdo->query("SELECT mi.id, mi.name, c.name AS category, mi.description, mi.price, mi.rating, mi.image_url AS image FROM menu_items mi JOIN categories c ON c.id=mi.category_id WHERE mi.is_available=1 ORDER BY mi.id")->fetchAll();
            if ($rows) return $rows;
        } catch (Throwable $e) {}
    }
    return seed_menu();
}
function menu_item(int $id): ?array {
    foreach (menu_items() as $item) if ((int)$item['id'] === $id) return $item;
    return null;
}
function cart(): array { return $_SESSION['cart'] ?? []; }
function cart_count(): int { return array_sum(array_map(fn($x)=>(int)$x['qty'], cart())); }
function add_cart(int $id, int $qty=1): void {
    $item = menu_item($id); if (!$item) return;
    $qty = max(1,min(20,$qty));
    if (!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = ['id'=>$id,'qty'=>0,'name'=>$item['name'],'price'=>(float)$item['price'],'image'=>$item['image']];
    $_SESSION['cart'][$id]['qty'] = min(20, $_SESSION['cart'][$id]['qty'] + $qty);
}
function update_cart(int $id, int $qty): void {
    if (!isset($_SESSION['cart'][$id])) return;
    if ($qty <= 0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id]['qty'] = min(20,$qty);
}
function cart_totals(): array {
    $subtotal=0; foreach(cart() as $row) $subtotal += $row['price']*$row['qty'];
    $delivery = $subtotal > 0 && $subtotal < 5000 ? 350 : 0;
    return ['subtotal'=>$subtotal,'delivery'=>$delivery,'total'=>$subtotal+$delivery];
}
function flash(string $key, ?string $value=null): ?string {
    if ($value !== null) { $_SESSION['flash'][$key]=$value; return null; }
    $v=$_SESSION['flash'][$key]??null; unset($_SESSION['flash'][$key]); return $v;
}
